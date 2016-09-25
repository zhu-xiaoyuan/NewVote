<?php
namespace Admin\Controller;
use Think\Controller;

class ManageController extends BaseController {
	public function index() {

		$vote = M('vote_info');
		$where['is_del'] = 0;
		$where['create_person'] = session('id');
		$count = $vote->where($where)->count();
		$page = new \Think\Page($count, 8);
		$show_page = $page->show();
		$vote_list = $vote->where($where)
		                  ->field('id,theme,start_time,status')
		                  ->limit($page->firstRow . ',' . $page->listRows)
			->order('id desc')
			->select();
		$this->assign("vote_list", $vote_list);
		$this->assign("page", $show_page);
		$this->display();
	}

	/**
	 * 开启或关闭一次投票
	 * @param status   1: 正在投票(发起)  2:待投票  4: 关闭(关闭)
	 *       vote_id        投票id
	 **/
	public function changeStatus() {
		$status = (int) $_POST['status'];
		$vote_id = (int) $_POST['vote_id'];
		$vote = M('vote_info');
		if (1 === $status) {
			$rlt = $vote->where(array('status' => 1))->find();
			if ($rlt > 0) {
				$this->response(null, '已经存在正在投票项目', ONE_VOTING);
				return;
			}
			$this->launch($vote_id);
		} elseif (4 === $status) {
			$this->close($vote_id);
		} elseif (2 === $status) {
			$rlt = $vote->where(array('id' => $vote_id))->save(array('status' => 2));
			if ($rlt !== false) {
				$this->response();
				return;
			}
		}
	}

	/**
	 * 关闭一次投票
	 * @param 
	 *       vote_id        投票id
	 **/
	public function close($vote_id) {
		$rlt = M('vote_info')->where(array('id' => $vote_id))->save(array('status' => 4));
		if (false === $rlt) {
			$this->response(null, '关闭投票失败', FALSE);
			return;
		}
		M('token')->where(array('vote_id' => $vote_id))->save(array('is_del' => 1));
		$sql = M('attend_person_info')->field('id')->table('attend_person_info')->where(array('vote_id' => $vote_id))->buildSql();
		if (false === M('vote_result')->where('attend_id in' . $sql)->save(array('is_del' => 1))) {
			$this->response();
			return true;
		} else {
			$this->response(null, '关闭投票失败', FALSE);
			return false;
		}
	}

	/**
	 * 发起一次投票
	 * @param 
	 *       vote_id        投票id
	 **/
	public function launch($vote_id) {
		$vote = M('vote_info');
		$vote->startTrans();
		$data['status'] = 1;
		$data['start_time'] = time();
		$rlt = $vote->where(array('id' => $vote_id))->save($data);
		if (false === $rlt) {
			$this->response(null, '发起投票失败', FALSE);
			return;
		}

		/*生成token记录*/
		$t = M('token');
		$token['vote_id'] = $vote_id;
		$rlt = $t->where($token)->count();
		if ($rlt) {
			$token['token'] = $this->genToken();
			$token['update_time'] = time();
			$token['expires'] = strtotime("+1 day");
			$token['is_del'] = 0;
			if (false === $t->where(array('vote_id' => $vote_id))->save($token)) {
				$vote->rollback();
				$this->response(null, 'token生成失败', FALSE);
				return;
			}
		} else {
			$token['token'] = $this->genToken();
			$token['create_time'] = time();
			$token['expires'] = strtotime("+1 day");
			if (false === $t->add($token)) {
				$vote->rollback();
				$this->response(null, 'token生成失败', FALSE);
				return;
			}
		}
		$vote->commit();
		$this->response(null, '发起成功');
	}

	/**
	 * 重投
	 * @param vote_id        投票id
	 *
	 **/
	public function rebowl() {

		$where['id'] = $_POST['vote_id'];

		$vote = M('vote_info');
		$vote->startTrans();
		/*是否存在正在投票项目*/
		$rlt = $vote->where('status=1')->find();
		if ($rlt > 0) {
			$this->response(null, '已经存在正在投票项目', ONE_VOTING);
			return;
		}
		$data['status'] = 1;
		$data['jury_result_num'] = 0;

		if (false === $vote->where($where)->save($data)) {
			$this->response(null, '重投失败', FALSE);
		} else {
			$vote->where($where)->setInc('vote_result_num');

			/*生成token记录*/
			$token['token'] = $this->genToken();
			$token['update_time'] = time();
			$token['expires'] = strtotime("+1 day");
			$token['is_del'] = 0;
			$t = M('token');
			$rlt = $t->where(array('vote_id' => $_POST['vote_id']))->save($token);

			if (false === $rlt) {
				$vote->rollback();
				$this->response(null, 'token生成失败', FALSE);
				return;
			}
			$attend = M('attend_person_info');
			$map['vote_id'] = $_POST['vote_id'];
			$rlt = $attend->where($map)->save(array('is_vote' => 1));
			if (false === $rlt) {
				$vote->rollback();
				$this->response(null, '重投失败', FALSE);
			} else {
				$vote->commit();
				$this->response(null, '已开始重新投票');
			}
		}
	}

