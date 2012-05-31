<?php
/**
 * LMErrorMessenger.php is error messenger class
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /p-class/message/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

 /**
  * LMErrorMessenger is error messenger class
  *
  * An example of a LMErrorMessenger is:
  *
  * <code>
  *   $error_messenger_obj = new LMErrorMessenger();
  * </code>
  *
  * @category PHP
  * @package  /p-class/message/
  * @author   Fukuball Lin <fukuball@gmail.com>
  * @license  No Licence
  * @version  Release: <1.0>
  * @link     http://sarasti.cs.nccu.edu.tw
  */
class LMErrorMessenger
{

   protected $version = "0.1";
   protected $type;
   protected $code;
   protected $parameter;
   protected $message;
   public    $readable_title;
   public    $readable_description;

   /**
    * Method __construct initialize instance
    *
    * @param string $type      # the error type
    * @param array  $parameter # option parameter to use
    *
    * @return void
    */
   public function __construct($type,$parameter)
   {

      try {

         $this->type = $this->validateNotEmpty($type);

      } catch (Exception $e) {

         echo "<h2>".get_class($this)."</h2>";
         var_dump($e->getMessage());
         exit;

      }// end try

      $this->getMyErrorCode();
      $this->parameter = $parameter;

   }// end function __construct

   /**
    * Method validateNotEmpty validate type not empty
    *
    * @param string $property # the error type
    *
    * @return string $property
    */
   protected function validateNotEmpty($property)
   {

      if (empty($property)) {

         throw new Exception('Empty value exception.');

      } else {

         return $property;

      }

   }// end function validateNotEmpty

   /**
    * Method getMyErrorCode to initialize some member property
    *
    * @return void
    */
   protected function getMyErrorCode()
   {
      switch ($this->type) {

      case 'page_not_found':

         $this->code = "404";
         $this->message = $this->type." - Page not found!";
         $this->readable_title = 'Page Not Found';
         $this->readable_description = 'Page not found!';

         break;

      case 'not_exist_item':

         $this->code = "5";
         $this->message = $this->type." - Not Exist Item!";
         $this->readable_title = "此物件不存在";
         $this->readable_description = "您要求的物件不存在於資料庫，請確認id是否正確。";

         break;

      case 'not_exist_value':

         $this->code = "2";
         $this->message = $this->type." - Not Exist Value!";
         $this->readable_title = 'Not Exist Value';
         $this->readable_description = 'Not Exist Value!';

         break;

      case 'success':

         $this->code = "0";
         $this->message = $this->type." - Success!";
         $this->readable_title = 'Success';
         $this->readable_description = 'Success.';

         break;

      case 'unknow_error':
      default:

         $this->code = "1";
         $this->message = $this->type." - Error happens!";
         $this->readable_title = 'Unknow Error';
         $this->readable_description = 'Unknow Error.';

         break;

      }// end switch

   }// end function getMyErrorCode

   /**
    * Method printErrorJSON print error json
    *
    * @return void
    */
   public function printErrorJSON()
   {

      $version = $this->version;
      $error_code = $this->code;
      $error_type = $this->type;
      $message = $this->message;
      $parameter = $this->parameter;

      include 'LMErrorMessengerView/error-json.php';

   }// end function printErrorJSON

   /**
    * Method __destruct unset instance value
    *
    * @return void
    */
   public function __destruct()
   {

      unset($this->version);
      unset($this->type);
      unset($this->code);
      unset($this->parameter);
      unset($this->message);
      unset($this->readable_title);
      unset($this->readable_description);

   }// end function __destruct

}// end class LMErrorMessenger
?>