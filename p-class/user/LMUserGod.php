<?php
/**
 * LMUserGod.php is user god class
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /p-class/user/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

 /**
  * LMUserGod is user god class
  *
  * An example of a LMUserGod is:
  *
  * <code>
  *   $usergod_obj = new LMUserGod();
  * </code>
  *
  * @category PHP
  * @package  /p-class/user/
  * @author   Fukuball Lin <fukuball@gmail.com>
  * @license  No Licence
  * @version  Release: <1.0>
  * @link     http://sarasti.cs.nccu.edu.tw
  */
class LMUserGod extends LMActiveRecordGod
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
    * Method checkUserPassword check user password
    *
    * @param string $email    # user email
    * @param string $password # user password
    *
    * @return int user id
    */
   public function checkUserPassword($username, $password)
   {

      $select_sql = "SELECT ".
                    "id ".
                    "FROM user ".
                    "WHERE (username = '".addslashes($username)."' ".
                            "AND password = '".addslashes($password)."') ".
                    "LIMIT 1";

      $query_user = $this->db_obj->selectCommand($select_sql);
      $user_id = '0';

      foreach ($query_user as $query_user_data) {

         $user_id = $query_user_data['id'];

      }// end while

      return $user_id;

   }// end function checkUserPassword

   /**
    * Method __destruct unset instance value
    *
    * @return void
    */
   public function __destruct()
   {

      parent::__destruct();

   }// end function __destruct

}// end class IndievoxUserGod
?>