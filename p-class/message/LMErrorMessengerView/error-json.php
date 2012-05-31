<?php
/**
 * error_json.php is the view of error json
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /p-class/message/LMErrorMessengerView
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

header('Content-type: application/json');

$json_data = array (
   "response"=>
   array(
      "status"=>
         array(
            "version"=>$version,
            "code"=>$error_code,
            "error_type"=>$error_type,
            "message"=>$message,
            "parameter"=>$parameter,
         )
   )
);

echo json_encode($json_data);
?>