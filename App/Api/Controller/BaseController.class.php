<?php
namespace Api\Controller;
use Think\Controller;

class BaseController extends Controller {
	public function _initialize() {
		//根据http_method将GET赋值给POST，对参数的操作统一用$_POST的方式
		if (IS_GET) {
			$_POST = $_GET;
		}
	}

	/*
	数据统一返回方法
	 */
	public function response($info = '', $msg = 'success', $code = 0x0000) {
		$response['code'] = $code;
		$response['msg'] = $msg;
		$response['current_time'] = time();
		if (!empty($info)) {
			$response['info'] = $info;
		}
		echo json_encode($response);
	}

}