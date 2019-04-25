<?php
    
    /**
     * Created by PhpStorm.
     * User: xuhao
     * Date: 2019/4/24 0024
     * Time: 19:05
     */
    class getZhushouData{
        public $url = 'http://www.hulizhushou.com/api/1.0/';
        public $headers = [
            "Content-type: application/json;charset='utf-8'",
            "Accept: application/json",
            "Cache-Control: no-cache"
        ];
    
    
        public $arg = [
            "project_id"    => "97831",
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
            "user_paper_id" => "97831-111637-2",
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
        
        public $db;
        
        public $rand_str = ['A', 'B', 'C', 'D', 'E'];
        
        public function run(){
            
            while (true){
                $this->runOne();
                sleep(rand(5, 10));
            }
        }
        
    
        public function runOne(){
        
            $end = $this->postCurl($this->url . $this->arg['method'], json_encode($this->arg));
    
            $this->ans['user_paper_id'] = $end['data']['user_paper_id'];
            
            $this->creatDB();
    
            $mis = $this->getMistake();
            
            foreach ($end['data']['user_paper_items'] as $k => $v){
                $right_answers = $v['qa_item']['right_answers'];
                if(in_array($k, $mis)){
                    unset($mis[$k]);
                    $right_answers = $this->rand_str[rand(0, 4)];
                }
                
                $this->ans['answer'][$v['id']] = [
                    'sel'      => [$right_answers],
                    'duration' => 0
                ];
                
                $ins = [$v['id'], $v['qa_item']['name'], $v['qa_item']['right_answers'], $v['qa_item']['explain'], $v['qa_item']['sel_items']];
                
                $this->insertQuestion($ins);
            }
            
            $this->db->close();
        
            sleep(rand(20, 30));
            
//            echo json_encode($this->ans);
//            die;
            
            $end = $this->postCurl($this->url . $this->ans['method'], json_encode($this->ans));
        
            return $end;
        }
        
        
        public function postCurl($url,$requestString,$timeout = 5){
            if($url == '' || $requestString == '' || $timeout <=0){
                return false;
            }
            $con = curl_init((string)$url);
            curl_setopt($con, CURLOPT_HEADER, false);
            curl_setopt($con, CURLOPT_POSTFIELDS, $requestString);
            curl_setopt($con, CURLOPT_POST,true);
            curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($con, CURLOPT_HTTPHEADER, $this->headers);
            curl_setopt($con, CURLOPT_TIMEOUT,(int)$timeout);
            
            return json_decode(curl_exec($con), true);
        }
        
        public function creatDB(){
            $servername = "127.0.0.1";
            $username = "root";
            $password = "root";
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
            
            $sql = "INSERT INTO question (q_id, q_text, q_sel, q_explain, q_sel_text) VALUES ('" . implode("','", $ins) . "')";
    
            
            
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
    }
    
    $ob = new getZhushouData();
    $ob->run();
