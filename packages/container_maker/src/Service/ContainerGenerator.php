<?php
namespace Concrete\Package\ContainerMaker\Service;

use Concrete\Core\Page\Theme\Theme;

class ContainerGenerator
{
    public function readDesignerState($filePath)
    {
        if (!$filePath || !is_file($filePath)) {
            return null;
        }
        $contents = @file_get_contents($filePath);
        if ($contents === false) {
            return null;
        }
        if (!preg_match('/CM_DESIGNER_STATE\s+([A-Za-z0-9+\/=]+)\s+CM_DESIGNER_STATE_END/', $contents, $m)) {
            return null;
        }
        $json = base64_decode($m[1], true);
        if ($json === false) {
            return null;
        }
        $data = json_decode($json, true);
        return is_array($data) ? $data : null;
    }

    /** @deprecated Use generate() */
    public function generateFlex(Theme $theme, array $data)
    {
        return $this->generate($theme, $data);
    }

    public function generate(Theme $theme, array $data)
    {
        $result = ['created' => [], 'registered' => [], 'skipped' => [], 'errors' => []];
        $themePath = $this->getThemePath($theme);
        if (!$themePath || !is_dir($themePath)) {
            $result['errors'][] = t('Could not find the selected theme directory.');
            return $result;
        }

        $name = trim((string) ($data['name'] ?? ''));
        $handle = $this->sanitizeHandle((string) ($data['handle'] ?? ''));
        if ($name === '') {
            $name = t('Grid Container');
        }
        if ($handle === '') {
            $handle = $this->sanitizeHandle($name);
        }
        if ($handle === '') {
            $result['errors'][] = t('Please enter a valid container handle.');
            return $result;
        }

        $areas = $this->parseAreaNames((string) ($data['areas'] ?? 'Main'));
        if (empty($areas)) {
            $result['errors'][] = t('Please add at least one area.');
            return $result;
        }

        $responsiveColumns = $this->parseResponsiveColumns((string) ($data['responsive_columns'] ?? ''), $areas);
        $areaResponsive = $this->parseAreaResponsive((string) ($data['area_responsive'] ?? ''), $areas);
        $areaPlacement = $this->parseAreaPlacement((string) ($data['area_placement'] ?? ''), $areas);
        $areaFullRows = $this->parseAreaFullRows((string) ($data['area_full_rows'] ?? ''), $areas);
        $nestedAreas = $this->parseAreaFullRows((string) ($data['nested_areas'] ?? ''), $areas);
        $areaPadding = $this->parseAreaPadding((string) ($data['area_padding'] ?? ''), $areas);
        $areaIds = $this->parseAreaIds((string) ($data['area_ids'] ?? ''), $areas);
        $settings = $this->normalizeSettings($data);
        $layoutMode = $settings['layout_mode'];

        $containerDir = rtrim($themePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'elements' . DIRECTORY_SEPARATOR . 'containers';
        if (!is_dir($containerDir) && !@mkdir($containerDir, 0755, true)) {
            $result['errors'][] = t('Could not create directory: %s', $containerDir);
            return $result;
        }

        $customCode = null;
        if (!empty($data['use_custom_code'])) {
            $customCode = (string) ($data['custom_code'] ?? '');
            if (trim($customCode) === '') {
                $result['errors'][] = t('Edit code is on but the code box is empty.');
                return $result;
            }
        }

        $file = $containerDir . DIRECTORY_SEPARATOR . $handle . '.php';
        $overwrite = !empty($data['overwrite']);
        if (file_exists($file) && !$overwrite) {
            $result['skipped'][] = $file;
        } else {
            if ($customCode !== null) {
                // Advanced mode: the user hand-edited the code, write it verbatim.
                $php = $this->normalizeCustomCode($customCode);
            } elseif ($layoutMode === 'bootstrap5') {
                $php = $this->buildBootstrapContainerPhp($handle, $name, $areas, $settings, $areaFullRows, $responsiveColumns, $nestedAreas, $areaPadding, $areaPlacement, $areaResponsive, $areaIds);
            } else {
                $php = $this->buildContainerPhp($handle, $name, $areas, $settings, $areaFullRows, $responsiveColumns, $nestedAreas, $areaPadding, $areaPlacement, $areaResponsive, $areaIds);
            }
            if (@file_put_contents($file, $php) === false) {
                $result['errors'][] = t('Could not write file: %s', $file);
                return $result;
            }
            $result['created'][] = $file;
        }

        if (!empty($data['register'])) {
            $registered = $this->tryRegisterContainer($handle, $name);
            if ($registered === true) {
                $result['registered'][] = $name . ' (' . $handle . ')';
            } elseif (is_string($registered)) {
                $result['errors'][] = $registered;
            }
        }

        return $result;
    }

    /**
     * Prepare hand-edited code for writing. We normalise line endings and make
     * sure the direct-access guard is present, but otherwise trust the author.
     */
    protected function normalizeCustomCode($code)
    {
        $code = str_replace(["\r\n", "\r"], "\n", (string) $code);
        $code = rtrim($code) . "\n";
        if (strpos($code, 'C5_EXECUTE') === false) {
            $guard = "<?php\ndefined('C5_EXECUTE') or die('Access Denied.');\n?>\n";
            if (strncmp(ltrim($code), '<?php', 5) === 0) {
                $code = preg_replace('/^\s*<\?php\s*\n?/', "<?php\ndefined('C5_EXECUTE') or die('Access Denied.');\n", $code, 1);
            } else {
                $code = $guard . $code;
            }
        }

        return $code;
    }

    protected function buildContainerPhp($handle, $name, array $areas, array $settings, array $areaFullRows, array $responsiveColumns, array $nestedAreas, array $areaPadding, array $areaPlacement = [], array $areaResponsive = [], array $areaIds = [])
    {
        $class = 'cm-' . str_replace('_', '-', $handle);
        $inner = $settings['inner'];
        $designerState = $this->encodeDesignerState($handle, $name, 'css_grid', $settings, $areas, $responsiveColumns, $areaFullRows, $areaPadding, $areaPlacement, $nestedAreas, $areaResponsive, $areaIds);

        [$body, $css] = $this->buildCssGridMarkup($class, $inner, $areas, $areaFullRows, $responsiveColumns, $settings, $areaPadding, $areaPlacement, $areaResponsive, $areaIds);
        $headerPhp = $this->buildHeaderStyleInjection($settings['container_id'] ?: $class, $css);

        return $this->wrapContainerPhp($designerState, $headerPhp, $body);
    }

    protected function buildBootstrapContainerPhp($handle, $name, array $areas, array $settings, array $areaFullRows, array $responsiveColumns, array $nestedAreas, array $areaPadding, array $areaPlacement = [], array $areaResponsive = [], array $areaIds = [])
    {
        $class = 'cm-' . str_replace('_', '-', $handle);
        $inner = $settings['inner'];
        $designerState = $this->encodeDesignerState($handle, $name, 'bootstrap5', $settings, $areas, $responsiveColumns, $areaFullRows, $areaPadding, $areaPlacement, $nestedAreas, $areaResponsive, $areaIds);

        [$body, $css] = $this->buildBootstrapMarkup($class, $inner, $areas, $areaFullRows, $responsiveColumns, $settings, $areaPadding, $areaPlacement, $areaResponsive, $areaIds);
        $headerPhp = $this->buildHeaderStyleInjection($settings['container_id'] ?: $class, $css);

        return $this->wrapContainerPhp($designerState, $headerPhp, $body);
    }

    protected function encodeDesignerState($handle, $name, $layoutMode, array $settings, array $areas, array $responsiveColumns, array $areaFullRows, array $areaPadding, array $areaPlacement, array $nestedAreas, array $areaResponsive, array $areaIds)
    {
        return base64_encode(json_encode([
            'name' => $name,
            'handle' => $handle,
            'layout_mode' => $layoutMode,
            'theme_id' => (int) ($settings['theme_id'] ?? 0),
            'settings' => [
                'gap' => $settings['gap'],
                'inner' => $settings['inner'],
                'grid_columns' => $settings['grid_columns'],
                'grid_rows' => $settings['grid_rows'],
                'grid_tracks' => $settings['grid_tracks'],
                'row_size' => $settings['row_size'],
                'tablet_columns' => $settings['tablet_columns'],
                'mobile_columns' => $settings['mobile_columns'],
                'side_margin' => $settings['side_margin'],
                'theme_has_bootstrap' => !empty($settings['theme_has_bootstrap']),
                'bootstrap_gutter' => $settings['bootstrap_gutter'],
                'container_id' => $settings['container_id'],
            ],
            'areas' => array_map(static function ($areaName) use ($responsiveColumns, $areaFullRows, $areaPadding, $areaPlacement, $nestedAreas, $areaResponsive, $areaIds) {
                $placement = $areaPlacement[$areaName] ?? [];
                $responsiveMeta = $areaResponsive[$areaName] ?? [];
                return [
                    'name' => $areaName,
                    'id' => $areaIds[$areaName] ?? '',
                    'col' => (int) ($placement['col'] ?? 1),
                    'row' => (int) ($placement['row'] ?? 1),
                    'row_span' => (int) ($placement['row_span'] ?? 1),
                    'grid_column' => $placement['grid_column'] ?? '',
                    'grid_row' => $placement['grid_row'] ?? '',
                    'min_height' => $placement['min_height'] ?? '',
                    'desktop' => (int) ($responsiveColumns[$areaName]['desktop'] ?? ($placement['span'] ?? 12)),
                    'tablet' => (int) ($responsiveColumns[$areaName]['tablet'] ?? ($placement['span'] ?? 12)),
                    'mobile' => (int) ($responsiveColumns[$areaName]['mobile'] ?? ($placement['span'] ?? 12)),
                    'tablet_span' => $responsiveMeta['tablet_span'] ?? null,
                    'mobile_span' => $responsiveMeta['mobile_span'] ?? null,
                    'mobile_stack' => $responsiveMeta['mobile_stack'] ?? true,
                    'tablet_col' => $responsiveMeta['tablet_col'] ?? null,
                    'mobile_col' => $responsiveMeta['mobile_col'] ?? null,
                    'tablet_row' => $responsiveMeta['tablet_row'] ?? null,
                    'mobile_row' => $responsiveMeta['mobile_row'] ?? null,
                    'full' => in_array($areaName, $areaFullRows, true),
                    'nested' => in_array($areaName, $nestedAreas, true),
                    'padding' => $areaPadding[$areaName] ?? '',
                ];
            }, $areas),
        ]));
    }

    protected function wrapContainerPhp($designerState, $headerPhp, $body)
    {
        return <<<PHP
<?php
defined('C5_EXECUTE') or die('Access Denied.');

/* CM_DESIGNER_STATE {$designerState} CM_DESIGNER_STATE_END */

use Concrete\Core\Area\ContainerArea;
{$headerPhp}?>
{$body}

PHP;
    }

    protected function buildHeaderStyleInjection($class, $css)
    {
        $classExport = var_export($class, true);
        $cssExport = var_export($css, true);

        return <<<PHP

\$cmContainerClass = {$classExport};
\$cmContainerCss = {$cssExport};
if (!isset(\$GLOBALS['cm_container_styles_added'])) {
    \$GLOBALS['cm_container_styles_added'] = [];
}
if (!in_array(\$cmContainerClass, \$GLOBALS['cm_container_styles_added'], true)) {
    \\Concrete\\Core\\View\\View::getInstance()->addHeaderItem('<style>' . \$cmContainerCss . '</style>');
    \$GLOBALS['cm_container_styles_added'][] = \$cmContainerClass;
}

PHP;

    }

    protected function areaPhpBlock($areaName)
    {
        $escapedName = addslashes($areaName);

        return "<?php\n"
            . '$area = new ContainerArea($container, \'' . $escapedName . "');\n"
            . "\$area->display(\$c);\n"
            . '?>';
    }

    protected function indentLines($text, $spaces)
    {
        $prefix = str_repeat(' ', $spaces);

        return $prefix . str_replace("\n", "\n" . $prefix, rtrim((string) $text));
    }

    protected function wrapInner($inner, $content)
    {
        if ($inner === '') {
            return $content;
        }

        return '<div class="' . $inner . "\">\n" . $this->indentLines($content, 4) . "\n</div>";
    }

    protected function buildCssRules($scopeSelector, array $rules, $mediaExtra = '')
    {
        $lines = $rules;
        $lines[] = '.ccm-edit-mode ' . $scopeSelector . ' .ccm-area { min-height: 48px; outline: 1px dashed rgba(0, 0, 0, .25); outline-offset: -1px; }';
        $css = implode("\n", $lines);
        if ($mediaExtra !== '') {
            $css .= "\n" . rtrim($mediaExtra);
        }

        return $css;
    }

    protected function buildCssGridMarkup($class, $inner, array $areas, array $areaFullRows, array $responsiveColumns, array $settings, array $areaPadding = [], array $areaPlacement = [], array $areaResponsive = [], array $areaIds = [])
    {
        $scope = $settings['container_id'] !== '' ? ('#' . $this->safeHtmlId($settings['container_id'])) : ('.' . $class);
        $items = [];
        $desktopRules = [];
        $tabletRules = [];
        $mobileRules = [];
        $maxRow = 1;
        $trackCount = !empty($settings['grid_tracks']) ? count($settings['grid_tracks']) : max(1, substr_count($settings['grid_columns'], ' ') + 1);
        $i = 0;
        foreach ($areas as $areaName) {
            $i++;
            $placement = $areaPlacement[$areaName] ?? ['col' => 1, 'span' => 12, 'row' => 1];
            $row = max(1, (int) ($placement['row'] ?? 1));
            $maxRow = max($maxRow, $row);
            $idAttr = !empty($areaIds[$areaName]) ? (' id="' . $this->safeHtmlId($areaIds[$areaName]) . '"') : '';
            $items[] = '<div class="' . $class . '__item"' . $idAttr . ">\n" . $this->indentLines($this->areaPhpBlock($areaName), 4) . "\n</div>";
            $selector = $scope . ' .' . $class . '__grid > *:nth-child(' . $i . ')';
            $ruleParts = $this->buildAreaPlacementCss($areaName, $placement, $areaFullRows, $responsiveColumns);
            if (!in_array($areaName, $areaFullRows, true) && empty($placement['grid_column'])) {
                $col = max(1, (int) ($placement['col'] ?? 1));
                $desktop = (int) ($responsiveColumns[$areaName]['desktop'] ?? ($placement['span'] ?? 12));
                $tablet = (int) ($responsiveColumns[$areaName]['tablet'] ?? $desktop);
                $mobile = (int) ($responsiveColumns[$areaName]['mobile'] ?? $tablet);
                $desktop = max(1, $desktop);
                $meta = $areaResponsive[$areaName] ?? [];
                $mobileStack = !isset($meta['mobile_stack']) || $meta['mobile_stack'];
                $tabletCol = !empty($meta['tablet_col']) ? max(1, (int) $meta['tablet_col']) : $col;
                $mobileCol = !empty($meta['mobile_col']) ? max(1, (int) $meta['mobile_col']) : 1;
                $row = max(1, (int) ($placement['row'] ?? 1));
                $rowSpan = max(1, (int) ($placement['row_span'] ?? 1));
                $tabletRow = !empty($meta['tablet_row']) ? max(1, (int) $meta['tablet_row']) : $row;
                $mobileRow = !empty($meta['mobile_row']) ? max(1, (int) $meta['mobile_row']) : $row;
                $tabletSpan = !empty($meta['tablet_span']) ? max(1, (int) $meta['tablet_span']) : $tablet;
                $mobileSpan = !empty($meta['mobile_span']) ? max(1, (int) $meta['mobile_span']) : $mobile;
                if ($tabletSpan !== $desktop || $tabletCol !== $col || $tabletRow !== $row || $rowSpan > 1) {
                    $rule = $tabletSpan >= $trackCount
                        ? $selector . ' { grid-column: 1 / -1;'
                        : $selector . ' { grid-column: ' . $tabletCol . ' / span ' . max(1, $tabletSpan) . ';';
                    if ($tabletRow !== $row || $rowSpan > 1) {
                        $rule .= ' grid-row: ' . $this->viewportRowCss($tabletRow, $rowSpan) . ';';
                    }
                    $tabletRules[] = $rule . ' }';
                }
                $needsMobileRule = $mobileSpan !== $tabletSpan
                    || $mobileSpan !== $desktop
                    || $mobileStack
                    || $mobileCol !== $tabletCol
                    || $mobileRow !== $tabletRow
                    || $mobileRow !== $row
                    || $rowSpan > 1;
                if ($needsMobileRule) {
                    if ($mobileStack && empty($meta['mobile_span'])) {
                        $mobileRules[] = $selector . ' { grid-column: 1 / -1; grid-row: ' . $this->viewportRowCss($mobileRow, $rowSpan) . '; }';
                    } elseif ($mobileSpan >= $trackCount) {
                        $mobileRules[] = $selector . ' { grid-column: 1 / -1; grid-row: ' . $this->viewportRowCss($mobileRow, $rowSpan) . '; }';
                    } else {
                        $rule = $selector . ' { grid-column: ' . $mobileCol . ' / span ' . max(1, $mobileSpan) . ';';
                        if ($mobileRow !== $row || $rowSpan > 1) {
                            $rule .= ' grid-row: ' . $this->viewportRowCss($mobileRow, $rowSpan) . ';';
                        }
                        $mobileRules[] = $rule . ' }';
                    }
                }
            }
            $desktopRules[] = $selector . ' { ' . implode('; ', $ruleParts) . '; }';
        }
        $itemsHtml = $this->indentLines(implode("\n", $items), 4);
        $grid = '<div class="' . $class . "__grid\">\n{$itemsHtml}\n</div>";

        $content = $this->indentLines($this->wrapInner($inner, $grid), 4);
        $containerIdAttr = $settings['container_id'] !== '' ? (' id="' . $this->safeHtmlId($settings['container_id']) . '"') : '';
        $body = '<div class="' . $class . '"' . $containerIdAttr . ">\n{$content}\n</div>";

        $rules = array_merge(
            $this->buildCssGridRootRules($scope, $settings),
            [
                $scope . ' .' . $class . '__grid { display: grid; grid-template-columns: ' . $settings['grid_columns'] . '; grid-auto-rows: ' . $settings['grid_rows'] . '; gap: ' . $settings['gap'] . '; }',
            ],
            $desktopRules
        );
        $tabletColumns = trim((string) ($settings['tablet_columns'] ?? ''));
        $mobileColumns = trim((string) ($settings['mobile_columns'] ?? ''));
        $media = '';
        if (!empty($tabletRules) || $tabletColumns !== '') {
            $tabletBlock = [];
            if ($tabletColumns !== '') {
                $tabletBlock[] = $scope . ' .' . $class . '__grid { grid-template-columns: ' . $tabletColumns . '; }';
            }
            $tabletBlock = array_merge($tabletBlock, $tabletRules);
            $media .= "@media (max-width: 991px) {\n    " . implode("\n    ", $tabletBlock) . "\n}\n";
        }
        if (!empty($mobileRules) || $mobileColumns !== '') {
            $mobileBlock = [];
            if ($mobileColumns !== '') {
                $mobileBlock[] = $scope . ' .' . $class . '__grid { grid-template-columns: ' . $mobileColumns . '; }';
            }
            $mobileBlock = array_merge($mobileBlock, $mobileRules);
            $media .= "@media (max-width: 767px) {\n    " . implode("\n    ", $mobileBlock) . "\n}\n";
        }
        $css = $this->buildCssRules($scope, $rules, $media);

        return [$body, $css];
    }

    protected function buildCssGridRootRules($scope, array $settings)
    {
        $rules = [];
        $sideMargin = trim((string) ($settings['side_margin'] ?? ''));
        if ($sideMargin !== '') {
            $rules[] = $scope . ' { margin-left: ' . $sideMargin . '; margin-right: ' . $sideMargin . '; }';
        }

        return $rules;
    }

    protected function buildBootstrapMarkup($class, $inner, array $areas, array $areaFullRows, array $responsiveColumns, array $settings, array $areaPadding = [], array $areaPlacement = [], array $areaResponsive = [], array $areaIds = [])
    {
        $gutter = $this->safeBootstrapGutter($settings['bootstrap_gutter'] ?? 'g-3');
        $byRow = [];
        foreach ($areas as $areaName) {
            $placement = $areaPlacement[$areaName] ?? ['col' => 1, 'span' => 12, 'row' => 1];
            $row = max(1, (int) ($placement['row'] ?? 1));
            $byRow[$row][] = $areaName;
        }
        ksort($byRow);

        $sortedAreas = [];
        foreach ($byRow as $rowNum => $rowAreas) {
            usort($rowAreas, static function ($a, $b) use ($areaPlacement) {
                $colA = (int) (($areaPlacement[$a] ?? [])['col'] ?? 1);
                $colB = (int) (($areaPlacement[$b] ?? [])['col'] ?? 1);
                return $colA <=> $colB;
            });
            foreach ($rowAreas as $areaName) {
                $sortedAreas[] = $areaName;
            }
        }

        $packedRows = [];
        foreach ($sortedAreas as $areaName) {
            $placement = $areaPlacement[$areaName] ?? ['col' => 1, 'span' => 12, 'row' => 1];
            $isFull = in_array($areaName, $areaFullRows, true);
            $span = $isFull
                ? 12
                : max(1, min(12, (int) (($responsiveColumns[$areaName] ?? [])['desktop'] ?? ($placement['span'] ?? 12))));
            $col = $isFull
                ? 1
                : max(1, min(12 - $span + 1, (int) ($placement['col'] ?? 1)));

            $target = 0;
            while (true) {
                if (!isset($packedRows[$target])) {
                    $packedRows[$target] = [];
                    break;
                }
                $overlap = false;
                foreach ($packedRows[$target] as $item) {
                    $itemStart = $item['col'];
                    $itemEnd = $item['col'] + $item['span'] - 1;
                    $nextEnd = $col + $span - 1;
                    if (!($nextEnd < $itemStart || $itemEnd < $col)) {
                        $overlap = true;
                        break;
                    }
                }
                if (!$overlap) {
                    break;
                }
                $target++;
            }
            $packedRows[$target][] = ['area' => $areaName, 'col' => $col, 'span' => $span];
        }

        $rowsMarkup = [];
        $itemIndex = 0;
        foreach ($packedRows as $rowItems) {
            usort($rowItems, static function ($a, $b) {
                return $a['col'] <=> $b['col'];
            });
            $cols = [];
            foreach ($rowItems as $item) {
                $areaName = $item['area'];
                $itemIndex++;
                $placement = $areaPlacement[$areaName] ?? ['col' => 1, 'span' => 12, 'row' => 1];
                $meta = $areaResponsive[$areaName] ?? [];
                $colClasses = $this->buildBootstrapColClasses(
                    $areaName,
                    $placement,
                    $areaFullRows,
                    $responsiveColumns[$areaName] ?? [],
                    $meta
                );
                $itemClass = $class . '__item ' . $class . '__item--' . $itemIndex . ' ' . $colClasses;
                $idAttr = !empty($areaIds[$areaName]) ? (' id="' . $this->safeHtmlId($areaIds[$areaName]) . '"') : '';
                $style = '';
                if (!empty($placement['min_height'])) {
                    $style = $style === ''
                        ? (' style="min-height: ' . $placement['min_height'] . ';"')
                        : rtrim($style, '"') . '; min-height: ' . $placement['min_height'] . ';"';
                }
                $cols[] = '<div class="' . $itemClass . '"' . $idAttr . $style . ">\n" . $this->indentLines($this->areaPhpBlock($areaName), 4) . "\n</div>";
            }
            $rowsMarkup[] = '<div class="row ' . $gutter . "\">\n" . $this->indentLines(implode("\n", $cols), 4) . "\n</div>";
        }

        $grid = implode("\n", $rowsMarkup);
        $content = $this->indentLines($this->wrapInner($inner, $grid), 4);
        $containerIdAttr = $settings['container_id'] !== '' ? (' id="' . $this->safeHtmlId($settings['container_id']) . '"') : '';
        $body = '<div class="' . $class . '"' . $containerIdAttr . ">\n{$content}\n</div>";

        $css = $this->buildBootstrapCssBundle(
            $class,
            $areas,
            $areaFullRows,
            $responsiveColumns,
            $settings,
            $areaPlacement,
            $areaResponsive,
            $areaPadding
        );

        return [$body, $css];
    }

    protected function buildBootstrapColClasses($areaName, array $placement, array $areaFullRows, array $responsiveColumns, array $meta)
    {
        if (in_array($areaName, $areaFullRows, true)) {
            return 'col-12';
        }

        $desktopCol = max(1, min(12, (int) ($placement['col'] ?? 1)));
        $desktop = max(1, min(12, (int) ($responsiveColumns['desktop'] ?? ($placement['span'] ?? 12))));
        $tablet = max(1, min(12, (int) ($responsiveColumns['tablet'] ?? $desktop)));
        $mobileStack = !isset($meta['mobile_stack']) || $meta['mobile_stack'];
        $mobile = $mobileStack && empty($meta['mobile_span'])
            ? 12
            : max(1, min(12, (int) ($responsiveColumns['mobile'] ?? $tablet)));

        if (!empty($meta['tablet_span'])) {
            $tablet = max(1, min(12, (int) $meta['tablet_span']));
        }
        if (!empty($meta['mobile_span'])) {
            $mobile = max(1, min(12, (int) $meta['mobile_span']));
            $mobileStack = false;
        }

        $tabletCol = !empty($meta['tablet_col']) ? max(1, min(12, (int) $meta['tablet_col'])) : $desktopCol;
        $mobileCol = !empty($meta['mobile_col']) ? max(1, min(12, (int) $meta['mobile_col'])) : 1;

        $classes = ['col-' . $mobile];
        if ($mobileCol > 1 && $mobile < 12) {
            $classes[] = 'offset-' . ($mobileCol - 1);
        }
        $classes[] = 'col-md-' . $tablet;
        if ($tabletCol > 1 && $tablet < 12) {
            $classes[] = 'offset-md-' . ($tabletCol - 1);
        }
        $classes[] = 'col-lg-' . $desktop;
        if ($desktopCol > 1 && $desktop < 12) {
            $classes[] = 'offset-lg-' . ($desktopCol - 1);
        }

        return implode(' ', array_unique($classes));
    }

    protected function buildBootstrapCssBundle($class, array $areas, array $areaFullRows, array $responsiveColumns, array $settings, array $areaPlacement, array $areaResponsive, array $areaPadding)
    {
        $scope = $settings['container_id'] !== '' ? ('#' . $this->safeHtmlId($settings['container_id'])) : ('.' . $class);
        $rules = [];
        if (empty($settings['theme_has_bootstrap'])) {
            $rules[] = $this->buildBootstrapScopedGridCss($class, $scope, $settings['bootstrap_gutter'] ?? 'g-3');
        }
        $rules[] = $this->buildBootstrapOrderCss($class, $scope, $areas, $areaPlacement, $areaResponsive);
        $rules[] = '.ccm-edit-mode ' . $scope . ' .ccm-area { min-height: 48px; outline: 1px dashed rgba(0, 0, 0, .25); outline-offset: -1px; }';

        return implode("\n", array_filter($rules));
    }

    protected function buildBootstrapOrderCss($class, $scope, array $areas, array $areaPlacement, array $areaResponsive)
    {
        $tabletRules = [];
        $mobileRules = [];
        $itemIndex = 0;
        foreach ($areas as $areaName) {
            $itemIndex++;
            $placement = $areaPlacement[$areaName] ?? ['row' => 1, 'col' => 1];
            $meta = $areaResponsive[$areaName] ?? [];
            $desktopRow = max(1, (int) ($placement['row'] ?? 1));
            $desktopCol = max(1, (int) ($placement['col'] ?? 1));
            $tabletRow = !empty($meta['tablet_row']) ? max(1, (int) $meta['tablet_row']) : $desktopRow;
            $mobileRow = !empty($meta['mobile_row']) ? max(1, (int) $meta['mobile_row']) : $desktopRow;
            $selector = $scope . ' .' . $class . '__item--' . $itemIndex;

            if ($tabletRow !== $desktopRow || !empty($meta['tablet_col'])) {
                $tabletOrder = ($tabletRow * 100) + max(1, (int) ($meta['tablet_col'] ?? $desktopCol));
                $tabletRules[] = $selector . ' { order: ' . $tabletOrder . '; }';
            }
            if ($mobileRow !== $desktopRow || !empty($meta['mobile_col'])) {
                $mobileOrder = ($mobileRow * 100) + max(1, (int) ($meta['mobile_col'] ?? 1));
                $mobileRules[] = $selector . ' { order: ' . $mobileOrder . '; }';
            }
        }

        $css = '';
        if (!empty($tabletRules)) {
            $css .= "@media (max-width: 991px) {\n    " . $scope . " .row { display: flex; flex-wrap: wrap; }\n    " . implode("\n    ", $tabletRules) . "\n}\n";
        }
        if (!empty($mobileRules)) {
            $css .= "@media (max-width: 767px) {\n    " . $scope . " .row { display: flex; flex-wrap: wrap; }\n    " . implode("\n    ", $mobileRules) . "\n}\n";
        }

        return trim($css);
    }

    protected function buildBootstrapScopedGridCss($class, $scope, $gutter)
    {
        $gutter = $this->safeBootstrapGutter($gutter);
        $gutterRem = [
            'g-0' => '0',
            'g-1' => '.25rem',
            'g-2' => '.5rem',
            'g-3' => '1rem',
            'g-4' => '1.5rem',
            'g-5' => '3rem',
        ];
        $gap = $gutterRem[$gutter] ?? '1rem';
        $halfGap = $gap === '0' ? '0' : 'calc(' . $gap . ' * .5)';

        $lines = [
            '/* Container Maker — Bootstrap 5 grid scoped to ' . $scope . ' */',
            $scope . ' .container, ' . $scope . ' .container-fluid { width: 100%; padding-right: ' . $halfGap . '; padding-left: ' . $halfGap . '; margin-right: auto; margin-left: auto; }',
            $scope . ' .container { max-width: 1320px; }',
            $scope . ' .row { --bs-gutter-x: ' . $gap . '; --bs-gutter-y: 0; display: flex; flex-wrap: wrap; margin-top: calc(-1 * var(--bs-gutter-y)); margin-right: calc(-.5 * var(--bs-gutter-x)); margin-left: calc(-.5 * var(--bs-gutter-x)); }',
            $scope . ' .row > [class*="col-"], ' . $scope . ' .row > [class*="offset-"] { flex-shrink: 0; max-width: 100%; padding-right: calc(var(--bs-gutter-x) * .5); padding-left: calc(var(--bs-gutter-x) * .5); margin-top: var(--bs-gutter-y); box-sizing: border-box; }',
            $scope . ' .' . $class . '__row-break { flex-basis: 100%; width: 100%; height: 0; overflow: hidden; padding: 0; margin: 0; border: 0; }',
        ];

        $baseRules = [];
        $mdColRules = [];
        $lgColRules = [];
        $baseOffsetRules = [];
        $mdOffsetRules = [];
        $lgOffsetRules = [];
        for ($i = 1; $i <= 12; $i++) {
            $pct = round(100 / 12 * $i, 6);
            $baseRules[] = $scope . ' .row > .col-' . $i . ' { flex: 0 0 auto; width: ' . $pct . '%; }';
            $mdColRules[] = $scope . ' .row > .col-md-' . $i . ' { flex: 0 0 auto; width: ' . $pct . '%; }';
            $lgColRules[] = $scope . ' .row > .col-lg-' . $i . ' { flex: 0 0 auto; width: ' . $pct . '%; }';
        }
        $baseRules[] = $scope . ' .row > .col-12 { flex: 0 0 auto; width: 100%; }';
        $mdColRules[] = $scope . ' .row > .col-md-12 { flex: 0 0 auto; width: 100%; }';
        $lgColRules[] = $scope . ' .row > .col-lg-12 { flex: 0 0 auto; width: 100%; }';

        for ($i = 1; $i <= 11; $i++) {
            $pct = round(100 / 12 * $i, 6);
            $baseOffsetRules[] = $scope . ' .row > .offset-' . $i . ' { margin-left: ' . $pct . '%; }';
            $mdOffsetRules[] = $scope . ' .row > .offset-md-' . $i . ' { margin-left: ' . $pct . '%; }';
            $lgOffsetRules[] = $scope . ' .row > .offset-lg-' . $i . ' { margin-left: ' . $pct . '%; }';
        }

        $lines = array_merge($lines, $baseRules, $baseOffsetRules);
        $lines[] = '@media (min-width: 768px) {';
        $lines[] = '    ' . implode("\n    ", array_merge($mdColRules, $mdOffsetRules));
        $lines[] = '}';
        $lines[] = '@media (min-width: 992px) {';
        $lines[] = '    ' . implode("\n    ", array_merge($lgColRules, $lgOffsetRules));
        $lines[] = '}';

        return implode("\n", $lines);
    }

    protected function safeBootstrapGutter($value)
    {
        return $this->inList((string) $value, ['g-0', 'g-1', 'g-2', 'g-3', 'g-4', 'g-5'], 'g-3');
    }

    protected function parseAreaNames($input)
    {
        $parts = preg_split('/[\r\n,]+/', $input);
        $areas = [];
        foreach ($parts as $part) {
            $part = trim($part);
            if ($part !== '') {
                $areas[] = $part;
            }
        }
        return array_values(array_unique($areas));
    }

    protected function buildAreaPlacementCss($areaName, array $placement, array $areaFullRows, array $responsiveColumns)
    {
        $rules = [];
        if (in_array($areaName, $areaFullRows, true)) {
            $rules[] = 'grid-column: 1 / -1';
        } elseif (!empty($placement['grid_column'])) {
            $rules[] = 'grid-column: ' . $placement['grid_column'];
        } else {
            $col = max(1, (int) ($placement['col'] ?? 1));
            $span = max(1, (int) ($placement['span'] ?? ($responsiveColumns[$areaName]['desktop'] ?? 12)));
            $rules[] = 'grid-column: ' . $col . ' / span ' . $span;
        }

        if (!empty($placement['grid_row'])) {
            $rules[] = 'grid-row: ' . $placement['grid_row'];
        } else {
            $row = max(1, (int) ($placement['row'] ?? 1));
            $rowSpan = max(1, (int) ($placement['row_span'] ?? 1));
            $rules[] = $rowSpan > 1 ? ('grid-row: ' . $row . ' / span ' . $rowSpan) : ('grid-row: ' . $row);
        }

        if (!empty($placement['min_height'])) {
            $rules[] = 'min-height: ' . $placement['min_height'];
        }

        return $rules;
    }

    protected function parseAreaPlacement($input, array $areas)
    {
        $placement = [];
        $lines = preg_split('/[\r\n]+/', $input);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '::') === false) {
                continue;
            }
            [$area, $value] = array_map('trim', explode('::', $line, 2));
            if ($area === '' || !in_array($area, $areas, true)) {
                continue;
            }
            $parts = array_pad(explode('|', $value), 7, '');
            $placement[$area] = [
                'col' => max(1, (int) $parts[0]),
                'span' => max(1, (int) $parts[1]),
                'row' => max(1, (int) $parts[2]),
                'row_span' => max(1, (int) ($parts[3] !== '' ? $parts[3] : 1)),
                'grid_column' => $this->safeGridPlacement($parts[4] ?? ''),
                'grid_row' => $this->safeGridPlacement($parts[5] ?? ''),
                'min_height' => $this->safeCssSize($parts[6] ?? ''),
            ];
        }

        return $placement;
    }

    protected function parseResponsiveColumns($input, array $areas)
    {
        $columns = [];
        $lines = preg_split('/[\r\n]+/', $input);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '::') === false) {
                continue;
            }
            [$area, $value] = array_map('trim', explode('::', $line, 2));
            if ($area === '' || !in_array($area, $areas, true)) {
                continue;
            }
            $parts = array_map('intval', array_pad(explode('|', $value), 3, 12));
            $columns[$area] = [
                'desktop' => max(1, $parts[0]),
                'tablet' => max(1, $parts[1]),
                'mobile' => max(1, $parts[2]),
            ];
        }
        return $columns;
    }

    protected function parseAreaResponsive($input, array $areas)
    {
        $responsive = [];
        $lines = preg_split('/[\r\n]+/', $input);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '::') === false) {
                continue;
            }
            [$area, $value] = array_map('trim', explode('::', $line, 2));
            if ($area === '' || !in_array($area, $areas, true)) {
                continue;
            }
            $parts = array_pad(explode('|', $value), 7, '');
            $responsive[$area] = [
                'tablet_span' => $parts[0] !== '' ? max(1, (int) $parts[0]) : null,
                'mobile_span' => $parts[1] !== '' ? max(1, (int) $parts[1]) : null,
                'mobile_stack' => ($parts[2] ?? '1') !== '0',
                'tablet_col' => $parts[3] !== '' ? max(1, (int) $parts[3]) : null,
                'mobile_col' => $parts[4] !== '' ? max(1, (int) $parts[4]) : null,
                'tablet_row' => $parts[5] !== '' ? max(1, (int) $parts[5]) : null,
                'mobile_row' => $parts[6] !== '' ? max(1, (int) $parts[6]) : null,
            ];
        }

        return $responsive;
    }

    protected function viewportRowCss($row, $rowSpan)
    {
        $row = max(1, (int) $row);
        $rowSpan = max(1, (int) $rowSpan);

        return $rowSpan > 1 ? ($row . ' / span ' . $rowSpan) : (string) $row;
    }

    protected function parseAreaFullRows($input, array $areas)
    {
        $fullRows = [];
        $lines = preg_split('/[\r\n,]+/', $input);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line !== '' && in_array($line, $areas, true)) {
                $fullRows[] = $line;
            }
        }
        return array_values(array_unique($fullRows));
    }

    protected function parseAreaPadding($input, array $areas)
    {
        return [];
    }

    protected function parseAreaIds($input, array $areas)
    {
        $ids = [];
        $lines = preg_split('/[\r\n]+/', $input);
        foreach ($lines as $line) {
            $line = trim((string) $line);
            if ($line === '' || strpos($line, '::') === false) {
                continue;
            }
            [$area, $value] = array_map('trim', explode('::', $line, 2));
            if ($area === '' || !in_array($area, $areas, true)) {
                continue;
            }
            $id = $this->safeHtmlId($value);
            if ($id !== '') {
                $ids[$area] = $id;
            }
        }

        return $ids;
    }

    protected function normalizeSettings(array $data)
    {
        $gridTracks = [];
        if (!empty($data['grid_tracks'])) {
            $decoded = json_decode((string) $data['grid_tracks'], true);
            if (is_array($decoded)) {
                $gridTracks = $decoded;
            }
        }

        $gridColumns = $this->safeGridTemplate($data['grid_columns'] ?? '', 'repeat(12, minmax(0, 1fr))');
        $gridRows = $this->sanitizeGridAutoRows($this->safeGridTemplate($data['grid_rows'] ?? '', 'minmax(64px, auto)'));
        $viewportSettings = [];
        if (!empty($data['viewport_settings'])) {
            $decoded = json_decode((string) $data['viewport_settings'], true);
            if (is_array($decoded)) {
                $viewportSettings = $decoded;
            }
        }
        $bootstrapSettings = [];
        if (!empty($data['bootstrap_settings'])) {
            $decoded = json_decode((string) $data['bootstrap_settings'], true);
            if (is_array($decoded)) {
                $bootstrapSettings = $decoded;
            }
        }
        if (!empty($data['theme_has_bootstrap'])) {
            $bootstrapSettings['theme_has_bootstrap'] = true;
        }

        $layoutMode = $this->inList($data['layout_mode'] ?? '', ['css_grid', 'bootstrap5'], 'css_grid');

        return [
            'layout_mode' => $layoutMode,
            'inner' => $this->inList($data['inner'] ?? '', ['container', 'container-fluid', ''], ''),
            'gap' => $layoutMode === 'bootstrap5'
                ? ''
                : 'var(--cm-theme-gap, 1rem)',
            'side_margin' => $layoutMode === 'bootstrap5'
                ? ''
                : $this->safeOptionalCssLength($data['side_margin'] ?? '', ''),
            'theme_id' => (int) ($data['theme_id'] ?? 0),
            'grid_columns' => $gridColumns,
            'grid_rows' => $gridRows,
            'grid_tracks' => $gridTracks,
            'row_size' => is_array($data['row_size'] ?? null) ? $data['row_size'] : [],
            'tablet_columns' => $this->safeGridTemplate($viewportSettings['tablet_columns'] ?? '', ''),
            'mobile_columns' => $this->safeGridTemplate($viewportSettings['mobile_columns'] ?? '', ''),
            'theme_has_bootstrap' => !empty($bootstrapSettings['theme_has_bootstrap']) || !empty($data['theme_has_bootstrap']),
            'bootstrap_gutter' => $this->safeBootstrapGutter($bootstrapSettings['gutter'] ?? 'g-3'),
            'container_id' => $this->safeHtmlId($data['container_id'] ?? ''),
        ];
    }

    protected function safeHtmlId($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }
        $value = preg_replace('/[^A-Za-z0-9\-_:.]+/', '-', $value);
        $value = trim((string) $value, '-');
        if ($value === '') {
            return '';
        }
        if (!preg_match('/^[A-Za-z]/', $value)) {
            $value = 'cm-' . $value;
        }
        if (strlen($value) > 120) {
            $value = substr($value, 0, 120);
        }

        return $value;
    }

    protected function safeOptionalCssLength($value, $default)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return $default;
        }

        return $this->safeCssLength($value, $default);
    }

    protected function sanitizeGridAutoRows($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return 'minmax(64px, auto)';
        }
        if (preg_match('/^clamp\([^)]*(?:fr|%).*?(?:fr|%)[^)]*\)/i', $value)) {
            return 'minmax(64px, auto)';
        }

        return $value;
    }

    protected function safeGridTemplate($value, $default)
    {
        $value = trim((string) $value);
        if ($value === '' || strlen($value) > 600) {
            return $default;
        }
        if (preg_match('/[<>{};]/', $value)) {
            return $default;
        }

        return $value;
    }

    protected function safeGridPlacement($value)
    {
        $value = trim((string) $value);
        if ($value === '' || strlen($value) > 160) {
            return '';
        }
        if (!preg_match('/^[a-z0-9\\s\\-\\/%,.+()fr]+$/i', $value)) {
            return '';
        }

        return $value;
    }

    protected function safeCssSize($value)
    {
        $value = trim((string) $value);
        if ($value === '' || strlen($value) > 160) {
            return '';
        }
        if (preg_match('/[<>{};]/', $value)) {
            return '';
        }
        if (preg_match('/^(clamp|minmax|fit-content)\(/i', $value)) {
            return $value;
        }

        return $this->safeCssLength($value, '') ?: '';
    }

    protected function sanitizeHandle($handle)
    {
        $handle = strtolower(trim($handle));
        $handle = preg_replace('/[^a-z0-9]+/', '_', $handle);

        return trim($handle, '_');
    }

    protected function inList($value, array $allowed, $default)
    {
        $value = (string) $value;

        return in_array($value, $allowed, true) ? $value : $default;
    }

    protected function safeCssLength($value, $default)
    {
        $value = trim((string) $value);

        return preg_match('/^[-0-9.]+(px|rem|em|%|vw|vh)?$/', $value) ? $value : $default;
    }

    protected function safeCssLengthList($value, $default)
    {
        $value = trim((string) $value);

        return preg_match('/^[-0-9. ]+(px|rem|em|%|vw|vh)?( [-0-9.]+(px|rem|em|%|vw|vh)?){0,3}$/', $value) ? $value : $default;
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
        $paths = [
            DIR_BASE . '/application/themes/' . $handle,
            DIR_BASE . '/themes/' . $handle,
            DIR_BASE_CORE . '/themes/' . $handle,
        ];
        foreach ($paths as $path) {
            if (is_dir($path)) {
                return $path;
            }
        }
        return null;
    }

    protected function tryRegisterContainer($handle, $name)
    {
        $classes = [
            '\\Concrete\\Core\\Entity\\Page\\Container',
            '\\Concrete\\Core\\Entity\\Page\\ContainerTemplate',
        ];

        foreach ($classes as $class) {
            if (!class_exists($class)) {
                continue;
            }
            try {
                $app = \Core::make('app');
                $em = $app->make('database/orm')->entityManager();
                $repo = $em->getRepository($class);
                $existing = method_exists($repo, 'findOneBy') ? $repo->findOneBy(['containerHandle' => $handle]) : null;
                if ($existing) {
                    return true;
                }
                $entity = new $class();
                if (method_exists($entity, 'setContainerName')) {
                    $entity->setContainerName($name);
                }
                if (method_exists($entity, 'setContainerHandle')) {
                    $entity->setContainerHandle($handle);
                }
                $em->persist($entity);
                $em->flush();

                return true;
            } catch (\Throwable $e) {
                return t('Created file for %s, but automatic registration failed: %s', $handle, $e->getMessage());
            }
        }

        return t('Created file for %s, but automatic registration is not available. Add it manually in Dashboard → Pages & Themes → Containers.', $handle);
    }
}
