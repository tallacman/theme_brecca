<?php

declare(strict_types=1);
defined('C5_EXECUTE') or die('Access Denied.');
$this->inc('elements/header.php');
$this->inc('elements/content.php', [
    'layout' => 'center',
    'showInnerContent' => true,
    'innerContent' => $innerContent,
    'error' => $error ?? null,
    'success' => $success ?? null,
    'message' => $message ?? null,
]);
$this->inc('elements/footer.php');
