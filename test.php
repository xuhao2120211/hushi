<?php
    /**
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2019/11/1 0001
     * Time: 22:44
     */
    
    $arr = array(
    
        "project_id"    => "113581",
        "paper_id"      => "",
        "systemVersion" => "9",
        "deviceModel"   => "ALP-AL00",
        "codeVersion"   => "4.0.3",
        "method"        => "hlzs/paper/fetch",
        "api_key"       => "f34b59ac9857e9bbf6a7d58a5e35996b",
        "call_id"       => "9783176383fcc8d468725fe8a10b5806",
        "apiversion"    => "1.0",
//        "sig"           => "d50843479ad5e5656f80b3d390ca4cad",
        "access_token"  => "4bbb227d1b7afee52eff1316b1212abc",
    );
    
    sort($arr);
    var_dump(md5(implode('', $arr)));