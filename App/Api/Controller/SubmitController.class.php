<?php
namespace Api\Controller;
use Think\Controller;

define("ERROR_SUBMIT", 0x2001); // 错误提交信息
define("ERROR_TOKEN", 0x2002); // 提交时，错误的token
define("EXPIRES_TOKEN", 0x2003); // 提交时, 过期的token
define("INEXIST_TOKEN", 0x2004); // 不存在的token

class SubmitController extends BaseController {

	public function index() {
		$this->display();
	}
	/**
	 * @param   $_POST['token']       此次投票的token
	 *          $_POST['vote_id']     投票id
	 *          $_POST['vote_type']   投票类型
	 *          $_POST['result']      投票结果
	 *          $_POST['user_id']     评委id
	 *
	 * @return  'msg' 成功或失败
	 *
	 * @author  L
	 **/
	public function submit() {
		/*验证输入*/
		if (!isset($_POST['token'])) {
			$this->response(null, '提交时, 错误token', ERROR_TOKEN);
			return;
		}
		/*验证token*/
		$t = M('token');
		$vote_id = (int) $_POST['vote_id'];
		$where['vote_id'] = $vote_id;
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

			$this->response(null, '提交时, 错误token', ERROR_TOKEN);
			return;
		}

		$rlt = M('vote_info')->where(array('id' => $vote_id))->field('jury_result_num,vote_result_num')->find();

		$jury_id = (int) $_POST['user_id'];
		$result = $_POST['result'];
		$type = (int) $_POST['vote_type'];
		$jury_revote_num = (int) $rlt['jury_result_num'];
		$vote_revote_num = (int) $rlt['vote_result_num'];

		/*检查jury是否已经提交过*/
		$jury_result = M('jury_result');
		$can = $jury_result
			->where(array('jury_id' => $jury_id, 'jury_revote_num' => $jury_revote_num, 'revote_num' => $vote_revote_num))
			->count();
		if ($can) {
			$this->response(null, '您已提交过,请勿重新提交', ERROR_TOKEN);
			return;
		}

		$data = json_decode($result, TRUE);
		if (empty($data)) {
			$this->response(null, '提交错误', ERROR_SUBMIT);
			return;
		}

		/*生成过程结果*/

		$jury_result->startTrans();
		$insert['vote_id'] = $vote_id;
		$insert['jury_id'] = $jury_id;
		$insert['revote_num'] = $vote_revote_num;
		$insert['jury_revote_num'] = $jury_revote_num;
		$insert['create_time'] = time();

		if ($type == 2) {
			$insert['grade'] = 1;
			foreach ($data as $value) {
				$insert['attend_id'] = (int) $value['id'];
				$rlt = $jury_result->add($insert);
				if (false === $rlt) {
					$jury_result->rollback();
					$this->response(null, '提交错误', ERROR_SUBMIT);
					return;
				}
			}
		} else if ($type == 3) {

			$num = count($data);
			for ($i = 0; $i < $num; $i++) {
				$insert['grade'] = $num - $i;
				$insert['attend_id'] = (int) $data[$i]['id'];
				$rlt = $jury_result->add($insert);
				if (false === $rlt) {
					$this->response(null, '提交错误', ERROR_SUBMIT);
					return;
				}
			}
		} else if ($type == 1) {

			foreach ($data as $value) {
				$insert['grade'] = (int) $value['grade'];
				$insert['attend_id'] = (int) $value['id'];
				$rlt = $jury_result->add($insert);
				if (false === $rlt) {
					$this->response(null, '提交错误', ERROR_SUBMIT);
					return;
				}
			}
		} else if ($type == 4) {

			foreach ($data as $value) {
				$grade = (int) $value['grade'];
				switch ($grade) {
					case 1:
						$insert['grade'] = 10;
						break;
					case 2:
						$insert['grade'] = 9;
						break;
					case 3:
						$insert['grade'] = 8;
						break;
					case 4:
						$insert['grade'] = 6;
						break;
					case 5:
						$insert['grade'] = 1;
						break;
					default:
						break;
				}
				$insert['attend_id'] = (int) $value['id'];
				$rlt = $jury_result->add($insert);
				if (false === $rlt) {
					$this->response(null, '提交错误', ERROR_SUBMIT);
					return;
				}
			}
		} else {
			$this->response(null, '提交错误', ERROR_SUBMIT);
			return;
		}
		$jury_result->commit();
		$this->response();
		return;
	}
}
