<?php
/**
 * word_unique.php
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

$db_obj = LMDBAccess::getInstance();

$select_sql = "SELECT * FROM lyrics_term_remove_stop_word GROUP BY term ORDER BY term";

$query_result = $db_obj->selectCommand($select_sql);

foreach ($query_result as $query_result_data) {

   $term = $query_result_data['term'];
   $pos = $query_result_data['pos'];

   $insert_sql = "INSERT INTO lyrics_term_unique (term,pos,create_time,modify_time) VALUES ('".addslashes($term)."', '".addslashes($pos)."', NOW(), NOW())";
   $query_result3 = $db_obj->insertCommand($insert_sql);

}// end foreach ($query_result as $query_result_data) {


require_once SITE_ROOT."/p-config/application-unsetter.php";

?>