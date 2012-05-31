<?php
/**
 * LMRESTControl.php is the controller
 * to dispatch all the rest action to it's controller
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /p-class/rest/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

/**
 * LMRESTControl is the controller
 * to dispatch all the rest action to it's controller
 *
 * An example of a LMRESTControl is:
 *
 * <code>
 *  # This will done by rest request
 * </code>
 *
 * @category PHP
 * @package  /p-class/rest/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */
class LMRESTControl
{

   /**
    * Method exceptionResponse output some default exception
    *
    * @param int    $statusCode # the http status code
    * @param string $message    # status message
    *
    * @return void
    */
   static function exceptionResponse($statusCode, $message)
   {

      header("HTTP/1.0 {$statusCode} {$message}");
      echo "{$statusCode} {$message}";
      exit;

   }// end function exceptionResponse

   /**
    * Method index can list all action
    *
    * @return void
    */
   function index()
   {

      echo 'Index will not open.';

   }// end function index

}// end class LMRESTControl
?>