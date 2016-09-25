<?php
namespace Admin\Controller;
use Think\Controller;

class CalloutController extends BaseController {
	public function index() {
		$vote = M('vote_info');
		$where['status'] = 1;
		$where['create_person'] = session('id');
		$info = $vote->where($where)->find();
		if ($info['jury_result_num']) {
			$info['attend_num'] = M('attend_person_info')->where(array('is_vote' => 1, 'vote_id' => $info['id']))->count();
			$condition['vote_id'] = $info['id'];
			$condition['revote_num'] = $info['vote_result_num'];
			$vote_result = M('vote_result');
			$valid = $vote_result
				->where(array('vote_id' => $info['id'], 'revote_num' => $info['vote_result_num'], 'is_del' => 0))
				->order('sort')
				->select();
			if ($valid[0]['sort'] > 1) {
				$flag = 0;
			} else {
				for ($i = 0; $i < count($valid) - 1; $i++) {
					if ($valid[$i + 1]['sort'] - $valid[$i]['sort'] > 1) {
						break;
					}
				}
				$flag = $valid[$i]['sort'] + 1;
			}
			$condition['sort'] = array('lt', $flag);
			$info['prepare_num'] = $info['prepare_num'] - $vote_result->where($condition)->count();
		}
		$this->assign("info", $info);

		$this->display();
	}

	/**
	 * 得到投票过程中的 已投人数
	 *
	 * @param   vote_id    投票id
	 *			jury_result_num  过程重投次数
	 *			revote_num  重头次数
	 *
	 **/
	public function get_voted_num($vote_id, $jury_result_num, $revote_num) {
		$jury_result = M('jury_result');
		$where['vote_id'] = $vote_id;
		$where['revote_num'] = $revote_num;
		$where['jury_revote_num'] = $jury_result_num;
		$voted_num = $jury_result->where($where)->count('DISTINCT jury_id');
		return $voted_num;
	}

