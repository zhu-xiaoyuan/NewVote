<?php
namespace Admin\Controller;
use Think\Controller;

define('ERROR_THEME', 0xa001);
define('ERROR_DESC', 0xa002);
define('ERROR_TYPE', 0xa003);
define('ERROR_SHOW', 0xa004);
define('ERROR_ATTEND_NUM', 0xa005);
define('ERROR_PREPARE_NUM', 0xa006);
define('ERROR_JURY_NUM', 0xa007);
define('TURE_TO_WAIT', 0xa008);
define('SAME_THEME', 0xa009);
define('ERROR_USER', 0xa00a);

class IndexController extends BaseController {
	public function index($id = '') {
		/*编辑时有id*/
		if (isset($id) && !empty($id)) {
			$where['id'] = $id;
			$where['is_del'] = 0;
			$vote = M('vote_info');
			$vote_info = $vote->where($where)->find();

			$map['vote_id'] = $vote_info['id'];
			$map['is_del'] = 0;
			$attend = M('attend_person_info');
			$attend_list = $attend->where($map)->select();

			$jury = M('jury_info');
			$jury_list = $jury->where($map)->select();

			$this->assign('flag', 1);
			$this->assign('vote_info', $vote_info);
			$this->assign('attend_list', $attend_list);
			$this->assign('jury_list', $jury_list);
		}
		$this->display();
	}

	/**
	 * 导出评委人员表格Excel
	 * @param excelFileName   文件名
	 *        sheetTitle      sheet名
	 *			data         数据
	 *
	 **/
	public function import($excelFileName = "评委人员", $sheetTitle = "评委") {
		$jury = $_POST['data'];

		//删除以前生成的
		$check_file = glob("Public/excel/*.xls");
		foreach ($check_file as $check_filename) {
			$file_time = filectime($check_filename);
			$now_time = time();
			if ($now_time - $file_time > 30) {
				unlink($check_filename);
			} else {
				$var_time = "error";
			}
		}
		/* 实例化类 */
		Vendor("PHPExcel.PHPExcel");
		Vendor("PHPExcel.PHPExcel.IOFactory");
		$objPHPExcel = new \PHPExcel();

		$objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);

		/* 设置当前的sheet */
		$objPHPExcel->setActiveSheetIndex(0);
		$objActSheet = $objPHPExcel->getActiveSheet();

		/* sheet标题 */
		$objActSheet->setTitle($sheetTitle);
		$objActSheet->setCellValue('A' . '1', "账号"); //设置第一行的值
		$objPHPExcel->getActiveSheet()->getStyle('A' . '1')->getFont()->setBold(true);
		$objActSheet->setCellValue('B' . '1', "密码");
		$objPHPExcel->getActiveSheet()->getStyle('B' . '1')->getFont()->setBold(true);
		$i = 2;
		foreach ($jury as $value) {
			/* excel文件内容 */
			$j = 'A';
			foreach ($value as $value2) {
				$objActSheet->setCellValue($j . $i, $value2);
				$j++;
			}
			$i++;
		}

