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

    /* 获得列表
{
	"type": "all",
	"systemVersion": "9",
	"deviceModel": "ALP-AL00",
	"codeVersion": "4.0.3",
	"method": "hlzs/practice/list",
	"api_key": "f34b59ac9857e9bbf6a7d58a5e35996b",
	"call_id": "06b83fd199aad2eb6f82a8ea6b3f78a5",
	"apiversion": "2.0",
	"sig": "ee3a21045b1243c23afb7d6f8b46335f",
	"access_token": "4bbb227d1b7afee52eff1316b1212abc"
}


    {
	"source": "rails",
	"apiVersion": "2.0",
	"method": "hlzs/practice/list",
	"context": null,
	"params": {
		"type": "all",
		"systemVersion": "9",
		"deviceModel": "ALP-AL00",
		"codeVersion": "4.0.3",
		"method": "hlzs/practice/list",
		"api_key": "f34b59ac9857e9bbf6a7d58a5e35996b",
		"call_id": "06b83fd199aad2eb6f82a8ea6b3f78a5",
		"apiversion": "2.0",
		"sig": "ee3a21045b1243c23afb7d6f8b46335f",
		"access_token": "4bbb227d1b7afee52eff1316b1212abc",
		"api": {
			"type": "all",
			"systemVersion": "9",
			"deviceModel": "ALP-AL00",
			"codeVersion": "4.0.3",
			"method": "hlzs/practice/list",
			"api_key": "f34b59ac9857e9bbf6a7d58a5e35996b",
			"call_id": "06b83fd199aad2eb6f82a8ea6b3f78a5",
			"apiversion": "2.0",
			"sig": "ee3a21045b1243c23afb7d6f8b46335f",
			"access_token": "4bbb227d1b7afee52eff1316b1212abc"
		}
	},
	"duration": 87.12543600000001,
	"id": 0,
	"data": {
		"status": "success",
		"list": [{
			"id": 118525,
			"title": "\u6218\u4f24\u6551\u62a4200\u9898\u9898\u5e93",
			"question_numb": 10,
			"start_time": "2019-07-01 08:00:00",
			"start_month": 7,
			"end_time": "2019-08-01 07:59:59",
			"detail_url": "http://www.hulizhushou.com/client/qa_pro/118525?access_token=38uw4d7ptyymhqqdc",
			"location": "",
			"hospital": {
				"id": 83,
				"name": "\u89e3\u653e\u519b\u603b\u533b\u9662\u7b2c\u516d\u533b\u5b66\u4e2d\u5fc3",
				"type": "inner"
			},
			"integral": 10,
			"paper_id": 136595,
			"total_question_numb": 200,
			"duration": 0
		}, {
			"id": 125211,
			"title": "\u793c\u4eea\u89c4\u8303\u670d\u52a1\u57fa\u5730\u57f9\u8bad\u8bd5\u9898",
			"question_numb": 10,
			"start_time": "2019-07-01 08:00:00",
			"start_month": 7,
			"end_time": "2019-12-28 07:59:59",
			"detail_url": "http://www.hulizhushou.com/client/qa_pro/125211?access_token=38uw4d7ptyymhqqdc",
			"location": "",
			"hospital": {
				"id": 83,
				"name": "\u89e3\u653e\u519b\u603b\u533b\u9662\u7b2c\u516d\u533b\u5b66\u4e2d\u5fc3",
				"type": "inner"
			},
			"integral": 10,
			"paper_id": 144007,
			"total_question_numb": 309,
			"duration": 0
		}, {
			"id": 125212,
			"title": "\u9759\u8109\u7a7f\u523a\u57fa\u5730\u57f9\u8bad\u8bd5\u9898",
			"question_numb": 10,
			"start_time": "2019-07-01 08:00:00",
			"start_month": 7,
			"end_time": "2019-12-28 07:59:59",
			"detail_url": "http://www.hulizhushou.com/client/qa_pro/125212?access_token=38uw4d7ptyymhqqdc",
			"location": "",
			"hospital": {
				"id": 83,
				"name": "\u89e3\u653e\u519b\u603b\u533b\u9662\u7b2c\u516d\u533b\u5b66\u4e2d\u5fc3",
				"type": "inner"
			},
			"integral": 10,
			"paper_id": 144008,
			"total_question_numb": 120,
			"duration": 0
		}, {
			"id": 113581,
			"title": "\u516d\u6708\u4efd\u6218\u6551\u7ec3\u4e601000\u9898",
			"question_numb": 10,
			"start_time": "2019-06-01 08:00:00",
			"start_month": 6,
			"end_time": "2019-07-01 07:59:59",
			"detail_url": "http://www.hulizhushou.com/client/qa_pro/113581?access_token=38uw4d7ptyymhqqdc",
			"location": "",
			"hospital": {
				"id": 83,
				"name": "\u89e3\u653e\u519b\u603b\u533b\u9662\u7b2c\u516d\u533b\u5b66\u4e2d\u5fc3",
				"type": "inner"
			},
			"integral": 10,
			"paper_id": 131005,
			"total_question_numb": 1000,
			"duration": 0
		}]
	},
	"success": true,
	"time": "2019-07-01 16:13:22",
	"auth": true,
	"main_version": 2.0,
	"sub_version": ""
}
    */
}