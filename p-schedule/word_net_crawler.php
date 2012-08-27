<?php
/**
 * word_net_crawler.php
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /p-schedule/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

require_once dirname(dirname(__FILE__))."/p-config/application-setter.php";

$word_net_link = 'http://cwn.ling.sinica.edu.tw/_process.asp';

$db_obj = LMDBAccess::getInstance();

?>