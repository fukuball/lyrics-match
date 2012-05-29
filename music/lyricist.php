<?php
/**
 * lyricist.php is the page controller
 * to control music lyricist page content
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /music/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

require_once dirname(dirname(__FILE__))."/p-config/application-setter.php";

$yield_path = '/p-view/music/lyricist.php';
$yield_top_tab_path = '/p-view/tab/music-tab.php';
$page_title = '作詞人資料庫';

include SITE_ROOT."/p-layout/top-tab-layout.php";

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>