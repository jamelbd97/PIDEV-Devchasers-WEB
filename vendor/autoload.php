<?php

if (\PHP_VERSION_ID < 70200) {
    echo sprintf("Fatal Error: composer.lock was created for PHP version 7.2 or higher but the current PHP version is %d.%d.%d.\n", PHP_MAJOR_VERSION, PHP_MINOR_VERSION, PHP_RELEASE_VERSION);
    exit(1);
}

// autoload.php @generated by Composer

require_once __DIR__ . '/composer/autoload_real.php';

return ComposerAutoloaderInit900d7ad5c6b4db00b3d797d1b078c94f::getLoader();
