<?php
/**
 * đây là controller quản lí các function chạy cho file Admin
 */
require_once (dirname(__DIR__).'/core/Controller.php');
class AdminController extends Controller
{
    public function __construct($parameter)
    {
        parent::__construct($parameter);
    }
    /**
     * viết 1 function get dành cho login admin
     */
    public function login(){
        parent::render('login' , ['hung' => 'chắc chắn đẹp trai']);
    }
    public function render($view,$data=null){

    }
}