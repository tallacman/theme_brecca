<?php

declare(strict_types=1);

namespace Concrete\Package\TallacmansTextHr;

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Package\Package;
use Concrete\Core\Support\Facade\Database;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends Package
{
    protected $pkgHandle = 'tallacmans_text_hr';

    protected $appVersionRequired = '9.0.0';

    protected $pkgVersion = '2.0.9';

    public function getPackageName()
    {
        return t("Tallacman's Text HR");
    }

    public function getPackageDescription()
    {
        return t('A decorative horizontal rule with centered text and customizable typography.');
    }

    public function install()
    {
        $pkg = parent::install();

        if (!BlockType::getByHandle('tallacmans_text_hr')) {
            BlockType::installBlockType('tallacmans_text_hr', $pkg);
        }

        return $pkg;
    }

    public function upgrade()
    {
        $pkg = parent::upgrade();

        $blockType = BlockType::getByHandle('tallacmans_text_hr');
        if ($blockType) {
            $blockType->refresh();
        }

        $this->migrateLegacyTypography();
        $this->migrateLegacyLineAndBorder();

        return $pkg;
    }

    public function uninstall()
    {
        $pkg = parent::uninstall();

        $db = Database::connection();
        $db->executeQuery('drop table if exists btTallacmansTextHr');

        return $pkg;
    }

    protected function migrateLegacyTypography()
    {
        $db = Database::connection();
        $schemaManager = $db->getSchemaManager();

        if (!$schemaManager->tablesExist(['btTallacmansTextHr'])) {
            return;
        }

        $sizeMap = [
            '1' => 16,
            '2' => 14,
            '3' => 19,
            '4' => 35,
            '5' => 48,
        ];
        $weightMap = [
            '1' => '400',
            '2' => '300',
            '3' => '600',
            '4' => '700',
        ];
        $leadingMap = [
            '1' => '0',
            '2' => '-0.5',
            '3' => '1',
            '4' => '3',
            '5' => '5',
        ];

        $rows = $db->fetchAllAssociative(
            'select bID, textSize, textLeading, textWeight from btTallacmansTextHr'
        );

        foreach ($rows as $row) {
            $textSize = (string) ($row['textSize'] ?? '');
            if ($textSize === '') {
                continue;
            }

            $textLeading = (string) ($row['textLeading'] ?? '');
            $textWeight = (string) ($row['textWeight'] ?? '');

            $db->update('btTallacmansTextHr', [
                'fontSize' => $sizeMap[$textSize] ?? 16,
                'fontWeight' => $weightMap[$textWeight] ?? '400',
                'letterSpacing' => $leadingMap[$textLeading] ?? '0',
            ], ['bID' => (int) $row['bID']]);
        }
    }

    protected function migrateLegacyLineAndBorder()
    {
        $db = Database::connection();
        $schemaManager = $db->getSchemaManager();

        if (!$schemaManager->tablesExist(['btTallacmansTextHr'])) {
            return;
        }

        $heightMap = [
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
        ];
        $borderMap = [
            '1' => 0,
            '2' => 1,
            '3' => 2,
            '4' => 3,
            '5' => 4,
            '6' => 5,
        ];

        $rows = $db->fetchAllAssociative(
            'select bID, heightLine, textBorder from btTallacmansTextHr'
        );

        foreach ($rows as $row) {
            $updates = [];
            $heightLine = (string) ($row['heightLine'] ?? '');
            $textBorder = (string) ($row['textBorder'] ?? '');

            if (array_key_exists($heightLine, $heightMap)) {
                $updates['heightLine'] = $heightMap[$heightLine];
            }

            if (array_key_exists($textBorder, $borderMap)) {
                $updates['textBorder'] = $borderMap[$textBorder];
            }

            if ($updates !== []) {
                $db->update('btTallacmansTextHr', $updates, ['bID' => (int) $row['bID']]);
            }
        }
    }
}
