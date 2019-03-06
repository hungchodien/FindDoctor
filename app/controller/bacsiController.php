<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 3/6/2019
 * Time: 9:22 AM
 */
require_once (dirname(__DIR__).'/core/Controller.php');
class bacsiController extends Controller
{
    public function __construct($parameter)
    {
        parent::__construct($parameter);
    }
    /**
     * viết 1 function get dành cho trang bac-si admin
     */
    public function bacsi(){
        parent::render('bacsi' , ['hung' => 'bac-si đẹp trai']);
    }
}