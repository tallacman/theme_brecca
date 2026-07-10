<?php

declare(strict_types=1);

namespace Concrete\Package\TallacmansIconHr;

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Package\Package;
use Concrete\Core\Support\Facade\Database;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends Package
{
    protected $pkgHandle = 'tallacmans_icon_hr';

    protected $appVersionRequired = '9.0.0';

    protected $pkgVersion = '2.0.5';

    public function getPackageName()
    {
        return t("Tallacman's Icon HR");
    }

    public function getPackageDescription()
    {
        return t('A decorative horizontal rule with a centered Font Awesome icon.');
    }

    public function install()
    {
        $pkg = parent::install();

        if (!BlockType::getByHandle('tallacmans_icon_hr')) {
            BlockType::installBlockType('tallacmans_icon_hr', $pkg);
        }

        return $pkg;
    }

    public function upgrade()
    {
        $this->prepareLegacyDataForSchemaChange();

        $pkg = parent::upgrade();

        $blockType = BlockType::getByHandle('tallacmans_icon_hr');
        if ($blockType) {
            $blockType->refresh();
        }

        $this->migrateLegacyValues();

        return $pkg;
    }

    public function uninstall()
    {
        $pkg = parent::uninstall();

        $db = Database::connection();
        $db->executeQuery('drop table if exists btTallacmansIconHr');

        return $pkg;
    }

    protected function prepareLegacyDataForSchemaChange()
    {
        $db = Database::connection();
        $schemaManager = $db->getSchemaManager();

        if (!$schemaManager->tablesExist(['btTallacmansIconHr'])) {
            return;
        }

        $sizeMap = [
            '' => 46,
            '1' => 24,
            '2' => 37,
            '3' => 46,
            '4' => 54,
            '5' => 64,
        ];
        $heightMap = [
            '' => 1,
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
        ];

        $rows = $db->fetchAllAssociative(
            'select bID, icon, sizeIcon, heightLine from btTallacmansIconHr'
        );

        foreach ($rows as $row) {
            $sizeIcon = (string) ($row['sizeIcon'] ?? '');
            $heightLine = (string) ($row['heightLine'] ?? '');
            $icon = trim((string) ($row['icon'] ?? ''));

            if ($icon !== '' && preg_match('/^fa-[a-z0-9-]+$/i', $icon)) {
                $icon = 'fas ' . $icon;
            }

            $resolvedSize = array_key_exists($sizeIcon, $sizeMap)
                ? $sizeMap[$sizeIcon]
                : max(12, (int) $sizeIcon ?: 46);
            $resolvedHeight = array_key_exists($heightLine, $heightMap)
                ? $heightMap[$heightLine]
                : max(1, (int) $heightLine ?: 1);

            $db->update('btTallacmansIconHr', [
                'icon' => $icon,
                'sizeIcon' => (string) $resolvedSize,
                'heightLine' => (string) $resolvedHeight,
            ], ['bID' => (int) $row['bID']]);
        }
    }

    protected function migrateLegacyValues()
    {
        $db = Database::connection();
        $schemaManager = $db->getSchemaManager();

        if (!$schemaManager->tablesExist(['btTallacmansIconHr'])) {
            return;
        }

        $rows = $db->fetchAllAssociative(
            'select bID, sizeIcon, blockMarginTop, blockMarginBottom from btTallacmansIconHr'
        );

        foreach ($rows as $row) {
            $updates = [];

            if (!isset($row['blockMarginTop']) || (int) $row['blockMarginTop'] === 0) {
                $updates['blockMarginTop'] = 33;
            }

            if (!isset($row['blockMarginBottom']) || (int) $row['blockMarginBottom'] === 0) {
                $updates['blockMarginBottom'] = 33;
            }

            if ($updates !== []) {
                $db->update('btTallacmansIconHr', $updates, ['bID' => (int) $row['bID']]);
            }
        }
    }
}
