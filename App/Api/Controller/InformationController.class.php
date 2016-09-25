<?php
namespace Api\Controller;
use Think\Controller;

define("ERROR", 0x3001); // 失败

class InformationController extends BaseController {
	/**
	 * @param  $_POST['vote_id']   参与投票的id
	 *
	 * @author  L
	 *
	 * @return
	 *
	 * id
	 * theme 主题
	 * describe 描述
	 * vote_type  投票类型:1,打分;2,选与不选;3,排序;4,等级
	 * is_show_name  1,显示;0,不显示
	 * attend_num  参评人数
	 * prepare_num  拟投人数
	 * jury_num  评委人数
	 * is_dislodge  1.去除 0.不去除
	 * create_person  创建人account
	 * create_time  创建时间
	 * start_time  发起时间
	 * update_time  修改时间
	 * vote_result_num  vote_result的revote_num
	 * jury_result_num  jury_result的revote_num
	 **/

	public function get_vote_info() {
		if (!isset($_POST['vote_id']) || empty($_POST['vote_id'])) {
			$this->response(null, '错误的投票id', ERROR_THEME);
			return;
		}

		$m = M('vote_info');
		$where['id'] = $_POST['vote_id'];
		$where['is_del'] = 0;

		$data = $m->where($where)
		          ->field('id,theme,describe,vote_type,is_show_name,attend_num,prepare_num,jury_num,is_dislodge,
				create_person,create_time,start_time,update_time,vote_result_num,jury_result_num')
		          ->find();
		if ($data['jury_result_num']) {
			$data['attend_num'] = M('attend_person_info')->where(array('is_vote' => 1, 'vote_id' => $data['id']))->count();
			$condition['vote_id'] = $data['id'];
			$condition['revote_num'] = $data['vote_result_num'];
			$vote_result = M('vote_result');
			$valid = $vote_result
				->where(array('vote_id' => $data['id'], 'revote_num' => $data['vote_result_num'], 'is_del' => 0))
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
			$data['prepare_num'] = $data['prepare_num'] - M('vote_result')->where($condition)->count();
		}

		if (false === $data) {
			$this->response(null, '获取失败', ERROR);
			return;
		}
		$create_person = M('user')->where(array('id' => $data['create_person']))->field('account')->find();
		if (false === $create_person) {
			$this->response(null, '获取失败', ERROR);
			return;
		}
		$data['create_person'] = $create_person['account'];

		$this->response($data);
	}

	/**
	 * @param  $_POST['vote_id']   参与投票的id
	 *         $_POST['page_index']   参选人列表所在页
	 *
	 * @author  L
	 *
	 * @return
	 * attend_list 参选人列表 [
	 *                  id
	 *                  code 代号
	 *                  name 姓名
	 *                  introduce  简介
	 *              ]
	 **/
	public function get_attend_person_info() {
		$page = (int) $_POST['page_index'];
		$vote_id = (int) $_POST['vote_id'];
		$limit = 2;
		$first = ($page - 1) * $limit;
		$where['vote_id'] = $vote_id;
		$where['is_del'] = 0;
		$where['is_vote'] = 1;
		$attend_list = M('attend_person_info')
			->where($where)
			->field('id,code,name,introduce')
			->limit($first . ',' . $limit)
			->select();
		if (false === $attend_list) {
			$this->response(null, '获取失败', ERROR);
			return;
		}
		$this->response($attend_list);
	}
}
?>