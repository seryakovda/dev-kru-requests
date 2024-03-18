<?php
/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 03.10.2019
 * Time: 11:17
 */

namespace models;

use \backend\Connection;

class ControlElements
{
    private static $_object;
    private $arraySecurity;
    private $conn;
    private $files;
    public function getNameMethod($class,$method)
    {
        $res = explode("::", $method);
        return $class."_".$res[1];
    }

    public static function get()
    {
        if (!isset(self::$_object)) {
            self::$_object = new self;
        }
        return self::$_object;
    }

}