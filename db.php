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
    public function insertQuestion($ins, $table_name){
        $all_sel = array_pop($ins);
        $ans_text = [];

        foreach(explode(',', $ins[2]) as $v){
            $sel_key = getSel($v);
            $ans_text[] = $all_sel[$sel_key];
        }
        $ins[] = implode(',', $ans_text);

        $sql = "INSERT INTO " . $table_name . " (q_id, q_text, q_sel, q_sel_text) VALUES ('" . implode("','", $ins) . "')";

        if ($this->db->query($sql) === TRUE) {
            showStr($ins[0] . " 新记录插入成功");
        } else {
            showStr('Error: ' . $sql . '   ' . $this->db->error);
        }
    }

    /**
     * 关闭数据库连接
     */
    public function closeDB(){
        $this->db->close();
        $this->db = null;
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
        $c_sql = "CREATE TABLE IF NOT EXISTS `" . $table_name . "` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `q_id` int(11) NOT NULL,
                  `q_text` text,
                  `q_sel` varchar(255) DEFAULT NULL,
                  `q_sel_text` varchar(255) DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `in_q_id` (`q_id`)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $this->db->query($c_sql);

        $sql    = 'select count(id) num from ' . $table_name;
        $db_ret = $this->db->query($sql);
        $ret    = $db_ret->fetch_assoc();

        return $ret['num'];
    }

}