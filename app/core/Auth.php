<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 3/6/2019
 * Time: 12:59 PM
 */

class Auth
{
    private $check_login = null;
    private $user = null;

    public function __construct()
    {
        $this->getUserSession();
    }
    /**
     * check login nếu :
     * - chưa login trả ra false
     * - đã login trả ra true
     */
    public function check_login(){
        if($this->check_login == null || $this->check_login == false)
            return false;
        else
            return true;
    }
    /**
     * login user
     * @param
     */
    public function login($username , $password ){
        $db = new Database();
        if($db->check_connect){
            $query = new SqlQueryBuilder();
            $sql = $query->select(['*'])->from('doctor')->where([
                [
                    "column" => "title",
                    "operator" => "=",
                    "value" => $username
                ],
                [
                    "column" => 'description',
                    "operator" => "=",
                    "value" => $password
                ]
            ])->build();
            $kq = $db->query_sql($sql);
            ///new session user
            if(count($kq) > 0){
                $_SESSION["user"] = [
                    'username' => $kq[0]['title'],
                    'email' => $kq[0]['title'],
                    'password' => $kq[0]['description']
                ];
                $this->getUserSession();
                $this->check_login = true;
                return true;
            }else{
                $this->check_login = false;
                return false;
            }
        }
    }
    /**
     * get user infor
     */
    public function user(){
        return $this->user;
    }
    /**
     * logout
     */
    public function logout(){
        $this->check_login = null;
        $this->user = null;
        unset($_SESSION["user"]);
    }
    /**
     * get infor user login form session
     */
    private function getUserSession(){
        if(isset($_SESSION['user'])){
            $this->user = $_SESSION["user"];
            $this->check_login = true;
        }
    }
    /**
     * ghi lên $_SESSION các thông tin cần thiết đến khi khơi tạo lại đối tượng Auth thì mặc định tiếp tục đã có thông tin rồi
     */
}