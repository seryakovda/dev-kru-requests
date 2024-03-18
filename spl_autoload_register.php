<?php
/**
 * Функция подгрузки объектов
 */
spl_autoload_register(function ($class) {
    $path = explode("\\", $class);
    $class = $_SERVER['DOCUMENT_ROOT'];
    if ($_SERVER['SERVER_NAME'] == 'afina'){
        $class = $class."/KRU";
    }
    foreach ($path as $key => $value){
        switch ($value){
            case "PhpOffice":
            case "Zend":
            case "Psr":
                $value="External\\".$value;
                break;
        }
        $class=$class."\\".$value;
    }
    $class=$class.'.php';
    $class = str_replace("\\","/",$class);
    if (file_exists($class)){
        require_once $class;
    }
});