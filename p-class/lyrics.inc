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
require 'helper/LMValidateHelper.php';
require 'helper/LMUploadHelper.php';
require 'helper/LMAuthHelper.php';
// Database
require 'database/LMActiveRecordGod.php';
require 'database/LMActiveRecord.php';
require 'database/LMDBAccess.php';
// REST
require 'rest/LMRESTControl.php';
require 'rest/LMRESTfulInterface.php';
require 'message/LMErrorMessenger.php';
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
require 'song/LMMusicFeatureGod.php';
require 'song/LMMusicFeature.php';
require 'song/LMLyricsBlockTruthGod.php';
require 'song/LMLyricsBlockTruth.php';
require 'user/LMUserGod.php';
// Matrix
require 'matrix/LMMusicFeatureMatrixGod.php';
require 'matrix/LMMusicFeatureMatrix.php';
require 'matrix/LMMusicAudioWordMatrixGod.php';
require 'matrix/LMMusicAudioWordMatrix.php';
require 'matrix/LMMusicAudioWordGod.php';
require 'matrix/LMMusicAudioWord.php';

?>