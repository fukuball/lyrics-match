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

$select_sql = "SELECT count(*) doc_num FROM (SELECT * FROM lyrics_term_tfidf GROUP BY song_id) doc";
$query_result = $db_obj->selectCommand($select_sql);
foreach ($query_result as $query_result_data) {
   $doc_num = $query_result_data['doc_num'];
}

$select_sql = "SELECT count(*) feature_num FROM lyrics_term_unique";
$query_result = $db_obj->selectCommand($select_sql);
foreach ($query_result as $query_result_data) {
   $feature_num = $query_result_data['feature_num'];
}

$select_sql = "SELECT count(*) none_zero_entry_num FROM lyrics_term_tfidf";
$query_result = $db_obj->selectCommand($select_sql);
foreach ($query_result as $query_result_data) {
   $none_zero_entry_num = $query_result_data['none_zero_entry_num'];
}


$word_id_file = "/var/www/html/lyrics-match/p-library/model/music_feature/20120917_lyrics_tfidf.mm";
$fh = fopen($word_id_file, 'w') or die("can't open file");

$string_data = "%%MatrixMarket matrix coordinate real general\n";
$string_data = $string_data."$doc_num $feature_num $none_zero_entry_num\n";

$select_sql = "SELECT ltt.term,ltt.song_id,ltt.tfidf,ltu.id term_id FROM lyrics_term_tfidf ltt INNER JOIN lyrics_term_unique ltu ON (ltt.term=ltu.term)";

$query_result = $db_obj->selectCommand($select_sql);

foreach ($query_result as $query_result_data) {

   $doc_id = $query_result_data['song_id'];
   $term_id = ($query_result_data['term_id']-1);
   $tfidf = $query_result_data['tfidf'];

   $string_data = $string_data."$doc_id\t$term_id\t$tfidf\n";
   echo "$doc_id\t$term_id\t$tfidf\n";

}// end foreach ($query_result as $query_result_data) {

fwrite($fh, $string_data);
fclose($fh);

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>s