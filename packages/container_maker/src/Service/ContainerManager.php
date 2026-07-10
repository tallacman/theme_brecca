<?php
namespace Concrete\Package\ContainerMaker\Service;

use Concrete\Core\Entity\Page\Container;
use Concrete\Core\Page\Theme\Theme;

class ContainerManager
{
    /**
     * Return every registered Concrete container, plus whether a matching PHP template
     * exists in the selected theme, application override, or core.
     */
    public function getRegisteredContainersForTheme(Theme $theme)
    {
        $rows = [];
        $containers = $this->getRegisteredContainers();
        $themePath = $this->getThemePath($theme);

        foreach ($containers as $container) {
            $handle = $this->read($container, 'getContainerHandle', 'containerHandle');
            if (!$handle) {
                continue;
            }

            $paths = $this->getTemplateCandidates($handle, $theme, $themePath);
            $found = null;
            foreach ($paths as $path) {
                if (is_file($path)) {
                    $found = $path;
                    break;
                }
            }

            $package = null;
            if (method_exists($container, 'getPackage')) {
                $pkg = $container->getPackage();
                if (is_object($pkg) && method_exists($pkg, 'getPackageHandle')) {
                    $package = $pkg->getPackageHandle();
                }
            }

            $rows[] = [
                'id' => $this->read($container, 'getContainerID', 'containerID'),
                'name' => $this->read($container, 'getContainerName', 'containerName') ?: $handle,
                'handle' => $handle,
                'icon' => $this->read($container, 'getContainerIcon', 'containerIcon'),
                'package' => $package,
                'has_template' => (bool) $found,
                'template_exists' => (bool) $found,
                'template_path' => $found,
                'searched_paths' => $paths,
                'is_core_or_package' => $package !== null,
            ];
        }

        usort($rows, static function ($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });

        return $rows;
    }

