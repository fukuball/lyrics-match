<?php
/**
 * word_net_parse.php
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

$word = '依然';
$link = 'http://cwn.ling.sinica.edu.tw/_process.asp?inputword='.$word.'&radiobutton=1';

$db_obj = LMDBAccess::getInstance();

// get song detail
$yql_query = urlencode('SELECT * FROM html WHERE url="'.$link.'"');
$wordnet_page_html = file_get_contents('http://query.yahooapis.com/v1/public/yql?q='.$yql_query.'&format=json');
$wordnet_page_dom = json_decode($wordnet_page_html);

$table = $wordnet_page_dom->query->results->body->table;

print_r($table);


?>