<?php
namespace controllers;

class SiteController
{
    /*
    public function actionAuthorization()
    {
        $Viewer = new Views();
        $Viewer  ->autorisationForm('');
    }
    */
    public function getBlockDB(){
        $conn = new \backend\Connection(true);
        $org=array_key_exists("ORG",  $_SESSION)?     $_SESSION["ORG"]:0;
        $res=$conn->table('block')
            ->where('org','=',$org)
            ->select('value')
            ->fetchField('value');
        if ($res){
            return $res;
        }else{
            return 0;
        }
    }
    public function MessageBlockDB(){
        $views= new \views\Views();
        $views->MessageBlockDB("Внимание","В данное время производится глобальная операция, в связи с этим доступ Ограничен");

    }

}