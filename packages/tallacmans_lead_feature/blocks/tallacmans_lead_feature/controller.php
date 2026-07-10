<?php

declare(strict_types=1);

namespace Concrete\Package\TallacmansLeadFeature\Block\TallacmansLeadFeature;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use Concrete\Core\File\File;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{
    protected $btExportFileColumns = ['Image'];

    protected $btExportContentColumns = ['LeadText', 'SecondaryText'];

    protected $btTable = 'btTallacmansLeadFeature';

    protected $btInterfaceWidth = 780;

    protected $btInterfaceHeight = 720;

    protected $btIgnorePageThemeGridFrameworkContainer = true;

    protected $btDefaultSet = 'basic';

    protected $btCacheBlockRecord = true;

    protected $btCacheBlockOutput = true;

    protected $btCacheBlockOutputOnPost = true;

    protected $btCacheBlockOutputForRegisteredUsers = true;

    protected $pkg = 'tallacmans_lead_feature';

    public $Image;

    public $Align;

    public $Height;

    public $ScrollBehaviour;

    public $Overlay;

    public $LeadText;

    public $LeadAnimation;

    public $LeadSpeed;

    public $LeadDelay;

    public $SecondaryText;

    public $SecondaryAnimation;

    public $SecondarySpeed;

    public $SecondaryDelay;

    public $ShowArrow;

    public $ChevronColor;

    public function getBlockTypeDescription()
    {
        return t('Lead your page with a full-width image, overlay, and animated text.');
    }

    public function getBlockTypeName()
    {
        return t("Tallacman's Lead Feature");
    }

    public function getSearchableContent()
    {
        $lead = strip_tags(LinkAbstractor::translateFrom((string) ($this->LeadText ?? '')));
        $secondary = strip_tags(LinkAbstractor::translateFrom((string) ($this->SecondaryText ?? '')));

        return trim($lead . ' ' . $secondary);
    }

    public function view()
    {
        $file = null;
        if (!empty($this->Image) && ($file = File::getByID((int) $this->Image)) && is_object($file) && !$file->isError()) {
            $this->set('Image', $file);
        } else {
            $this->set('Image', false);
        }

        $this->set('Height', $this->sanitizeHeight($this->Height ?? '50vh'));
        $this->set('Align', $this->sanitizeAlign($this->Align ?? 'center center'));
        $this->set('ScrollBehaviour', $this->sanitizeScrollBehaviour($this->ScrollBehaviour ?? 'scroll'));
        $this->set('Overlay', $this->sanitizeColor($this->Overlay ?? '', 'rgba(0,0,0,0.1)'));
        $this->set('LeadText', LinkAbstractor::translateFrom((string) ($this->LeadText ?? '')));
        $this->set('SecondaryText', LinkAbstractor::translateFrom((string) ($this->SecondaryText ?? '')));
        $this->set('LeadAnimation', $this->sanitizeAnimation($this->LeadAnimation ?? ''));
        $this->set('LeadSpeed', $this->sanitizeSpeed($this->LeadSpeed ?? ''));
        $this->set('LeadDelay', $this->sanitizeDelay($this->LeadDelay ?? ''));
        $this->set('SecondaryAnimation', $this->sanitizeAnimation($this->SecondaryAnimation ?? ''));
        $this->set('SecondarySpeed', $this->sanitizeSpeed($this->SecondarySpeed ?? ''));
        $this->set('SecondaryDelay', $this->sanitizeDelay($this->SecondaryDelay ?? ''));
        $this->set('ShowArrow', $this->sanitizeShowArrow($this->ShowArrow ?? '0'));
        $this->set('showArrow', $this->isShowArrowEnabled($this->ShowArrow ?? '0'));
        $this->set('ChevronColor', $this->sanitizeColor($this->ChevronColor ?? '', '#ffffff'));
    }

    public function add()
    {
        $this->addEdit();
        $this->setDefaults();
    }

