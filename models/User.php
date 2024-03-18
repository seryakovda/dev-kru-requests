<?php

namespace models;

class User{
    private static $_object;

    public $id;
    public $data;
    private $conn;
    function __construct()
    {
        $this->conn = new \backend\Connection();
    }

    public static function get()
    {
        if (!isset(self::$_object)) {
            self::$_object = new self;
        }
        return self::$_object;

    }

    public function searchUserByLogin($login)
    {
        $data = $this->conn->table("users")->where("login",$login)->select('id');

        if ($res = $data->fetch()){
            return $res['id'];
        }else{
            return false;
        }
    }

    public function addUser($login)
    {
        return $this->conn->table("users")
            ->set("login",$login)
            ->set("HTTP_USER_AGENT",$_SERVER['HTTP_USER_AGENT'])
            ->insert();
    }

    public function login($login,$password){
        //проверяем правильность ввода логина и пароля
        $res=$this->conn->table("users")
            ->where("login",$login)
            ->where("password",$password)
            ->select();
        if ($this->data=$res->fetch()){
            $this->id=$this->data['id'];
            return $this->id;
        }else{
            return false;
        }
    }

    public function find($id){
        $res=$this->conn->table("users")->where("id",$id)->select();

        if ($this->data=$res->fetch()){
            $this->id=$this->data['id'];
            $this->update();
            return true;
        }else{
            return false;
        }

    }

    private function update()
    {
        $this->conn->table("users")
            ->set("HTTP_USER_AGENT",$_SERVER['HTTP_USER_AGENT'])
            ->where('id',$this->data['id'])
            ->update();

    }

}