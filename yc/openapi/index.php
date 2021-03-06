<?php
/**
 * @title index
 * @description
 * index
 * @author zhangchunsheng423@gmail.org
 * @version V1.0
 * @date 2014-06-27
 * @copyright  Copyright (c) 2014-2014 Luomor Inc. (http://www.luomor.com)
 */
require("./functions.php");

$method = isset($_GET["method"]) ? $_GET["method"] : "all_price";
$server = isset($_GET["server"]) ? $_GET["server"] : "test";// release test

if($method == "login") {
    $result = json_decode(login($server));
    echo json_encode($result);
} elseif($method == "all_price") {
    $login_info = json_decode(login($server));
    $access_token = $login_info->access_token;
    $result = json_decode(get_all_price($access_token, $server));
    echo json_encode($result);
} else {
    echo '{"ret_code":200}';
}

function login($server) {
    if($server == "release") {
        $url = "https://open.didiwuliu.com/oauth2/token.php";
        $array = array(
            "grant_type" => "password",
            "username" => "16846014601",
            "password" => "123456",
            "device_token" => "666666",
            "uuid" => "666666",
            "macaddress" => "111111",
        );

        $header = array(
            'User-Agent:iWeidao/5.2.2 (iPhone; iOS 7.0.2)',
            'Accept-Encoding:deflate',
            //'Authorization:Basic MTU0NmNhMjdiOGMwYjBiZmM5MGM1Nzg4NDE2ZjBiYzU6NDM2YWY5OGRlZGIwZGZmMjM0NmU1Mzc5MTNhYTlkYzU='
            'Authorization:Basic ZDBkYTYxYWJkNjI0Zjg4ODMxZDExNzM1MzdiMjk0MzA6YjMyYzBiZDM3NTBiNDk2OTdhMWM4NDk4YWZmZDhlY2M='
        );
    } elseif($server == "test") {
        $url = "http://openapi.didiwuliu.org/oauth2/token.php";
        $array = array(
            "grant_type" => "password",
            "username" => "16811303455",
            "password" => "111111",
            "device_token" => "666666",
            "uuid" => "666666",
            "macaddress" => "111111",
        );

        $header = array(
            'User-Agent:iWeidao/5.2.2 (iPhone; iOS 7.0.2)',
            'Accept-Encoding:deflate',
            'Authorization:Basic dGVzdDp0ZXN0'
        );
    }

    return request($url, "POST", $array, $header);
}

function get_all_price($access_token, $server) {
    if($server == "release") {
        $url = "https://open.didiwuliu.com/v3/price/all?last_version=0&product_type_id=1%2C7%2C8%2C11%2C12&access_token=$access_token&filter_price_list=0";
    } else {
        $url = "http://openapi.didiwuliu.org/v3/price/all?last_version=0&product_type_id=1%2C7%2C8%2C11%2C12&access_token=$access_token&filter_price_list=0";
    }

    $header = array(
        'User-Agent:iWeidao/5.2.2 (iPhone; iOS 7.0.2)',
        'Accept-Encoding:deflate'
    );
    return request($url, "GET", null, $header);
}