	/**
	 * 显示一次投票的信息
	 * @param 
	 *       vote_id        投票id
	 **/
	public function showInfo() {
		$id = (int) $_POST['id'];
		$info = M('vote_info')->where(array('id' => $id))->find();
		$info['jury_list'] = M('jury_info')->where(array('vote_id' => $info['id'], 'is_del' => 0))->field('account,password')->select();
		if ($info['status'] == 3) {
			$attend = M('attend_person_info');
			$where['attend_person_info.vote_id'] = $info['id'];
			for ($i = 0; $i <= (int) $info['vote_result_num']; $i++) {
				$info['result'][$i] = $attend
					->where($where)
					->field('sort,code,name,introduce')
					->join('vote_result on attend_person_info.id = vote_result.attend_id and vote_result.revote_num=' . $i)
					->order('sort,attend_person_info.id')
					->select();
			}
		} else {
			$info['attend_list'] = M('attend_person_info')->where(array('vote_id' => $info['id'], 'is_del' => 0))->field('code,name,introduce')->select();
		}
		$this->response($info);
	}

	/**
	 *搜索
	 * @param 
	 *       search_state        投票状态
	 *       search_date_end        发起时间上限
	 *       search_date_start       发起时间下限
	 *       theme        投票主题
	 **/
	public function search() {
		$search_state = (int) $_POST['search_state'];
		if (!empty($_POST['search_date_start'])) {
			$mintime = strtotime($_POST['search_date_start']);
		}
		if (!empty($_POST['search_date_end'])) {
			$maxtime = strtotime($_POST['search_date_end']) + 60 * 60 * 24;
		}
		if (!!$mintime) {
			$where['start_time'] = array('gt', $mintime);
		}
		if (!!$maxtime) {
			$where['start_time'] = array('lt', $maxtime);
		}
		if (!!$maxtime && !!$mintime) {
			$where['start_time'] = array('between', array($mintime, $maxtime));
		}

		$where['theme'] = array('like', '%' . $_POST['search_theme'] . '%');

		if ($search_state) {
			$where['status'] = $search_state;
		}
		$where['is_del'] = 0;
		$where['create_person'] = session('id');
		$vote = M('vote_info');
		$count = $vote->where($where)->count();
		$page = new \Think\Page($count, 8);
		$show_page = $page->show();
		$result = $vote->where($where)
		               ->field('id,theme,start_time,status')
		               ->limit($page->firstRow . ',' . $page->listRows)
			->order('id desc')
			->select();
		$search_info = $_POST;

		$this->assign("vote_list", $result);
		$this->assign("page", $show_page);
		$this->assign("search_info", $search_info);
		$this->display('index');
	}

	/*获取32位随机token*/
	function genToken($len = 32, $md5 = true) {
		# Seed random number generator
		# Only needed for PHP versions prior to 4.2
		mt_srand((double) microtime() * 1000000);
		# Array of characters, adjust as desired
		$chars = array(
			'Q', '@', '8', 'y', '%', '^', '5', 'Z', '(', 'G', '_', 'O', '`',
			'S', '-', 'N', '<', 'D', '{', '}', '[', ']', 'h', ';', 'W', '.',
			'/', '|', ':', '1', 'E', 'L', '4', '&', '6', '7', '#', '9', 'a',
			'A', 'b', 'B', '~', 'C', 'd', '>', 'e', '2', 'f', 'P', 'g', ')',
			'?', 'H', 'i', 'X', 'U', 'J', 'k', 'r', 'l', '3', 't', 'M', 'n',
			'=', 'o', '+', 'p', 'F', 'q', '!', 'K', 'R', 's', 'c', 'm', 'T',
			'v', 'j', 'u', 'V', 'w', ',', 'x', 'I', '$', 'Y', 'z', '*',
		);
		# Array indice friendly number of chars;
		$numChars = count($chars) - 1;
		$token = '';
		# Create random token at the specified length
		for ($i = 0; $i < $len; $i++) {
			$token .= $chars[mt_rand(0, $numChars)];
		}

		# Should token be run through md5?
		if ($md5) {
			# Number of 32 char chunks
			$chunks = ceil(strlen($token) / 32);
			$md5token = '';
			# Run each chunk through md5
			for ($i = 1; $i <= $chunks; $i++) {
				$md5token .= md5(substr($token, $i * 32 - 32, 32));
			}

			# Trim the token
			$token = substr($md5token, 0, $len);
		}return $token;
	}
}
?>
