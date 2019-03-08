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
    public function logout(){
        $auth = new Auth();
        $auth->logout();
        $this->redirect($GLOBALS['Config']['home_page'].'/Admin/login');
    }
    public function login(){
        $auth = new Auth();
        if($auth->check_login() == false){
            ///user chưa login
            $sql = new SqlQueryBuilder();
            $query = $sql->select(['*'])->from(' doctor ')->where('1 == 1 ')->build();
            $db = new Database();
            $kq = $db->query_sql('SELECT * FROM doctor');
            parent::render('login' , ['hung' => 'chắc chắn đẹp trai' , 'sql' => $query , 'kq' => $kq]);
        }else{
            //login thành công
            $this->redirect($GLOBALS['Config']['home_page'] . '/Admin/login_suceess');
        }
    }
    public function post_login(){
        $username = $_REQUEST['username'];
        $password = $_REQUEST['pasword'];

        $db = new Database();
        if($db->check_connect['status']){
            $auth = new Auth();
            if(!$auth->check_login()){
                ///chưa login thì login
                if($auth->login($username , $password)){
                    $sessio = new Session();
                    echo '<pre>';
                    $sessio->setSessionNew(['login_thanhcong'=> 1]);
                    //login thành công
                    $this->redirect($GLOBALS['Config']['home_page'] . '/Admin/login_suceess');

                }else{
                    $sessio = new Session();
                    $sessio->setSessionNew(['login_thatbai'=> 1]);
                    /// login thất bại
                    $this->redirect($GLOBALS['Config']['home_page'].'/Admin/login');
                }
            }
        }else{
            die('error system connect db');
        }
    }
}