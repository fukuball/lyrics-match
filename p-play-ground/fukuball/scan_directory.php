<?php

require_once dirname(dirname(dirname(__FILE__)))."/p-config/application-setter.php";

$female_dir = SITE_ROOT."/p-data/midi/female";
$dir_handler  = opendir($female_dir);
while (false !== ($filename = readdir($dir_handler))) {
    echo $filename.'<br/>';
}

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>