    public function edit()
    {
        $this->addEdit();
        $this->set('LeadText', LinkAbstractor::translateFromEditMode((string) ($this->LeadText ?? '')));
        $this->set('SecondaryText', LinkAbstractor::translateFromEditMode((string) ($this->SecondaryText ?? '')));
    }

    public function composer()
    {
        $this->edit();
    }

    public function save($args)
    {
        $args['Image'] = (int) ($args['Image'] ?? 0);
        $args['Height'] = $this->sanitizeHeight($args['Height'] ?? '50vh');
        $args['Align'] = $this->sanitizeAlign($args['Align'] ?? 'center center');
        $args['ScrollBehaviour'] = $this->sanitizeScrollBehaviour($args['ScrollBehaviour'] ?? 'scroll');
        $args['Overlay'] = $this->sanitizeColor($args['Overlay'] ?? '', 'rgba(0,0,0,0.1)');
        $args['LeadText'] = LinkAbstractor::translateTo((string) ($args['LeadText'] ?? ''));
        $args['SecondaryText'] = LinkAbstractor::translateTo((string) ($args['SecondaryText'] ?? ''));
        $args['LeadAnimation'] = $this->sanitizeAnimation($args['LeadAnimation'] ?? '');
        $args['LeadSpeed'] = $this->sanitizeSpeed($args['LeadSpeed'] ?? '');
        $args['LeadDelay'] = $this->sanitizeDelay($args['LeadDelay'] ?? '');
        $args['SecondaryAnimation'] = $this->sanitizeAnimation($args['SecondaryAnimation'] ?? '');
        $args['SecondarySpeed'] = $this->sanitizeSpeed($args['SecondarySpeed'] ?? '');
        $args['SecondaryDelay'] = $this->sanitizeDelay($args['SecondaryDelay'] ?? '');
        $args['ShowArrow'] = $this->sanitizeShowArrow($args['ShowArrow'] ?? '0');
        $args['ChevronColor'] = $this->sanitizeColor($args['ChevronColor'] ?? '', '#ffffff');

        return parent::save($args);
    }

    public function validate($args)
    {
        $e = $this->app->make('error');

        foreach (['Overlay', 'ChevronColor'] as $field) {
            if (!empty($args[$field]) && !$this->isValidColor((string) $args[$field])) {
                $e->add(t('Color values must be a valid hex or rgba color.'));
                break;
            }
        }

        return $e;
    }

    protected function addEdit()
    {
        $this->set('identifier_getString', uniqid('tallacmans_lead_feature_', true));
        $this->set('Height_options', $this->getHeightOptions());
        $this->set('Align_options', $this->getAlignOptions());
        $this->set('ScrollBehaviour_options', $this->getScrollBehaviourOptions());
        $this->set('ShowArrow_options', $this->getShowArrowOptions());
        $this->set('LeadAnimation_options', $this->getAnimationOptions());
        $this->set('SecondaryAnimation_options', $this->getAnimationOptions());
        $this->set('LeadDelay_options', $this->getDelayOptions());
        $this->set('SecondaryDelay_options', $this->getDelayOptions());
        $this->set('LeadSpeed_options', $this->getSpeedOptions());
        $this->set('SecondarySpeed_options', $this->getSpeedOptions());
        $this->set('ShowArrow', $this->sanitizeShowArrow($this->ShowArrow ?? '0'));

        $this->requireAsset('core/file-manager');
        $this->requireAsset('core/editor');
    }

    protected function setDefaults()
    {
        $this->set('Image', 0);
        $this->set('Height', '50vh');
        $this->set('Align', 'center center');
        $this->set('ScrollBehaviour', 'scroll');
        $this->set('Overlay', 'rgba(0,0,0,0.1)');
        $this->set('LeadText', '');
        $this->set('LeadAnimation', '');
        $this->set('LeadSpeed', '');
        $this->set('LeadDelay', '');
        $this->set('SecondaryText', '');
        $this->set('SecondaryAnimation', '');
        $this->set('SecondarySpeed', '');
        $this->set('SecondaryDelay', '');
        $this->set('ShowArrow', '0');
        $this->set('ChevronColor', '#ffffff');
    }

