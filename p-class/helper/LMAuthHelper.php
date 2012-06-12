<?php
/**
 * LMAuthHelper.php is auth helper class
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
  * LMAuthHelper is auth helper class
  *
  * An example of a LMAuthHelper is:
  *
  * <code>
  *   LMAuthHelper::function();
  * </code>
  *
  * @category PHP
  * @package  /p-class/helper/
  * @author   Fukuball Lin <fukuball@indievox.com>
  * @license  No Licence
  * @version  Release: <1.0>
  * @link     http://sarasti.cs.nccu.edu.tw
  */
class LMAuthHelper
{

   /**
    * Method static isLogin check user login status
    *
    * @return boolean $is_login
    */
   public static function isLogin()
   {
     if (isset($_COOKIE['lm_user_id']) && !empty($_COOKIE['lm_user_id'])) {

        return true;

     } else {

        return false;

     }

  }// end function isLogin

  /**
    * Method login set user login
    *
    * @param int $user_id # the user id
    *
    * @return void
    */
  public static function login($user_id)
  {

     $cookie_domain = LMAuthHelper::getCookieDomain();

     setcookie(
           "lm_user_id", $user_id,
           time()+60*60*24*365,
           "/", $cookie_domain
     );

  }// end function login

  /**
    * Method static logout set user logout
    *
    * @return void
    */
  public static function logout()
  {

      if (isset($_SERVER['HTTP_COOKIE'])) {

         $cookie_domain = LMAuthHelper::getCookieDomain();

         $cookies = explode(';', $_SERVER['HTTP_COOKIE']);

         foreach ($cookies as $cookie) {

            $parts = explode('=', $cookie);
            $name = trim($parts[0]);

            if ($name == 'lm_user_id') {

               setcookie($name, '', time()-60000, '/', $cookie_domain);
               setcookie($name, '', time()-60000, '/');

            }// end if

         }// end foreach

      }// end if

  }// end function logout


   /**
    * Method getCookieDomain get current host domain
    *
    * @return string $cookie_domain # the domain string
    */
   public static function getCookieDomain()
   {

      switch ($_SERVER['HTTP_HOST']) {

      default:

         $cookie_domain = ".cs.nccu.edu.tw";

         break;
      }

      return $cookie_domain;

   }// end function getCookieDomain

  /**
    * Method permitAccess check access permission
    *
    * @param string $page_path # the page path
    *
    * @return void
    */
   public static function permitAccess($page_path)
   {

      $backyard_pattern = "/^\/b\/*/";

      if (preg_match($backyard_pattern, $page_path)) {

         if (!LMAuthHelper::isLogin()) {

            header("Location: ".SITE_HOST."/403.php");
            exit;

         }// end if

      }// end if

   }// end function permitAccess

}// end class LMAuthHelper
?>