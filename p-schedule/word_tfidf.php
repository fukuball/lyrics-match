<?php
/**
 * word_tf.php
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

$select_sql = "SELECT id FROM song WHERE lyric!=''";

$query_result = $db_obj->selectCommand($select_sql);
$document_num = 0;
foreach ($query_result as $query_result_data) {

   $song_id = $query_result_data['id'];
   $select_sql = "SELECT ltrsw.*,COUNT(ltrsw.term) tf, ltu.idf FROM lyrics_term_remove_stop_word ltrsw INNER JOIN lyrics_term_unique ltu ON (ltrsw.term=ltu.term) WHERE ltrsw.song_id='$song_id' GROUP BY ltrsw.term";

   $query_result2 = $db_obj->selectCommand($select_sql);
   foreach ($query_result2 as $query_result_data2) {

      $song_id = $query_result_data2['song_id'];
      $term = $query_result_data2['term'];
      $pos = $query_result_data2['pos'];
      $tf = $query_result_data2['tf'];
      $idf = $query_result_data2['idf'];
      $tfidf = $tf*$idf;

      $insert_sql = "INSERT INTO lyrics_term_tfidf (song_id,term,pos,tf,tfidf,create_time,modify_time) VALUES ('".addslashes($song_id)."', '".addslashes($term)."', '".addslashes($pos)."', '".addslashes($tf)."', '".addslashes($tfidf)."', NOW(), NOW())";
      $query_result3 = $db_obj->insertCommand($insert_sql);
       echo $song_id." term:".$term." tfidf:".$tfidf." \n";
   }

}


require_once SITE_ROOT."/p-config/application-unsetter.php";

?>