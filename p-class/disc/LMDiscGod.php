<?php
/**
 * LMDiscGod.php is disc god class
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /p-class/disc/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

 /**
  * LMDiscGod is disc god class
  *
  * An example of a LMDiscGod is:
  *
  * <code>
  *   $discgod_obj = new LMDiscGod();
  * </code>
  *
  * @category PHP
  * @package  /p-class/disc/
  * @author   Fukuball Lin <fukuball@gmail.com>
  * @license  No Licence
  * @version  Release: <1.0>
  * @link     http://sarasti.cs.nccu.edu.tw
  */

class LMDiscGod extends LMActiveRecordGod
{
   // extends from LMActiveRecordGod
   //
   // protected $db_obj;
   // protected $table_name;

   /**
    * Method __construct initialize instance
    *
    * @return void
    */
   public function __construct()
   {

      parent::__construct();

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
    * Method getMaxId get the max id of this table
    *
    * @return void
    */
   public function getMaxId()
   {

      return parent::getMaxId();

   }// end function getMaxId

   /**
    * Method getList to get object list
    *
    * @param string $type   # list type
    * @param string $offset # list offset
    * @param string $length # list length
    *
    * @return pdo_list
    */
   public function getList($type='all', $offset='0', $length='20')
   {

      $result = parent::getList($type, $offset, $length);

      return $result;

   }// end function getList

   /**
    * Method findByTitleNPerformerId to find id by title and performer_id
    *
    * @param string $title
    * @param int    $performer_id
    *
    * @return int $id
    */
   public function findByTitleNPerformerId($title, $performer_id)
   {

      $select_sql = "SELECT ".
                    "id ".
                    "FROM $this->table_name ".
                    "WHERE ".
                    "title='$title' ".
                    "AND performer_id='$performer_id' ".
                    "LIMIT 1";

      $query_result = $this->db_obj->selectCommand($select_sql);
      foreach ($query_result as $query_result_data) {
         $instance_id = $query_result_data['id'];
      }

      if (!empty($instance_id)) {
         return $instance_id;
      } else {
         return 0;
      }

   }// end function findByTitleNPerformerId

   /**
    * Method create create one record in database
    *
    * @param array $parameter # the key value array of the instance
    *
    * @return int instance id
    */
   public function create($parameter)
   {

      $result = parent::create($parameter);

      return $result;

   }// end function create

   /**
    * Method __destruct unset instance value
    *
    * @return void
    */
   public function __destruct()
   {

      parent::__destruct();

   }// end function __destruct

}// end class LMDiscGod
?>