    protected function getHeightOptions()
    {
        $options = [];
        for ($i = 10; $i <= 100; $i += 5) {
            $value = $i . 'vh';
            $options[$value] = t('%s viewport height', $value);
        }

        return $options;
    }

    protected function getAlignOptions()
    {
        return [
            'center center' => t('Center Center'),
            'top left' => t('Top Left'),
            'top center' => t('Top Center'),
            'top right' => t('Top Right'),
            'center left' => t('Center Left'),
            'center right' => t('Center Right'),
            'bottom left' => t('Bottom Left'),
            'bottom center' => t('Bottom Center'),
            'bottom right' => t('Bottom Right'),
        ];
    }

    protected function getScrollBehaviourOptions()
    {
        return [
            'scroll' => t('Scroll'),
            'fixed' => t('Fixed'),
        ];
    }

    protected function getShowArrowOptions()
    {
        return [
            '0' => t('No'),
            '1' => t('Yes'),
        ];
    }

    protected function getSpeedOptions()
    {
        return [
            '' => t('Normal'),
            'slow' => t('Slow'),
            'slower' => t('Slower'),
            'fast' => t('Fast'),
            'faster' => t('Faster'),
        ];
    }

    protected function getDelayOptions()
    {
        return [
            '' => t('None'),
            'delay-1s' => t('1 second'),
            'delay-2s' => t('2 seconds'),
            'delay-3s' => t('3 seconds'),
            'delay-4s' => t('4 seconds'),
            'delay-5s' => t('5 seconds'),
        ];
    }

    protected function getAnimationOptions()
    {
        return [
            '' => t('None'),
            'bounce' => t('Bounce'),
            'flash' => t('Flash'),
            'pulse' => t('Pulse'),
            'rubberBand' => t('Rubber Band'),
            'shake' => t('Shake'),
            'headShake' => t('Head Shake'),
            'swing' => t('Swing'),
            'tada' => t('Tada'),
            'wobble' => t('Wobble'),
            'jello' => t('Jello'),
            'bounceIn' => t('Bounce In'),
            'bounceInDown' => t('Bounce In Down'),
            'bounceInLeft' => t('Bounce In Left'),
            'bounceInRight' => t('Bounce In Right'),
            'bounceInUp' => t('Bounce In Up'),
            'bounceOut' => t('Bounce Out'),
            'bounceOutDown' => t('Bounce Out Down'),
            'bounceOutLeft' => t('Bounce Out Left'),
            'bounceOutRight' => t('Bounce Out Right'),
            'bounceOutUp' => t('Bounce Out Up'),
            'fadeIn' => t('Fade In'),
            'fadeInDown' => t('Fade In Down'),
            'fadeInDownBig' => t('Fade In Down Big'),
            'fadeInLeft' => t('Fade In Left'),
            'fadeInLeftBig' => t('Fade In Left Big'),
            'fadeInRight' => t('Fade In Right'),
            'fadeInRightBig' => t('Fade In Right Big'),
            'fadeInUp' => t('Fade In Up'),
            'fadeInUpBig' => t('Fade In Up Big'),
            'fadeOut' => t('Fade Out'),
            'fadeOutDown' => t('Fade Out Down'),
            'fadeOutDownBig' => t('Fade Out Down Big'),
            'fadeOutLeft' => t('Fade Out Left'),
            'fadeOutLeftBig' => t('Fade Out Left Big'),
            'fadeOutRight' => t('Fade Out Right'),
            'fadeOutRightBig' => t('Fade Out Right Big'),
            'fadeOutUp' => t('Fade Out Up'),
            'fadeOutUpBig' => t('Fade Out Up Big'),
            'flipInX' => t('Flip In X'),
            'flipInY' => t('Flip In Y'),
            'flipOutX' => t('Flip Out X'),
            'flipOutY' => t('Flip Out Y'),
            'lightSpeedIn' => t('Light Speed In'),
            'lightSpeedOut' => t('Light Speed Out'),
            'rotateIn' => t('Rotate In'),
            'rotateInDownLeft' => t('Rotate In Down Left'),
            'rotateInDownRight' => t('Rotate In Down Right'),
            'rotateInUpLeft' => t('Rotate In Up Left'),
            'rotateInUpRight' => t('Rotate In Up Right'),
            'rotateOut' => t('Rotate Out'),
            'rotateOutDownLeft' => t('Rotate Out Down Left'),
            'rotateOutDownRight' => t('Rotate Out Down Right'),
            'rotateOutUpLeft' => t('Rotate Out Up Left'),
            'rotateOutUpRight' => t('Rotate Out Up Right'),
            'hinge' => t('Hinge'),
            'jackInTheBox' => t('Jack In The Box'),
            'rollIn' => t('Roll In'),
            'rollOut' => t('Roll Out'),
            'zoomIn' => t('Zoom In'),
            'zoomInDown' => t('Zoom In Down'),
            'zoomInLeft' => t('Zoom In Left'),
            'zoomInRight' => t('Zoom In Right'),
            'zoomInUp' => t('Zoom In Up'),
            'zoomOut' => t('Zoom Out'),
            'zoomOutDown' => t('Zoom Out Down'),
            'zoomOutLeft' => t('Zoom Out Left'),
            'zoomOutRight' => t('Zoom Out Right'),
            'zoomOutUp' => t('Zoom Out Up'),
            'slideInDown' => t('Slide In Down'),
            'slideInLeft' => t('Slide In Left'),
            'slideInRight' => t('Slide In Right'),
            'slideInUp' => t('Slide In Up'),
            'slideOutDown' => t('Slide Out Down'),
            'slideOutLeft' => t('Slide Out Left'),
            'slideOutRight' => t('Slide Out Right'),
            'slideOutUp' => t('Slide Out Up'),
            'heartBeat' => t('Heart Beat'),
        ];
    }

