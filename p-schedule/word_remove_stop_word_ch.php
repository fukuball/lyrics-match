<?php
/**
 * word_remove_stop_word_ch.php
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

$select_sql = "SELECT lt.*,s.* FROM lyrics_term_combine lt LEFT JOIN lyrics_stop_word_mapping ls ON (lt.term=ls.stop_word) INNER JOIN lyrics_pos_mapping lp ON (lt.pos=lp.pos) INNER JOIN song s ON (lt.song_id=s.id) WHERE ls.stop_word IS NULL AND lt.pos!='DET' AND lt.pos!='C' AND lt.pos!='M' AND lt.pos!='P' AND lt.pos!='T' AND lt.pos!='POST' AND s.genre NOT LIKE '%台語歌曲%' AND s.genre NOT LIKE '%粵語歌曲%' ORDER BY lt.id";

$query_result = $db_obj->selectCommand($select_sql);

foreach ($query_result as $query_result_data) {

   $id = $query_result_data['id'];
   $song_id = $query_result_data['song_id'];
   $term = $query_result_data['term'];
   $pos = $query_result_data['pos'];
   $offset = $query_result_data['offset'];
   $length = $query_result_data['length'];

   $insert_sql = "INSERT INTO lyrics_term_remove_stop_word_ch (song_id,term,pos,offset,length,create_time,modify_time) VALUES ('".addslashes($song_id)."', '".addslashes($term)."', '".addslashes($pos)."', '$offset', '$length', NOW(), NOW())";
   $query_result3 = $db_obj->insertCommand($insert_sql);

   echo $term." added. \n";

}// end foreach ($query_result as $query_result_data) {


require_once SITE_ROOT."/p-config/application-unsetter.php";

?>