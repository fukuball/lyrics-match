<?php

require_once dirname(dirname(dirname(__FILE__)))."/p-config/application-setter.php";

$db_obj = LMDBAccess::init();


$female_dir = SITE_ROOT."/p-data/midi/female";
$dir_handler  = opendir($female_dir);
while (false !== ($filename = readdir($dir_handler))) {

   $files[] = $filename;

}
sort($files);

foreach ($files as $key => $subdirectory ) {

   if ($subdirectory != '..' && $subdirectory != '.') {

      $dir_handler  = opendir($female_dir.'/'.$subdirectory);
      while (false !== ($filename = readdir($dir_handler))) {

         $files[] = $filename;

      }
      sort($files);

      foreach ($files as $key => $midi_file ) {

         if ($midi_file != '..' && $midi_file != '.') {

            $midi_file_name_array = explode('.', $midi_file);
            $midi_path = $female_dir.'/'.$subdirectory.'/'.$midi_file;
            $artist_title = $subdirectory;
            $song_title = $midi_file_name_array[0];

            echo $artist_title."\n";
            echo $song_title."\n";
            echo $midi_path."\n";

            //$db_obj->selectCommand($select_sql);

         }

      }

   }

}

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>