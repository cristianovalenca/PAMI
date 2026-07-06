<?php
/**
 * PHPUnit bootstrap for the PAMI test suite.
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('UTC');

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__));
}
if (!defined('TMPDIR')) {
    define('TMPDIR', sys_get_temp_dir());
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
