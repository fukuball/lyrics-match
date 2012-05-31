<?php
/**
 * lyrics.inc is p-class include file
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /p-class/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

// Helper
require 'helper/LMHelper.php';
// Database
require 'database/LMActiveRecordGod.php';
require 'database/LMActiveRecord.php';
require 'database/LMDBAccess.php';
// REST
require 'rest/LMRESTControl.php';
require 'rest/LMRESTfulInterface.php';
// Model
require 'composer/LMComposerGod.php';
require 'composer/LMComposer.php';
require 'disc/LMDiscGod.php';
require 'disc/LMDisc.php';
require 'lyricist/LMLyricistGod.php';
require 'lyricist/LMLyricist.php';
require 'performer/LMPerformerGod.php';
require 'performer/LMPerformer.php';
require 'song/LMSongGod.php';
require 'song/LMSong.php';

?>