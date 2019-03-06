<?php

	/**
	* App
	*/
	class App
	{
		private $router;

		function __construct()
		{
            ob_start();
            session_start();
			$this->router = new Router();
            $this->router->get('/',function(){
                echo 'home';
            });
            $this->router->any('/logout',function($param){
                require_once (dirname(__DIR__).'/controller/AdminController.php');
                $AdminController = new AdminController($param);
                $AdminController->logout();
            });
            $this->router->get('/Admin/login',function($param){
                echo 'admin';
                require_once (dirname(__DIR__).'/controller/AdminController.php');
                $AdminController = new AdminController($param);
                $AdminController->login();
            });
            $this->router->post('/Admin/post_login',function($param){
                require_once (dirname(__DIR__).'/controller/AdminController.php');
                $AdminController = new AdminController($param);
                $AdminController->post_login();
            });
            $this->router->get('/Admin/login_suceess',function($param){
                $auth = new Auth();
                echo '<pre>';
                var_dump($auth->user());
                echo 'login thành công';
                echo '<a href="/logout">logout</a>';
            });

            $this->router->get('/bac-si/{id}/{list}',function($param){
                echo 'bac-si';
                require_once (dirname(__DIR__).'/controller/bacsiController.php');
                $AdminController = new bacsiController($param);
                $AdminController->bacsi();
            });
            $this->router->get('/admin/{id}',function($param){
                echo 'page admin với tham số : ';
                echo '<pre>';
                var_dump($param);
            });
            $this->router->get('/benh-vien/{id}/{list}',function($param){
                echo 'page bệnh viện với tham số : ';
                echo '<pre>';
                var_dump($param);
            });
            $this->router->any('*',function(){
                echo '404';
            });
		}

		public function run(){
			$this->router->run();
		}
	}
?>