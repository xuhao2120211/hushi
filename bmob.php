<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/6/26
 * Time: 下午2:12
 */
include_once 'tools.php';

class bmob{

    public $db;

    public $header = [
        'X-Bmob-Application-Id:3e4ae044c2b77a191860c32436d9eed1',
        'X-Bmob-REST-API-Key:4e7c233bb6a61c484be445fefce9fca9'
    ];

    public $url = 'https://api2.bmob.cn/1/classes/';

    /**
     * 获得配置信息
     * @param $key_name
     */
    public function getConf($obj, $retry = 0){
        $url   = $this->url . 'conf';

        $ret   = curlRequest($url, [], $this->header);

        if(!$ret || !isset($ret['results'])){
            $retry++;
            if($retry <= 3){
                return $this->getConf($obj, $retry);
            }
            return false;
        }

        foreach($ret['results'] as $val){
            if(!isset($val['key_name']) || !isset($val['value'])){

                showStr('获取配置失败');
                return false;
            }
            $key_name = $val['key_name'];
            $obj->$key_name = $val['value'];
        }

        return true;
    }

    /**
     * 修改运行状态配置
     * @param $status
     */
    public function updateConf($name, $value){
        $where = urlencode(json_encode(['key_name' => $name]));
        $url   = $this->url . 'conf?where=' . $where;

        curlRequest($url, ['value' => $value], $this->header, 1);
    }


    /**
     * 获得题库列表
     * @param $status 0 不跑的 1 正在跑的 2 全部
     */
    public function getQuestionList($status = '1'){
        $url_after = '';
        if($status != 2){
            $where = urlencode(json_encode(['status' => $status]));
            $url_after = '?where=' . $where;
        }
        $url   = $this->url . 'question_list' . $url_after;

        $ret   = curlRequest($url, [], $this->header);

        return isset($ret['results']) ? $ret['results'] : false;
    }
}
