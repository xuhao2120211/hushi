<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/6/19
 * Time: 下午6:25
 */
include_once 'tools.php';

class db{

    public $db;

    /**
     * 构造数据库对象
     * @return mysqli
     */
    public function creatDB(){
        if($this->db){
            return $this->db;
        }

        $servername = "127.0.0.1";
        $username   = "xuhao";
        $password   = "Xh2120211";
        $dbname     = "hushi";

        // 创建连接
        $this->db = new \mysqli($servername, $username, $password, $dbname);
        // 检测连接
        if ($this->db->connect_error) {
            die("连接失败: " . $this->db->connect_error);
        }

        // 设置编码
        $this->db->query("SET NAMES utf8");


        return $this->db;
    }


    /**
     * 插入试题信息
     * @param $ins
     */
    public function insertQuestion($ins){
        $all_sel = array_pop($ins);
        $ans_text = [];

        foreach(explode(',', $ins[2]) as $v){
            $sel_key = getSel($v);
            $ans_text[] = $all_sel[$sel_key];
        }
        $ins[] = implode(',', $ans_text);

        $sql = "INSERT INTO hushi (q_id, q_text, q_sel, q_sel_text) VALUES ('" . implode("','", $ins) . "')";



        if ($this->db->query($sql) === TRUE) {
            echo date('Y-m-d H:i:s', time()) . ' ' . $ins[0] . " 新记录插入成功\r\n";
        } else {
            echo "Error: " . date('Y-m-d H:i:s', time()) . ' ' . $sql . "<br>" . $this->db->error . "\r\n";
        }
    }

    /**
     * 关闭数据库连接
     */
    public function closeDB(){
        $this->db->close();
    }

    /**
     * 获得配置信息
     * @param $key_name
     */
    public function getConf($key_name){
        $sql    = "select value from conf where key_name='" . $key_name . "'";
        $db_ret = $this->db->query($sql);
        $ret    = $db_ret->fetch_assoc();

        return $ret['value'];
    }

    /**
     * 修改运行状态配置
     * @param $status
     */
    public function updateOpenRun($status){
        $sql = "update conf set ='" . $status . "' where key_name='end_time'";
        $this->db->query($sql);
    }

    /**
     * 获得表中数据条数
     * @param $key_name
     */
    public function getCount($table_name){
        $c_sql = "create table if not exists `" . $table_name . "` like `hushi`;";
        $this->db->query($c_sql);

        $sql    = 'select count(id) num from ' . $table_name;
        $db_ret = $this->db->query($sql);
        $ret    = $db_ret->fetch_assoc();

        return $ret['num'];
    }

}