    /**
     * List container template files in the selected theme directory.
     */
    public function getThemeContainerFiles(Theme $theme)
    {
        $themePath = $this->getThemePath($theme);
        if (!$themePath) {
            return [];
        }

        $dir = rtrim($themePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'containers';
        if (!is_dir($dir)) {
            return [];
        }

        $rows = [];
        foreach (glob($dir . DIRECTORY_SEPARATOR . '*.php') ?: [] as $path) {
            $handle = pathinfo($path, PATHINFO_FILENAME);
            if ($handle === '') {
                continue;
            }
            $rows[] = [
                'handle' => $handle,
                'name' => $this->humanizeHandle($handle),
                'path' => $path,
                'has_designer_state' => $this->fileHasDesignerState($path),
            ];
        }

        usort($rows, static function ($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });

        return $rows;
    }

    /**
     * Best-effort detection of whether the theme already ships Bootstrap 5.
     * We look for bootstrap-named CSS/JS assets and, failing that, sniff a
     * couple of stylesheet files for the tell-tale grid selectors.
     */
    public function themeHasBootstrap(Theme $theme)
    {
        $themePath = $this->getThemePath($theme);
        if (!$themePath) {
            return false;
        }
        $themePath = rtrim($themePath, DIRECTORY_SEPARATOR);

        // 1. Any file whose name mentions bootstrap (bootstrap.min.css, bootstrap.bundle.js, ...).
        foreach (['css', 'js'] as $sub) {
            $pattern = $themePath . DIRECTORY_SEPARATOR . $sub . DIRECTORY_SEPARATOR . '*bootstrap*';
            foreach (glob($pattern) ?: [] as $hit) {
                if (is_file($hit)) {
                    return true;
                }
            }
        }
        foreach (glob($themePath . DIRECTORY_SEPARATOR . '*bootstrap*') ?: [] as $hit) {
            if (is_file($hit)) {
                return true;
            }
        }

        // 2. Sniff a handful of stylesheets for Bootstrap's grid signature.
        $cssFiles = array_merge(
            glob($themePath . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . '*.css') ?: [],
            glob($themePath . DIRECTORY_SEPARATOR . '*.css') ?: []
        );
        $checked = 0;
        foreach ($cssFiles as $cssFile) {
            if ($checked >= 6 || !is_file($cssFile)) {
                continue;
            }
            $checked++;
            $contents = @file_get_contents($cssFile, false, null, 0, 200000);
            if (!is_string($contents) || $contents === '') {
                continue;
            }
            if (strpos($contents, '.col-md-') !== false || strpos($contents, 'bootstrap') !== false) {
                return true;
            }
        }

        return false;
    }

    public function resolveThemeContainerPath(Theme $theme, $handle)
    {
        $handle = preg_replace('/[^A-Za-z0-9_\-]/', '', (string) $handle);
        if ($handle === '') {
            return null;
        }

        foreach ($this->getThemeContainerFiles($theme) as $file) {
            if ($file['handle'] === $handle) {
                return $file['path'];
            }
        }

        return null;
    }

    protected function fileHasDesignerState($path)
    {
        if (!$path || !is_file($path)) {
            return false;
        }
        $contents = @file_get_contents($path);

        return is_string($contents) && strpos($contents, 'CM_DESIGNER_STATE') !== false;
    }

    protected function humanizeHandle($handle)
    {
        $handle = str_replace(['_', '-'], ' ', (string) $handle);

        return ucwords(trim($handle));
    }

    public function uninstallByHandle($handle)
    {
        $handle = preg_replace('/[^A-Za-z0-9_\-]/', '', (string) $handle);
        if ($handle === '') {
            return t('Invalid container handle.');
        }

        $em = $this->entityManager();
        $repo = $em->getRepository(Container::class);
        $container = method_exists($repo, 'findOneByContainerHandle')
            ? $repo->findOneByContainerHandle($handle)
            : $repo->findOneBy(['containerHandle' => $handle]);

        if (!$container) {
            return t('Container %s is not registered.', $handle);
        }

        // Prefer Concrete's command layer when it is available.
        if (class_exists('\\Concrete\\Core\\Page\\Container\\Command\\DeleteContainerCommand')) {
            try {
                $commandClass = '\\Concrete\\Core\\Page\\Container\\Command\\DeleteContainerCommand';
                $command = new $commandClass($container);
                $app = \Core::make('app');
                if (method_exists($app, 'executeCommand')) {
                    $app->executeCommand($command);
                    return true;
                }
            } catch (\Throwable $e) {
                // Fall through to entity removal below.
            }
        }

        try {
            $em->remove($container);
            $em->flush();
            return true;
        } catch (\Throwable $e) {
            return t('Could not uninstall %s: %s', $handle, $e->getMessage());
        }
    }

    protected function getRegisteredContainers()
    {
        if (!class_exists(Container::class)) {
            return [];
        }
        return $this->entityManager()->getRepository(Container::class)->findAll();
    }

    protected function entityManager()
    {
        $app = \Core::make('app');
        if ($app->bound('database/orm')) {
            return $app->make('database/orm')->entityManager();
        }
        return \Database::connection()->getEntityManager();
    }

    protected function getTemplateCandidates($handle, Theme $theme, $themePath = null)
    {
        $paths = [];
        $file = 'elements' . DIRECTORY_SEPARATOR . 'containers' . DIRECTORY_SEPARATOR . $handle . '.php';

        if ($themePath) {
            $paths[] = rtrim($themePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;
        }

        $themeHandle = $theme->getThemeHandle();
        $paths[] = DIR_BASE . '/application/themes/' . $themeHandle . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $file);
        $paths[] = DIR_BASE . '/themes/' . $themeHandle . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $file);
        $paths[] = DIR_BASE_CORE . '/themes/' . $themeHandle . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $file);
        $paths[] = DIR_BASE . '/application/elements/containers/' . $handle . '.php';
        $paths[] = DIR_BASE_CORE . '/elements/containers/' . $handle . '.php';

        return array_values(array_unique($paths));
    }

    protected function getThemePath(Theme $theme)
    {
        if (method_exists($theme, 'getThemeDirectory')) {
            $dir = $theme->getThemeDirectory();
            if ($dir && is_dir($dir)) {
                return $dir;
            }
        }

        $handle = $theme->getThemeHandle();
        foreach ([
            DIR_BASE . '/application/themes/' . $handle,
            DIR_BASE . '/themes/' . $handle,
            DIR_BASE_CORE . '/themes/' . $handle,
        ] as $path) {
            if (is_dir($path)) {
                return $path;
            }
        }
        return null;
    }

    protected function read($object, $method, $property)
    {
        if (is_object($object) && method_exists($object, $method)) {
            return $object->{$method}();
        }
        if (is_object($object) && property_exists($object, $property)) {
            return $object->{$property};
        }
        return null;
    }
}
