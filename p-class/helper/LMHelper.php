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

  /**
   * Method static doGet to get url content
   *
   * @param string $url # the url
   *
   * @return string $response
   */
   public static function doGet($url)
   {
      $ch = curl_init($url);

      $header[0] = 'Accept: text/xml,application/xml,application/xhtml+xml,'
                      . 'text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5';
      $header[] = 'Cache-Control: max-age=0';
      $header[] = 'Connection: keep-alive';
      $header[] = 'Keep-Alive: 300';
      $header[] = 'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7';
      $header[] = 'Accept-Language: en-us,en;q=0.5';

      $cookieFile = "cookie_china"; // I've changed this value and it seems to be working fine, I get the same results

      $options = array(
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_HEADER => false,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_ENCODING => 'gzip,deflate',
                  CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:6.0) Gecko/20100101 Firefox/6.0 FirePHP/0.6',
                  CURLOPT_AUTOREFERER => true,
                  CURLOPT_CONNECTTIMEOUT => 120,
                  CURLOPT_TIMEOUT => 120,
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_SSL_VERIFYHOST => 0,
                  CURLOPT_SSL_VERIFYPEER => false,
                  CURLOPT_VERBOSE => 1,
                  CURLOPT_HTTPHEADER => $header,
                  CURLOPT_COOKIEFILE => $cookieFile,
                  CURLOPT_COOKIEJAR => $cookieFile,
      );

      curl_setopt_array($ch, $options);

      $response = curl_exec($ch);

      curl_close($ch);

      return $response;

      /*$ch = curl_init();


      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:12.0) Gecko/20100101 Firefox/12.0');
      curl_setopt($ch, CURLOPT_COOKIE, 'BIGipServerTWWSs_main=855703562.20480.0000; __utma=129119753.1274697662.1338372832.1338372832.1338372832.1; __utmb=129119753.3.10.1338372832; __utmc=129119753; __utmz=129119753.1338372832.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __atuvc=3|22');
      curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
      curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");

      $response = curl_exec($ch);
      curl_close($ch);

      return $response;*/
   }// end function doGet

   /**
   * Method static doPost to post url content
   *
   * @param string $url   # the url
   * @param array  $fields # the parameters
   *
   * @return string $response
   */
   public static function doPost($url, $fields)
   {

      $fields_string = '';

      foreach ($fields as $key => $value)
      {
          $fields_string .= $key . '=' . $value . '&';
      }
      $fields_string = rtrim($fields_string, '&');

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:12.0) Gecko/20100101 Firefox/12.0');
      curl_setopt($ch, CURLOPT_COOKIE, 'BIGipServerTWWSs_main=855703562.20480.0000; __utma=129119753.1274697662.1338372832.1338372832.1338372832.1; __utmb=129119753.3.10.1338372832; __utmc=129119753; __utmz=129119753.1338372832.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __atuvc=3|22');
      curl_setopt($ch, CURLOPT_POST, count($fields));
      curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

      $response = curl_exec($ch);
      curl_close($ch);

      return $response;

   }// end function doPost

}// end class LMHelper
?>