<?php

require_once dirname(dirname(dirname(__FILE__)))."/p-config/application-setter.php";

$db_obj = LMDBAccess::getInstance();




$female_dir = SITE_ROOT."/p-data/midi/man";
$dir_handler  = opendir($female_dir);
$files = array();
while (false !== ($filename = readdir($dir_handler))) {

   array_push($files, $filename);

}
sort($files);

/*$count = 0;
$count_artist = 0;

foreach ($files as $key => $subdirectory ) {

   if ($subdirectory != '..' && $subdirectory != '.') {

      $count_artist++;
      echo $subdirectory."\n";
      echo $count_artist."\n";

      $innerdir_handler  = opendir($female_dir.'/'.$subdirectory);
      while (false !== ($midifilename = readdir($innerdir_handler))) {

         $midifiles[] = $midifilename;

      }

   }

}*/

foreach ($files as $key => $subdirectory ) {

   if ($subdirectory != '..' && $subdirectory != '.') {

      $innerdir_handler  = opendir($female_dir.'/'.$subdirectory);
      $midifiles = array();
      while (false !== ($midifilename = readdir($innerdir_handler))) {

         array_push($midifiles, $midifilename);

      }
      sort($midifiles);

      foreach ($midifiles as $key => $midi_file ) {

         if ($midi_file != '..' && $midi_file != '.') {

            $count++;
            $midi_file_name_array = explode('.', $midi_file);
            $midi_path = $female_dir.'/'.$subdirectory.'/'.$midi_file;
            $artist_title = $subdirectory;
            $song_title = $midi_file_name_array[0];

            echo $artist_title."\n";
            echo $song_title."\n";
            echo $midi_path."\n";
            echo $count."\n";

            $select_sql = "SELECT ".
                          "id ".
                          "FROM temp_midi ".
                          "WHERE ".
                          "artist_title='".addslashes($artist_title)."' ".
                          "AND song_title='".addslashes($song_title)."'";

            $query_result = $db_obj->selectCommand($select_sql);
            foreach ($query_result as $query_result_data) {

               $temp_midi_id = $query_result_data['id'];

            }
            if (empty($temp_midi_id)) {

               $insert_sql = "INSERT ".
                             "INTO temp_midi ".
                             "(".
                             "artist_title, ".
                             "song_title, ".
                             "midi_path ".
                             ") ".
                             "VALUES ".
                             "(".
                             "'".addslashes($artist_title)."', '".addslashes($song_title)."', '".addslashes($midi_path)."'".
                             ")";

               if ($db_obj->insertCommand($insert_sql)) {
                  echo $artist_title."\n";
                  echo $song_title."\n";
                  echo $midi_path."\n";
                  echo $insert_sql."\n";
               } else {
                  echo "fail \n";
               }

            }


         }

      }

   }

}


require_once SITE_ROOT."/p-config/application-unsetter.php";

?>