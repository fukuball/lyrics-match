<?php
/**
 * LMHelper.php is site helper class
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
  * LMHelper is site helper class
  * 
  * An example of a LMHelper is:
  *
  * <code>
  *   LMHelper::function();
  * </code>
  *
  * @category PHP
  * @package  /p-class/helper/
  * @author   Fukuball Lin <fukuball@gmail.com>
  * @license  No Licence
  * @version  Release: <1.0>
  * @link     http://sarasti.cs.nccu.edu.tw
  */
class LMHelper
{

   /**
    * Method static currentFilePath get current file path
    *
    * @return string $current_file_path
    */
	public static function currentFilePath()
	{
	   
	   return $_SERVER['PHP_SELF'];
	
	}// end function currentFilePath
	
	/**
    * Method static currentFullPageURL get current full page url
    *
    * @return string $current_page_url
    */
	public static function currentFullPageURL()
	{
		
		$pageURL = 'http';
		
		if ($_SERVER["HTTPS"] == "on") {
		   
		   $pageURL .= "s";
		
		}// end if
		
		$pageURL .= "://";
		
		if ($_SERVER["SERVER_PORT"] != "80") {
		   
			$pageURL .= $_SERVER["SERVER_NAME"].":".
			   $_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		
		} else {
		   
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		
		}// end if
		
		return $pageURL;
	
	}// end function currentFullPageURL
	
	/**
    * Method static currentPageURL get current page url
    *
    * @return string $current_page_url
    */
	public static function currentPageURL()
	{
		
		$pageURL = 'http';
		
		if ($_SERVER["HTTPS"] == "on") {
		   
		   $pageURL .= "s";
		
		}// end if
		
		$pageURL .= "://";
		
		if ($_SERVER["SERVER_PORT"] != "80") {
		   
			$pageURL .= $_SERVER["SERVER_NAME"].":".
			   $_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		
		} else {
		   
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		
		}// end if
		
	   $pageURL_array = explode("?", $pageURL);
		$pageURL = $pageURL_array[0];
		
		return $pageURL;
	
	}// end function currentPageURL
	
	/**
    * Method static currentPageURLPath get current page url path
    *
    * @return string $current_page_url_path
    */
	public static function currentPageURLPath()
	{
		
		$currentPageURL = self::currentPageURL();
		
		return str_replace(SITE_HOST, "", $currentPageURL);
		
	}// end function currentPageURLPath
	
	/**
    * Method static getUserIP get current user ip
    *
    * @return string $ip
    */
	public static function getUserIP()
	{
      if ($_SERVER["HTTP_X_FORWARDED_FOR"]) {
         
         if ($_SERVER["HTTP_CLIENT_IP"]) {
            
            $proxy = $_SERVER["HTTP_CLIENT_IP"];
         
         } else {
            
            $proxy = $_SERVER["REMOTE_ADDR"];
         
         }// end if
         
         $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
      
      } else {
         
         if ($_SERVER["HTTP_CLIENT_IP"]) {
            
            $ip = $_SERVER["HTTP_CLIENT_IP"];
         
         } else {
            
            $ip = $_SERVER["REMOTE_ADDR"];
         
         }// end if
      
      }// end if
      
      return $ip;
      
	}// end function getUserIP
	
}// end class LMHelper
?>