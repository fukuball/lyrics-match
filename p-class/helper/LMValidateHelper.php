<?php
/**
 * LMValidateHelper.php is validate helper class
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /iv-class/helper/
 * @author   Fukuball Lin <fukuball@indievox.com>
 * @license  iNDIEVOX Licence
 * @version  Release: <1.0>
 * @link     http://www.indievox.com
 */

 /**
  * LMValidateHelper is validate helper class
  *
  * An example of a LMValidateHelper is:
  *
  * <code>
  *   LMValidateHelper::function();
  * </code>
  *
  * @category PHP
  * @package  /iv-class/helper/
  * @author   Fukuball Lin <fukuball@indievox.com>
  * @license  iNDIEVOX Licence
  * @version  Release: <1.0>
  * @link     http://www.indievox.com
  */
class LMValidateHelper
{

   /**
    * Method static validateNoEmpty check the input value is not empty
    *
    * @param string $input_value # user input value
    *
    * @return boolean $is_validate
    */
   public static function validateNoEmpty($input_value)
   {

      $validate_set                = isset($input_value);
      $validate_no_empty           = !empty($input_value);
      $validate_no_only_whitespace = !ctype_space($input_value);

      if (   $validate_set
          && $validate_no_empty
          && $validate_no_only_whitespace
      ) {

         return true;

      } else {

         return false;

      }// end if

  }// end function validateNoEmpty

  /**
    * Method static validateImageFile check the image file is validate
    *
    * @param file $input_file # user input file
    *
    * @return boolean $is_validate
    */
  public static function validateImageFile($input_file)
  {

     $validate_set                = isset($input_file['tmp_name']);
      $validate_no_empty           = !empty($input_file['tmp_name']);
      $validate_gif                = ($input_file['type'] == 'image/gif');
      $validate_jpg                = ($input_file['type'] == 'image/jpeg');
      $validate_png                = ($input_file['type'] == 'image/png');
      $validate_pjpg               = ($input_file['type'] == 'image/pjpeg');
      $validate_no_error           = ($input_file['error'] == 0);

      if (   $validate_set
          && $validate_no_empty
          && ($validate_gif || $validate_jpg || $validate_png || $validate_pjpg)
          && $validate_no_error
      ) {

         return true;

      } else {

         return false;

      }// end if

   }// end function validateImageFile

   /**
    * Method static validateExist check the item is exist
    *
    * @param string $table_name # item table name
    * @param int    $item_id    # item id
    *
    * @return boolean $is_exist
    */
   public static function validateExist($table_name, $item_id)
   {

      $db_obj = IndievoxDBAccess::getInstance();
      $select_sql
          = "SELECT * ".
            "FROM ".$table_name." ".
            "WHERE id = ".$item_id." ".
            "LIMIT 1";
      $query_instance = $db_obj->selectCommand($select_sql);
      $query_instance = $db_obj->getResultArray($query_instance);

      if (count($query_instance)==0) {

         return false;

      } else {

         return true;

      }
      unset($db_obj);

  }// end function validateExist

}// end class LMValidateHelper
?>