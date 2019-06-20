<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/6/20
 * Time: 下午3:14
 */

/**
 * 当前时间
 * @return time
 */
function nowTime(){
    return date('Y-m-d H:i:s', time(true));
}

/**
 * 打印
 * @param $str
 */
function showStr($str){
    echo nowTime() . ' ' . $str . "\r\n";
}

/**
 * 模拟做题间隙
 */
function writeSleep($min, $max){
    sleep(rand($min, $max));
}


/**
 * 获得选项对应的数字
 * @param $sel
 * @return int
 */
function getSel($sel){
    switch ($sel){
        case 'A':
            $ret = 0;
            break;
        case 'B':
            $ret = 1;
            break;
        case 'C':
            $ret = 2;
            break;
        case 'D':
            $ret = 3;
            break;
        case 'E':
            $ret = 4;
            break;
        case 'F':
            $ret = 5;
            break;
        case 'G':
            $ret = 6;
            break;
        case 'H':
            $ret = 7;
            break;
    }

    return $ret;
}

/**
 * curl请求
 * @param $url
 * @param $requestString
 * @param int $timeout
 * @return bool|mixed
 */
function postCurl($url,$requestString,$timeout = 5){
    if($url == '' || $requestString == '' || $timeout <= 0){
        return false;
    }
    $headers = [
        "Content-type: application/json;charset='utf-8'",
        "Accept: application/json",
        "Cache-Control: no-cache"
    ];

    $con = curl_init((string)$url);
    curl_setopt($con, CURLOPT_HEADER, false);
    curl_setopt($con, CURLOPT_POSTFIELDS, $requestString);
    curl_setopt($con, CURLOPT_POST,true);
    curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($con, CURLOPT_TIMEOUT,(int)$timeout);

    $ret = curl_exec($con);

    return json_decode($ret, true);
}

/**
 * 模拟错题，随机返回错题，第几道题错误
 * @return array
 */
function getMistake(){
    $ret = [];
    $ans_arr = array_rand(range(1,10), 10);
    shuffle($ans_arr);
    $num = rand(0, 1);
    if (!$num){
        return $ret;
    }

    for($i = 1; $i <= $num; $i++){
        $ret[] = $ans_arr[$i];
    }

    return $ret;
}