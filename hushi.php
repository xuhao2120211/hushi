<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/6/20
 * Time: 下午3:06
 */
include_once 'db.php';
include_once 'tools.php';

class hushi{
    public $url = 'http://www.hulizhushou.com/api/';

    public $sleep_time = 120;
    public $write_sleep_min = 5;
    public $write_sleep_max = 10;
    public $exceed_score = 500;

    public $db;

    public $rand_str = ['A', 'B', 'C', 'D', 'E'];

    public $are = [
        'top' => 'top',
        'ans' => 'ans',
        'arg' => 'arg',
    ];

    public $tag     = 'andr';
    public $top_val = 0;
    public $my_val  = 0;
    public $my_rank = 0;

    public $chack_val = 0;

    public $arg = [
        "project_id"    => "113581",
        "paper_id"      => "",
        "systemVersion" => "9",
        "deviceModel"   => "ALP-AL00",
        "codeVersion"   => "4.0.3",
        "method"        => "hlzs/paper/fetch",
        "api_key"       => "f34b59ac9857e9bbf6a7d58a5e35996b",
        "call_id"       => "65dd88e8392aa7f50c97a427e2e5f0af",
        "apiversion"    => "1.0",
        "sig"           => "2a755ce9f79f5d5e3a2a75f05610d7df",
        "access_token"  => "96e34a564b9a666f9bed87ea70561df1"
    ];


    public $ans = [
        "user_paper_id" => "",
        "answer"        => [],
        "systemVersion" => "9",
        "deviceModel"   => "ALP-AL00",
        "codeVersion"   => "4.0.3",
        "method"        => "hlzs/paper/commit",
        "api_key"       => "f34b59ac9857e9bbf6a7d58a5e35996b",
        "call_id"       => "14580d0e01e56b4d61fe9a9aca349c00",
        "apiversion"    => "1.0",
        "sig"           => "0cd55916d83aade3c52d91152181005f",
        "access_token"  => "96e34a564b9a666f9bed87ea70561df1"
    ];

    public $top = [
        "top"           => 2,
        "range"         => "daily",
        "order_type"    => "integral",
        "systemVersion" => "9",
        "deviceModel"   => "ALP-AL00",
        "codeVersion"   => "4.0.3",
        "method"        => "hlzs/integral/rank",
        "api_key"       => "f34b59ac9857e9bbf6a7d58a5e35996b",
        "call_id"       => "12084ee8028d7f9c8075a363e019551d",
        "apiversion"    => "1.0",
        "sig"           => "d1384263e70235bf75f8312b754b66d8",
        "access_token"  => "96e34a564b9a666f9bed87ea70561df1",
    ];

    public $andr = [
        'arg' => [
            "api_key"       => "f34b59ac9857e9bbf6a7d58a5e35996b",
            "call_id"       => "ec5b01a64eaab45953774bdecbddbac2",
            "sig"           => "ff63400fed365bcfb640fec057578ae5",
            "access_token"  => "4bbb227d1b7afee52eff1316b1212abc"
        ],
        'ans' => [
            "api_key"       => "f34b59ac9857e9bbf6a7d58a5e35996b",
            "call_id"       => "ec5b01a64eaab45953774bdecbddbac2",
            "sig"           => "ff63400fed365bcfb640fec057578ae5",
            "access_token"  => "4bbb227d1b7afee52eff1316b1212abc"
        ],
        'top' => [
            "api_key"       => "f34b59ac9857e9bbf6a7d58a5e35996b",
            "call_id"       => "e9fadb4e8bff6292ce93011e5147ee35",
            "sig"           => "d7ec253ca0130edf57ae85b41dfb702a",
            "access_token"  => "4bbb227d1b7afee52eff1316b1212abc"
        ]
    ];

    public $ios = [
        'arg' => [
            "api_key"       => "f34b59ac9857e9bbf6a7d58a5e35996b",
            "call_id"       => "65dd88e8392aa7f50c97a427e2e5f0af",
            "sig"           => "2a755ce9f79f5d5e3a2a75f05610d7df",
            "access_token"  => "96e34a564b9a666f9bed87ea70561df1"
        ],
        'ans' => [
            "api_key"       => "f34b59ac9857e9bbf6a7d58a5e35996b",
            "call_id"       => "14580d0e01e56b4d61fe9a9aca349c00",
            "sig"           => "0cd55916d83aade3c52d91152181005f",
            "access_token"  => "96e34a564b9a666f9bed87ea70561df1"
        ],
        'top' => [
            "api_key"       => "f34b59ac9857e9bbf6a7d58a5e35996b",
            "call_id"       => "12084ee8028d7f9c8075a363e019551d",
            "sig"           => "d1384263e70235bf75f8312b754b66d8",
            "access_token"  => "96e34a564b9a666f9bed87ea70561df1"
        ]


    ];

