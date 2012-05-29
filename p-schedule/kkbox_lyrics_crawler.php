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

$kkbox_link = 'http://tw.kkbox.com';

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

   //$search_resp_html_dom = file_get_html("http://tw.kkbox.com/search.php?word=".urlencode($song_title)."&search=song&search_lang=");
   //print_r($search_resp_html_dom);


   $search_resp = LMHelper::doGet($kkbox_link."/search.php?word=".urlencode($song_title)."&search=song&search_lang=");
   $process_string = explode('<div class="search-notice">', $search_resp);
   $process_string = explode('</div>', $process_string[1]);
   $process_string = explode('<strong>', $process_string[0]);
   $song_result_song_num = explode('</strong>', $process_string[1]);

   if ($song_result_song_num[0]>=1) {

      $process_string = explode('<td class="song-name">',$search_resp);
      $process_string = explode('</td>',$process_string[2]);
      $song_title = trim(strip_tags($process_string[0]));
      $artist_title = trim(strip_tags($process_string[1]));
      $disc_title = trim(strip_tags($process_string[2]));
      $genre = trim(strip_tags($process_string[3]));

      $process_song_link = explode('href="',$process_string[6]);
      $process_song_link = explode('"',$process_song_link[1]);
      $song_url = $kkbox_link.$process_song_link[0];

      print_r($process_string);
      echo $artist_title."\n";
      echo $song_title."\n";
      echo $disc_title."\n";
      echo $genre."\n";
      echo $song_url."\n";

   }

//<a href="/album/uvV-tx3Hpcub8Gs0FKXC008l-index.html#9"><strong class="keyword c1">悲傷的茱麗葉</strong></a></td>


   //$search_resp_html_dom = str_get_html($song_result_num_html);
   //print_r($search_resp_html_dom);



   /*
   echo $artist_title."\n";
   echo $song_title."\n";
   echo $midi_path."\n";
   */

}


require_once SITE_ROOT."/p-config/application-unsetter.php";

?>