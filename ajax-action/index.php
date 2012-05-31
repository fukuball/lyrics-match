<?php
/**
 * index.php is the controller
 * to dispatch all the rest ajax-actions to it's controller
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /ajax-action/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

require_once dirname(dirname(__FILE__))."/p-config/application-setter.php";

/**
 * AjaxActionContainer is the controller
 * to dispatch all the rest ajax-actions to it's controller
 *
 * An example of a AjaxActionContainer is:
 *
 * <code>
 *  # This will done by rest request
 * </code>
 *
 * @category PHP
 * @package  /ajax-action/
 * @author   Fukuball Lin <fukuball@gamil.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */
class AjaxActionContainer extends LMRESTControl
{

   private $_control = false;
   private $_segments = false;

   /**
    * AjaxActionContainer construct
    *
    * @return void
    */
   function __construct()
   {

      if ( !isset($_SERVER['PATH_INFO']) or $_SERVER['PATH_INFO'] == '/') {

         // $this->_control = $this->_segments = false;
         return;

      }

      $this->_segments = explode('/', $_SERVER['PATH_INFO']);
      array_shift($this->_segments); // first element always is an empty string.
      $the_class_string = array_shift($this->_segments);

      $raw_control_name_array = explode('-', $the_class_string);
      $control_name = '';

      foreach ($raw_control_name_array as $control_name_partial) {

         $control_name = $control_name.ucfirst($control_name_partial);

      }

      if ( !class_exists($control_name) ) {
         $control_file_path = $control_name . '.php';

         if ( file_exists($control_file_path) ) { // 載入客戶要求的 control

            include_once $control_file_path;

         } else { // 找不到客戶要求的 control

            self::exceptionResponse(503, 'Service Unavailable!');
            // 回傳 501 (Not Implemented) 或 503.
            // See also: RFC 2616

         }
      }

      $this->_control = new $control_name;

   }// end function __construct()

   /**
    * Index AjaxActionContainer resources
    *
    * @return void
    */
   function index()
   {

      //echo 'index/{control name}/{object id}';

   }// end function index


   /**
    * Run AjaxActionContainer to get the resource
    *
    * @return void
    */
   function run()
   {

      if ( $this->_control === false) {

         return $this->index();

      }

      if ( empty($this->_segments) ) { // Without parameter

         return $this->_control->index();

      }

      //request resource by RESTful way.
      //$method = $this->restMethodname;
      $method = 'rest'.ucfirst(strtolower($_SERVER['REQUEST_METHOD']));

      if ( !method_exists($this->_control, $method) ) {

         self::exceptionResponse(405, 'Method not Allowed!');

      }

      $arguments = $this->_segments;
      $this->_control->$method($arguments);

   }// end function run

}// end class AjaxActionContainer

$container = new AjaxActionContainer();
$container->run();

require_once SITE_ROOT."/p-config/application-unsetter.php";

?>