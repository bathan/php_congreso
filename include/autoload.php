<?php

function __autoload($class) {

    if (substr($class, 0, strlen(__NAMESPACE__)) != __NAMESPACE__)
        return false;

    if(strpos($class,"Congreso\\entities\\")===0) {
        $class=str_replace("Congreso\\entities\\", "Congreso\\data\\entities\\", $class);
    }

    if(strpos($class,"Congreso\\Logica\\")===0) {
        $class=str_replace("Congreso\\Logica\\", "Congreso\\class\\", $class);
    }

    $class=substr($class, strlen("Congreso\\"));

    $path = sprintf(
        '%s/%s.php', __DIR__ , str_replace("\\", '/', $class)
    );


    if (file_exists($path)){
        require_once($path);
    }else{
        return false;
    }
}