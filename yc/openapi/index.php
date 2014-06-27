<?php
/**
 * @title index
 * @description
 * index
 * @author zhangchunsheng423@yongche.org
 * @version V3.0
 * @date 2014-06-27
 * @copyright  Copyright (c) 2014-2014 Luomor Inc. (http://www.luomor.com)
 */
$method = $_GET["method"];

if($method == "login") {
    $result = json_decode(login());
    echo json_encode($result);
} elseif($method == "all_price") {
    $login_info = json_decode(login());
    $access_token = $login_info->access_token;
    $result = json_decode(get_all_price($access_token));
    echo json_encode($result);
} else {
    echo '{"ret_code":200}';
}

function login() {
    $url = "http://openapi.yongche.org/oauth2/token.php";
    $array = array(
        "grant_type" => "password",
        "username" => "16811303455",
        "password" => "111111",
        "device_token" => "111111",
        "uuid" => "111111",
        "macaddress" => "111111",
    );
    /*$array = array(
        "grant_type:password",
        "username:16811303455",
        "password:111111",
        "device_token:111111",
        "uuid:111111",
        "macaddress:111111",
    );*/
    $header = array(
        'User-Agent:iWeidao/5.2.2 (iPhone; iOS 7.0.2)',
        'Accept-Encoding:deflate',
        'Authorization:Basic dGVzdDp0ZXN0'
    );
    return request($url, "POST", $array, $header);
}

function get_all_price($access_token) {
    $url = "http://openapi.yongche.org/v3/price/all?last_version=0&product_type_id=1%2C7%2C8%2C11%2C12&access_token=$access_token";

    $header = array(
        'User-Agent:iWeidao/5.2.2 (iPhone; iOS 7.0.2)',
        'Accept-Encoding:deflate'
    );
    return request($url, "GET", null, $header);
}

function request($url, $method = "GET", $post_fields = null, $header = array()) {
    $ch = curl_init();

    try {
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        if($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        }
        $result = curl_exec($ch);

        if(curl_error($ch)) {
            error_log("access $url error:" . curl_error($ch));
        }
        curl_close($ch);
    } catch(Exception $e) {
        curl_close($ch);
        throw $e;
    }

    if(empty($result)) {
        return false;
    }

    return $result;
}