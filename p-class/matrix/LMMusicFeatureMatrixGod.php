<?php
/**
 * LMMusicFeatureMatrixGod.php is music_feature_matrix god class
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /p-class/matrix/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

 /**
  * LMMusicFeatureMatrixGod is music_feature_matrix god class
  *
  * An example of a LMMusicFeatureMatrixGod is:
  *
  * <code>
  *   $music_feature_matrix_god_obj = new LMMusicFeatureMatrixGod();
  * </code>
  *
  * @category PHP
  * @package  /p-class/matrix/
  * @author   Fukuball Lin <fukuball@gmail.com>
  * @license  No Licence
  * @version  Release: <1.0>
  * @link     http://sarasti.cs.nccu.edu.tw
  */

class LMMusicFeatureMatrixGod extends LMActiveRecordGod
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

}// end class LMMusicFeatureMatrixGod
?>