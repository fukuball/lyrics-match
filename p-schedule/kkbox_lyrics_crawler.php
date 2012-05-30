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
$performer_god_obj = new LMPerformerGod();

$select_sql = "SELECT ".
              "* ".
              "FROM temp_midi ".
              "WHERE ".
              "is_moved='0' ".
              "LIMIT 1";

$query_result = $db_obj->selectCommand($select_sql);

// get unprocess data
foreach ($query_result as $query_result_data) {

   $artist_title = $query_result_data['artist_title'];
   $song_title = $query_result_data['song_title'];
   $midi_path = $query_result_data['midi_path'];

   $search_resp = LMHelper::doGet($kkbox_link."/search.php?word=".urlencode($song_title)."&search=song&search_lang=");
   $process_string = explode('<div class="search-notice">', $search_resp);
   $process_string = explode('</div>', $process_string[1]);
   $process_string = explode('<strong>', $process_string[0]);
   $song_result_song_num = explode('</strong>', $process_string[1]);

   // has search result
   if ($song_result_song_num[0]>=1) {

      $process_string = explode('<td class="song-name">',$search_resp);
      $process_string = explode('</td>',$process_string[2]);
      $kk_song_title = trim(strip_tags($process_string[0]));
      $kk_artist_title = trim(strip_tags($process_string[1]));
      $kk_disc_title = trim(strip_tags($process_string[2]));
      $kk_genre = trim(strip_tags($process_string[3]));
      // parse song link
      $process_song_link = explode('href="',$process_string[6]);
      $process_song_link = explode('"',$process_song_link[1]);
      $kk_song_url = $kkbox_link.$process_song_link[0];

      // confirm search result is correct
      if ( utf8_encode($song_title) == utf8_encode($kk_song_title) && utf8_encode($artist_title) == utf8_encode($kk_artist_title) ) {

         // get song detail
         $yql_query = urlencode('SELECT * FROM html WHERE url="'.$kk_song_url.'"');
         $song_page_html = file_get_contents('http://query.yahooapis.com/v1/public/yql?q='.$yql_query.'&format=json');
         $song_page_dom = json_decode($song_page_html);

         // get lyric
         $kk_lyric = $song_page_dom->query->results->body->div[3]->div[1]->div[0]->div[1]->p->content;

         // parse wrighter
         $kk_lyric_array = explode('：', $kk_lyric);

         // parse lyricist
         $parse_lyricist = explode(' ', $kk_lyric_array[1]);
         $in_lyricist_name = trim($parse_lyricist[0]);

         // parse lyricist
         $parse_composer = explode(' ', $kk_lyric_array[2]);
         $in_composer_name = trim($parse_composer[0]);

         // parse lyric
         $parse_lyric = explode("\n", $kk_lyric_array[4]);
         $parse_lyric = array_slice($parse_lyric, 1);
         $in_lyric = implode("\n", $parse_lyric);

         echo "lyricist_name:".$in_lyricist_name."\n";
         echo "composer_name:".$in_composer_name."\n";
         echo "lyric:".$in_lyric."\n";


         print_r($kk_lyric_array);


         // parse song link
         /*$process_song_link = explode('href="',$process_string[6]);
         $process_song_link = explode('"',$process_song_link[1]);
         $kk_song_url = $kkbox_link.$process_song_link[0];

         echo $kk_artist_title."\n";
         echo $kk_song_title."\n";
         echo $kk_disc_title."\n";
         echo $kk_genre."\n";
         echo $kk_song_url."\n";

         // get song page data
         $song_resp = LMHelper::doGet($kk_song_url);


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


         // parse link
         $process_string = explode('<ul class="breadcrumbs">',$song_resp);
         $process_string = explode('</ul>',$process_string[1]);
         $process_string = explode('<li>',$process_string[0]);

         // performer link
         $process_performer_link = explode('href="',$process_string[1]);
         $process_performer_link = explode('"',$process_performer_link[1]);
         $kk_performer_url = $kkbox_link.$process_performer_link[0];

         // disc link
         $process_disc_link = explode('href="',$process_string[2]);
         $process_disc_link = explode('"',$process_disc_link[2]);
         $kk_disc_url = $process_disc_link[0];

         // parse cover image
         $process_string = explode('<div class="five columns">',$song_resp);
         $process_string = explode('</div>',$process_string[1]);

         // image url link
         $process_cover_link = explode('src="',$process_string[0]);
         $process_cover_link = explode('"',$process_cover_link[1]);
         $kk_cover_path = $kkbox_link.$process_cover_link[0];

         // parse release date
         $process_release_date = explode('<dd>',$process_string[7]);
         $kk_release_date = trim(strip_tags($process_release_date[3]));
         $kk_release_date = $kk_release_date.'-01';

         // parse lyric
         $process_string = explode('<div class="content">',$song_resp);
         //$process_string = explode('</div>',$process_string[1]);
         echo "\n";
         echo "\n";
         echo $song_resp;
         echo "\n";
         echo "\n";

         $test_resp = LMHelper::doGet('http://tw.kkbox.com/m/tc/song/QNzCht803HWKlDCqnDCqn0P4-index.html');
         echo $test_resp;
         echo "\n";
         echo "\n";

         //目前尚無相關歌詞

         // get performer id
         $performer_id = $performer_god_obj->findByName($kk_artist_title);
         if (empty($performer_id)) {

            $parameter_array = array();
            $parameter_array['name']
                = $kk_artist_title;
            $parameter_array['kkbox_url']
                = $kk_performer_url;
            $performer_id = $performer_god_obj->create($parameter_array);

         }*/

         /*echo "\n";
         echo "\n";
         echo 'disc_data:'."\n";
         echo $kk_disc_title."\n";
         echo $kk_genre."\n";
         echo $kk_release_date."\n";
         echo $kk_cover_path."\n";
         echo $kk_disc_url."\n";
         echo "\n";
         echo "\n";*/

         // disc
         //title o
         //genre o
         //release_date o
         //cover_path o
         //kkbox_url o



         // composer
         //name o

         // lyricist
         //name o




      }

   }

}


require_once SITE_ROOT."/p-config/application-unsetter.php";

?>