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

require_once dirname(__FILE__)."/p-config/application-setter.php";

$yield_path = '/p-view/index.php';
$page_title = 'Sing My Story';

include SITE_ROOT."/p-layout/home-layout.php";

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>