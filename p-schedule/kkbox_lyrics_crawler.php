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
$composer_god_obj = new LMComposerGod();
$lyricist_god_obj = new LMLyricistGod();

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

         // get lyric info
         $kk_lyric = $song_page_dom->query->results->body->div[3]->div[1]->div[0]->div[1]->p->content;

         // parse wrighter
         $kk_lyric_array = explode('：', $kk_lyric);

         // parse lyricist
         $parse_lyricist = explode('   ', $kk_lyric_array[1]);
         $in_lyricist_name = trim($parse_lyricist[0]);

         // parse lyricist
         $parse_composer = explode(' ', $kk_lyric_array[2]);
         $in_composer_name = trim($parse_composer[0]);

         // parse lyric
         $parse_lyric = explode("\n", $kk_lyric_array[4]);
         $parse_lyric = array_slice($parse_lyric, 1);
         $in_lyric = implode("\n", $parse_lyric);

         if (!empty($in_lyric)) {

            // get performer info
            $in_performer_name = trim($song_page_dom->query->results->body->div[3]->div[0]->ul->li[1]->a->content);
            $in_performer_url = $kkbox_link.$song_page_dom->query->results->body->div[3]->div[0]->ul->li[1]->a->href;

            // get disc info
            $in_disc_name = trim($song_page_dom->query->results->body->div[3]->div[0]->ul->li[2]->a->content);
            $in_disc_url = $kkbox_link.$song_page_dom->query->results->body->div[3]->div[0]->ul->li[2]->a->href;
            $in_disc_src = $song_page_dom->query->results->body->div[3]->div[1]->div[0]->div[0]->div->div[0]->img->src;
            $in_disc_genre = $song_page_dom->query->results->body->div[3]->div[1]->div[0]->div[0]->div->div[1]->dl->dd[1]->p;
            $in_disc_release = trim($song_page_dom->query->results->body->div[3]->div[1]->div[0]->div[0]->div->div[1]->dl->dd[2]->p).'-01';

            // get song info
            $in_song_name = trim($song_page_dom->query->results->body->div[3]->div[0]->ul->li[3]->a->content);
            $in_song_url = $kkbox_link.$song_page_dom->query->results->body->div[3]->div[0]->ul->li[3]->a->href;

            echo "lyricist_name:".$in_lyricist_name."\n";
            echo "composer_name:".$in_composer_name."\n";
            echo "performer_name:".$in_performer_name."\n";
            echo "performer_url:".$in_performer_url."\n";
            echo "disc_name:".$in_disc_name."\n";
            echo "disc_url:".$in_disc_url."\n";
            echo "disc_src:".$in_disc_src."\n";
            echo "disc_release:".$in_disc_release."\n";
            echo "disc_genre:".$in_disc_genre."\n";
            echo "song_name:".$in_song_name."\n";
            echo "song_url:".$in_song_url."\n";
            echo "song_release:".$in_disc_release."\n";
            echo "song_genre:".$in_disc_genre."\n";
            echo "midi_path:".$midi_path."\n";
            echo "lyric:".$in_lyric."\n";

            // get lyricist id
            $lyricist_id = $lyricist_god_obj->findByName($in_lyricist_name);
            if (empty($lyricist_id)) {

               $parameter_array = array();
               $parameter_array['name']
                   = $in_lyricist_name;

               $lyricist_id = $lyricist_god_obj->create($parameter_array);

            }

            // get composer id
            $composer_id = $composer_god_obj->findByName($in_composer_name);
            if (empty($composer_id)) {

               $parameter_array = array();
               $parameter_array['name']
                   = $in_composer_name;

               $composer_id = $composer_god_obj->create($parameter_array);

            }

            // get performer id
            $performer_id = $performer_god_obj->findByName($in_performer_name);
            if (empty($performer_id)) {

               $parameter_array = array();
               $parameter_array['name']
                   = $kk_artist_title;
               $parameter_array['kkbox_url']
                   = $kk_performer_url;
               $performer_id = $performer_god_obj->create($parameter_array);

            }

            // disc
            //title
            //genre
            //release_date
            //cover_path
            //kkbox_url

            // song
            //title
            //lyric
            //genre
            //release_date
            //kkbox_url
            //audio_path
            //midi_path
            //performer_id
            //composer_id
            //lyricist_id
            //disc_id

         }



      }

   }

}


require_once SITE_ROOT."/p-config/application-unsetter.php";

?>