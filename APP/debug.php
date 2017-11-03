<?php

// SET DIRECTORY SEPARATOR TO DS
define('DS', DIRECTORY_SEPARATOR);

function debug($var){
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}


$req = $_GET;

debug($request);

if( isset($req['page']) ){
    $page = $req['page'];
}else{
    $file = dirname(__FILE__) . DS . 'app' . DS . 'views' . DS . '404.php';
    include $file;
}

?>
