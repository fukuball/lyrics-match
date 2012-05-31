<?php
/**
 * LMRESTfulInterface.php is the interface
 * to describe rest methods
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
 * LMRESTfulInterface is the interface
 * to describe rest methods
 *
 * An example of a LMRESTfulInterface is:
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
interface LMRESTfulInterface
{

    /**
     * Dispatch get actions
     *
     * @param array $segments Method segments indicate action and resource
     *
     * @return void
     */
    public function restGet($segments);

    /**
     * Dispatch put actions
     *
     * @param array $segments Method segments indicate action and resource
     *
     * @return void
     */
    public function restPut($segments);

    /**
     * Dispatch post actions
     *
     * @param array $segments Method segments indicate action and resource
     *
     * @return void
     */
    public function restPost($segments);

    /**
     * Dispatch delete actions
     *
     * @param array $segments Method segments indicate action and resource
     *
     * @return void
     */
    public function restDelete($segments);

}// end interface LMRESTfulInterface
?>