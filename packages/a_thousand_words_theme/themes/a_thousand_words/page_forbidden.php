<?php

declare(strict_types=1);
defined('C5_EXECUTE') or die('Access Denied.');
$this->inc('elements/header.php');
$this->inc('elements/content.php', [
    'layout' => 'center',
    'errorCode' => '403',
    'errorMessage' => t('You are not allowed to access this page.'),
]);
$this->inc('elements/footer.php');
