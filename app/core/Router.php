<?php
	/**
	* Router
	*/
	class Router
	{
		public $routers = [];

		function __construct()
		{
		}

		private function getRequestURL(){

			$url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
			$url = $url === '' || empty($url) ? '/' : $url;
			return $url;
		}

		private function getRequestMethod(){
			$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
			return $method;
		}

		private function addRouter($method,$url,$action){
			$this->routers[] = [$method,$url,$action];
		}

		public function get($url,$action){
            $this->addRouter('GET',$url,$action);
		}

		public function post($url,$action){
            $this->addRouter('POST',$url,$action);
		}

		public function any($url,$action){
			$this->addRouter('GET|POST',$url,$action);
		}

		public function map(){

			$checkRoute = false;
			$params 	= [];
            ///người dùng nhập 1 URL bất kì ta lấy URL + Method
            /// chúng ta sẽ đi phân tích URL xem chạy root nào và param nào. ví dụ :
            /// URL : /sanpham/list/3
            /// ta chạy root : /sanpham/
            /// parram [' list ' , '3 ' ]
			$requestURL = $this->getRequestURL();
			$requestMethod = $this->getRequestMethod();

			$routers = $this->routers;
			////duyệt từ router đã đăng kí
			foreach( $routers as $route ){
			    /// trong từ router
                /// khởi tạo các biến giá trị :
                /// $method = ***method đã đăng kí ****
                /// $url = ***url đã đăng kí****
                /// *action = *** action đang chạy*****
				list($method,$url,$action) = $route;
                /// nếu duyệt mảng tại vị trí i bất kì
                /// $method đã đăng kí khác requestMethod do người dùng request thì chuyển sang vị trí i + 1;
				if( strpos($method, $requestMethod) === FALSE ){
					continue;
				}
                /// nếu duyệt mảng tại vị trí i bất kì
                /// $url đăng kí là * thì cờ checkRoute từ false chuyển thành true;
				if( $url === '*' ){
					$checkRoute = true;
				}
				/// còn không phải đăng kí dạng * thì xem trong url có param không
                /// note : param được để trong dấu ngoặc nhọn (' { ')
				else
				    if( strpos($url, '{') === FALSE ){
				        /// nếu không có *
				        /// nếu không có param
                        /// thì so sánh trùng khớp URL
                        if( strcmp(strtolower($url), strtolower($requestURL)) === 0 ){
                            /// nếu không có *
                            /// nếu không có param
                            /// nếu trùng khớp URL
                            /// cờ checkRoute từ false chuyển thành true;
                            $checkRoute = true;
                        }else{
                            /// tiếp tục chuyển sang vị trí i + 1 để tìm router phù hợp
                            continue;
                        }
				    }else{
                        /// nếu không có *
                        /// nếu có param
                        /// thì xem có mở có đóng đàng hoàng không
                        if( strpos($url, '}') === FALSE ){
                            ///có mở mà không đóng thì bõ và qua i+ 1
                            continue;
                        }else{
                            ///có mở { và đóng } đàng hoàng thì ta cắt chuỗi url thành các param đăng kí và param người dùng request để so sánh
                            /// ví dụ :
                            /// $url = /sanpham/{list}/{id}
                            $routeParams 	= explode('/', $url);
                            /// $routeParams = [ 'san-pham', '{list}' , '{id}' ]
                            $requestParams 	= explode('/', $requestURL);
                            /// $requestParams = [ 'san-pham', 'list' , '3' ]

                            /// so sánh số lượng phần tử khác nhau thì chắc chắn không đúng route rồi nên next
                            if( count($routeParams) !== count($requestParams) ){
                                continue;
                            }
                            /// đặt biến before mục đích để cho next từ i lên i + 1 của vòng lặp ngoài cùng
                            $before = false;
                            /// mapping các param vào
                            foreach( $routeParams as $k => $rp ){
                                if(strpos($rp, '{') === FALSE ){
                                    if($routeParams[$k] !== $requestParams[$k])
                                        $before = !$before;
                                }
                                if( preg_match('/^{\w+}$/',$rp) ){
                                    $params[str_replace(['{' , '}'], '', $routeParams[$k] )] = $requestParams[$k];
                                }
                            }
                            if($before)
                                continue;
                            $checkRoute = true;
                        }
                    }
                /// khi cờ router được bật true nghĩa là route đang xét trùng khớp với route do người dùng nhập trên thanh địa chỉ
                /// ta sẽ cho chạy hàm $action
				if( $checkRoute === true ){
                    $action($params);
					return;
				}else{
					continue;
				}
			}
			return;
		}

		function run(){
			$this->map();
		}
	}
?>