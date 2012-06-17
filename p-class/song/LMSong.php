<?php
/**
 * LMSong.php is song model class
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /p-class/song/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

 /**
  * LMSong is song model class
  *
  * An example of a LMSong is:
  *
  * <code>
  *   $song_obj = new LMSong($instance_key);
  * </code>
  *
  * @category PHP
  * @package  /p-class/song/
  * @author   Fukuball Lin <fukuball@gmail.com>
  * @license  No Licence
  * @version  Release: <1.0>
  * @link     http://sarasti.cs.nccu.edu.tw
  */
class LMSong extends LMActiveRecord
{
   // extends from LMActiveRecord
   //
   // protected $db_obj;
   // protected $memcache_obj;
   // protected $use_cache;
   // protected $table_name;
   // protected $id;
   // protected $is_deleted;
   // protected $create_time;
   // protected $modify_time;
   // protected $delete_time;
   // protected $modify_unix_time;
   public $title;
   public $lyric;
   public $genre;
   public $release_date;
   public $kkbox_url;
   public $audio_path;
   public $midi_path;
   public $performer_id;
   public $composer_id;
   public $lyricist_id;
   public $disc_id;
   public $echonest_track_id;
   public $key;
   public $mode;
   public $tempo;
   public $time_signature;
   public $energy;
   public $danceability;
   public $speechiness;
   public $loudness;
   public $retrieval_status;

   /**
    * Method __construct initialize instance
    *
    * @param int    $instance_key   # the key of instance
    *
    * @return void
    */
   public function __construct($instance_key)
   {

      parent::__construct($instance_key);

   }// end function __construct

   /**
    * Method setDBAccess set the database connection
    *
    * @param string $type # the database type
    *
    * @return void
    */
   public function setDBAccess($type='normal')
   {

      parent::setDBAccess($type);

   }// end function setDBAccess

   /**
    * Method getDBAccess get the database connection
    *
    * @return db_obj
    */
   public function getDBAccess()
   {

      return $this->db_obj;

   }// end function getDBAccess

   /**
    * Method getTableName return this class table name
    *
    * @return string $table_name
    */
   public function getTableName()
   {

      return parent::getTableName();

   }// end function getTableName

   /**
    * Method getId get this instance id
    *
    * @return int $instance_id
    */
   public function getId()
   {

      return parent::getId();

   }// end function getId

   /**
    * Method getIsDeleted get this instance is_deleted
    *
    * @return int $is_deleted
    */
   public function getIsDeleted()
   {

      return $this->is_deleted;

   }// end function getIsDeleted

   /**
    * Method toJSON get this instance public data json file
    *
    * @return json $json_data
    */
   public function toJSON()
   {

      return parent::toJSON();

   }// end function toJSON

   /**
    * Method getMidiUrl to get midi url
    *
    * @return string $midi_url
    */
   public function getMidiUrl()
   {

      $midi_url = str_replace(SITE_ROOT, SITE_HOST, $this->midi_path);
      return $midi_url;

   }// end function getMidiUrl

   /**
    * Method getAudioUrl to get audio url
    *
    * @return string $audio_url
    */
   public function getAudioUrl()
   {

      $audio_url = str_replace(SITE_ROOT, SITE_HOST, $this->audio_path);
      return $audio_url;

   }// end function getAudioUrl


   /**
    * Method update to update some instance value
    *
    * @param array $parameter # the key value array of the instance
    *
    * @return int $affected_rows
    */
   public function update($parameter)
   {

      $result = parent::update($parameter);

      return $result;

   }// end function update

   /**
    * Method save to update all instance value
    *
    * @return int $affected_rows
    */
   public function save()
   {

      $result = parent::save();

      return $result;

   }// end function save

   /**
    * Method destroy to delete instance, default is soft delete
    *
    * @return int $affected_rows
    */
   public function destroy()
   {

      $result = parent::destroy();

      return $result;

   }// end function destroy

   /**
    * Method __destruct unset instance value
    *
    * @return void
    */
   public function __destruct()
   {

      parent::__destruct();

   }// end function __destruct

}// end class LMSong
?>
