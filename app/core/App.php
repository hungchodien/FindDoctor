<?php

	/**
	* App
	*/
	class App
	{
		private $router;

		function __construct()
		{
			$this->router = new Router();
            $this->router->get('/',function(){
                echo 'home';
            });
            $this->router->get('/Admin/login/{id}',function($param){
                require_once (dirname(__DIR__).'/controller/AdminController.php');
                $AdminController = new AdminController($param);
                $AdminController->login();
            });



            $this->router->get('/bac-si/{id}/{list}',function($param){
                echo 'page bác sĩ có các tham số :';
                echo '<pre>';
                var_dump($param);
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