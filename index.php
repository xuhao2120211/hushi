<?php
    
    /**
     * Created by PhpStorm.
     * User: xuhao
     * Date: 2019/4/24 0024
     * Time: 19:05
     */
    class getZhushouData{
        public $url = 'http://www.hulizhushou.com/api/';
        public $headers = [
            "Content-type: application/json;charset='utf-8'",
            "Accept: application/json",
            "Cache-Control: no-cache"
        ];
    
    
        public $arg = [
            "project_id"    => "107407",//97831
            "paper_id"      => "",
            "systemVersion" => "9",
            "deviceModel"   => "ALP-AL00",
            "codeVersion"   => "4.0.3",
            "method"        => "hlzs/paper/fetch",
            "api_key"       => "f34b59ac9857e9bbf6a7d58a5e35996b",
            "call_id"       => "7b3758ef043dfbf86b77c13f0dedd268",
            "apiversion"    => "1.0",
            "sig"           => "d047e54d6fd0a77220ef61cd4cdf3468",
            "access_token"  => "4bbb227d1b7afee52eff1316b1212abc"
        ];

        
        public $ans = [
            "user_paper_id" => "",
            "answer"        => [],
            "systemVersion" => "9",
            "deviceModel"   => "ALP-AL00",
            "codeVersion"   => "4.0.3",
            "method"        => "hlzs/paper/commit",
            "api_key"       => "f34b59ac9857e9bbf6a7d58a5e35996b",
            "call_id"       => "ec5b01a64eaab45953774bdecbddbac2",
            "apiversion"    => "1.0",
            "sig"           => "ff63400fed365bcfb640fec057578ae5",
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
            "call_id"       => "e9fadb4e8bff6292ce93011e5147ee35",
            "apiversion"    => "2.0",
            "sig"           => "d7ec253ca0130edf57ae85b41dfb702a",
            "access_token"  => "4bbb227d1b7afee52eff1316b1212abc"
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
        
        public function run(){
            
            while (true){
                sleep(rand(10, 20));

                try{
    
                    $end = $this->getTop();
                    
                    if ($end === 0){
                        continue;
                        
                    } elseif ($end){
                        
                        sleep(300);
                        echo date('Y-m-d H:i:s', time()) . " 暂停300秒\r\n";
                        continue;
                    }

                    $this->runOne();

                }catch (Exception $e) {

                    $this->updateToken();

                    echo date('Y-m-d H:i:s', time()) . " 修改token\r\n";
                    continue;

                }
                
            }
        }
        
    
        public function runOne(){

            $end = $this->postCurl($this->url . $this->ans['apiversion'] . '/' . $this->arg['method'], json_encode($this->arg));

            $this->ans['user_paper_id'] = $end['data']['user_paper_id'];
            
//            $this->creatDB();
    
            $mis = $this->getMistake();
            
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
                
//                $ins = [$v['id'], $v['qa_item']['name'], $v['qa_item']['right_answers'], $v['qa_item']['sel_items']];
//
//                $this->insertQuestion($ins);
            }
            
//            $this->db->close();
        
            sleep(rand(10, 20));
            
//            echo json_encode($this->ans);
//            die;
            
            $end = $this->postCurl($this->url . $this->ans['apiversion'] . '/' . $this->ans['method'], json_encode($this->ans));
    
            $this->my_val += 10;
            
            return $end;
        }
        
        
        public function postCurl($url,$requestString,$timeout = 5){
            if($url == '' || $requestString == '' || $timeout <= 0){
                return false;
            }

            $con = curl_init((string)$url);
            curl_setopt($con, CURLOPT_HEADER, false);
            curl_setopt($con, CURLOPT_POSTFIELDS, $requestString);
            curl_setopt($con, CURLOPT_POST,true);
            curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($con, CURLOPT_HTTPHEADER, $this->headers);
            curl_setopt($con, CURLOPT_TIMEOUT,(int)$timeout);

            $ret = curl_exec($con);
            
            return json_decode($ret, true);
        }
        
        public function creatDB(){
            $servername = "cd-cdb-q5l7aqy0.sql.tencentcdb.com:63116";
            $username = "root";
            $password = "XUhao2120211";
            $dbname = "hushi";

            // 创建连接
            $conn = new mysqli($servername, $username, $password, $dbname);
            // 检测连接
            if ($conn->connect_error) {
                die("连接失败: " . $conn->connect_error);
            }
    
            $this->db = $conn;

            
            return;
        }
        
        public function insertQuestion($ins){
            $all_sel = array_pop($ins);
			$ans_text = [];
			
			foreach(explode(',', $ins[2]) as $v){
				$sel_key = $this->getSel($v);
				$ans_text[] = $all_sel[$sel_key];
			}
			$ins[] = implode(',', $ans_text);
            
            $sql = "INSERT INTO question (q_id, q_text, q_sel, q_sel_text) VALUES ('" . implode("','", $ins) . "')";
    
            
            
            if ($this->db->query($sql) === TRUE) {
                echo date('Y-m-d H:i:s', time()) . ' ' . $ins[0] . " 新记录插入成功\r\n";
            } else {
                echo "Error: " . date('Y-m-d H:i:s', time()) . ' ' . $sql . "<br>" . $this->db->error . "\r\n";
            }
        }
        
        public function getMistake(){
            $ret = [];
            $ans_arr = array_rand(range(1,10), 10);
            shuffle($ans_arr);
            $num = rand(0, 3);
            if (!$num){
                return $ret;
            }
            
            for($i = 1; $i <= $num; $i++){
                $ret[] = $ans_arr[$i];
            }
            
            return $ret;
        }
        
		
		public function getSel($sel){
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

		public function getTop(){
            
            if($this->my_val < $this->top_val){
                return false;
            }

            $end = $this->postCurl($this->url . $this->top['apiversion'] . '/' . $this->top['method'], json_encode($this->top));
            
            if($end['data']['status'] == 'fail'){
                $this->updateToken();
                echo date('Y-m-d H:i:s', time()) . " 修改token\r\n";
                return 0;
            }
            
            $data = $end['data']['list'];
            
            echo date('Y-m-d H:i:s', time()) . ' 当前分数为' . $end['data']['my_value'] . '，排名为' . $end['data']['my_rank'] . "\r\n";
            
            if (!is_array($data) || count($data) == 0){
                return false;
            }
            
            if ($end['data']['my_rank'] == 1){
                echo date('Y-m-d H:i:s', time()) . ' 第二名为' . $data[1]['integral'] . '，是' . $data[1]['dep_name'] . '的' . $data[1]['name'] . "\r\n";
            
            }else{
                echo date('Y-m-d H:i:s', time()) . ' 第一名为' . $data[0]['integral'] . '，是' . $data[0]['dep_name'] . '的' . $data[0]['name'] . "\r\n";
    
            }
            
            $this->my_val  = (int)($end['data']['my_value']);
            $this->top_val = (int)($data[0]['integral']);

            if(!is_array($data) || count($data) == 0){
                return true;
            }
            
            if ($end['data']['my_rank'] == 1 && $this->my_val > ($data[1]['integral'] + 100)){
                return true;
            }
            
            return false;
        }

        public function updateToken(){

            $this->tag = $this->tag == 'andr' ? 'ios' : 'andr';
            $tag = $this->tag;

            foreach ($this->are as $v){
                $this->$v = array_merge($this->$v, $this->$tag[$v]);
            }
        }
    }
    
//    $ob = new getZhushouData();
//    $ob->run();
//    $ob->getTop();

phpinfo();