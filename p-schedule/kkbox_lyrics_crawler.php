<?php
/**
 * kkbox_lyrics_crawler.php to cache kkbox lyrics
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
              "* ".
              "FROM temp_midi ".
              "WHERE ".
              "is_moved='0' ".
              "LIMIT 1";

$query_result = $db_obj->selectCommand($select_sql);
foreach ($query_result as $query_result_data) {

   $artist_title = $query_result_data['artist_title'];
   $song_title = $query_result_data['song_title'];
   $midi_path = $query_result_data['midi_path'];

   $search_resp_html_dom = file_get_html("http://tw.kkbox.com/search.php?word=".urlencode($song_title)."&search=song&search_lang=");
   print_r($search_resp_html_dom);

   /*$search_resp = LMHelper::doGet("http://tw.kkbox.com/search.php?word=".urlencode($song_title)."&search=song&search_lang=");
   $search_resp_html_dom = str_get_html($search_resp);

   print_r($search_resp_html_dom);*/



   /*
   echo $artist_title."\n";
   echo $song_title."\n";
   echo $midi_path."\n";
   */

}


require_once SITE_ROOT."/p-config/application-unsetter.php";

?>