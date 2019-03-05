<?php
/**
 *
 */

class AutoLoad
{
    public function __construct()
    {
        require_once(dirname(__FILE__).'/Config.php');
        require_once(dirname(__FILE__).'/Database.php');
        require_once (dirname(__FILE__).'/Router.php');
        require_once(dirname(__FILE__).'/App.php');
    }
}