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
    require_once $filename;
    return class_exists($class, false);
});