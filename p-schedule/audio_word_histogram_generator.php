<?php
/**
 * echonest_vector_counter.php to count feature vector
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

$select_sql = "SELECT ".
              "id ".
              "FROM music_feature ".
              "WHERE is_deleted = '0' ".
              "AND id=1 ".
              "ORDER BY id";

$query_result = $db_obj->selectCommand($select_sql);

// get unprocess data
foreach ($query_result as $query_result_data) {

   $music_feature_obj = new LMMusicFeature($query_result_data['id']);
   $song_id = $music_feature_obj->song_id;

   $select_sql2 = "SELECT ".
                 "macw.id code_word_id,".
                 "maw.song_id,".
                 "COUNT(maw.code_word_id) word_count ".
                 "FROM muisc_audio_code_word macw ".
                 "LEFT JOIN music_audio_word maw ".
                 "ON (macw.id=maw.code_word_id AND maw.song_id=".$song_id.") ".
                 "WHERE macw.code_book_id=1 ".
                 "GROUP BY macw.id ".
                 "ORDER BY macw.id";

   $query_result2 = $db_obj->selectCommand($select_sql2);

   foreach ($query_result2 as $query_result_data2) {
      echo $query_result_data['word_count'];
   }

}

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>
