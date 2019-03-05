<?php
/**
 * Create controller
 */

class Controller{

    protected $param = null;
    public function __construct($parameter){
        $this->param = $parameter;
    }

    public function redirect($url,$isEnd=true,$resPonseCode=302){
        header('Location:'.$url,true,$resPonseCode);
        if( $isEnd )
            die();
    }
    public function render($viewName,$data=null){
        $data['parameter'] = $this->param;
        $layoutPath = dirname(__DIR__).'/view/'.$viewName.'.php';
        if( file_exists($layoutPath) ){
            require( $layoutPath );
        }
    }
}