		date_default_timezone_set("Asia/Shanghai");
		$date = date("YmdHi");
		$path = "Public/excel/" . $file_name . $date . ".xls";
		$objWriter->save($path);
		echo $path;
	}

	/**
	 * 提交
	 * @param status   1: 正在投票(发起)  2: 待投票(保存)
	 *        id        投票id (有值: 编辑)
	 *
	 **/
	public function submit($status, $id = '') {
		if (!isset($_POST['create_person']) || empty($_POST['create_person'])) {
			$this->response(null, '错误的发起人', ERROR_USER);
			return;
		}
		if (!isset($_POST['theme']) || empty($_POST['theme'])) {
			$this->response(null, '标题不能为空', ERROR_THEME);
			return;
		}
		if (!isset($_POST['describe']) || empty($_POST['describe'])) {
			$this->response(null, '描述不能为空', ERROR_DESC);
			return;
		}
		if (!isset($_POST['vote_type']) || !is_numeric($_POST['vote_type']) || $_POST['vote_type'] < 0 || $_POST['vote_type'] > 4) {
			$this->response(null, '请选择投票模式', ERROR_TYPE);
			return;
		}
		if (!isset($_POST['is_show_name'])) {
			$_POST['is_show_name'] = 0;
		}
		if (!is_numeric($_POST['is_show_name']) || $_POST['is_show_name'] < 0 || $_POST['is_show_name'] > 1) {
			$this->response(null, '请选择是否显示参选人姓名', ERROR_SHOW);
			return;
		}
		if (!isset($_POST['attend_num']) || !is_numeric($_POST['attend_num']) || (int) $_POST['attend_num'] <= 0) {
			$this->response(null, '错误的参选人数', ERROR_ATTEND_NUM);
			return;
		}
		if (!isset($_POST['prepare_num']) || !is_numeric($_POST['prepare_num']) || (int) $_POST['prepare_num'] <= 0 || $_POST['prepare_num'] > $_POST['attend_num']) {
			$this->response(null, '错误的拟选人数', ERROR_PREPARE_NUM);
			return;
		}
		if (!isset($_POST['jury_num']) || $_POST['jury_num'] <= 0 || !is_numeric($_POST['jury_num'])) {
			$this->response(null, '错误的评委人数', ERROR_JURY_NUM);
			return;
		}
		if (empty($_POST['attend_list'])) {
			$this->response(null, '请确定参评人员', ERROR_ATTEBD_LIST);
			return;
		}
		if (empty($_POST['jury_list'])) {
			$this->response(null, '请确定评委人员', ERROR_JURY_LIST);
			return;
		}
		$data = $_POST;
		$attend_list = $_POST['attend_list'];
		$jury_list = $_POST['jury_list'];
		unset($data['attend_list']);
		unset($data['jury_list']);
		$vote = M('vote_info');
		$vote->startTrans();
		/*判断是否存在正在投票的项目*/
		if (1 == $status) {
			if (null === $vote->where('status=1')->find()) {
				$data['start_time'] = time();
			} else {
				$turn = true; //记录转为 待投票 状态
				$status = 2;
			}
		}
		$data['status'] = $status;
		if (!empty($id)) {
			/*编辑时的处理*/
			$data['update_time'] = time();
			$rlt = $vote->where(array('id' => $id))->save($data);
			if (false === $rlt) {
				$vote->rollback();
				$this->response(null, 'false', FALSE);
			} else {
				$map['vote_id'] = $id;
				$map['is_del'] = 0;
				$attend = M('attend_person_info');
				$attend_list_prev = $attend->where($map)->field('id')->select();
				//处理attend表
				$a = count($attend_list_prev);
				$b = count($attend_list);
				if ($a >= $b) {
					for ($i = 0; $i < $b; $i++) {
						$rlt = $attend->where(array('id' => $attend_list_prev[$i]['id']))->save($attend_list[$i]);
					}
					for (; $i < $a; $i++) {    // 删除多出数据
						$rlt = $attend->where(array('id' => $attend_list_prev[$i]['id']))->delete();
					}
				} else {
					for (; $i < $a; $i++) {
						$rlt = $attend->where(array('id' => $attend_list_prev[$i]['id']))->save($attend_list[$i]);
					}
					for (; $i < $b; $i++) {   // 添加新增数据
						$attend_list[$i]['vote_id'] = $id;
						$attend_list[$i]['create_time'] = time();
						$rlt = $attend->add($attend_list[$i]);
					}
				}
				// 同样的方式处理jury表
				$jury = M('jury_info');
				$jury_list_prev = $jury->where($map)->select();
				$a = count($jury_list_prev);
				$b = count($jury_list);
				if ($a >= $b) {
					for ($i = 0; $i < $b; $i++) {
						$rlt = $jury->where(array('id' => $jury_list_prev[$i]['id']))->save($jury_list[$i]);
					}
					for (; $i < $a; $i++) {
						$rlt = $jury->where(array('id' => $jury_list_prev[$i]['id']))->delete();
					}
				} else {
					for ($i = 0; $i < $a; $i++) {
						$rlt = $jury->where(array('id' => $jury_list_prev[$i]['id']))->save($jury_list[$i]);
					}
					for (; $i < $b; $i++) {
						$jury_list[$i]['vote_id'] = $id;
						$jury_list[$i]['create_time'] = time();
						$rlt = $jury->add($jury_list[$i]);
					}
				}
				$vote->commit();
				if ($turn) {
					$this->response(null, '已有项目正在投票,已转为待投票', TURE_TO_WAIT);
				} else {
					if ($status == 1) {
						A('Manage')->launch($id);
					} else {
						$this->response(null, '发起成功');
					}
				}
			}
			return;
		}
		/*新建投票的处理*/
		$rlt = $vote->where(array('theme' => $data['theme']))->find();
		if (count($rlt) > 0) {
			$this->response(null, '投票已存在', SAME_THEME);
			return;
		}

		$data['create_time'] = time();

		if ($vote->data($data)) {
			$vote_id = $vote->add();
			if ($vote_id !== false) {
				/*插入参选人记录*/
				$attend = M('attend_person_info');
				$attend->startTrans();
				foreach ($attend_list as $value) {
					$value['vote_id'] = $vote_id;
					$value['create_time'] = time();
					$attend_id = $attend->data($value)->add();
					if (false === $attend_id) {
						$attend->rollback();
						$vote->rollback();
						$this->response(null, "创建参选人失败", FALSE);
						return;
					}
				}
				$jury = M('jury_info');
				$jury->startTrans();
				foreach ($jury_list as $value) {
					$value['vote_id'] = $vote_id;
					$value['create_time'] = time();
					if (false === $jury->data($value)->add()) {
						$vote->rollback();
						$attend->rollback();
						$jury->rollback();
						$this->response(null, "创建评委失败", FALSE);
						return;
					}
				}
				$attend->commit();
				$jury->commit();
				$vote->commit();
				if ($turn) {
					$this->response(null, '已有项目正在投票,已转为待投票', TURE_TO_WAIT);
				} else {
					if ($status == 1) {
						A('Manage')->launch($vote_id);
					} else {
						$this->response();
					}
				}
			} else {
				$vote->rollback();
				$this->response(null, '新建投票失败', FALSE);
			}
		} else {
			$vote->rollback();
			$this->response(null, '新建投票失败', FALSE);
		}
	}

	public function help() {
		$this->display();
	}
}
?>