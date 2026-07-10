<?php namespace Concrete\Package\ImageReplacer\Controller\SinglePage\Dashboard\System;

use Concrete\Core\Page\Controller\DashboardPageController;

defined('C5_EXECUTE') or die('Access Denied.');

class ImageReplacer extends DashboardPageController
{
    /** Allowed file extensions this tool will scan/replace. */
    protected $allowedExtensions = ['jpg', 'jpeg', 'png'];

    /** Providers offered in the dropdown, keyed by form value. */
    protected $providers = [
        'picsum' => 'Lorem Picsum (real photos, no key needed)',
        'loremflickr' => 'LoremFlickr (real photos by keyword, no key needed)',
        'unsplash' => 'Unsplash (curated photos, needs a free Access Key)',
        'pexels' => 'Pexels (stock photos, needs a free API key)',
        'pixabay' => 'Pixabay (stock photos, needs a free API key)',
    ];

    public function view()
    {
        $folder = (string) $this->request->query->get('folder', '');
        $this->set('folderInput', $folder);
        $this->set('providers', $this->providers);

        $images = [];
        $scanError = null;

        if ($folder !== '') {
            $absFolder = $this->resolveFolder($folder);
            if ($absFolder === null) {
                $scanError = t('That folder was not found, or is outside the site root.');
            } else {
                $images = $this->scanFolder($absFolder);
            }
        }

        $this->set('scanError', $scanError);
        $this->set('images', $images);
    }

    public function processed()
    {
        $session = $this->app->make('session');
        $messages = (array) $session->get('image_replacer_messages');
        $folder = (string) $session->get('image_replacer_folder');
        $session->remove('image_replacer_messages');
        $session->remove('image_replacer_folder');

        $this->request->query->set('folder', $folder);
        $this->set('success', implode("\n", $messages));
        $this->view();
    }

    public function process()
    {
        $folder = (string) $this->request->request->get('folder', '');
        $this->request->query->set('folder', $folder);

        if ($this->token->validate('image_replacer_process')) {
            if ($this->isPost()) {
                $absFolder = $this->resolveFolder($folder);

                if ($absFolder === null) {
                    $this->error->add(t('That folder was not found, or is outside the site root.'));
                } else {
                    $action = (string) $this->request->request->get('do', 'replace');
                    $selected = (array) $this->request->request->get('files', []);
                    $provider = (string) $this->request->request->get('provider', 'picsum');
                    $options = [
                        'seed' => (string) $this->request->request->get('seed', ''),
                        'category' => (string) $this->request->request->get('category', ''),
                        'api_key' => (string) $this->request->request->get('api_key', ''),
                    ];

                    if (empty($selected)) {
                        $this->error->add(t('Select at least one image first.'));
                    } else {
                        $messages = [];
                        foreach ($selected as $filename) {
                            $filename = basename((string) $filename);
                            $absFile = $absFolder . '/' . $filename;

                            if (!is_file($absFile) || dirname($absFile) !== $absFolder) {
                                continue;
                            }

                            $ext = strtolower(pathinfo($absFile, PATHINFO_EXTENSION));
                            if (!in_array($ext, $this->allowedExtensions, true)) {
                                continue;
                            }

                            $result = $action === 'restore'
                                ? $this->restoreFile($absFile)
                                : $this->replaceFile($absFile, $provider, $options);

                            $messages[] = $result['message'];
                        }

                        $session = $this->app->make('session');
                        $session->set('image_replacer_messages', $messages);
                        $session->set('image_replacer_folder', $folder);
                        $this->redirect('/dashboard/system/image_replacer', 'processed');
                    }
                }
            }
        } else {
            $this->error->add($this->token->getErrorMessage());
        }

        $this->view();
    }

    /**
     * Resolves a user-supplied folder path (relative to the site root) to a
     * real, existing directory inside the site root. Returns null if the
     * folder doesn't exist or would resolve outside the site root.
     */
    protected function resolveFolder(?string $folder): ?string
    {
        $folder = trim((string) $folder, "/ \t\n\r\0\x0B");
        if ($folder === '') {
            return null;
        }

        $base = rtrim(DIR_BASE, '/');
        $abs = realpath($base . '/' . $folder);

        if ($abs === false || !is_dir($abs)) {
            return null;
        }

        if ($abs !== $base && strpos($abs, $base . '/') !== 0) {
            return null;
        }

        return $abs;
    }

