<?php
namespace Admin\Controller;
use Think\Controller;

define('ERROR', 0x0001);

class BaseController extends Controller {
	private $_logger = null;

	/*
	初始化方法，防止重写
	 */
	final public function _initialize() {
		if (session('?name')) {
			$login = session('id');
			$this->assign('login', $login);
			if (session('name') == 'admin') {
				$this->assign('admin', true);
			}
		} else if (preg_match("/index.html$/", $_SERVER['REQUEST_URI']) ||
			preg_match("/index.php$/", $_SERVER['REQUEST_URI']) ||
			preg_match("/Admin$/", $_SERVER['REQUEST_URI']) ||
			preg_match("/Index$/", $_SERVER['REQUEST_URI']) ||
			preg_match("/Manage$/", $_SERVER['REQUEST_URI']) ||
			preg_match("/Callout$/", $_SERVER['REQUEST_URI']) ||
			preg_match("/help.html$/", $_SERVER['REQUEST_URI'])) {

		} else {
			exit();
		}
		if (IS_GET) {
			$_POST = $_GET;
		}
		if (isset($_ENV["HOSTNAME"])) {
			$MachineName = $_ENV["HOSTNAME"];
		} else if (isset($_ENV["COMPUTERNAME"])) {
			$MachineName = $_ENV["COMPUTERNAME"];
		} else {
			$MachineName = "";
		}
		$ip = gethostbyname($MachineName);
		$this->assign('ip_address', $ip);
	}

	/*
	数据统一返回方法
	 */
	public function response($info = '', $msg = '操作成功', $code = 0x0000) {
		$response['code'] = $code;
		$response['msg'] = $msg;
		$response['current_time'] = time();
		if (!empty($info)) {
			$response['info'] = $info;
		}
		echo json_encode($response);
	}

	/*
	错误日志
	 */
	public final function __error($msg) {
		if (false === C('ME_LOG')) {
			return;
		}

		$this->_logger->log_error($msg);
	}
	/*
	信息日志
	 */
	public final function __info($msg) {
		if (false === C('ME_LOG')) {
			return;
		}

		$this->_logger->log_info($msg);
	}

	/*
	警告日志
	 */
	public final function __warning($msg) {
		if (false === C('ME_LOG')) {
			return;
		}

		$this->_logger->log_warning($msg);
	}

	/*
	防克隆
	 */
	final public function __clone() {

		return false;
	}

	/*
	析构方法
	 */
	final public function __destruct() {

		return false;
	}

}