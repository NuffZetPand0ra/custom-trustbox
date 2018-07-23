<?php
require_once 'functions.php';
$data = getCached();
if(isOld($data->cached) || (isset($_GET['forcenew']))){
    $data = getNew();
}
if(isset($_GET['callback'])){
    header('Access-Control-Allow-Origin: https://inkpro.dk/');
    header('Content-Type: text/javascript; charset=utf8');
    header('Access-Control-Allow-Methods: GET');
    echo $_GET['callback'].'('.json_encode($data).')';
}else{
    header("Content-Type: application/json; charset=utf8");
    echo json_encode($data);
}