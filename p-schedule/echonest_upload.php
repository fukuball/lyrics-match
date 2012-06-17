<?php
/**
 * echonest_upload.php to upload audio to echonest
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
              "FROM song ".
              "WHERE is_deleted = '0' ".
              "AND audio_path!='' ".
              "AND echonest_track_id='' ".
              "AND retrieval_status='queue' ".
              "ORDER BY id DESC ".
              "LIMIT 1";

$query_result = $db_obj->selectCommand($select_sql);

// get unprocess data
foreach ($query_result as $query_result_data) {

   $song_obj = new LMSong($query_result_data['id']);

   echo "upload ".$song_id." \n";
   $echonest_upload_return = shell_exec(
      'curl -X POST -H "Content-Type:application/octet-stream" '.
      '"http://developer.echonest.com/api/v4/track/upload?api_key='.ECHONEST_KEY.'&filetype=mp3" '.
      '--data-binary "@'.$song_obj->audio_path.'"'
   );

   $echonest_upload_return_jdecode = json_decode($echonest_upload_return);

   if ($echonest_upload_return_jdecode->response->status->message == "Success") {

      echo "upload ".$song_id." success. \n";
      echo "analyze ".$song_id." \n";
      $echonest_track_id = $echonest_upload_return_jdecode->response->track->id;
      $echonest_analyze_return = shell_exec(
         'curl -F "api_key='.ECHONEST_KEY.'" '.
         '-F "format=json" -F "id='.$echonest_track_id.'" '.
         '-F "bucket=audio_summary" "http://developer.echonest.com/api/v4/track/analyze"'
      );
      $echonest_analyze_return_jdecode = json_decode($echonest_analyze_return);

      if ($echonest_analyze_return_jdecode->response->status->message == "Success") {

         echo "analyze ".$song_id." success. \n";

         $key = $echonest_analyze_return_jdecode->response->track->audio_summary->key;
         $mode = $echonest_analyze_return_jdecode->response->track->audio_summary->mode;
         $tempo = $echonest_analyze_return_jdecode->response->track->audio_summary->tempo;
         $time_signature = $echonest_analyze_return_jdecode->response->track->audio_summary->time_signature;
         $energy = $echonest_analyze_return_jdecode->response->track->audio_summary->energy;
         $danceability = $echonest_analyze_return_jdecode->response->track->audio_summary->danceability;
         $loudness = $echonest_analyze_return_jdecode->response->track->audio_summary->loudness;
         $analysis_url = $echonest_analyze_return_jdecode->response->track->audio_summary->analysis_url;

         echo $key."\n";
         echo $mode."\n";
         echo $tempo."\n";
         echo $time_signature."\n";
         echo $energy."\n";
         echo $danceability."\n";
         echo $loudness."\n";
         echo $analysis_url."\n";

      }

   }

   unset($song_obj);

}


/*$query_song = $db_obj->dbc->query("SELECT s.id,s.artist_id,s.file_path FROM songs s WHERE  s.echonest_track_id IS NULL AND s.is_deleted='0' AND s.is_released='1' ORDER BY s.id");
while($query_song_data = $query_song->fetch_array()){

    $song_id = $query_song_data['id'];
    $artist_id = $query_song_data['artist_id'];
    $song_file = $query_song_data['file_path'];


    $temp = strrpos($song_file,"/");
    $song_filename = substr($song_file,$temp+1);
    $path_obj = new IndievoxFilePathProcess($artist_id);
    $mp3_dir = $path_obj->preview_path;
    $music_path = $mp3_dir.'/'.$song_filename;

  if(filesize($music_path)>30000000){
    continue;
  }

    //echo $song_id." <br />";
    //echo $music_path." <br />";
  if(file_exists($music_path)){
    echo "analyze ".$song_id." \n";
    $echonest_upload_return = shell_exec('curl -X POST -H "Content-Type:application/octet-stream" '.
                        '"http://developer.echonest.com/api/v4/track/upload?api_key=95TXZMTWG41IYIATB&filetype=mp3" '.
                        '--data-binary "@'.$music_path.'"');

    $echonest_upload_return_jdecode = json_decode($echonest_upload_return);

    if($echonest_upload_return_jdecode->response->status->message == "Success"){
      $echonest_track_id = $echonest_upload_return_jdecode->response->track->id;
      //echo $echonest_track_id." <br />";

      $echonest_analyze_return = shell_exec('curl -F "api_key=95TXZMTWG41IYIATB" '.
                            '-F "format=json" -F "id='.$echonest_track_id.'" '.
                            '-F "bucket=audio_summary" "http://developer.echonest.com/api/v4/track/analyze"');
      $echonest_analyze_return_jdecode = json_decode($echonest_analyze_return);

      if($echonest_analyze_return_jdecode->response->status->message == "Success"){
        $key = $echonest_analyze_return_jdecode->response->track->audio_summary->key;
        $mode = $echonest_analyze_return_jdecode->response->track->audio_summary->mode;
        $tempo = $echonest_analyze_return_jdecode->response->track->audio_summary->tempo;
        $time_signature = $echonest_analyze_return_jdecode->response->track->audio_summary->time_signature;
        $energy = $echonest_analyze_return_jdecode->response->track->audio_summary->energy;
        $danceability = $echonest_analyze_return_jdecode->response->track->audio_summary->danceability;
        $loudness = $echonest_analyze_return_jdecode->response->track->audio_summary->loudness;
        $analysis_url = $echonest_analyze_return_jdecode->response->track->audio_summary->analysis_url;

        $echonest_analysis = file_get_contents($analysis_url);

        $db_obj->dbc->query("UPDATE songs SET ".
                    "echonest_track_id='$echonest_track_id',echonest_track_key='$key',".
                    "echonest_track_mode='$mode',echonest_track_tempo='$tempo',".
                    "echonest_track_time_signature='$time_signature', ".
                    "echonest_track_energy='$energy', ".
                    "echonest_track_danceability='$danceability', ".
                    "echonest_track_loudness='$loudness' ".
                    "WHERE id='$song_id'");
        //echo $db_obj->dbc->error;


        $echonest_analysis_file = $mp3_dir.'/echonest_a_'.$song_id.'.json';

        $handle = fopen($echonest_analysis_file, 'w');
        fwrite($handle, $echonest_analysis);
        fclose($handle);
        chown($echonest_analysis_file, "www-data");
        chgrp($echonest_analysis_file, "www-data");

        echo "$song_id echonest analysis update. \n";
      }else{
        echo "$song_id Echonest Analyze Failed. \n";
      }

    }else{
      echo "$song_id Upload To Echonest Failed. \n";
    }

  }else{
    echo $music_path.'file not exist. \n';
  }

}//end of while*/

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>
