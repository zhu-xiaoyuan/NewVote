<?php
namespace Admin\Controller;
use Think\Controller;

define('ERROR_ACCOUNT', 0x4001);
define('ERROR_PASS', 0x4002);
define('ERROR_LOGOUT', 0x4003);
define('NULL_ACCOUNT', 0x4004);
define('NULL_PASS', 0x4005);
define('SAME_ACCOUNT', 0x4006);

class LoginController extends Controller {

	final public function _initialize() {
		if (IS_GET) {
			$_POST = $_GET;
		}
	}

	/**
	 * @param account 账号
	 *        password 密码
	 *
	 **/
	public function login() {
		if ($this->is_login()) {
			$this->response(null, '用户已登录', ERROR_LOGIN);
			return;
		}
		$user = M('user')->where("account = BINARY '" . $_POST['account'] . "'")->field('id,password')->find();
		if (empty($user)) {
			$this->response(null, '不存在的账号', ERROR_ACCOUNT);
			return;
		}
		if ($user['password'] == $_POST['password']) {
			session('name', $_POST['account']);
			session('id', $user['id']);
			$this->response(null, '登录成功');
			return;
		} else {
			$this->response(null, '密码不正确', ERROR_PASS);
			return;
		}
	}

	/**
	 * @param account 账号
	 *
	 **/
	public function logout() {
		if ($this->is_login()) {
			session(null);
			$this->response();
		} else {
			$this->response(null, '用户并未登录', ERROR_LOGOUT);
		}
	}

	/**
	 * @param account 账号
	 *
	 **/
	public function is_login() {
		if (session('?name')) {
			return true;
		} else {
			return false;
		}
	}

	public function response($info = '', $msg = '操作成功', $code = 0x0000) {
		$response['code'] = $code;
		$response['msg'] = $msg;
		$response['current_time'] = time();
		if (!empty($info)) {
			$response['info'] = $info;
		}
		echo json_encode($response);
	}
}
?>
