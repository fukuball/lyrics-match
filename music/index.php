<?php
/**
 * index.php is the page controller
 * to control site index page content 
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

require_once $_SERVER["DOCUMENT_ROOT"]."/lyrics-match/p-config/application-setter.php";

$yield_path = '/p-view/music/index.php';
$page_title = '音樂資料庫';
   
include SITE_ROOT."/p-layout/home-layout.php";

require_once SITE_ROOT."/p-config/application-unsetter.php";
   
?>