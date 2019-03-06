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
        $sql = new SqlQueryBuilder();
        $query = $sql->select(['*'])->from(' doctor ')->where('1 == 1 ')->build();
        $db = new Database();
        $kq = $db->query_sql('SELECT * FROM doctor');
        parent::render('login' , ['hung' => 'chắc chắn đẹp trai' , 'sql' => $query , 'kq' => $kq]);
    }
    public function post_login(){
        if($_REQUEST['username'] == 'hung' && $_REQUEST['pasword'] == 'hung')
            $this->redirect($GLOBALS['Config']['home_page'].'/Admin/login_suceess');
        else{
            $this->redirect($GLOBALS['Config']['home_page'].'/Admin/login');
        }
    }
}