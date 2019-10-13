<?php
/**
 * Created by PhpStorm.
 * User: xuhao
 * Date: 2019/4/24 0024
 * Time: 19:05
 */
if(PHP_SAPI === 'cli' OR defined('STDIN')){
    include_once 'hushi.php';
    $ob = new hushi();
    $ob->run();

}else{

    include_once 'control.php';
    $ob = new control();
    $ob->main();

}
