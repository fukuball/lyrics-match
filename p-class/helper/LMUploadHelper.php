<?php
/**
 * LMUploadHelper.php is upload helper class
 *
 * PHP version 5
 *
 * @category PHP
 * @package  /p-class/helper/
 * @author   Fukuball Lin <fukuball@gmail.com>
 * @license  No Licence
 * @version  Release: <1.0>
 * @link     http://sarasti.cs.nccu.edu.tw
 */

 /**
  * LMUploadHelper is upload helper class
  *
  * An example of a LMUploadHelper is:
  *
  * <code>
  *   LMUploadHelper::function();
  * </code>
  *
  * @category PHP
  * @package  /p-class/helper/
  * @author   Fukuball Lin <fukuball@gmail.com>
  * @license  No Licence
  * @version  Release: <1.0>
  * @link     http://sarasti.cs.nccu.edu.tw
  */
class LMUploadHelper
{

   /**
    * Method static pluploadProcess to process plupload
    *
    * @param array $REQUEST
    * @param array $SERVER
    * @param array $FILES
    *
    * @return void
    */
  public static function pluploadProcess($REQUEST, $SERVER, $FILES, $target_file_name)
  {

     $chunk = isset($REQUEST["chunk"]) ? intval($REQUEST["chunk"]) : 0;
     $chunks = isset($REQUEST["chunks"]) ? intval($REQUEST["chunks"]) : 0;
     $file_name = isset($REQUEST["name"]) ? $REQUEST["name"] : '';
     // Clean the file_name for security reasons
     $file_name = preg_replace('/[^\w\._]+/', '_', $file_name);

     $occ = strrpos($file_name, ".");
     $subname = substr($file_name, $occ);
     $file_path = $target_file_name.$subname;

     // Look for the content type header
     if (isset($SERVER["HTTP_CONTENT_TYPE"]))
        $content_type = $SERVER["HTTP_CONTENT_TYPE"];

     if (isset($SERVER["CONTENT_TYPE"]))
        $content_type = $SERVER["CONTENT_TYPE"];

     // Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
     if (strpos($content_type, "multipart") !== false) {

        if (isset($FILES['file']['tmp_name']) && is_uploaded_file($FILES['file']['tmp_name'])) {
           // Open temp file
           $out = fopen("{$file_path}.part", $chunk == 0 ? "wb" : "ab");
           if ($out) {
              // Read binary input stream and append it to temp file
              $in = fopen($FILES['file']['tmp_name'], "rb");

              if ($in) {
                 while ($buff = fread($in, 4096)) {
                    fwrite($out, $buff);
                 }
              } else {
                 //die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                 return 'fail';
              }
              fclose($in);
              fclose($out);
              @unlink($FILES['file']['tmp_name']);
           } else {
              //die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
              return 'fail';
           }
        } else {
           //die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
           return 'fail';
        }

     } else {

        // Open temp file
        $out = fopen("{$file_path}.part", $chunk == 0 ? "wb" : "ab");
        if ($out) {
           // Read binary input stream and append it to temp file
           $in = fopen("php://input", "rb");

           if ($in) {
              while ($buff = fread($in, 4096)) {
                 fwrite($out, $buff);
              }
           } else {
              //die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
              return 'fail';
           }
           fclose($in);
           fclose($out);
        } else {
           //die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
           return 'fail';
        }
     }

     // Check if file has been uploaded
     if (!$chunks || $chunk == $chunks - 1) {
       // Strip the temp .part suffix off
        rename("{$file_path}.part", $file_path);

        return $file_path;
     }

  }// end function pluploadProcess

}// end class LMUploadHelper
?>