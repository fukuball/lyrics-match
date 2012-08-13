<?php
/**
 * word_combine.php
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

$select_sql = "SELECT t.* FROM lyrics_term t";

$query_result = $db_obj->selectCommand($select_sql);
$skip_id = 0;
foreach ($query_result as $query_result_data) {

   $id = $query_result_data['id'];
   $song_id = $query_result_data['song_id'];
   $term = $query_result_data['term'];
   $pos = $query_result_data['pos'];
   $offset = $query_result_data['offset'];
   $length = $query_result_data['length'];

   if ($id==$skip_id) {
      continue;
   }

   if (utf8_encode($term) == utf8_encode('不')) {

      if (($song_id==186 && $offset==105)
       || ($song_id==186 && $offset==210)
       || ($song_id==186 && $offset==313)
       || ($song_id==237 && $offset==343)
      ) {

      } else {
         $select_sql2 = "SELECT t.* FROM lyrics_term t WHERE t.id > '$id' AND t.song_id='$song_id' LIMIT 1";
         $query_result2 = $db_obj->selectCommand($select_sql2);
         foreach ($query_result2 as $query_result_data2) {

            $skip_id = $query_result_data2['id'];
            $term = $term.$query_result_data2['term'];
            $pos = 'ADV';
            $length = $length+$query_result_data2['length'];

            echo "combine :".$term." \n";

         }
      }


   }

   $insert_sql = "INSERT INTO lyrics_term_combine (song_id,term,pos,offset,length,create_time,modify_time) VALUES ('".addslashes($song_id)."', '".addslashes($term)."', '".addslashes($pos)."', '$offset', '$length', NOW(), NOW())";
   $query_result3 = $db_obj->insertCommand($insert_sql);

}// end foreach ($query_result as $query_result_data) {


require_once SITE_ROOT."/p-config/application-unsetter.php";

?>