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

$select_sql = "SELECT count(*) doc_num FROM (SELECT * FROM lyrics_term_tfidf_ch GROUP BY song_id) doc";
$query_result = $db_obj->selectCommand($select_sql);
foreach ($query_result as $query_result_data) {
   $doc_num = $query_result_data['doc_num'];
}

$select_sql = "SELECT count(*) feature_num FROM lyrics_term_unique_ch";
$query_result = $db_obj->selectCommand($select_sql);
foreach ($query_result as $query_result_data) {
   $feature_num = $query_result_data['feature_num'];
}

$select_sql = "SELECT count(*) none_zero_entry_num FROM lyrics_term_tfidf_ch";
$query_result = $db_obj->selectCommand($select_sql);
foreach ($query_result as $query_result_data) {
   $none_zero_entry_num = $query_result_data['none_zero_entry_num'];
}


$word_id_file = "/var/www/html/lyrics-match/p-library/model/music_feature/20120924_lyrics_tfidf_ch.mm";
$word_id_file_tf = "/var/www/html/lyrics-match/p-library/model/music_feature/20120924_lyrics_tf_ch.mm";
$fh = fopen($word_id_file, 'w') or die("can't open file");
$fh_tf = fopen($word_id_file_tf, 'w') or die("can't open file");

$string_data = "%%MatrixMarket matrix coordinate real general\n";
$string_data = $string_data."$doc_num $feature_num $none_zero_entry_num\n";

$string_data_tf = "%%MatrixMarket matrix coordinate real general\n";
$string_data_tf = $string_data_tf."$doc_num $feature_num $none_zero_entry_num\n";

$select_sql = "SELECT ltt.*,ltu.id term_id FROM lyrics_term_tfidf_ch ltt INNER JOIN lyrics_term_unique_ch ltu ON (ltt.term=ltu.term) ORDER BY ltt.song_id";

$query_result = $db_obj->selectCommand($select_sql);

$song_id = 0;
$new_song_id = 0;

foreach ($query_result as $query_result_data) {

   if ($query_result_data['song_id']!=$song_id) {
      $song_id = $query_result_data['song_id'];
      $new_song_id++;
   }
   $term_id = $query_result_data['term_id'];
   $tfidf = $query_result_data['tfidf'];
   $term = $query_result_data['term'];
   $pos = $query_result_data['pos'];
   $tf = $query_result_data['tf'];

   $string_data = $string_data."$new_song_id\t$term_id\t$tfidf\n";
   echo "$new_song_id\t$term_id\t$tfidf\n";

   $string_data_tf = $string_data_tf."$new_song_id\t$term_id\t$tf\n";
   echo "$new_song_id\t$term_id\t$tf\n";

   $insert_sql = "INSERT INTO lyrics_term_tfidf_continue_ch (song_id,new_song_id,term,pos,tf,tfidf,create_time,modify_time) VALUES ('".addslashes($song_id)."', '".addslashes($new_song_id)."', '".addslashes($term)."', '".addslashes($pos)."', '".addslashes($tf)."', '".addslashes($tfidf)."', NOW(), NOW())";
   $query_result3 = $db_obj->insertCommand($insert_sql);

}// end foreach ($query_result as $query_result_data) {

fwrite($fh, $string_data);
fclose($fh);

fwrite($fh_tf, $string_data_tf);
fclose($fh_tf);

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>s