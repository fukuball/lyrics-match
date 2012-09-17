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

$select_sql = "SELECT *,COUNT(id) term_count FROM lyrics_term_remove_stop_word GROUP BY term ORDER BY term";

$query_result = $db_obj->selectCommand($select_sql);

$word_id_file = "/var/www/html/lyrics_match/p-library/model/music_feature/20120917_lyrics_wordids.txt";
$fh = fopen($word_id_file, 'x+') or die("can't open file");

$count_id = 0;
$string_data = '';
foreach ($query_result as $query_result_data) {

   $count_id++;
   $term = $query_result_data['term'];
   $pos = $query_result_data['pos'];
   $term_count = $query_result_data['term_count'];

   $string_data = $string_data."$count_id\t$term\t$term_count\n";
   echo "$count_id\t$term\t$term_count\n";

}// end foreach ($query_result as $query_result_data) {

fwrite($fh, $string_data);
fclose($fh);

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>