<?php
namespace Concrete\Package\ContainerMaker\Service;

class ContainerImporter
{
    /** @var ContainerGenerator */
    protected $generator;

    public function __construct(ContainerGenerator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @return array{state?: array, error?: string, warnings?: string[]}
     */
    public function importFromPath($filePath, $handle = null)
    {
        if (!$filePath || !is_file($filePath)) {
            return ['error' => t('Container file not found.')];
        }

        $handle = $this->sanitizeHandle($handle ?: pathinfo($filePath, PATHINFO_FILENAME));
        $contents = @file_get_contents($filePath);
        if ($contents === false) {
            return ['error' => t('Could not read container file.')];
        }

        return $this->importFromContents($contents, $handle, $filePath);
    }

    /**
     * @return array{state?: array, error?: string, warnings?: string[]}
     */
    public function importFromContents($contents, $handle, $sourcePath = null)
    {
        $handle = $this->sanitizeHandle($handle);
        if ($handle === '') {
            return ['error' => t('Please enter a valid container handle.')];
        }

        if ($sourcePath) {
            $state = $this->generator->readDesignerState($sourcePath);
            if (is_array($state)) {
                $state['handle'] = $handle;
                $state['name'] = $state['name'] ?? $this->humanizeHandle($handle);
                return ['state' => $state, 'warnings' => []];
            }
        }

        if (preg_match('/CM_DESIGNER_STATE\s+([A-Za-z0-9+\/=]+)\s+CM_DESIGNER_STATE_END/', $contents, $match)) {
            $json = base64_decode($match[1], true);
            $state = json_decode($json ?: '', true);
            if (is_array($state)) {
                $state['handle'] = $handle;
                $state['name'] = $state['name'] ?? $this->humanizeHandle($handle);
                return ['state' => $state, 'warnings' => []];
            }
        }

        $parsed = $this->parseContainerMarkup($contents, $handle);
        if ($parsed === null) {
            return ['error' => t('Could not parse this container. It must include at least one ContainerArea block.')];
        }

        return [
            'state' => $parsed['state'],
            'warnings' => $parsed['warnings'],
        ];
    }

    protected function parseContainerMarkup($contents, $handle)
    {
        $areaNames = $this->extractAreaNames($contents);
        if (empty($areaNames)) {
            return null;
        }

        $warnings = [];
        $css = $this->extractCss($contents);
        $layoutMode = $this->detectLayoutMode($contents, $css);
        $settings = $layoutMode === 'bootstrap5'
            ? $this->parseBootstrapSettings($contents, $css, $warnings)
            : $this->parseGridSettings($css, $warnings);
        $settings['inner'] = $this->detectInnerWrapper($contents);
        $settings['container_id'] = $this->detectContainerId($contents);
        $placements = $this->parseAreaPlacements($css, count($areaNames), $settings, $warnings);
        $responsive = $this->parseResponsiveRules($css, count($areaNames), $settings);
        $areaIds = $this->extractAreaIdsFromMarkup($contents);

        $areas = [];
        foreach ($areaNames as $index => $name) {
            $placement = $placements[$index] ?? $this->defaultPlacement($index, count($areaNames), $settings['track_count']);
            $resp = $responsive[$index] ?? [];
            $areas[] = [
                'name' => $name,
                'id' => $areaIds[$index] ?? '',
                'col' => (int) $placement['col'],
                'row' => (int) $placement['row'],
                'row_span' => (int) $placement['row_span'],
                'grid_column' => $placement['grid_column'],
                'grid_row' => $placement['grid_row'],
                'min_height' => $placement['min_height'],
                'desktop' => (int) $placement['span'],
                'tablet' => (int) ($resp['tablet'] ?? $placement['span']),
                'mobile' => (int) ($resp['mobile'] ?? ($resp['tablet'] ?? $placement['span'])),
                'tablet_span' => $resp['tablet_span'] ?? null,
                'mobile_span' => $resp['mobile_span'] ?? null,
                    'tablet_col' => $resp['tablet_col'] ?? null,
                    'mobile_col' => $resp['mobile_col'] ?? null,
                    'tablet_row' => (int) $placement['row'],
                    'mobile_row' => (int) $placement['row'],
                'mobile_stack' => $resp['mobile_stack'] ?? true,
                'full' => !empty($placement['full']),
                'nested' => true,
                'padding' => '',
            ];
        }

        if ($css === '') {
            $warnings[] = t('No grid CSS was found. Areas were placed using a best-guess layout.');
        } else {
            $warnings[] = t('Imported from existing PHP/CSS. Review the layout, then save with Overwrite to store designer state.');
        }

        return [
            'state' => [
                'name' => $this->humanizeHandle($handle),
                'handle' => $handle,
                'layout_mode' => $layoutMode,
                'theme_id' => 0,
                'import_source' => 'parsed',
                'settings' => $settings,
                'areas' => $areas,
            ],
            'warnings' => $warnings,
        ];
    }

    protected function detectLayoutMode($contents, $css)
    {
        if (preg_match('/__grid\b/', $contents) || preg_match('/display\s*:\s*grid/i', $css)) {
            return 'css_grid';
        }
        if (preg_match('/class=["\'][^"\']*\brow\b[^"\']*["\']/i', $contents)
            && preg_match('/\bcol-(?:lg-|md-)?\d+\b/i', $contents)) {
            return 'bootstrap5';
        }

        return 'css_grid';
    }

    protected function parseBootstrapSettings($contents, $css, array &$warnings)
    {
        $settings = [
            'gap' => '1rem',
            'inner' => $this->detectInnerWrapper($contents),
            'grid_columns' => 'repeat(12, minmax(0, 1fr))',
            'grid_rows' => 'minmax(64px, auto)',
            'grid_tracks' => array_fill(0, 12, ['mode' => 'fr', 'value' => '1', 'min' => '200px', 'preferred' => '1fr', 'max' => '100%']),
            'row_size' => ['mode' => 'minmax', 'value' => '64px|auto', 'min' => '200px', 'preferred' => '1fr', 'max' => '100%'],
            'tablet_columns' => '',
            'mobile_columns' => '',
            'side_margin' => '',
            'track_count' => 12,
            'theme_has_bootstrap' => !preg_match('/Container Maker — Bootstrap 5 grid scoped/i', $css),
            'bootstrap_gutter' => 'g-3',
            'container_id' => '',
        ];

        if (preg_match('/class=["\'][^"\']*\b(g-[0-5])\b[^"\']*["\']/i', $contents, $match)) {
            $settings['bootstrap_gutter'] = $match[1];
        }

        if (preg_match('/Container Maker — Bootstrap 5 grid scoped/i', $css)) {
            $settings['theme_has_bootstrap'] = false;
        } elseif ($css === '') {
            $warnings[] = t('Bootstrap row/column markup detected. If your theme does not include Bootstrap 5, enable scoped CSS when you save.');
        }

        return $settings;
    }

    protected function extractAreaNames($contents)
    {
        $names = [];
        if (preg_match_all('/new\s+ContainerArea\s*\(\s*\$container\s*,\s*(["\'])((?:\\\\.|(?!\1).)*)\1\s*\)/s', $contents, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $name = stripcslashes($match[2]);
                if ($name !== '') {
                    $names[] = $name;
                }
            }
        }

        if (empty($names) && preg_match('/\$areas\s*=\s*array\s*\((.*?)\)\s*;/s', $contents, $arrayMatch)) {
            if (preg_match_all('/(["\'])((?:\\\\.|(?!\1).)*)\1/', $arrayMatch[1], $arrayNames, PREG_SET_ORDER)) {
                foreach ($arrayNames as $match) {
                    $name = stripcslashes($match[2]);
                    if ($name !== '') {
                        $names[] = $name;
                    }
                }
            }
        }

        return array_values($names);
    }

    protected function extractCss($contents)
    {
        if (preg_match('/\$cmContainerCss\s*=\s*<<<\'CSS\'\s*(.*?)\s*CSS\s*;/s', $contents, $match)) {
            return trim($match[1]);
        }

        if (preg_match('/\$cmContainerCss\s*=\s*\'((?:\\\\\'|[^\'])*)\'\s*;/s', $contents, $match)) {
            return stripcslashes($match[1]);
        }

        if (preg_match('/\$cmContainerCss\s*=\s*"((?:\\\\"|[^"])*)"\s*;/s', $contents, $match)) {
            return stripcslashes($match[1]);
        }

        if (preg_match('/<style>(.*?)<\/style>/s', $contents, $match)) {
            return trim($match[1]);
        }

        return '';
    }

    protected function parseGridSettings($css, array &$warnings)
    {
        $settings = [
            'gap' => '1rem',
            'inner' => '',
            'grid_columns' => 'repeat(12, minmax(0, 1fr))',
            'grid_rows' => 'minmax(64px, auto)',
            'grid_tracks' => [],
            'row_size' => ['mode' => 'minmax', 'value' => '64px|auto', 'min' => '200px', 'preferred' => '1fr', 'max' => '100%'],
            'tablet_columns' => '',
            'mobile_columns' => '',
            'side_margin' => '',
            'track_count' => 12,
            'container_id' => '',
        ];

        if ($css === '') {
            return $settings;
        }

        if (preg_match('/grid-template-columns\s*:\s*([^;}{]+)/i', $css, $match)) {
            $columns = trim($match[1]);
            $settings['grid_columns'] = $columns;
            $settings['grid_tracks'] = $this->parseTracksFromColumns($columns);
            $settings['track_count'] = max(1, count($settings['grid_tracks']));
        }

        if (preg_match('/grid-auto-rows\s*:\s*([^;}{]+)/i', $css, $match)) {
            $settings['grid_rows'] = trim($match[1]);
            $settings['row_size'] = $this->parseRowSize(trim($match[1]));
        }

        if (preg_match('/\bgap\s*:\s*([^;}{]+)/i', $css, $match)) {
            $settings['gap'] = trim($match[1]);
        }

        if (preg_match('/@media\s*\(\s*max-width\s*:\s*991px\s*\)\s*\{[^}]*grid-template-columns\s*:\s*([^;}{]+)/is', $css, $match)) {
            $settings['tablet_columns'] = trim($match[1]);
        }

        if (preg_match('/@media\s*\(\s*max-width\s*:\s*767px\s*\)\s*\{[^}]*grid-template-columns\s*:\s*([^;}{]+)/is', $css, $match)) {
            $settings['mobile_columns'] = trim($match[1]);
        }

        return $settings;
    }

    protected function detectInnerWrapper($contents)
    {
        if (preg_match('/<div\s+class=["\']([^"\']*\bcontainer-fluid\b[^"\']*)["\']/i', $contents)) {
            return 'container-fluid';
        }
        if (preg_match('/<div\s+class=["\']([^"\']*\bcontainer\b[^"\']*)["\']/i', $contents)) {
            return 'container';
        }

        return '';
    }

    protected function detectContainerId($contents)
    {
        if (preg_match('/<div\s+class=["\'][^"\']*\bcm-[^"\']*["\'][^>]*\bid=["\']([^"\']+)["\']/i', $contents, $match)) {
            return trim((string) $match[1]);
        }
        if (preg_match('/<div\s+id=["\']([^"\']+)["\'][^>]*\bclass=["\'][^"\']*\bcm-[^"\']*["\']/i', $contents, $match)) {
            return trim((string) $match[1]);
        }

        return '';
    }

    protected function extractAreaIdsFromMarkup($contents)
    {
        $ids = [];
        if (!preg_match_all('/class=["\'][^"\']*__item--(\d+)[^"\']*["\'][^>]*\bid=["\']([^"\']+)["\']/i', $contents, $matches, PREG_SET_ORDER)
            && !preg_match_all('/\bid=["\']([^"\']+)["\'][^>]*class=["\'][^"\']*__item--(\d+)[^"\']*["\']/i', $contents, $matches, PREG_SET_ORDER)) {
            return $ids;
        }
        foreach ($matches as $match) {
            if (is_numeric($match[1])) {
                $index = max(0, (int) $match[1] - 1);
                $value = (string) $match[2];
            } else {
                $index = max(0, (int) $match[2] - 1);
                $value = (string) $match[1];
            }
            $ids[$index] = $value;
        }

        return $ids;
    }

    protected function parseTracksFromColumns($columnsCss)
    {
        $columnsCss = trim($columnsCss);
        if ($columnsCss === '') {
            return [];
        }

        if (preg_match('/repeat\s*\(\s*(\d+)\s*,\s*([^)]+)\)/i', $columnsCss, $match)) {
            $count = max(1, (int) $match[1]);
            $trackCss = trim($match[2]);
            $track = $this->parseTrackToken($trackCss);

            return array_fill(0, $count, $track);
        }

        $tokens = preg_split('/\s+(?![^(]*\))/') ?: [];
        $tracks = [];
        foreach ($tokens as $token) {
            $token = trim($token);
            if ($token !== '') {
                $tracks[] = $this->parseTrackToken($token);
            }
        }

        return $tracks;
    }

    protected function parseTrackToken($token)
    {
        $token = trim($token);
        if ($token === 'auto') {
            return ['mode' => 'auto', 'value' => '1', 'min' => '200px', 'preferred' => '1fr', 'max' => '100%'];
        }
        if (preg_match('/^minmax\(/i', $token)) {
            return ['mode' => 'minmax', 'value' => '1fr', 'min' => '0px', 'max' => '1fr'];
        }
        if (preg_match('/^clamp\(/i', $token)) {
            return ['mode' => 'clamp', 'value' => '1fr', 'min' => '0px', 'preferred' => '1fr', 'max' => '100%'];
        }
        if (preg_match('/px$/i', $token)) {
            return ['mode' => 'px', 'value' => preg_replace('/px$/i', '', $token), 'min' => '200px', 'preferred' => '1fr', 'max' => '100%'];
        }
        if (preg_match('/rem$/i', $token)) {
            return ['mode' => 'rem', 'value' => preg_replace('/rem$/i', '', $token), 'min' => '200px', 'preferred' => '1fr', 'max' => '100%'];
        }
        if (preg_match('/%$/', $token)) {
            return ['mode' => 'percent', 'value' => preg_replace('/%$/', '', $token), 'min' => '200px', 'preferred' => '1fr', 'max' => '100%'];
        }
        if (preg_match('/fr$/i', $token)) {
            return ['mode' => 'fr', 'value' => preg_replace('/fr$/i', '', $token) ?: '1', 'min' => '200px', 'preferred' => '1fr', 'max' => '100%'];
        }

        return ['mode' => 'fr', 'value' => '1', 'min' => '200px', 'preferred' => '1fr', 'max' => '100%'];
    }

    protected function parseRowSize($rowsCss)
    {
        if (preg_match('/^minmax\((.+)\)$/i', $rowsCss, $match)) {
            $parts = array_map('trim', explode(',', $match[1], 2));

            return [
                'mode' => 'minmax',
                'value' => ($parts[0] ?? '64px') . '|' . ($parts[1] ?? 'auto'),
                'min' => '200px',
                'preferred' => '1fr',
                'max' => '100%',
            ];
        }
        if (preg_match('/^clamp\(/i', $rowsCss)) {
            return ['mode' => 'clamp', 'value' => '64px|auto', 'min' => '200px', 'preferred' => '1fr', 'max' => '100%'];
        }

        return ['mode' => 'minmax', 'value' => '64px|auto', 'min' => '200px', 'preferred' => '1fr', 'max' => '100%'];
    }

    protected function parseAreaPlacements($css, $areaCount, array $settings, array &$warnings)
    {
        $trackCount = max(1, (int) ($settings['track_count'] ?? 12));
        $placements = [];

        if ($css !== '' && preg_match_all('/>\s*\*:nth-child\((\d+)\)\s*\{([^}]*)\}/s', $css, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $index = (int) $match[1] - 1;
                if ($index < 0 || $index >= $areaCount) {
                    continue;
                }
                $placements[$index] = $this->parseRuleBlock($match[2], $trackCount);
            }
        }

        for ($i = 0; $i < $areaCount; $i++) {
            if (!isset($placements[$i])) {
                $placements[$i] = $this->defaultPlacement($i, $areaCount, $trackCount);
                if ($css !== '') {
                    $warnings[] = t('Area %s had no grid CSS rule; a default placement was used.', $i + 1);
                }
            }
        }

        ksort($placements);

        return array_values($placements);
    }

    protected function parseRuleBlock($rule, $trackCount)
    {
        $placement = [
            'col' => 1,
            'span' => $trackCount,
            'row' => 1,
            'row_span' => 1,
            'grid_column' => '',
            'grid_row' => '',
            'min_height' => '',
            'full' => false,
        ];

        if (preg_match('/grid-column\s*:\s*([^;]+)/i', $rule, $match)) {
            $gridColumn = trim($match[1]);
            if ($gridColumn === '1 / -1') {
                $placement['full'] = true;
                $placement['col'] = 1;
                $placement['span'] = $trackCount;
            } elseif (preg_match('/^\s*(\d+)\s*\/\s*span\s+(\d+)\s*$/i', $gridColumn, $parts)) {
                $placement['col'] = max(1, (int) $parts[1]);
                $placement['span'] = max(1, (int) $parts[2]);
            } elseif (preg_match('/^\s*(\d+)\s*\/\s*(\d+)\s*$/', $gridColumn, $parts)) {
                $placement['col'] = max(1, (int) $parts[1]);
                $placement['span'] = max(1, (int) $parts[2] - (int) $parts[1]);
            } elseif (preg_match('/span\s+(\d+)/i', $gridColumn, $parts)) {
                $placement['span'] = max(1, (int) $parts[1]);
            } else {
                $placement['grid_column'] = $gridColumn;
            }
        }

        if (preg_match('/grid-row\s*:\s*([^;]+)/i', $rule, $match)) {
            $gridRow = trim($match[1]);
            if (preg_match('/^\s*(\d+)\s*\/\s*span\s+(\d+)\s*$/i', $gridRow, $parts)) {
                $placement['row'] = max(1, (int) $parts[1]);
                $placement['row_span'] = max(1, (int) $parts[2]);
            } elseif (preg_match('/^\s*(\d+)\s*$/', $gridRow, $parts)) {
                $placement['row'] = max(1, (int) $parts[1]);
            } else {
                $placement['grid_row'] = $gridRow;
            }
        }

        if (preg_match('/min-height\s*:\s*([^;]+)/i', $rule, $match)) {
            $placement['min_height'] = trim($match[1]);
        }

        return $placement;
    }

    protected function defaultPlacement($index, $areaCount, $trackCount)
    {
        $trackCount = max(1, $trackCount);
        $span = max(1, (int) floor($trackCount / max(1, min($areaCount, $trackCount))));
        if ($areaCount <= $trackCount) {
            $span = max(1, (int) floor($trackCount / $areaCount));
        }
        $col = ($index * $span) + 1;
        if ($col + $span - 1 > $trackCount) {
            $col = 1;
        }

        return [
            'col' => $col,
            'span' => min($span, $trackCount),
            'row' => $index + 1,
            'row_span' => 1,
            'grid_column' => '',
            'grid_row' => '',
            'min_height' => '',
            'full' => false,
        ];
    }

    protected function parseResponsiveRules($css, $areaCount, array $settings)
    {
        $trackCount = max(1, (int) ($settings['track_count'] ?? 12));
        $responsive = [];

        if ($css === '') {
            return $responsive;
        }

        $blocks = [
            'tablet' => $this->extractMediaBlock($css, '991px'),
            'mobile' => $this->extractMediaBlock($css, '767px'),
        ];

        foreach ($blocks as $breakpoint => $block) {
            if ($block === '') {
                continue;
            }
            if (!preg_match_all('/>\s*\*:nth-child\((\d+)\)\s*\{([^}]*)\}/s', $block, $matches, PREG_SET_ORDER)) {
                continue;
            }
            foreach ($matches as $match) {
                $index = (int) $match[1] - 1;
                if ($index < 0 || $index >= $areaCount) {
                    continue;
                }
                $rule = $this->parseRuleBlock($match[2], $trackCount);
                if (!isset($responsive[$index])) {
                    $responsive[$index] = [
                        'tablet' => null,
                        'mobile' => null,
                        'tablet_span' => null,
                        'mobile_span' => null,
                        'tablet_col' => null,
                        'mobile_col' => null,
                        'mobile_stack' => true,
                    ];
                }
                if ($breakpoint === 'tablet') {
                    $responsive[$index]['tablet'] = $rule['full'] ? $trackCount : $rule['span'];
                    $responsive[$index]['tablet_span'] = $rule['full'] ? $trackCount : $rule['span'];
                    $responsive[$index]['tablet_col'] = $rule['col'];
                } else {
                    $responsive[$index]['mobile'] = $rule['full'] ? $trackCount : $rule['span'];
                    $responsive[$index]['mobile_span'] = $rule['full'] ? null : $rule['span'];
                    $responsive[$index]['mobile_col'] = $rule['col'];
                    $responsive[$index]['mobile_stack'] = $rule['full'];
                }
            }
        }

        return $responsive;
    }

    protected function extractMediaBlock($css, $maxWidth)
    {
        if (!preg_match('/@media\s*\(\s*max-width\s*:\s*' . preg_quote($maxWidth, '/') . '\s*\)\s*\{(.+)\}/is', $css, $match)) {
            return '';
        }

        return $match[1];
    }

    protected function humanizeHandle($handle)
    {
        $handle = str_replace(['_', '-'], ' ', (string) $handle);

        return ucwords(trim($handle));
    }

    protected function sanitizeHandle($handle)
    {
        $handle = strtolower(trim((string) $handle));
        $handle = preg_replace('/[^a-z0-9]+/', '_', $handle);

        return trim($handle, '_');
    }
}
