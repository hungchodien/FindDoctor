<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 3/5/2019
 * Time: 9:56 AM
 */

class AutoLoad
{
    public function __construct()
    {
        //include_once (dirname(__FILE__).'/Router.php');
        require_once (dirname(__FILE__).'/Router.php');
        require_once(dirname(__FILE__).'/App.php');
    }
}