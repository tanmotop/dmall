<?php
/**
 * @param $nu
 * @param string $com
 * @return mixed
 */
function getPostNumber( $nu, $com = 'HTKY' ){
    $host = "https://goexpress.market.alicloudapi.com";
    $path = "/goexpress";
    $method = "GET";
    $appcode = "30ce86034089475f949c17bfc805209e";
    $headers = array();
    array_push($headers, "Authorization:APPCODE " . $appcode);
    $querys = "no=$nu&type=$com";
    $bodys = "";
    $url = $host . $path . "?" . $querys;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_FAILONERROR, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    if (1 == strpos("$".$host, "https://"))
    {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    }
    $res = curl_exec($curl);
    return $res;
}