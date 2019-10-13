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
function nowTime($time = ''){
    date_default_timezone_set('Asia/Shanghai');
    if(!$time){
        $time = time();
    }
    return date('Y-m-d H:i:s', $time);
}

/**
 * 打印
 * @param $str
 */
function showStr($str){
    $LogFile = 'log/' . date('Ymd') . '.log';
if(is_array($str)){
        $str = var_export($str);
    }
    $echo_text = nowTime() . ' ' . $str . PHP_EOL;

    //echo $echo_text;
    file_put_contents($LogFile, $echo_text, FILE_APPEND);
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


/**
 * curl请求
 * @param $url
 * @param $requestString
 * @param int $timeout
 * @return bool|mixed
 */
function curlRequest($url, $requestString = [], $head = [], $type = 0, $timeout = 5){
    if($url == '' || $timeout <= 0){
        return false;
    }

    $headers = [
        "Content-type: application/json;charset='utf-8'",
        "Accept: application/json",
        "Cache-Control: no-cache"
    ];

    if(is_array($head) && count($head) > 0){
        $headers = array_merge($headers, $head);
    }

    $con = curl_init((string)$url);

    // 参数
    if(is_array($requestString) && count($requestString) > 0){
        curl_setopt($con, CURLOPT_POSTFIELDS, json_encode($requestString));

        if($type == 0){
            // POST
            curl_setopt($con, CURLOPT_POST,true);

        }elseif($type == 1){
            // PUT
            curl_setopt($con, CURLOPT_CUSTOMREQUEST,"PUT");

        }elseif($type == 2){
            // DELETE
            curl_setopt($con, CURLOPT_CUSTOMREQUEST,"DELETE");
        }
    }

    curl_setopt($con, CURLOPT_HEADER, false);
    curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($con, CURLOPT_TIMEOUT,(int)$timeout);

    $ret = curl_exec($con);

    return json_decode($ret, true);
}