    /**
     * 主体
     */
    public function run(){
//        $db_obj = new db();
//        $this->db = $db_obj->creatDB();
//
//        $ret = $this->db->getConf('project_id');
//
//        var_dump($ret);die;

        while (true){
            //writeSleep($this->write_sleep_min, $this->write_sleep_max);

            try{

                // 获得排行
                $end = $this->getTop();

                // 根据排行确认是否要继续
                if ($end === 0){
                    continue;

                } elseif ($end){

                    sleep($this->sleep_time);
                    showStr('暂停' . $this->sleep_time . '秒');
                    continue;
                }

//                $this->db = $db_obj->creatDB();
                $this->runOne();
//                $db_obj->closeDB();

            }catch (Exception $e) {

                $this->updateToken();
                showStr('修改token');
            }

        }
    }

    /**
     * 模拟一次做题，每次做十道
     * @return bool|mixed|void
     */
    public function runOne(){
        $end = postCurl($this->url . $this->ans['apiversion'] . '/' . $this->arg['method'], json_encode($this->arg));

        $this->ans['user_paper_id'] = $end['data']['user_paper_id'];

        $mis = getMistake();

        if(!is_array($end['data']['user_paper_items'])){
            return;
        }

        foreach ($end['data']['user_paper_items'] as $k => $v){
            $right_answers = $v['qa_item']['right_answers'];

            if(in_array($k, $mis)){
                unset($mis[$k]);
                $right_answers = $this->rand_str[rand(0, count($v['qa_item']['sel_items']) - 1)];
            }

            $right_answers = explode(',', $right_answers);

            $this->ans['answer'][$v['id']] = [
                'sel'      => $right_answers,
                'duration' => 0
            ];

            //$ins = [$v['id'], $v['qa_item']['name'], $v['qa_item']['right_answers'], $v['qa_item']['sel_items']];

            //$this->db->insertQuestion($ins);
        }

        writeSleep($this->write_sleep_min, $this->write_sleep_max);

        $end = postCurl($this->url . $this->ans['apiversion'] . '/' . $this->ans['method'], json_encode($this->ans));

        $this->my_val += 10;

        return $end;
    }



    public function getTop(){
        if($this->my_val < $this->top_val){
            return false;
        }

        showStr('查询开始');
        $end = postCurl($this->url . $this->top['apiversion'] . '/' . $this->top['method'], json_encode($this->top));

        showStr('查询结束');

        // 未获取到排行，主要是token错误
        if($end['data']['status'] == 'fail'){
            $this->updateToken();
            showStr('修改token');
            return 0;
        }

        $data = $end['data']['list'];

        var_dump($data);

        showStr('当前分数为' . $end['data']['my_value'] . '，排名为' . $end['data']['my_rank']);

        if (!is_array($data) || count($data) == 0){
            return false;
        }

        $this->my_val  = (int)($end['data']['my_value']);
        $this->top_val = (int)($data[0]['integral']);
        $this->my_rank = (int)$end['data']['my_rank'];

        if ($end['data']['my_rank'] == 1){
            showStr('第二名为' . $data[1]['integral'] . '，是' . $data[1]['dep_name'] . '的' . $data[1]['name']);

        }else{
            showStr('第一名为' . $data[0]['integral'] . '，是' . $data[0]['dep_name'] . '的' . $data[0]['name']);
        }

        if(!is_array($data) || count($data) == 0){
            return true;
        }

        if ($end['data']['my_rank'] == 1 && $this->my_val > ($data[1]['integral'] + $this->exceed_score)){
            return true;
        }

        return false;
    }

    /**
     * 修改请求token
     */
    public function updateToken(){
        $this->tag = $this->tag == 'andr' ? 'ios' : 'andr';
        $tag = $this->tag;

        foreach ($this->are as $v){
            $this->$v = array_merge($this->$v, $this->$tag[$v]);
        }
    }


}
