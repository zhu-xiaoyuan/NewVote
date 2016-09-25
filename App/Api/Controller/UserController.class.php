<?php
namespace Api\Controller;
use Think\Controller;

define("HAVE_NOT_TEST_CONNECTION", 0x1001); // 登录之前，未测试连通性
define("ERROR_ACCOUNT_OR_PASSWORD", 0x1002); // 登录时，错误的账号或密码
define("ERROR_TOKEN", 0x1003); // 登录时，错误的token
define("EXPIRES_TOKEN", 0x1004); // 登录时, 过期的token
define("ERROR_VOTE", 0x1005); // 未开始或不存在的投票
define("INEXIST_TOKEN", 0x1006); // 不存在的token
class UserController extends BaseController {
	/**
	 * @return  $info['vote_id']   参与投票的id
	 *          $info['token']     此次投票的token
	 *
	 * @author  L
	 **/
	public function test_connection() {
		$v = M('vote_info');

		$vote_id = $v->where('status=1')
		             ->field('id')
		             ->find();
		if (empty($vote_id)) {
			$this->response(null, '错误的投票', ERROR_VOTE);
			return;
		} else {
			$t = M('token');
			$where['vote_id'] = $vote_id['id'];
			$where['is_del'] = 0;
			$token = $t->where($where)
			           ->field('token')
			           ->find();
			$info['vote_id'] = $vote_id['id'];
			$info['token'] = $token['token'];
			$this->response($info);
			return;
		}
	}

	/**
	 * @param   $_POST['vote_id']   参与投票的id
	 *          $_POST['token']     此次投票的token
	 *          $_POST['account']   用户的账号
	 *          $_POST['password']  密码
	 *
	 * @return  $info['user_id']   用户id
	 *
	 * @author  L
	 **/
	public function login() {
		if (!isset($_POST['vote_id'])) {

			$this->response(null, '登录前未测试连通性', HAVE_NOT_TEST_CONNECTION);
			return;
		}

		if (!isset($_POST['token'])) {
			$this->response(null, '登录时,错误token', ERROR_TOKEN);
			return;
		}

		if (!isset($_POST['account'])) {
			$this->response(null, '登录时,用户名或密码错误', ERROR_ACCOUNT_OR_PASSWORD);
			return;
		}

		if (!isset($_POST['password'])) {
			$this->response(null, '登录时,用户名或密码错误', ERROR_ACCOUNT_OR_PASSWORD);
			return;
		}

		$t = M('token');
		$where['vote_id'] = $_POST['vote_id'];
		$where['is_del'] = 0;
		$token = $t->where($where)
		           ->field('token,expires')
		           ->find();
		if (empty($token)) {
			$this->response(null, '不存在的token', INEXIST_TOKEN);
			return;
		}
		if (time() >= $token['expires']) {
			$this->response(null, '过期的token', EXPIRES_TOKEN);
			return;
		}
		if ($token['token'] != $_POST['token']) {
			$this->response(null, '登录时,错误token', ERROR_TOKEN);
			return;
		}

		$m = M('jury_info');
		$where['vote_id'] = $_POST['vote_id'];
		$where['account'] = $_POST['account'];
		$where['password'] = $_POST['password'];
		$where['is_del'] = 0;
		$result = $m->where($where)->field('id')->find();
		if (empty($result)) {
			$this->response(null, '登录时,用户名或密码错误', ERROR_ACCOUNT_OR_PASSWORD);
			return;
		} else {
			$info['user_id'] = $result['id'];
			session("$user_id", 'login');
			$this->response($info);
			return;
		}
	}

	/**
	 * @param   $user_id 要退出的评委的id
	 *
	 * @return  'msg' 退出成功
	 *
	 * @author  L
	 **/
	public function logout($user_id) {
		session("$user_id", null);
		$this->response();
		return;
	}
}