    protected function scanFolder(string $absFolder): array
    {
        $images = [];

        foreach (glob($absFolder . '/*') as $path) {
            if (!is_file($path)) {
                continue;
            }

            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if (!in_array($ext, $this->allowedExtensions, true)) {
                continue;
            }

            $info = @getimagesize($path);
            if ($info === false) {
                continue;
            }

            $images[] = [
                'filename' => basename($path),
                'width' => $info[0],
                'height' => $info[1],
                'size' => filesize($path),
                'hasBackup' => file_exists($path . '.bak'),
            ];
        }

        usort($images, function ($a, $b) {
            return strcasecmp($a['filename'], $b['filename']);
        });

        return $images;
    }

    protected function replaceFile(string $absFile, string $provider, array $options): array
    {
        $info = @getimagesize($absFile);
        if ($info === false) {
            return ['ok' => false, 'message' => t('Could not read dimensions for %s.', basename($absFile))];
        }
        [$width, $height] = $info;

        $backupPath = $absFile . '.bak';
        if (!file_exists($backupPath) && !@copy($absFile, $backupPath)) {
            return ['ok' => false, 'message' => t('Could not create a backup for %s.', basename($absFile))];
        }

        try {
            $url = $this->resolveImageUrl($provider, $width, $height, $options);
            if ($url === '') {
                throw new \RuntimeException(t('No photo URL was returned.'));
            }

            $client = $this->app->make(\GuzzleHttp\Client::class);
            $response = $client->request('GET', $url, [
                'timeout' => 20,
                'headers' => ['User-Agent' => 'ConcreteCMS-ImageReplacer/1.0'],
            ]);
            $bytes = (string) $response->getBody();
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => t('Could not fetch a replacement for %s: %s', basename($absFile), $e->getMessage())];
        }

        if (@getimagesizefromstring($bytes) === false) {
            return ['ok' => false, 'message' => t('The provider did not return a valid image for %s.', basename($absFile))];
        }

        $extension = strtolower(pathinfo($absFile, PATHINFO_EXTENSION));
        $finalBytes = $this->normalizeImage($bytes, $extension, $width, $height);
        if ($finalBytes === null) {
            return ['ok' => false, 'message' => t('Could not process the downloaded image for %s.', basename($absFile))];
        }

        if (@file_put_contents($absFile, $finalBytes) === false) {
            return ['ok' => false, 'message' => t('Could not write %s.', basename($absFile))];
        }

