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

$inputword = '一一';
$radiobutton = 1;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $word_net_link);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'inputword='.$inputword.'&radiobutton='.$radiobutton);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER , 'http://www.google.com');
$return_doc = curl_exec ($ch);
curl_close($ch);

echo iconv("Big5", "UTF-8", $return_doc);

?>