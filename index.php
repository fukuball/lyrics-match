<?php
/**
 * index.php is the page controller
 * to control site index page content 
 * 
 * PHP version 5
 *
 * @category PHP
 * @package  /
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

require_once $_SERVER["DOCUMENT_ROOT"]."/p-config/application-setter.php";

//$yield_left_path = '/iv-view/sidebar/index-left.php';
//$yield_main_path = '/iv-view/index.php';
//$yield_right_path = '/iv-view/sidebar/index-right.php';
//$page_title = 'iNDIEVOX - '.$lang['HomePageTitle'];
   
//include SITE_ROOT."/iv-layout/three-column-layout.php";

require_once SITE_ROOT."/o-config/application-unsetter.php";
   
?>