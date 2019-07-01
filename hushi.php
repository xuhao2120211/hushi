<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/6/20
 * Time: 下午3:06
 */
include_once 'db.php';
include_once 'bmob.php';
include_once 'tools.php';

class hushi{
    public $url = 'http://www.hulizhushou.com/api/';

    // 到第一后的睡眠时间
    public $sleep_time = 120;

    // 模拟做题间隙睡眠时间范围
    public $write_sleep_min = 5;
    public $write_sleep_max = 10;

    // 比第二名多出多少分后可以开始睡眠
    public $exceed_score = 500;

    // 数据库对象，用于存题
    public $db;

    // 数据库存储对象，用于存设置
    public $bmob;

    // 答案对应数组
    public $rand_str = ['A', 'B', 'C', 'D', 'E', 'F'];

    public $are = [
        'top' => 'top',
        'ans' => 'ans',
        'arg' => 'arg',
    ];

    // 当前使用的token名称
    public $tag     = 'andr';

    // 最高分数
    public $top_val = 0;

    // 我的分数
    public $my_val  = 0;

    // 我的排名
    public $my_rank = 0;

    // 第二名得分
    public $second_val = 0;

    // 存题开关
    public $save_question = 0;

    // 开始开关
    public $open_run = 0;

    // 开始时间
    public $start_time = '';

    // 结束时间
    public $end_time = '';

    public $last_rank_time = 0;

    public $arg = [
        "project_id"    => "113581",
        "paper_id"      => "",
        "systemVersion" => "9",
        "deviceModel"   => "ALP-AL00",
        "codeVersion"   => "4.0.3",
        "method"        => "hlzs/paper/fetch",
        "api_key"       => "f34b59ac9857e9bbf6a7d58a5e35996b",
        "call_id"       => "9783176383fcc8d468725fe8a10b5806",
        "apiversion"    => "1.0",
        "sig"           => "d50843479ad5e5656f80b3d390ca4cad",
        "access_token"  => "4bbb227d1b7afee52eff1316b1212abc",
    ];


    public $ans = [
        "user_paper_id" => "",
        "answer"        => [],
        "systemVersion" => "9",
        "deviceModel"   => "ALP-AL00",
        "codeVersion"   => "4.0.3",
        "method"        => "hlzs/paper/commit",
        "api_key"       => "f34b59ac9857e9bbf6a7d58a5e35996b",
        "call_id"       => "fa1f57606f0368b91fd955b01cfa7ade",
        "apiversion"    => "1.0",
        "sig"           => "999a37fcb7968961f03f55e24cb1c2d6",
        "access_token"  => "4bbb227d1b7afee52eff1316b1212abc"
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
        "call_id"       => "2c73337646bda15e23c7cb280beae181",
        "apiversion"    => "2.0",
        "sig"           => "fd62788bf22cfda14a1f672eb766c876",
        "access_token"  => "4bbb227d1b7afee52eff1316b1212abc",
    ];

    public $andr = [
        'arg' => [
            "call_id"       => "9783176383fcc8d468725fe8a10b5806",
            "sig"           => "d50843479ad5e5656f80b3d390ca4cad",
            "access_token"  => "4bbb227d1b7afee52eff1316b1212abc",
        ],
        'ans' => [
            "call_id"       => "fa1f57606f0368b91fd955b01cfa7ade",
            "sig"           => "999a37fcb7968961f03f55e24cb1c2d6",
            "access_token"  => "4bbb227d1b7afee52eff1316b1212abc",
        ],
        'top' => [
            "call_id"       => "2c73337646bda15e23c7cb280beae181",
            "sig"           => "fd62788bf22cfda14a1f672eb766c876",
            "access_token"  => "4bbb227d1b7afee52eff1316b1212abc",
        ]
    ];

    public $ios = [
        'arg' => [
            "call_id"       => "65dd88e8392aa7f50c97a427e2e5f0af",
            "sig"           => "2a755ce9f79f5d5e3a2a75f05610d7df",
            "access_token"  => "96e34a564b9a666f9bed87ea70561df1"
        ],
        'ans' => [
            "call_id"       => "14580d0e01e56b4d61fe9a9aca349c00",
            "sig"           => "0cd55916d83aade3c52d91152181005f",
            "access_token"  => "96e34a564b9a666f9bed87ea70561df1"
        ],
        'top' => [
            "call_id"       => "12084ee8028d7f9c8075a363e019551d",
            "sig"           => "d1384263e70235bf75f8312b754b66d8",
            "access_token"  => "96e34a564b9a666f9bed87ea70561df1"
        ]


    ];

