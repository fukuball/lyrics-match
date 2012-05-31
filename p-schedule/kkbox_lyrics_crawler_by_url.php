<?php
/**
 * kkbox_lyrics_crawler_by_url.php to cache kkbox lyrics
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
$disc_god_obj = new LMDiscGod();
$song_god_obj = new LMSongGod();

$select_sql = "SELECT ".
              "* ".
              "FROM temp_midi ".
              "WHERE ".
              "is_moved='0' AND kkbox_url != '' LIMIT 1";

$query_result = $db_obj->selectCommand($select_sql);

// get unprocess data
foreach ($query_result as $query_result_data) {

   $midi_id = $query_result_data['id'];
   $artist_title = $query_result_data['artist_title'];
   $song_title = $query_result_data['song_title'];
   $midi_path = $query_result_data['midi_path'];
   $temp_kkbox_url = $query_result_data['kkbox_url'];

   // get song detail
   $yql_query = urlencode('SELECT * FROM html WHERE url="'.$temp_kkbox_url.'"');
   $song_page_html = file_get_contents('http://query.yahooapis.com/v1/public/yql?q='.$yql_query.'&format=json');
   $song_page_dom = json_decode($song_page_html);
   //print_r($song_page_dom);

   // get lyric info
   $kk_lyric = $song_page_dom->query->results->body->div[3]->div[1]->div[0]->div[1]->p->content;
   //print_r($kk_lyric);

   // parse wrighter
   $kk_lyric_array = explode('：', $kk_lyric);
   //print_r($kk_lyric_array);

   // parse lyricist
   $parse_lyricist = explode('   ', $kk_lyric_array[1]);
   $in_lyricist_name = trim($parse_lyricist[0]);

   // parse lyricist
   //$parse_composer = explode(' ', $kk_lyric_array[2]);
   $parse_composer = explode("\n", $kk_lyric_array[2]);
   $in_composer_name = trim($parse_composer[0]);

   // parse lyric
   //$parse_lyric = explode("\n", $kk_lyric_array[4]);
   $parse_lyric = explode("\n", $kk_lyric_array[2]);
   $parse_lyric = array_slice($parse_lyric, 1);
   //print_r($parse_lyric);
   $normalize_lyric_array = array();
   foreach ($parse_lyric as $key => $value) {
      if (nl2br(trim($value))!='<br />' && nl2br(trim($value))!='<br>') {
         array_push($normalize_lyric_array, trim($value));
      }
   }
   //print_r($normalize_lyric_array);
   $in_lyric = implode("\n", $normalize_lyric_array);

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


      //echo "lyricist_name:".$in_lyricist_name."\n";
      //echo "composer_name:".$in_composer_name."\n";
      //echo "performer_name:".$in_performer_name."\n";
      //echo "performer_url:".$in_performer_url."\n";
      //echo "disc_name:".$in_disc_name."\n";
      //echo "disc_url:".$in_disc_url."\n";
      //echo "disc_src:".$in_disc_src."\n";
      //echo "disc_release:".$in_disc_release."\n";
      //echo "disc_genre:".$in_disc_genre."\n";
      //echo "song_name:".$in_song_name."\n";
      //echo "song_url:".$in_song_url."\n";
      //echo "song_release:".$in_disc_release."\n";
      //echo "song_genre:".$in_disc_genre."\n";
      //echo "midi_path:".$midi_path."\n";
      //echo "lyric:".$in_lyric."\n";


      // get lyricist id
      $lyricist_id = $lyricist_god_obj->findByName($in_lyricist_name);
      if (empty($lyricist_id)) {

         $parameter_array = array();
         $parameter_array['name']
             = $in_lyricist_name;

         $lyricist_id = $lyricist_god_obj->create($parameter_array);

         echo "create lyricist $lyricist_id \n";

      }

      // get composer id
      $composer_id = $composer_god_obj->findByName($in_composer_name);
      if (empty($composer_id)) {

         $parameter_array = array();
         $parameter_array['name']
             = $in_composer_name;

         $composer_id = $composer_god_obj->create($parameter_array);

         echo "create composer $composer_id \n";

      }

      // get performer id
      $performer_id = $performer_god_obj->findByName($in_performer_name);
      if (empty($performer_id)) {

         $parameter_array = array();
         $parameter_array['name']
             = $in_performer_name;
         $parameter_array['kkbox_url']
             = $in_performer_url;

         $performer_id = $performer_god_obj->create($parameter_array);

         echo "create performer $performer_id \n";

      }

      // get disc id
      $disc_id = $disc_god_obj->findByTitleNPerformerId($in_disc_name, $performer_id);
      if (empty($disc_id)) {

         $parameter_array = array();
         $parameter_array['title']
             = $in_disc_name;
         $parameter_array['kkbox_url']
             = $in_disc_url;
         $parameter_array['release_date']
             = $in_disc_release;
         $parameter_array['cover_path']
             = $in_disc_src;
         $parameter_array['genre']
             = $in_disc_genre;
         $parameter_array['performer_id']
             = $performer_id;

         $disc_id = $disc_god_obj->create($parameter_array);

         echo "create disc $disc_id \n";

      }

      // get song id
      $song_id = $song_god_obj->findByTitleNPerformerId($in_song_name, $performer_id);

      if (empty($song_id)) {

         $song_id = $song_god_obj->getMaxId()+1;
         $new_midi_path = "/var/www/html/lyrics-match/p-data/midi/all/".$song_id.".mid";

         if (!copy($midi_path, $new_midi_path)) {

            echo "failed to copy $midi_path...\n";

         } else {

            $parameter_array = array();
            $parameter_array['id']
                = $song_id;
            $parameter_array['title']
                = $in_song_name;
            $parameter_array['lyric']
                = $in_lyric;
            $parameter_array['kkbox_url']
                = $in_song_url;
            $parameter_array['release_date']
                = $in_disc_release;
            $parameter_array['genre']
                = $in_disc_genre;
            $parameter_array['midi_path']
                = $new_midi_path;
            $parameter_array['performer_id']
                = $performer_id;
            $parameter_array['composer_id']
                = $composer_id;
            $parameter_array['lyricist_id']
                = $lyricist_id;
            $parameter_array['disc_id']
                = $disc_id;

            if ($song_god_obj->create($parameter_array)) {

               $update_sql = "UPDATE ".
                             "temp_midi ".
                             "SET is_moved='1' ".
                             "WHERE ".
                             "id='$midi_id' ".
                             "LIMIT 1";

               $query_result = $db_obj->updateCommand($update_sql);

               echo "create song $song_id \n";

            }// end if ($song_god_obj->create($parameter_array))

         }// end else (!copy($midi_path, $new_midi_path))



      }

   }// end if (!empty($in_lyric)) {

}// end foreach ($query_result as $query_result_data) {


require_once SITE_ROOT."/p-config/application-unsetter.php";

?>