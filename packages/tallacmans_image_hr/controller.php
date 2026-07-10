<?php

declare(strict_types=1);

namespace Concrete\Package\TallacmansImageHr;

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Package\Package;
use Concrete\Core\Support\Facade\Database;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends Package
{
    protected $pkgHandle = 'tallacmans_image_hr';

    protected $appVersionRequired = '9.0.0';

    protected $pkgVersion = '2.0.7';

    public function getPackageName()
    {
        return t("Tallacman's Image HR");
    }

    public function getPackageDescription()
    {
        return t('A decorative horizontal rule with a centered circular image.');
    }

    public function install()
    {
        $pkg = parent::install();

        if (!BlockType::getByHandle('tallacmans_image_hr')) {
            BlockType::installBlockType('tallacmans_image_hr', $pkg);
        }

        return $pkg;
    }

    public function upgrade()
    {
        $this->prepareLegacyDataForSchemaChange();

        $pkg = parent::upgrade();

        $blockType = BlockType::getByHandle('tallacmans_image_hr');
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
        $db->executeQuery('drop table if exists btTallacmansImageHr');

        return $pkg;
    }

    protected function prepareLegacyDataForSchemaChange()
    {
        $db = Database::connection();
        $schemaManager = $db->getSchemaManager();

        if (!$schemaManager->tablesExist(['btTallacmansImageHr'])) {
            return;
        }

        $sizeMap = [
            '1' => 45,
            '2' => 85,
            '3' => 110,
            '4' => 140,
            '5' => 175,
        ];
        $paddingMap = [
            '' => 0,
            '1' => 0,
            '2' => 3,
            '3' => 5,
            '4' => 9,
            '5' => 16,
        ];
        $borderMap = [
            '' => 0,
            '1' => 0,
            '2' => 1,
            '3' => 2,
            '4' => 3,
            '5' => 4,
            '6' => 5,
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
            'select bID, sizeImage, paddingImage, borderImage, heightLine from btTallacmansImageHr'
        );

        foreach ($rows as $row) {
            $sizeImage = (string) ($row['sizeImage'] ?? '');
            $paddingImage = (string) ($row['paddingImage'] ?? '');
            $borderImage = (string) ($row['borderImage'] ?? '');
            $heightLine = (string) ($row['heightLine'] ?? '');

            $resolvedSize = array_key_exists($sizeImage, $sizeMap)
                ? $sizeMap[$sizeImage]
                : max(1, (int) $sizeImage ?: 110);
            $resolvedPadding = array_key_exists($paddingImage, $paddingMap)
                ? $paddingMap[$paddingImage]
                : max(0, (int) $paddingImage);
            $resolvedBorder = array_key_exists($borderImage, $borderMap)
                ? $borderMap[$borderImage]
                : max(0, (int) $borderImage);
            $resolvedHeight = array_key_exists($heightLine, $heightMap)
                ? $heightMap[$heightLine]
                : max(1, (int) $heightLine ?: 1);

            $db->update('btTallacmansImageHr', [
                'sizeImage' => (string) $resolvedSize,
                'paddingImage' => (string) $resolvedPadding,
                'borderImage' => (string) $resolvedBorder,
                'heightLine' => (string) $resolvedHeight,
            ], ['bID' => (int) $row['bID']]);
        }
    }

    protected function migrateLegacyValues()
    {
        $db = Database::connection();
        $schemaManager = $db->getSchemaManager();

        if (!$schemaManager->tablesExist(['btTallacmansImageHr'])) {
            return;
        }

        $rows = $db->fetchAllAssociative(
            'select bID, sizeImage, blockMarginTop, blockMarginBottom from btTallacmansImageHr'
        );

        foreach ($rows as $row) {
            $updates = [];
            $sizeImage = max(1, (int) ($row['sizeImage'] ?? 110));

            if (!isset($row['blockMarginTop']) || (int) $row['blockMarginTop'] === 0) {
                $updates['blockMarginTop'] = (int) round($sizeImage / 2) + 25;
            }

            if (!isset($row['blockMarginBottom']) || (int) $row['blockMarginBottom'] === 0) {
                $updates['blockMarginBottom'] = (int) round($sizeImage / 2) + 25;
            }

            if ($updates !== []) {
                $db->update('btTallacmansImageHr', $updates, ['bID' => (int) $row['bID']]);
            }
        }
    }
}
