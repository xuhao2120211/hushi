<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/6/19
 * Time: 下午6:24
 */
include_once 'tools.php';

class control{

    public function main(){
        $post = $_POST;

        if(empty($post['action'])){
            $this->returnData(['data' => 'error']);
        }

        $this->returnData(['error' => '010']);
    }


    private function returnData($data){
        echo (json_encode($data));
        die;
    }
}