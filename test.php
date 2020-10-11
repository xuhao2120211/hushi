<?php
    include_once 'tools.php';
    $url = 'http://www.hulizhushou.com/api/';
    /**
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/11/1 0001
     * Time: 22:44
     */
    
    $arg = [
        "project_id"    => "308607",
        "paper_id"      => "",
        "systemVersion" => "12.3.1",
        "deviceModel"   => "iPhone",
        "codeVersion"   => "4.5.0",
        "method"        => "hlzs/paper/fetch",
        "api_key"       => "f34b59ac9857e9bbf6a7d58a5e35996b",
        "call_id"       => "c7d2ab382925a98ec932b10a7931c464",
        "apiversion"    => "1.0",
        "sig"           => "5eaabe994b71ecf589aa02c9b311a839",
        "access_token"  => "96e34a564b9a666f9bed87ea70561df1",
    
    
        "systemVersion" => "12.2",
        "deviceModel" => "iPhone",
        "codeVersion" => "4.2.0",
        "call_id" => "060e446843a828f42abc08ac4fd08952",
        "sig" => "377d8af11be0768b1a17c88305439664",
        "access_token" => "57dd05377dbb64eded01539c13f38bda"
    ];
    
    
    $end = curlRequest($url . $arg['apiversion'] . '/' . $arg['method'], $arg);
    
    var_dump($end);