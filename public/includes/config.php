<?php
declare(strict_types=1);

const APP_NAME = 'Creative Art by Serxho';
const APP_URL = 'https://creativeartbyserxho.gt.tc';
const APP_ENV = 'production';
const DEFAULT_LANGUAGE = 'sq';

$localConfig = __DIR__ . '/config.local.php';

if (is_file($localConfig)) {
    require $localConfig;
}