    protected function sanitizeHeight($height)
    {
        $height = (string) $height;

        return array_key_exists($height, $this->getHeightOptions()) ? $height : '50vh';
    }

    protected function sanitizeAlign($align)
    {
        $align = (string) $align;

        return array_key_exists($align, $this->getAlignOptions()) ? $align : 'center center';
    }

    protected function sanitizeScrollBehaviour($scrollBehaviour)
    {
        $scrollBehaviour = (string) $scrollBehaviour;

        return array_key_exists($scrollBehaviour, $this->getScrollBehaviourOptions()) ? $scrollBehaviour : 'scroll';
    }

    protected function sanitizeShowArrow($showArrow)
    {
        $showArrow = trim((string) $showArrow);

        if ($showArrow === '2') {
            return '1';
        }

        return $showArrow === '1' ? '1' : '0';
    }

    protected function isShowArrowEnabled($showArrow): bool
    {
        return $this->sanitizeShowArrow($showArrow) === '1';
    }

    protected function sanitizeAnimation($animation)
    {
        $animation = (string) $animation;

        return array_key_exists($animation, $this->getAnimationOptions()) ? $animation : '';
    }

    protected function sanitizeSpeed($speed)
    {
        $speed = (string) $speed;

        return array_key_exists($speed, $this->getSpeedOptions()) ? $speed : '';
    }

    protected function sanitizeDelay($delay)
    {
        $delay = (string) $delay;

        return array_key_exists($delay, $this->getDelayOptions()) ? $delay : '';
    }

    protected function sanitizeColor($color, $fallback = '')
    {
        $color = trim((string) $color);

        if ($color === '') {
            return $fallback;
        }

        if ($this->isValidColor($color)) {
            return $color;
        }

        return $fallback;
    }

    protected function isValidColor($color)
    {
        $color = trim((string) $color);

        if ($color === 'transparent') {
            return true;
        }

        if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/', $color)) {
            return true;
        }

        if (preg_match('/^rgba?\(\s*\d+\s*,\s*\d+\s*,\s*\d+(?:\s*,\s*(?:0?\.\d+|1(?:\.0)?|\d+%))?\s*\)$/i', $color)) {
            return true;
        }

        return false;
    }
}
