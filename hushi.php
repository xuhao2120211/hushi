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

    // 开始时间
    public $start_time = '';

    // 结束时间
    public $end_time = '';

    public $start_run_time = '';
    
    // 错题数，最小值
    public $mis_min = 0;
    
    // 错题数，最大值
    public $mis_max = 1;

    // 最后一次请求排行的时间
    public $last_rank_time = 0;

    public $arg = [
        "project_id"=> "",
        "paper_id"=> "",
        "sysVersion"=> "12.2",
        "deviceModel"=> "iPhone",
        "codeVersion"=> "5.0.5",
        "method"=> "hlzs/paper/fetch",
        "api_key"=> "f34b59ac9857e9bbf6a7d58a5e35996b",
        "call_id"=> "d1f513c76491a7a2fadf5a5a147ce9bd",
        "apiversion"=> "1.0",
        "sig"=> "3bf712b908577bbd48ae41c01d6a6082",
        "access_token"=> "7805863bd2409e45325fb258322a9bf6"
    ];


    public $ans = [
        "user_paper_id" => "",
        "answer"        => [],
        "sysVersion"=> "12.2",
        "deviceModel"=> "iPhone",
        "codeVersion"=> "5.0.5",
        "method"=> "hlzs/paper/commit",
        "api_key"=> "f34b59ac9857e9bbf6a7d58a5e35996b",
        "call_id"=> "1dc1b98d79fd7734f5dd947b68a3215e",
        "apiversion"=> "1.0",
        "sig"=> "e600cd4ebff30f8ec8e26594015254d2",
        "access_token"=> "7805863bd2409e45325fb258322a9bf6"
    ];


    public $top = [
        "top"=> 32,
        "range"=> "daily",
        "order_type"=> "integral",
        "sysVersion"=> "12.2",
        "deviceModel"=> "iPhone",
        "codeVersion"=> "5.0.5",
        "method"=> "hlzs/integral/rank",
        "api_key"=> "f34b59ac9857e9bbf6a7d58a5e35996b",
        "call_id"=> "6ae1c39239ed58b68dd78b76b89b058f",
        "apiversion"=> "2.0",
        "sig"=> "7697b1274e115eab6eca62171b8ac7ad",
        "access_token"=> "7805863bd2409e45325fb258322a9bf6"
    ];

    public $ios = [
        'arg' => [
            "systemVersion" => "12.2",
            "deviceModel" => "iPhone",
            "codeVersion"=> "5.0.5",
            "call_id"=> "d1f513c76491a7a2fadf5a5a147ce9bd",
            "sig"=> "3bf712b908577bbd48ae41c01d6a6082",
            "access_token"=> "7805863bd2409e45325fb258322a9bf6"
        ],
        'ans' => [
            "systemVersion" => "12.2",
            "deviceModel" => "iPhone",
            "codeVersion"=> "5.0.5",
            "call_id"=> "1dc1b98d79fd7734f5dd947b68a3215e",
            "sig"=> "e600cd4ebff30f8ec8e26594015254d2",
            "access_token"=> "7805863bd2409e45325fb258322a9bf6"
        ],
        'top' => [
            "systemVersion" => "12.2",
            "deviceModel" => "iPhone",
            "codeVersion"=> "5.0.5",
            "call_id"=> "6ae1c39239ed58b68dd78b76b89b058f",
            "sig"=> "7697b1274e115eab6eca62171b8ac7ad",
            "access_token"=> "7805863bd2409e45325fb258322a9bf6"
        ]
    ];

    public $andr = [
    ];

    /**
     * 主体
     */
    public function run(){
        date_default_timezone_set('Asia/Shanghai');
        $this->bmob = new bmob();

        $this->start_run_time = date('Y-m-d');
        while (true){
            if($this->start_run_time != date('Y-m-d')){
                die;
            }

            // 获取所有配置
            $get_conf_end = $this->bmob->getConf($this);

            // 根据开始时间开启任务
            $this->ifStartTime();
            
            // 判断是否开始跑，不跑则睡眠一分钟
            if(!$get_conf_end || !$this->open_run){
                showStr('未开启，暂停60秒');
                sleep(60);
                continue;
            }

            // 设置结束时间
            $this->setEndTime();

            // 结束
            if(time() > strtotime($this->end_time)){
                $this->bmob->updateConf('open_run', '0');
                $this->bmob->updateConf('end_time', '');
                $this->end_time   = '';
                showStr('结束跑题');
            }

            // 做题间隙睡眠
            writeSleep($this->write_sleep_min, $this->write_sleep_max);

            try{
                // 获得排行
                $end = $this->getTop();

                // 根据排行确认是否要继续
                if ($end === 0){
                    continue;

                } elseif ($end){

                    showStr('暂停' . $this->sleep_time . '秒');
                    sleep($this->sleep_time);
                    continue;
                }

                $this->runOne();

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
    public function runOne($retry = 0){
        $add_cons = 0;
        $now_question = $this->randRetQustion();
        if(!$now_question){
            showStr('获取题目列表失败' . $retry++);
            if($retry <= 3){
                return $this->runOne($retry);
            }
            return false;
        }


        // 当前要跑的题id
        $this->arg['project_id'] = $now_question['q_id'];

        $end = curlRequest($this->url . $this->ans['apiversion'] . '/' . $this->arg['method'], $this->arg);

        // 返回报错
        if(!is_array($end['data'])){
            showStr('报错为' . var_export($end, 1));
            return;
        }elseif (!isset($end['data']['user_paper_items']) || !is_array($end['data']['user_paper_items'])){
            showStr('结构错误' . json_encode($end));
            return;
        }

        $this->ans['user_paper_id'] = $end['data']['user_paper_id'];

        // 模拟人工错题当前分数为
        $mis = getMistake($this->mis_min, $this->mis_max);

//        $this->db = new db();
//        $this->db->creatDB();

        // 存题的库名
        $table_name = 'question_' . $now_question['q_id'];
//        // 已存的题数
//        $now_count  = $this->db->getCount($table_name);
//        // 是否已存满题，0继续存，1不再存
//        $save_ques  = $now_count >= $now_question['num'] ? 0 : 1;

        foreach ($end['data']['user_paper_items'] as $k => $v){
            $right_answers = $v['qa_item']['right_answers'];

            if(in_array($k, $mis)){
                unset($mis[$k]);
                $right_answers = $this->rand_str[rand(0, count($v['qa_item']['sel_items']) - 1)];
            }else{
                $add_cons += 1;
            }

            $right_answers = explode(',', $right_answers);

            $this->ans['answer'][$v['id']] = [
                'sel'      => $right_answers,
                'duration' => 0
            ];

            // 存题
//            if($this->save && $save_ques){
//                $ins = [$v['id'], $v['qa_item']['name'], $v['qa_item']['right_answers'], $v['qa_item']['sel_items']];
//                $this->db->insertQuestion($ins, $table_name);
//            }
        }

//        $this->db->closeDB();

        // 模拟人工做题时间
        writeSleep($this->write_sleep_min, $this->write_sleep_max);

        // 提交做题结果
        $end = curlRequest($this->url . $this->ans['apiversion'] . '/' . $this->ans['method'], $this->ans);
        $this->ans['answer'] = array();
        // 增加得分
        if(isset($end['success']) && $end['success']){
            $this->my_val += $add_cons;
        }

        return $end;
    }


    /**
     * 获取排行榜
     * @return bool|int
     */
    public function getTop(){
        if($this->last_rank_time + 305 < time()){
            showStr('当前分数为' . $this->my_val . ',上次查询的最高分为' . $this->top_val);
            return false;
        }

        // 由于排行每五分钟更新一次，所以每五分钟返回一次查询
        if($this->last_rank_time + 305 >= time()){

            showStr('当前分数为' . $this->my_val);

            if ($this->my_rank == 1 && $this->my_val > ($this->second_val + $this->exceed_score)){
                showStr('当前排名第一');
                return true;
            }

            return false;
        }

        $minute = date('m', time());
        $minute_tmp = '';
        if($minute > 9){
            $minute_tmp = substr($minute, 0, 1);
            $minute = substr($minute, 1);
        }
        $minute = $minute >= 5 ? 5 : 0;
        $minute = $minute_tmp . $minute;

        $this->last_rank_time = strtotime(date('Y-m-d H:' . $minute . ':00'));

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
            showStr('追赶分数为' . $this->exceed_score);
            return true;
        }

        // 更新排行榜
        $this->bmob->setRank($data);

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
     * 根据开始时间确定是否要开始任务
     */
    public function ifStartTime(){
        if(!property_exists($this, 'start_time') || !$this->start_time || time() < strtotime($this->start_time)){
            return;
        }

        // 未开始任务，则将任务开启
        if(property_exists($this, 'open_run') && !$this->open_run == '1'){
            $this->bmob->updateConf('open_run', '1');
            $this->open_run = '1';
        }

        // 置空开始时间
        $this->bmob->updateConf('start_time', '');
    }


    /**
     * 设置结束时间
     */
    public function setEndTime(){
        if($this->end_time != ''){
            return;
        }

        date_default_timezone_set('Asia/Shanghai');

        if(empty($this->end_time) || strtotime($this->end_time) < time()){
            $this->end_time = date('Y-m-d 00:00:00', strtotime('+1 day'));
            $this->bmob->updateConf('end_time', $this->end_time);
        }
    }


    /**
     * 随机返回一个题库
     * @param $qustion_list
     * @param $type 1 平均随机返回，其他 按权重随机返回
     */
    public function randRetQustion($type = 1){
        $qustion_list = $this->bmob->getQuestionList();
        if(!is_array($qustion_list)){
            return false;
        }

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
