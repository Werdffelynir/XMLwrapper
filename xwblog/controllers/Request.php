<?php
/**
 * Created by PhpStorm.
 * User: Comp-2
 * Date: 28.07.14
 * Time: 19:17
 */

//namespace controllers;


class Request
{
    public static $url = null;
    public static $path = null;

    public $controller = null;
    public $action = null;
    public $params = null;
    public static $r = null;
    public static $prxController = 'Controller';
    public static $prxAction = 'action';

    public function __construct()
    {
        self::$path = dirname(__DIR__);
        self::$url = self::url('',true);

        $requestParts = (strlen(self::$url)>1) ? array_values(array_diff(explode('/',self::$url),array(''))) : null;
        $this->controller = (!empty($requestParts[0]))?self::$prxController.ucfirst(array_shift($requestParts)):self::$prxController.'Index';
        $this->action = (!empty($requestParts[0]))?self::$prxAction.ucfirst(array_shift($requestParts)):self::$prxAction.'Index';
        $this->params = (!empty($requestParts[0]))?$requestParts:null;
        self::$r = $this->params;

        $this->run();
    }

    public function run()
    {
        $fileClass = self::$path.'/controllers/'.$this->controller.'.php';

        if (file_exists($fileClass)) {
            require_once($fileClass);
            $controllerObj = new $this->controller ();
            if (method_exists((object)$controllerObj, $this->action)) {
                if (empty($this->params)) {
                    call_user_func(array($controllerObj, $this->action));
                } else {
                    call_user_func_array(array($controllerObj, $this->action), self::params());
                }
            }
        }
    }

    public static function params($key=null)
    {
        $param = array();
        if($count = count(self::$r)){
            for($i=0;$i<$count;$i++)
                $param[self::$r[$i]] = self::$r[++$i];
        }

        if($key != null){
            if(isset($param[$key]))
                return $param[$key];
            else
                return false;
        }else{
            return $param;
        }
    }

    public static function urlFull($part='')
    {
        return $_SERVER['SERVER_NAME'].str_replace('/index.php','',$_SERVER['PHP_SELF']).$part;
    }

    public static function url($part='',$request=false)
    {
        $urlFull = self::urlFull();
        preg_match('/^([\w\.]+)(.*)/i', $urlFull, $eRegResult);

        if($request)
            return str_ireplace($eRegResult[2],'',$_SERVER['REQUEST_URI']).$part;
        else
            return $eRegResult[2].$part;
    }

    public static function urlTpl()
    {
        return self::url('/views/layout');
    }
} 