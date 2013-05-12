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
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// path and url constant define
define("SITE_ROOT", "/Users/Fukuball/localhost/lyrics-match");
define("SITE_HOST", "http://127.0.0.1/lyrics-match");
define("SITE_DOMAIN", "127.0.0.1");
//define("DATA_ROOT", "/Users/Fukuball/localhost/lyrics-data");
//define("DATA_HOST", "http://127.0.0.1/lyrics-data");
define("MIDI_ROOT", SITE_ROOT."/p-data/midi/all");
define("MIDI_HOST", SITE_HOST."/p-data/midi/all");
define("AUDIO_ROOT", SITE_ROOT."/p-data/mp3");
define("AUDIO_HOST", SITE_HOST."/p-data/mp3");
define("ECHONEST_KEY", "LSPUPAL5CD1NGVRUL");
// facebook constant define
define("FB_APP_ID", "");
define("FB_ADMIN_ID", "");
define("KEY_PREFIX", "stage_");
define("DEBUG_MODE", true);

// Library
require_once SITE_ROOT.'/p-library/simplehtmldom_1_5/simple_html_dom.php';
require_once SITE_ROOT."/p-class/lyrics.inc";

$current_page_full_url = LMHelper::currentFullPageURL();
$current_page_path_url = LMHelper::currentPageURLPath();

?>