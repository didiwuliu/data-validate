<?php
/**
 * @title functions
 * @description
 * functions
 * @author zhangchunsheng423@gmail.org
 * @version V1.0
 * @date 2014-07-08
 * @copyright  Copyright (c) 2014-2014 Luomor Inc. (http://www.luomor.com)
 */

/**
 * @param $url
 * @param string $method
 * @param null $post_fields
 * @param array $header
 * @return bool|mixed
 * @throws Exception
 */
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