    /**
     * 主体
     */
    public function run(){
        date_default_timezone_set('Asia/Shanghai');

        $this->bmob = new bmob();

        $this->db = new db();
        $this->db->creatDB();

        $this->setStartTime();

        while (true){
//            writeSleep($this->write_sleep_min, $this->write_sleep_max);

            // 判断是否开始跑，不跑则睡眠一分钟
            if(!$this->bmob->getConf('open_run')){
                showStr('暂停60秒');
                $this->end_time = '';
                sleep(60);

                continue;
            }

            // 设置结束时间
            $this->setEndTime();

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

                $this->runOne();

            }catch (Exception $e) {

                $this->updateToken();
                showStr('修改token');
            }

            // 结束
            if(time() > $this->end_time){
                $this->bmob->updateOpenRun('0');
            }
        }
    }

    /**
     * 模拟一次做题，每次做十道
     * @return bool|mixed|void
     */
    public function runOne(){

        $now_question = $this->randRetQustion();

        // 当前要跑的题id
        $this->arg['project_id'] = $now_question['q_id'];

        $end = curlRequest($this->url . $this->ans['apiversion'] . '/' . $this->arg['method'], $this->arg);

        // 返回报错
        if(!is_array($end['data']['user_paper_items'])){
            return;
        }

        $this->ans['user_paper_id'] = $end['data']['user_paper_id'];

        // 模拟人工错题
        $mis = getMistake();

        // 确认是否需要存题
        $this->save_question = $this->bmob->getConf('save');
        // 存题的库名
        $table_name = 'question_' . $now_question['q_id'];
        // 已存的题数
        $now_count  = $this->db->getCount($table_name);
        // 是否已存满题，0继续存，1不再存
        $save_ques  = $now_count >= $now_question['num'] ? 0 : 1;

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

            // 存题
            if($this->save_question && $save_ques){
                $ins = [$v['id'], $v['qa_item']['name'], $v['qa_item']['right_answers'], $v['qa_item']['sel_items']];
                $this->db->insertQuestion($ins);
            }
        }

        // 模拟人工做题时间
        writeSleep($this->write_sleep_min, $this->write_sleep_max);

        // 提交做题结果
        $end = curlRequest($this->url . $this->ans['apiversion'] . '/' . $this->ans['method'], $this->ans);

        // 增加得分
        $this->my_val += 10;

        return $end;
    }


    /**
     * 获取排行榜
     * @return bool|int
     */
    public function getTop(){
        if($this->my_val < $this->top_val){
            showStr('当前分数为' . $this->my_val . ',上次查询的最高分为' . $this->top_val);
            return false;
        }

        // 由于排行每五分钟更新一次，所以每五分钟返回一次查询
        if($this->last_rank_time + 305 < time()){
            $minute = date('m', time());
            $minute_tmp = '';
            if($minute > 9){
                $minute_tmp = substr($minute, 0, 1);
                $minute = substr($minute, 1);
            }
            $minute = $minute >= 5 ? 5 : 0;
            $minute = $minute_tmp . $minute;

            $this->last_rank_time = strtotime(date('Y-m-d H:' . $minute . ':00'));

        }else{

            showStr('当前分数为' . $this->my_val);

            if ($this->my_rank == 1 && $this->my_val > ($this->second_val + $this->exceed_score)){
                showStr('当前排名第一');
                return true;
            }

            return false;
        }

        $end = curlRequest($this->url . $this->top['apiversion'] . '/' . $this->top['method'], $this->top);

        // 未获取到排行，主要是token错误
        if($end['data']['status'] == 'fail'){
            $this->updateToken();
            showStr('修改token');
            return 0;
        }


        $data = $end['data']['list'];

        showStr('当前分数为' . $end['data']['my_value'] . '，排名为' . $end['data']['my_rank']);

        if (!is_array($data) || count($data) == 0){
            return false;
        }

        // 凌晨第一次跑时的错误兼容
        if(!isset($data[1])){
            $data[1] = [
                'integral' => $this->exceed_score,
                'dep_name' => '',
                'name'     => '没人'
            ];
        }

        $this->my_val     = (int)($end['data']['my_value']);
        $this->top_val    = (int)($data[0]['integral']);
        $this->my_rank    = (int)($end['data']['my_rank']);
        $this->second_val = (int)($data[1]['integral']);


        if ($end['data']['my_rank'] == 1){
            showStr('第二名为' . $data[1]['integral'] . '，是' . $data[1]['dep_name'] . '的' . $data[1]['name']);

        }else{
            showStr('第一名为' . $data[0]['integral'] . '，是' . $data[0]['dep_name'] . '的' . $data[0]['name']);
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

    /**
     * 设置开始时间
     */
    public function setStartTime(){
        $this->start_time = nowTime();
    }


    /**
     * 设置结束时间
     */
    public function setEndTime(){
        if($this->end_time != ''){
            return '';
        }

        date_default_timezone_set('Asia/Shanghai');
        $this->end_time = $this->bmob->getConf('end_time');

        if(empty($this->end_time) || strtotime($this->end_time) < time()){
            $this->end_time = strtotime(date('Y-m-d', time()) . ' +1 day');
        }else{
            $this->end_time = strtotime($this->end_time);
        }
    }


    /**
     * 随机返回一个题库
     * @param $qustion_list
     * @param $type 1 平均随机返回，其他 按权重随机返回
     */
    public function randRetQustion($type = 1){
        $qustion_list = $this->bmob->getQuestionList();

        if($type == 1){
            $ret_id = rand(0, count($qustion_list) - 1);
            return $qustion_list[$ret_id];
        }

        $rand_arr = [];
        foreach($qustion_list as $val){
            $rand_arr += array_fill(count($rand_arr), $val['weight'], $val['q_id']);
        }
        shuffle($rand_arr);

        return $rand_arr[rand(0, count($qustion_list) - 1)];
    }
}
