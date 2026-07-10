<?php

declare(strict_types=1);

namespace Concrete\Package\TallacmansCallMe\Block\TallacmansCallMe;

use Concrete\Core\Block\BlockController;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{
    protected $btTable = 'btTallacmansCallMe';

    protected $btInterfaceWidth = 480;

    protected $btInterfaceHeight = 420;

    protected $btIgnorePageThemeGridFrameworkContainer = true;

    protected $btDefaultSet = 'navigation';

    protected $btCacheBlockRecord = true;

    protected $btCacheBlockOutput = true;

    protected $btCacheBlockOutputOnPost = true;

    protected $btCacheBlockOutputForRegisteredUsers = true;

    protected $pkg = 'tallacmans_call_me';

    public $callOrText;

    public $phoneNumber;

    public $displayText;

    public $displayAs;

    public function getBlockTypeDescription()
    {
        return t('Click to contact via voice call or text message (SMS).');
    }

    public function getBlockTypeName()
    {
        return t("Tallacman's Call Me");
    }

    public function getSearchableContent()
    {
        return trim((string) ($this->phoneNumber ?? '') . ' ' . (string) ($this->displayText ?? ''));
    }

    public function view()
    {
        $scheme = $this->getContactScheme($this->callOrText ?? '1');
        $phoneHref = $this->sanitizePhoneHref($this->phoneNumber ?? '');
        $displayAs = $this->sanitizeDisplayAs($this->displayAs ?? '1');

        $this->set('contactHref', $scheme . ':' . $phoneHref);
        $this->set('displayText', trim((string) ($this->displayText ?? '')));
        $this->set('isButton', $displayAs === '1');
        $this->set('headingTag', $this->getHeadingTag($displayAs));
    }

    public function add()
    {
        $this->addEdit();
        $this->setDefaults();
    }

    public function edit()
    {
        $this->addEdit();
    }

    public function composer()
    {
        $this->edit();
    }

    public function save($args)
    {
        $args['callOrText'] = $this->sanitizeCallOrText($args['callOrText'] ?? '1');
        $args['phoneNumber'] = $this->sanitizePhoneStored($args['phoneNumber'] ?? '');
        $args['displayText'] = $this->sanitizeDisplayText($args['displayText'] ?? '');
        $args['displayAs'] = $this->sanitizeDisplayAs($args['displayAs'] ?? '1');

        return parent::save($args);
    }

    public function validate($args)
    {
        $e = $this->app->make('error');

        $phoneNumber = $this->sanitizePhoneStored($args['phoneNumber'] ?? '');
        if ($phoneNumber === '' || strlen(preg_replace('/\D+/', '', $phoneNumber)) < 7) {
            $e->add(t('A valid phone number is required (at least 7 digits).'));
        }

        if ($this->sanitizeDisplayText($args['displayText'] ?? '') === '') {
            $e->add(t('Display text is required.'));
        }

        if (!array_key_exists($this->sanitizeCallOrText($args['callOrText'] ?? ''), $this->getCallOrTextOptions())) {
            $e->add(t('Please choose call or text.'));
        }

        if (!array_key_exists($this->sanitizeDisplayAs($args['displayAs'] ?? ''), $this->getDisplayAsOptions())) {
            $e->add(t('Please choose how the link should display.'));
        }

        return $e;
    }

    protected function addEdit()
    {
        $this->set('callOrText_options', $this->getCallOrTextOptions());
        $this->set('displayAs_options', $this->getDisplayAsOptions());
    }

    protected function setDefaults()
    {
        $this->set('callOrText', '1');
        $this->set('phoneNumber', '');
        $this->set('displayText', '');
        $this->set('displayAs', '1');
    }

    protected function getCallOrTextOptions()
    {
        return [
            '1' => t('Call'),
            '2' => t('Text (SMS)'),
        ];
    }

    protected function getDisplayAsOptions()
    {
        return [
            '1' => t('Button'),
            '2' => t('Heading 1'),
            '3' => t('Heading 2'),
            '4' => t('Heading 3'),
            '5' => t('Heading 4'),
            '6' => t('Heading 5'),
            '7' => t('Heading 6'),
            '8' => t('Span'),
            '9' => t('Paragraph'),
        ];
    }

    protected function getContactScheme(string $callOrText): string
    {
        return $this->sanitizeCallOrText($callOrText) === '2' ? 'sms' : 'tel';
    }

    protected function getHeadingTag(string $displayAs): string
    {
        $map = [
            '2' => 'h1',
            '3' => 'h2',
            '4' => 'h3',
            '5' => 'h4',
            '6' => 'h5',
            '7' => 'h6',
            '8' => 'span',
            '9' => 'p',
        ];

        $displayAs = $this->sanitizeDisplayAs($displayAs);

        return $map[$displayAs] ?? 'span';
    }

    protected function sanitizeCallOrText($callOrText)
    {
        $callOrText = (string) $callOrText;

        return array_key_exists($callOrText, $this->getCallOrTextOptions()) ? $callOrText : '1';
    }

    protected function sanitizeDisplayAs($displayAs)
    {
        $displayAs = (string) $displayAs;

        return array_key_exists($displayAs, $this->getDisplayAsOptions()) ? $displayAs : '1';
    }

    protected function sanitizeDisplayText($displayText)
    {
        return trim(strip_tags((string) $displayText));
    }

    protected function sanitizePhoneStored($phoneNumber)
    {
        $phoneNumber = trim(strip_tags((string) $phoneNumber));

        return substr($phoneNumber, 0, 45);
    }

    protected function sanitizePhoneHref(string $phoneNumber): string
    {
        $phoneNumber = $this->sanitizePhoneStored($phoneNumber);

        if ($phoneNumber === '') {
            return '';
        }

        if ($phoneNumber[0] === '+') {
            return '+' . preg_replace('/\D+/', '', substr($phoneNumber, 1));
        }

        return preg_replace('/\D+/', '', $phoneNumber);
    }
}
