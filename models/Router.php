<?php

namespace models;

class Router
{
    private static $_object;
    private $checkMonth;
    private $f_grant;
    private $conn;
    private $parent;
    public $route;
    public $r1;


    function __construct()
    {
        $this->conn = new \backend\Connection(true);

        if ($_SERVER['REQUEST_METHOD']=="GET"){
            $this->parent = empty($_GET["parent"])?"":"\\".$_GET["parent"];
            $this->route = empty($_GET["r0"])?"index":$_GET["r0"];
            $this->r1 = empty($_GET["r1"])?"":$_GET["r1"];
        }else {
            $this->parent = empty($_POST["parent"])?"":"\\".$_POST["parent"];
            $this->route= empty($_POST["r0"])?"index":$_POST["r0"];
            $this->r1= empty($_POST["r1"])?"":$_POST["r1"];
        }
    }
    public static function get()
    {
        if (!isset(self::$_object)) {
            self::$_object = new self;
        }
        return self::$_object;
    }

    public function AppRun($newRoute=false)
    {
        $controller = new \controllers\SiteController();
        /**
         * проверяем номер пользователя в переменной сессии если пользователь не задан выставляем 0
         */
        $detectedUser=$this->detectActiveUser();

        /**
         * если у нас определён пользователь и при этом происходит перезагрузка страници
         * необъодимо
         */
        if (($detectedUser>0) and (($this->route=="index"))){
            $this->route="skeletonApp";
        }

        switch ($detectedUser){
            case 0:{ // если пользователь ещё не ваторизован
                switch ($this->route) {
                    case "index" : // задаём пустые переменные в массиве сессий
                        $_SESSION["id_user"]=0;
                        $_SESSION["idMenu"]=0;
                        break;
                }
                break;
            }
            default:{
                switch ($this->route) {
                    case "Блокировка_базы" :{
                        $views= new \views\Views();
                        $views->MessageBlockDB("Внимание","В данное время производится глобальная операция, в связи с этим доступ Ограничен");
                        break;
                    }
                    default:{
                        $this->runInstruction();
                        break;
                    }
                }
            }
        }
    }

    /**
     * Метод запуска вызываемых слассов
     */
    private function runInstruction()
    {
       // $ControlElements = ControlElements::get();
   //     require_once $_SERVER['DOCUMENT_ROOT']."/forms/".$this->route."/".$this->route.".php";
        $class="\\forms$this->parent\\$this->route\\Control";
        $run= new $class;
        $run->run();
    }



    private function detectActiveUser()
    {
        $user = \models\User::get();
        if (!array_key_exists("id_user",  $_SESSION)){
            $login  = gethostbyaddr($_SERVER['REMOTE_ADDR']); // имя рабочей станцииж
            if (!($id = $user->searchUserByLogin($login))){
                $id = $user->addUser($login);
            }
            $_SESSION["id_user"] = $id;
        }
        $user->find($_SESSION["id_user"]);
        $_SESSION['userAdmin'] = $user->data['admin'];
        return $_SESSION["id_user"];
    }

}



