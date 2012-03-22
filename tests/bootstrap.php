<?php
/**
 * Maximum level error reporting
 */
error_reporting(E_ALL | E_STRICT);

/**
 * Get both the test and library directories in the include path
 */
set_include_path(dirname(__DIR__) . '/library' . PATH_SEPARATOR . __DIR__ . PATH_SEPARATOR . get_include_path());

/**
 * Register a trivial autoloader
 */
spl_autoload_register(function($class) {
    $filename = str_replace(array("\\", "_"), DIRECTORY_SEPARATOR, $class) . '.php';
    foreach (explode(PATH_SEPARATOR, get_include_path()) as $includePath) {
        if (file_exists($includePath . DIRECTORY_SEPARATOR . $filename)) {
            include_once $filename;
            break;
        }
    }
    return class_exists($class, false);
});

/**
 * Set default time zone
 */
date_default_timezone_set('Europe/Helsinki');
