<?php

declare(strict_types=1);
defined('C5_EXECUTE') or die('Access Denied.');
$this->inc('elements/header.php');
$this->inc('elements/content.php', [
    'layout' => 'center',
    'errorCode' => '404',
    'errorMessage' => t('The requested page could not be found.'),
]);
$this->inc('elements/footer.php');
