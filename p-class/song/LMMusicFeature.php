<?php
/**
 * LMMusicFeature.php is music feature model class
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
  * LMMusicFeature is music feature model class
  *
  * An example of a LMMusicFeature is:
  *
  * <code>
  *   $music_feature_obj = new LMMusicFeature($instance_key);
  * </code>
  *
  * @category PHP
  * @package  /p-class/song/
  * @author   Fukuball Lin <fukuball@gmail.com>
  * @license  No Licence
  * @version  Release: <1.0>
  * @link     http://sarasti.cs.nccu.edu.tw
  */
class LMMusicFeature extends LMActiveRecord
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
   public $song_id;
   public $second;
   public $bar_count;
   public $beat_count;
   public $tatum_count;
   public $section_count;
   public $segment_count;
   public $bar_avg_second;
   public $beat_avg_second;
   public $tatum_avg_second;
   public $section_avg_second;
   public $segment_avg_second;
   public $pitch_avg_vector;
   public $timbre_avg_vector;
   public $pitch_std_vector;
   public $timbre_std_vector;
   public $pitch_audio_word;
   public $timbre_audio_word;

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