	/**
	 * 得到投票过程中的jury_result
	 *
	 * @param   vote_id    投票id
	 *			jury_result_num  过程重投次数
	 *			revote_num  重头次数
	 *			is_dislodge   是否去掉最高分最低分
	 *
	 **/
	public function get_result($vote_id, $jury_result_num, $revote_num, $is_dislodge) {
		$attend = M('attend_person_info');
		if ($is_dislodge) { //去掉最高分最低分
			$result = $attend
				->join('left join jury_result on jury_result.attend_id=attend_person_info.id
				and jury_result.is_del=0 and jury_result.revote_num=' . $revote_num .
					' and jury_result.jury_revote_num=' . $jury_result_num)
				->where(array('attend_person_info.vote_id' => $vote_id, 'attend_person_info.is_vote' => 1, 'attend_person_info.is_del' => 0))
				->field('attend_person_info.id,sum(grade) as total_grade,
					(sum(grade)-max(grade)-min(grade)) / (count(grade) - 2) as grade,code,name,introduce')
				->order('grade desc,id')
				->group('code')
				->select();
		} else {
			$result = $attend
				->join('left join jury_result on jury_result.attend_id=attend_person_info.id
				and jury_result.is_del=0 and jury_result.revote_num=' . $revote_num .
					' and jury_result.jury_revote_num=' . $jury_result_num)
				->where(array('attend_person_info.vote_id' => $vote_id, 'attend_person_info.is_vote' => 1, 'attend_person_info.is_del' => 0))
				->field('attend_person_info.id,sum(grade) as total_grade,
					avg(grade) as grade,code,name,introduce')
				->group('code')
				->order('total_grade desc,id')
				->select();
		}
		if (false !== $result) {
			return $result;
		} else {
			return false;
		}
	}

	/**
	 * 得到投票过程中的结果
	 *
	 * @param   vote_id    投票id
	 *
	 **/
	public function get_now_result() {
		$vote_id = (int) $_POST['vote_id'];
		$rlt = M('vote_info')
			->where(array('id' => $vote_id))
			->field('vote_type,vote_result_num,jury_result_num,is_dislodge')
			->find();
		$vote_result_num = $rlt['vote_result_num'];
		$jury_result_num = $rlt['jury_result_num'];
		if ($rlt['vote_type'] == 1) {
			$is_dislodge = $rlt['is_dislodge'];
		}
		$data['result'] = $this->get_result($vote_id, $jury_result_num, $vote_result_num, $is_dislodge);
		if ($data['result'] === false) {
			$this->response(null, '获取投票结果失败', ERROR);
		} else {
			$data['voted_num'] = $this->get_voted_num($vote_id, $jury_result_num, $vote_result_num);
			$this->response($data);
		}
	}

	/**
	 * 完成投票
	 *
	 * @param   vote_id    投票id
	 *			data     结果
	 *			sort_num   这次投票的名次数
	 *
	 **/
	public function complete() {
		$data = $_POST['data'];
		$vote_id = (int) $_POST['vote_id'];
		$sort_num = (int) $_POST['sort_num'];
		$this->save_result($vote_id, $data, $sort_num);

		M('attend_person_info')
			->where(array('vote_id' => $vote_id))
			->save(array('is_vote' => 0));

		$rlt = M('vote_info')->where(array('id' => $vote_id))->save(array('status' => 3));
		M('token')->where(array('vote_id' => $vote_id))->save(array('is_del' => 1));
		$this->response(null, '投票完成');
	}

	/**
	 * 写入结果
	 *
	 * @param   vote_id    投票id
	 *			data     结果
	 *			sort_num   这次投票的名次数
	 *
	 **/
	function save_result($vote_id, $result, $sort_num) {
		$revote_num = M('vote_info')
			->where(array('id' => $vote_id))
			->field('vote_result_num')
			->find();
		$vote_result = M('vote_result');
		$valid = $vote_result
			->where(array('vote_id' => $vote_id, 'revote_num' => $revote_num['vote_result_num'], 'is_del' => 0))
			->order('sort')
			->select();
		/*不存在结果*/
		if ($valid === null) {
			foreach ($result as $value) {
				$value['vote_id'] = $vote_id;
				$value['revote_num'] = $revote_num['vote_result_num'];
				$value['create_time'] = time();
				$rlt = $vote_result
					->field('attend_id,sort,grade,total_grade,revote_num')
					->add($value);
				if ($rlt === false) {
					$vote_result->rollback();
					$this->response(null, '保存结果失败', ERROR);
					return fasle;
				}
			}
			$vote_result->commit();
			return true;
		}
		/*存在结果*/
		// 查找插入点
		if ($valid[0]['sort'] > 1) {
			$i = 0;
			$flag = 0;
			//将之后结果后移 流出足够的空间
			$rlt = $vote_result
				->where(array('vote_id' => $vote_id, 'revote_num' => $revote_num['vote_result_num'], 'is_del' => 0))
				->setInc('sort', $sort_num - 1);
		} else {
			for ($i = 0; $i < count($valid) - 1; $i++) {
				if ($valid[$i + 1]['sort'] - $valid[$i]['sort'] > 1) {
					break;
				}
			}
			$flag = $valid[$i]['sort'];
			//将之后结果后移 流出足够的空间
			$vote_result
				->where(array('vote_id' => $vote_id, 'revote_num' => $revote_num['vote_result_num'], 'sort' => array('gt', $valid[$i]['sort'])))
				->setInc('sort', $sort_num - 1);
		}

		//将新结果插入
		foreach ($result as $value) {
			$value['vote_id'] = $vote_id;
			$value['revote_num'] = $revote_num['vote_result_num'];
			$value['create_time'] = time();
			$value['sort'] += $flag;
			$rlt = $vote_result
				->field('attend_id,sort,grade,total_grade,revote_num')
				->add($value);
			if ($rlt === false) {
				$vote_result->rollback();
				$this->response(null, '存入结果失败', ERROR);
				return fasle;
			}
		}
		$vote_result->commit();
		return true;
	}

	/**
	 * 过程重投
	 *
	 * @param   vote_id    投票id
	 *			valid_attend_list  重投人列表
	 *			result     以确定人员结果
	 *			sort_num   这次投票的名次数
	 *
	 **/
	public function revote() {
		$valid_attend_list = $_POST['valid_attend_list'];
		$result = $_POST['result'];
		$vote_id = (int) $_POST['vote_id'];
		$sort_num = (int) $_POST['sort_num'];
		if (!$this->save_result($vote_id, $result, $sort_num)) {
			return;
		}

		/*处理未确定参选人*/
		$attend = M('attend_person_info');
		foreach ($valid_attend_list as $value) {
			$attend->where(array('id' => $value))->save(array('is_vote' => 0));
		}

		/*处理重投*/
		$jury_revote_num = M('vote_info')->where(array('id' => $vote_id))->field('jury_result_num')->find();
		$jury_revote_num['jury_result_num']++;
		M('vote_info')
			->where(array('id' => $vote_id))
			->save(array('jury_result_num' => $jury_revote_num['jury_result_num']));
		$this->response($jury_revote_num['jury_result_num']);
	}
}
?>