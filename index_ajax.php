<?php
//header('Access-Control-Allow-Origin: *');
session_start();

include "spl_autoload_register.php"; //Функция подгрузки объектов

$router = \models\Router::get(); // Вызов гравного маршрутизатора приложения
$router->AppRun();
