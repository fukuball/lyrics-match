<?php
/**
 * LMDBAccess.php is database class
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /p-class/database/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */
 // hard code

 /**
  * LMDBAccess is database class
  *
  * An example of a LMDBAccess is:
  *
  * <code>
  *   $db_obj = LMDBAccess::getInstance();
  * </code>
  *
  * @category PHP
  * @package  /p-class/database/
  * @author   Fukuball Lin <fukuball@gmail.com>
  * @license  No Licence
  * @version  Release: <1.0>
  * @link     http://sarasti.cs.nccu.edu.tw
  */
class LMDBAccess
{

   protected static $db_obj;
   protected static $instance_count = 0;
   protected $db_host;
   protected $db_name;
   protected $db_user;
   protected $db_password;
   protected $db_connection;

   /**
    * Method __construct initialize instance
    *
    * @return void
    */
   private function __construct()
   {

      include SITE_ROOT."/p-config/db_stage.php";

      $this->db_host     = $stage_db_host;
      $this->db_name     = $stage_db_name;
      $this->db_user     = $stage_db_user;
      $this->db_password = $stage_db_password;

      try {

         $this->db_connection
             = new PDO(
                 'mysql:host=' . $this->db_host . ';dbname=' . $this->db_name,
                 $this->db_user,
                 $this->db_password
             );

         $this->db_connection->query("SET time_zone='+8:00'");
         $this->db_connection->query("SET NAMES UTF8");

      } catch (PDOException $e) {

         echo "<h2>".get_class($this)."</h2>";
         var_dump($e->getMessage());
         exit;

      } // end try

      self::$instance_count++;

   }// end function __construct

   /**
    * Method init to initial db connection
    *
    * @return void
    */
   public static function init()
   {

      if (!self::$db_obj || !isset(self::$db_obj) || empty(self::$db_obj)) {

         self::$db_obj = new LMDBAccess();

      }

   }// end function init

   /**
    * Method getInstance to get db_obj
    *
    * @return object $db_obj
    */
   public static function getInstance()
   {

      return self::$db_obj;

   }// end function getInstance

   /**
    * Method getProcesslist to get process list
    *
    * @return array $process_list
    */
   public function getProcesslist()
   {

      $sql = "SHOW FULL PROCESSLIST";

      $list_array = array();

      foreach ($this->db_connection->query($sql) as $row) {

         $process_id = $row["Id"];

         if ($row["Time"] > 200 ) {

            $sql = "KILL $process_id";
            $this->db_connection->query($sql);

         } else {

            array_push($list_array, $row);

         }

      }

      return $list_array;

   }// end function getProcesslist

   /**
    * Method killProcess to kill process
    *
    * @param int $process_id # the process id
    *
    * @return boolean $success
    */
   public function killProcess($process_id)
   {

      $sql = "KILL $process_id";

      $this->query_result = $this->db_connection->query($sql);

      if (!$this->query_result) {

         echo "<h2>".get_class($this)."</h2>";
         var_dump($this->db_connection->errorInfo());
         return false;

         exit;

      } else {

         return true;

      }

   }// end function killProcess

   /**
    * Method getResultArray to get result array
    *
    * @param pdostat $query_result # the pdo result
    *
    * @return array $query_result
    */
   public function getResultArray($query_result)
   {

      return $query_result->fetchAll();

   }// end function getResultArray

   /**
    * Method insertCommand to execute insert sql command
    *
    * @param string $insert_sql # the sql statement
    *
    * @return int $insert_id
    */
   public function insertCommand($insert_sql)
   {

      $query_result = $this->db_connection->query($insert_sql);

      if (!$query_result) {
         echo "<h2>".get_class($this)."</h2>";
         var_dump($this->db_connection->errorInfo());
         exit;
      }

      $insert_id = $this->db_connection->lastInsertId();

      return $insert_id;

   }// end function insertCommand

   /**
    * Method selectCommand to execute select sql command
    *
    * @param string $select_sql # the sql statement
    *
    * @return pdostat $query_result
    */
   public function selectCommand($select_sql)
   {

      $query_result = $this->db_connection->query($select_sql);

      if (!$query_result) {
         echo "<h2>".get_class($this)."</h2>";
         var_dump($this->db_connection->errorInfo());
         exit;
      }

      return $query_result;

   }// end function selectCommand

   /**
    * Method updateCommand to execute update sql command
    *
    * @param string $update_sql # the sql statement
    *
    * @return int $affected_rows
    */
   public function updateCommand($update_sql)
   {

      $query_result = $this->db_connection->query($update_sql);

      if (!$query_result) {
         echo "<h2>".get_class($this)."</h2>";
         var_dump($this->db_connection->errorInfo());
         exit;
      }

      return $query_result->rowCount();

   }// end function updateCommand

   /**
    * Method deleteCommand to execute delete sql command
    *
    * @param string $delete_sql # the sql statement
    *
    * @return int $affected_rows
    */
   public function deleteCommand($delete_sql)
   {

      $query_result = $this->db_connection->query($delete_sql);

      if (!$query_result) {
         echo "<h2>".get_class($this)."</h2>";
         var_dump($this->db_connection->errorInfo());
         exit;
      }

      return $query_result->rowCount();

   }// end function deleteCommand

   /**
    * Method __destruct unset instance value
    *
    * @return void
    */
   public function __destruct()
   {

   }// end function __destruct

}// end class LMDBAccess

// initial db connection
LMDBAccess::init();

?>