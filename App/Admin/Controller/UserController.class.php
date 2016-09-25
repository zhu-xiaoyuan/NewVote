<?php
namespace Admin\Controller;
use Think\Controller;

define('ERROR_ACCOUNT', 0x4001);
define('ERROR_PASS', 0x4002);
define('ERROR_LOGOUT', 0x4003);
define('NULL_ACCOUNT', 0x4004);
define('NULL_PASS', 0x4005);
define('SAME_ACCOUNT', 0x4006);

class UserController extends BaseController {
	public function index() {
		$user = M('user');
		$user_list = $user->field('id,account')->select();
		$this->assign('user_list', $user_list);
		$this->display();
	}

	/**
	 *  添加用户
	 * @param 
	 *       account        用户名
	 *       password        密码
	 **/
	public function add_user() {
		if (session('name') != 'admin') {
			A('index')->index();
		}
		$this->display();
	}
	public function insert_user() {
		if (!isset($_POST['account']) || empty($_POST['account'])) {
			$this->response(null, '用户名不能为空', NULL_ACCOUNT);
			return;
		}
		echo $_POST['account'];
		echo mb_strlen($_POST['account'], 'UTF-8');
		if (mb_strlen($_POST['account'], 'UTF-8') < 5 || mb_strlen($_POST['account'], 'UTF-8') > 12) {
			$this->response(null, '用户名为5-12位字符', LENGTH_ACCOUNT);
			return;
		}
		if (!isset($_POST['password']) || empty($_POST['password'])) {
			$this->response(null, '密码不能为空', NULL_PASS);
			return;
		}
		$rlt = M('user')->where("account = BINARY '" . $_POST['account'] . "'")->find();
		if (!empty($rlt)) {
			$this->response(null, '用户名已存在', SAME_ACCOUNT);
			return;
		}
		$rlt = M('user')->data($_POST)->add();
		if ($rlt) {
			$this->response(null, '创建成功');
			return;
		}
		$this->response(null, '创建失败', ERROR);
	}

	/**
	 *  判断用户是否存在
	 * @param 
	 *       account        用户名
	 *       password        密码
	 **/
	public function same_account() {
		$rlt = M('user')->where("account = BINARY '" . $_POST['account'] . "'")->find();
		if (!empty($rlt)) {
			echo '0';
			return;
		}
	}

	/**
	 *  删除用户
	 * @param 
	 *       id        用户id
	 **/
	public function del_user() {
		$rlt = M('user')->where(array('id' => array('in', $_POST['data'])))->delete();
		if ($rlt === false) {
			$this->response(null, '删除失败', ERROR);
		} else {
			$this->response(null, '删除成功');
		}
	}
}
?>
