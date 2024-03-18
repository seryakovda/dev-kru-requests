<?php

namespace models;

class ErrorLog
{
    static function saveError($value)
    {
        ob_start();
        print_r($value);
        $out = ob_get_contents();
        ob_end_clean();
        $path = $_SERVER['DOCUMENT_ROOT'];
        if ($_SERVER['SERVER_NAME'] == 'afina'){
            $path = $path."/KRU";
        }

        $file = fopen("$path/MyErrorLog.txt","a");

        fwrite($file, $out."\r\n" );

        fclose ($file );
    }
}
