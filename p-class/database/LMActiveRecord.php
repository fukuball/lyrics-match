<?php
/**
 * LMActiveRecord.php is model class
 * 
 * PHP version 5
 *
 * @category PHP
 * @package  /p-class/database/
 * @author   Fukuball Lin <fukuball@gamil.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */
 
 /**
  * LMActiveRecord is model class
  * 
  * An example of a LMActiveRecord is:
  *
  * <code>
  *   # this class can't be use directly
  * </code>
  *
  * @category PHP
  * @package  /p-class/database/
  * @author   Fukuball Lin <fukuball@gmail.com>
  * @license  No Licence
  * @version  Release: <1.0>
  * @link     http://sarasti.cs.nccu.edu.tw
  */
abstract class LMActiveRecord
{
   
   protected $db_obj;
   protected $table_name;
   protected $id;
   protected $is_deleted;
   protected $create_time;
   protected $modify_time;
   protected $delete_time;
   protected $modify_unix_time;
   
   /**
    * Method __construct initialize instance
    *
    * @param int    $id        # the key of instance
    *
    * @return void
    */
   public function __construct($id)
   {
      
      // check id is not empty
      try {
         if (empty($id)) {
            throw new Exception('Exception: '.get_class($this).' id is empty.');
         }
      } catch (Exception $e) {
         echo "<h2>".get_class($this)."</h2>";
         var_dump($e->getMessage());
         exit;
      }// end try
      
      $this->id = $id;
      // set database connection
      $this->setDBAccess();
      // find this class's table name
      $temp_table_name
          = str_replace("LM", "", get_class($this));
      
      $this->table_name
          = strtolower(
               preg_replace('/([^\s])([A-Z])/', '\1_\2', $temp_table_name)
          );
      
      // get all class property
      $class_property_array = get_object_vars($this);
      
      $select_sql
          = "SELECT * ".
            "FROM ".$this->table_name." ".
            "WHERE id = ".$this->id." ".
            "LIMIT 1";
      $query_instance = $this->db_obj->selectCommand($select_sql);
      $query_instance = $this->db_obj->getResultArray($query_instance);
      
      if (count($query_instance)==0) {
         echo "<h2>".get_class($this)."</h2>";
         echo "id: ".$this->id." not exist.";
         exit;
      }
      
      foreach ($query_instance as $query_instance_data) {
         
         foreach ($class_property_array as $property_key => $property_value) {
            
            switch ($property_key) {
            
            case 'db_obj':
               break;
            
            case 'table_name':
               break;
            
            case 'modify_time':
               
               $check_time
                   = ($query_instance_data['modify_time']
                        == '0000-00-00 00:00:00');
                        
               if ($check_time) {
                  
                  $this->modify_time = '1984-09-24 00:00:00';
               
               } else {
                  
                  $this->modify_time = $query_instance_data['modify_time'];
                  
               }// end if
               
               $this->modify_unix_time = strtotime($this->modify_time);
               
               break;
            
            case 'modify_unix_time':
               break;
               
            default:
               
               $this->$property_key = $query_instance_data[$property_key];
               
               break;
            
            }// end switch ($property_key)
            
         }// end foreach
          
      }// end foreach
      
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
      
      switch($type){
      
      case 'normal':
         
         $this->db_obj = IndievoxDBAccess::getInstance();
         
         break;
      
      default:
      
         $this->db_obj = IndievoxDBAccess::getInstance();
         
         break;
         
      }
      
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
      
      return $this->table_name;
   
   }// end function getTableName
   
   /**
    * Method getId get this instance id
    *
    * @return int $instance_id
    */
   public function getId()
   {
      
      return $this->id;
   
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
      
      return json_encode($this);
   
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
      
      $now = date('Y-m-d H:i:s');
      $sql = "UPDATE ".$this->table_name." SET ";
      
      foreach ($parameter as $property_key => $property_value) {
         
         switch ($property_key) {
         
         case 'id':
            break;
         
         case 'create_time':
            break;
         
         case 'modify_time':
            break;
         
         default:
            
            $this->$property_key = $property_value;
            $sql = $sql.$property_key."='".addslashes($property_value)."', ";
         
            break;
            
         }// end switch($property_key)
         
      }// end foreach
      
      $this->modify_time = $now;
      $this->modify_unix_time = strtotime($now);
      $sql = $sql."modify_time='$now' ";
      $sql = $sql."WHERE id='".addslashes($this->id)."' LIMIT 1";

      $result = $this->db_obj->updateCommand($sql);

      return $result;
      
   }// end function update
   
   /**
    * Method save to update all instance value
    *
    * @return int $affected_rows
    */
   public function save()
   {
      
      $class_property_array = get_object_vars($this);
      $now = date('Y-m-d H:i:s');
      $sql = "UPDATE ".$this->table_name." SET ";
      
      foreach ($class_property_array as $property_key => $property_value) {
         
         switch ($property_key) {
            
         case 'db_obj':
            break;
         
         case 'table_name':
            break;
         
         case 'id':
            break;
         
         case 'create_time':
            break;
         
         case 'modify_time':
            break;
         
         case 'modify_unix_time':
            break;
         
         default:
            
            $sql = $sql.$property_key."='".addslashes($this->$property_key)."', ";
            
            break;
         
         }// end switch ($property_key)
         
      }// end foreach
      
      $sql = $sql."modify_time='$now' ";
      $sql = $sql."WHERE id='".addslashes($this->id)."' LIMIT 1";

      $result = $this->db_obj->updateCommand($sql);

      return $result;
      
   }// end function save
   
   /**
    * Method destroy to delete instance, default is soft delete
    *
    * @param string $type # the delete type
    *
    * @return int $affected_rows
    */
   public function destroy($type='soft')
   {

      switch ($type) {
      
      case 'hard':
      
         $sql = "DELETE ".
                "FROM ".$this->table_name." ".
                "WHERE id = '".addslashes($this->id)."' ".
                "LIMIT 1";
         $result = $this->db_obj->deleteCommand($sql);
         
         break;
      
      case 'soft':
      default:
      
         $now = date('Y-m-d H:i:s');
         $sql = "UPDATE ".$this->table_name." SET ".
                "is_deleted='1', ".
                "modify_time='$now', ".
                "delete_time='$now' ".
                "WHERE id='".addslashes($this->id)."' ".
                "LIMIT 1";
         $result = $this->db_obj->updateCommand($sql);
         
         break;
         
      }// end switch ($type)
      
      $this->modify_time = $now;
      $this->modify_unix_time = strtotime($now);
      $this->delete_time = $now;
      $this->is_deleted = 1;

      return $result;
      
   }// end function destroy
   
   /**
    * Method __destruct unset instance value
    *
    * @return void
    */
   public function __destruct()
   {
      
      $class_property_array = get_object_vars($this);
      
      foreach ($class_property_array as $property_key => $property_value) {
         
         switch ($property_key) {
         
         case 'db_obj':
            break;
         
         default:
            
            unset($this->$property_key);
            
            break;
         
         }// end switch($property_key)
            
      }// end foreach
      
   }// end function __destruct
   
}//end class LMActiveRecord
?>
