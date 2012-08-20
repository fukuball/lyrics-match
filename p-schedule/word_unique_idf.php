<?php
/**
 * word_unique_idf.php
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

$select_sql = "SELECT COUNT(id) document_num FROM song WHERE lyric!=''";

$query_result = $db_obj->selectCommand($select_sql);
$document_num = 0;
foreach ($query_result as $query_result_data) {
   $document_num = $query_result_data['document_num'];
}

$select_sql = "SELECT a.term,COUNT(a.song_id) df, ltu.id FROM (SELECT ltrsw.* FROM lyrics_term_remove_stop_word ltrsw GROUP BY ltrsw.term, ltrsw.song_id) as a INNER JOIN lyrics_term_unique ltu ON (a.term=ltu.term) GROUP BY a.term";

$query_result = $db_obj->selectCommand($select_sql);
foreach ($query_result as $query_result_data) {

   $term_id = $query_result_data['id'];
   $term = $query_result_data['term'];
   $document_frequncy = $query_result_data['df'];

   $idf = log(($document_num/(1+$document_frequncy)));

   $update_sql = "UPDATE ".
                 "lyrics_term_unique ".
                 "SET idf='$idf' ".
                 "WHERE ".
                 "id='$term_id' ".
                 "modify_time=NOW() ".
                 "LIMIT 1";
   $query_result3 = $db_obj->insertCommand($update_sql);
   echo $term." idf:".$idf." \n";
}


require_once SITE_ROOT."/p-config/application-unsetter.php";

?>