<?php

namespace Concrete\Package\ThemeBrecca\Block\TallacmansBackgroundImage;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Block\BlockController;

class Controller extends BlockController
{
    protected $btInterfaceHeight = 360;
    protected $btInterfaceWidth = 520;
    protected $btCacheBlockOutput = true;
    protected $btExportFileColumns = ['fID'];
    protected $btIgnorePageThemeGridFrameworkContainer = true;
    protected $btTable = 'btTallacmansBackgroundImage';
    protected $btDefaultSet = 'multimedia';

    public function getBlockTypeDescription()
    {
        return t('Add a full-screen background image with an optional color overlay.');
    }

    public function getBlockTypeName()
    {
        return t('Tallacmans Background Image');
    }

    public function validate($args)
    {
        $errors = $this->app->make('error');
        if (empty($args['fID'])) {
            $errors->add(t('Please select a background image.'));
        }

        return $errors;
    }

    public function save($args)
    {
        $args['fID'] = (int) ($args['fID'] ?? 0);
        $args['bgOverlayColor'] = trim((string) ($args['bgOverlayColor'] ?? ''));

        parent::save($args);
    }
}
