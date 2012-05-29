<?php

require_once dirname(dirname(dirname(__FILE__)))."/p-config/application-setter.php";

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

            echo $female_dir.'/'.$subdirectory.'/'.$midi_file."\n";

         }

      }

   }

}

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>