<?php
/**
 * application-setter.php initialize application settings 
 * 
 * PHP version 5
 *
 * @category PHP
 * @package  /p-config/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */
// hard code

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error.log');
error_reporting(E_ALL);

// path and url constant define
define("SITE_ROOT", "/var/www/html/lyrics-match");
define("SITE_HOST", "http://sarasti.cs.nccu.edu.tw/lyrics-match");
define("SITE_DOMAIN", "sarasti.cs.nccu.edu.tw");
define("DATA_ROOT", "/var/www/html/lyrics-data");
define("DATA_HOST", "http://sarasti.cs.nccu.edu.tw/lyrics-data");
// facebook constant define
define("FB_APP_ID", "");
define("FB_ADMIN_ID", "");
define("KEY_PREFIX", "stage_");
define("DEBUG_MODE", true);
   
// Library
require_once SITE_ROOT.'/p-library/simplehtmldom_1_5/simple_html_dom.php';
require_once SITE_ROOT."/p-class/lyrics.inc";

?>