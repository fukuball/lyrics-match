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

   $search_resp = LMHelper::doGet($kkbox_link."/search.php?word=".urlencode($song_title)."&search=song&search_lang=");
   $process_string = explode('<div class="search-notice">', $search_resp);
   $process_string = explode('</div>', $process_string[1]);
   $process_string = explode('<strong>', $process_string[0]);
   $song_result_song_num = explode('</strong>', $process_string[1]);

   if ($song_result_song_num[0]>=1) {

      $process_string = explode('<td class="song-name">',$search_resp);
      $process_string = explode('</td>',$process_string[2]);
      $kk_song_title = trim(strip_tags($process_string[0]));
      $kk_artist_title = trim(strip_tags($process_string[1]));
      $kk_disc_title = trim(strip_tags($process_string[2]));
      $kk_genre = trim(strip_tags($process_string[3]));

      if ( utf8_encode($song_title) == utf8_encode($kk_song_title) && utf8_encode($artist_title) == utf8_encode($kk_artist_title) ) {

         $process_song_link = explode('href="',$process_string[6]);
         $process_song_link = explode('"',$process_song_link[1]);
         $kk_song_url = $kkbox_link.$process_song_link[0];

         echo $kk_artist_title."\n";
         echo $kk_song_title."\n";
         echo $kk_disc_title."\n";
         echo $kk_genre."\n";
         echo $kk_song_url."\n";

         $search_resp = LMHelper::doGet($kk_song_url);


         //echo $search_resp;


         // song
         //title o
         //lyric o
         //genre o
         //release_date o
         //kkbox_url o
         //audio_path
         //midi_path o
         //performer_id // other table o
         //composer_id  // other table o
         //lyricist_id  // other table o
         //disc_id      // other table o

         // performer
         //name o
         //kkbox_url o

         $process_string = explode('<ul class="breadcrumbs">',$search_resp);
         $process_string = explode('</ul>',$process_string[1]);
         $process_string = explode('<li>',$process_string[0]);

         print_r($process_string);

         // composer
         //name o

         // lyricist
         //name o

         // disc
         //title o
         //genre o
         //release_date o
         //cover_path o
         //kkbox_url o


      }

   }

}


require_once SITE_ROOT."/p-config/application-unsetter.php";

?>