        return ['ok' => true, 'message' => t('Replaced %s (%dx%d).', basename($absFile), $width, $height)];
    }

    protected function restoreFile(string $absFile): array
    {
        $backupPath = $absFile . '.bak';

        if (!file_exists($backupPath)) {
            return ['ok' => false, 'message' => t('No backup found for %s.', basename($absFile))];
        }

        if (!@copy($backupPath, $absFile)) {
            return ['ok' => false, 'message' => t('Could not restore %s.', basename($absFile))];
        }

        return ['ok' => true, 'message' => t('Restored %s from backup.', basename($absFile))];
    }

    /**
     * Resolves the given provider/options to a direct, downloadable photo
     * URL. For API-backed providers this makes a search call first and
     * picks one result at random from the page, so a batch replacement
     * doesn't return the same photo for every file.
     */
    protected function resolveImageUrl(string $provider, int $width, int $height, array $options): string
    {
        $width = max(1, $width);
        $height = max(1, $height);

        switch ($provider) {
            case 'loremflickr':
                $category = trim($options['category']);
                $url = sprintf('https://loremflickr.com/%d/%d', $width, $height);
                return $category !== '' ? $url . '/' . rawurlencode($category) : $url;

            case 'unsplash':
                return $this->resolveUnsplashUrl($width, $height, $options);

            case 'pexels':
                return $this->resolvePexelsUrl($width, $height, $options);

            case 'pixabay':
                return $this->resolvePixabayUrl($width, $height, $options);

            case 'picsum':
            default:
                $seed = trim($options['seed']);
                return $seed !== ''
                    ? sprintf('https://picsum.photos/seed/%s/%d/%d', rawurlencode($seed), $width, $height)
                    : sprintf('https://picsum.photos/%d/%d', $width, $height);
        }
    }

    protected function resolveUnsplashUrl(int $width, int $height, array $options): string
    {
        $apiKey = trim($options['api_key']);
        if ($apiKey === '') {
            throw new \RuntimeException(t('An Unsplash Access Key is required.'));
        }

        $query = [
            'client_id' => $apiKey,
            'orientation' => $width >= $height ? 'landscape' : 'portrait',
        ];
        $category = trim($options['category']);
        if ($category !== '') {
            $query['query'] = $category;
        }

        $client = $this->app->make(\GuzzleHttp\Client::class);
        $response = $client->request('GET', 'https://api.unsplash.com/photos/random', [
            'query' => $query,
            'timeout' => 15,
        ]);
        $data = json_decode((string) $response->getBody(), true);
        $raw = $data['urls']['raw'] ?? '';
        if ($raw === '') {
            throw new \RuntimeException(t('Unsplash did not return a photo.'));
        }

        $separator = strpos($raw, '?') === false ? '?' : '&';
        return $raw . $separator . sprintf('w=%d&h=%d&fit=crop', $width, $height);
    }

    protected function resolvePexelsUrl(int $width, int $height, array $options): string
    {
        $apiKey = trim($options['api_key']);
        if ($apiKey === '') {
            throw new \RuntimeException(t('A Pexels API key is required.'));
        }
        $category = trim($options['category']) ?: 'nature';

        $client = $this->app->make(\GuzzleHttp\Client::class);
        $response = $client->request('GET', 'https://api.pexels.com/v1/search', [
            'headers' => ['Authorization' => $apiKey],
            'query' => ['query' => $category, 'per_page' => 15, 'page' => random_int(1, 5)],
            'timeout' => 15,
        ]);
        $data = json_decode((string) $response->getBody(), true);
        $photos = $data['photos'] ?? [];
        if (empty($photos)) {
            throw new \RuntimeException(t('Pexels did not return any photos for that keyword.'));
        }
        $photo = $photos[array_rand($photos)];

        return $photo['src']['original'] ?? '';
    }

    protected function resolvePixabayUrl(int $width, int $height, array $options): string
    {
        $apiKey = trim($options['api_key']);
        if ($apiKey === '') {
            throw new \RuntimeException(t('A Pixabay API key is required.'));
        }
        $category = trim($options['category']) ?: 'nature';

        $client = $this->app->make(\GuzzleHttp\Client::class);
        $response = $client->request('GET', 'https://pixabay.com/api/', [
            'query' => [
                'key' => $apiKey,
                'q' => $category,
                'image_type' => 'photo',
                'safesearch' => 'true',
                'per_page' => 15,
                'page' => random_int(1, 3),
            ],
            'timeout' => 15,
        ]);
        $data = json_decode((string) $response->getBody(), true);
        $hits = $data['hits'] ?? [];
        if (empty($hits)) {
            throw new \RuntimeException(t('Pixabay did not return any photos for that keyword.'));
        }
        $hit = $hits[array_rand($hits)];

        return $hit['largeImageURL'] ?? '';
    }

    /**
     * Downloads may arrive in any format/aspect ratio; this always produces
     * bytes that are exactly $width x $height and encoded to match the
     * original file's extension, so replaced files stay pixel-exact drop-ins.
     */
    protected function normalizeImage(string $bytes, string $extension, int $width, int $height): ?string
    {
        if (!function_exists('imagecreatefromstring')) {
            return $bytes;
        }

        $src = @imagecreatefromstring($bytes);
        if ($src === false) {
            return null;
        }

        $srcWidth = imagesx($src);
        $srcHeight = imagesy($src);

        $dst = imagecreatetruecolor($width, $height);
        if ($extension === 'png') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }

        $srcRatio = $srcWidth / $srcHeight;
        $dstRatio = $width / $height;

        if ($srcRatio > $dstRatio) {
            $cropHeight = $srcHeight;
            $cropWidth = (int) round($srcHeight * $dstRatio);
            $cropX = (int) round(($srcWidth - $cropWidth) / 2);
            $cropY = 0;
        } else {
            $cropWidth = $srcWidth;
            $cropHeight = (int) round($srcWidth / $dstRatio);
            $cropX = 0;
            $cropY = (int) round(($srcHeight - $cropHeight) / 2);
        }

        imagecopyresampled($dst, $src, 0, 0, $cropX, $cropY, $width, $height, $cropWidth, $cropHeight);
        imagedestroy($src);

        ob_start();
        if ($extension === 'png') {
            imagepng($dst, null, 6);
        } else {
            imagejpeg($dst, null, 85);
        }
        $out = ob_get_clean();
        imagedestroy($dst);

        return $out === false ? null : $out;
    }
}
