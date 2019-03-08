<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 3/7/2019
 * Time: 9:11 AM
 */

class Session
{
    public $session;
    public static $stateChangeSessionServer;
    public function __construct($state = true){
        self::$stateChangeSessionServer = $state;

        if(self::$stateChangeSessionServer == false){
            $_SESSION['flash'] = [
                'new' => [
                    'url' => "$_SERVER[REQUEST_URI]"
                ],
                'old' => $_SESSION['flash']['new'],
                'lastest' => $_SESSION['flash']['old']
            ];
            ///trong xuốt hệ thống chúng ta chỉ convert sesion url cũ thành session
            self::$stateChangeSessionServer = true;
            echo 'thao tác chạy lần e';
        }
        ///mapping session
        $this->mapping();
    }
    private function mapping(){
        $this->session['flash'] = [
            'new' => $_SESSION['flash']['new'],
            'old' => $_SESSION['flash']['old'],
            'lastest' => $_SESSION['flash']['lastest']
        ];
    }
    public function setSessionNew($value){
        array_push($_SESSION['flash']['new'] , $value);
        $this->mapping();
    }
}

