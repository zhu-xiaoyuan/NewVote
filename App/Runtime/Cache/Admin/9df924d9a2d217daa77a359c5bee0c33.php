<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<title>发起投票</title>
	<link rel="stylesheet" type="text/css" href="/NewVote/Public/css/space.css">
	<link rel="stylesheet" type="text/css" href="/NewVote/Public/css/layer.css">
	<link rel="stylesheet" type="text/css" href="/NewVote/Public/css/page.css">
	<script type="text/javascript" src="/NewVote/Public/static/jquery-1.11.js"></script>
	<script type="text/javascript" src="/NewVote/Public/static/md5.js"></script>
	<script type="text/javascript" src="/NewVote/Public/js/layer.js"></script>
	<script type="text/javascript" src="/NewVote/Public/js/page2.js"></script>
	<script type="text/javascript" src="/NewVote/Public/js/tab.js"></script>
	
	
	<link rel="stylesheet" type="text/css" href="/NewVote/Public/css/index.css">
	<script type="text/javascript" src="/NewVote/Public/js/index.js"></script>
	<script type="text/javascript" src="/NewVote/Public/js/check.js"></script>

</head>
<body>
	
	<div id="add-layout" class="layout">
		<div class="dialog_back"></div>
		<div id="add-dialog" class="dialog">
			<p class="title">添加<span class="close-icon">×</span></p>
			<form id="add-form">
			<div class="lay-form-item">
				<label for="code">代号：</label><input type="text" name="code"><span>*</span>
			</div>
			<div class="lay-form-item">
				<label for="name">姓名：</label><input type="text" name="name"><span>*</span>
			</div>
			<div class="lay-form-item">
				<label for="introduce">简介：</label><textarea name="introduce"></textarea><span>*</span>
			</div>
			</form>
			<div class="warning">注: 带*号的为必填项</div>
			<button id="add-dialog-sure">确定</button>
			<button class="dialog-cancel">取消</button>
		</div>
	</div>
	<div id="edit-layout" class="layout">
		<div class="dialog_back"></div>
		<div id="edit-dialog" class="dialog">
			<p class="title">修改<span class="close-icon">×</span></p>
			<form id="edit-form">
			<div class="lay-form-item">
				<label for="code">代号: </label><input  type="text" name="code" ><span>*</span>
			</div>
			<div class="lay-form-item">
				<label for="name">姓名: </label><input type="text" name="name"><span>*</span>
			</div>
			<div class="lay-form-item">
				<label for="introduce">简介: </label><textarea name="introduce"></textarea><span>*</span>
			</div>
			</form>
			<div class="warning">注: 带*号的为必填项</div>
			<button id="edit-dialog-sure" for="">确定</button>
			<button class="dialog-cancel">取消</button>
		</div>
	</div>

	<?php if($login): else: ?>
		<div id="login_bar">
			<div class="op_back"></div>
			<h5>登录</h5>
			<form>
			<div class="login_form_block">
				<label class="login_label" for="login_name"><img src="/NewVote/Public/image/login_name.png"></label>
				<input type="text" name="login_name" class="login_input" />
			</div>
			<div class="login_form_block">
				<label class="login_label" for="login_password"><img src="/NewVote/Public/image/login_password.png"></label>
				<input type="password" name="login_password" class="login_input" />
			</div>
			</form>
			<button id="login_submit">登录</button>
		</div><?php endif; ?>
	<a href="<?php echo U('index/help');?>" class="help"></a>
	<div id="header">
		
			<span id="logo"></span>
			
	<ul id="bar">
		<li id="launch" class="tab action"><a href="<?php echo U('index/index');?>">发起投票</a></li>
		<li id="call-out" class="tab static"><a href="<?php echo U('Callout/index');?>">唱　　票</a></li>
		<li id="manage" class="tab static"><a href="<?php echo U('Manage/index');?>">投票管理</a></li>
	</ul>

			<?php if($login): ?><p id="login-status" value="<?php echo ($login); ?>">
				<?php if($admin): ?><span id="add_user" title="添加用户"></span><?php endif; ?>
				<span id="logout" title="退出"></span>
			<?php else: ?>
			<p id="login-status" value="0">
				<span id="login">登录</span><?php endif; ?>
			</p>
			<li class="ip static">IP 地址 : <?php echo ($ip_address); ?></li>
		
	</div>
	<div id="center">
	<form>
		<div id="info_panel">
			<div class="input-item">
				<label for="theme">主题:</label>
				<input type="text" name="theme" id="theme" value="<?php echo ($vote_info["theme"]); ?>"/>
				<label class="notice_label">(不超过30个字)</label>
			</div>
			<div class="input-item">
				<label for="describe">描述:</label>
				<textarea name="describe" id="describe"><?php echo ($vote_info["describe"]); ?></textarea>
				<label class="notice_label">(不超过200个字)</label>
			</div>
			<div class="input-item">
				<label for="vote_type">模式:</label>
				<label class="radio_label"><input type="radio" name="vote_type" value="1"/>打分</label>
				<label class="radio_label"><input type="radio" name="vote_type" value="2"/>选与不选</label>
				<label class="radio_label"><input type="radio" name="vote_type" value="3"/>排序</label>
				<label class="radio_label"><input type="radio" name="vote_type" value="4"/>等级</label>
				
				<?php if($vote_info['is_dislodge'] == '0'): ?><label id="rule" value='0'>
				<?php else: ?>
					<label id="rule" value='<?php echo ($vote_info["is_dislodge"]); ?>'><?php endif; ?>
					去掉最高最低分:
				<label><input type="radio" name="is_dislodge" value="1" />是</label>
				<label><input type="radio" name="is_dislodge" value="0" />否</label>
				</label>
			</div>
		</div>
		<div class="input-item" id="choose_bar">
			<label for="is_show_name">参选人员:</label>
			<label class="checkbox-bar">
				
				<?php if($vote_info['is_show_name']): ?><div id="check_show" class="check_on"></div>
					<input type="text" name="is_show_name" id="is_show_name"  value="1" />
				<?php else: ?>
					<div id="check_show" class="check_off"></div>
					<input type="text" name="is_show_name" id="is_show_name"  value="0" /><?php endif; ?>
				<span>显示姓名</span>
			</label>
			<button type="button" id="add-attend-person">添加</button>
		</div>
		<table id="attend_table"  cellpadding="1" cellspacing="0">
		<thead>
			<tr>
				<th class="attend-1">序号</th><th class="attend-2" class="code">代号</th><th class="attend-3">姓名</th><th class="attend-4">简介</th><th class="attend-5">操作</th>
			</tr>
			</thead>
			<tbody>
			<?php if($flag == 1): if(is_array($attend_list)): $i = 0; $__LIST__ = $attend_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
					<td class="attend-1"><?php echo ($i); ?></td><td class="attend-2"><?php echo ($vo["code"]); ?></td><td class="attend-3"><?php echo ($vo["name"]); ?></td><td class="attend-4"><?php echo ($vo["introduce"]); ?></td><td class="attend-5"><a class='operate-edit-attend' onclick='edit(this)'></a><a class='operate-del-attend' onclick='del(this)'></a></td>
				</tr><?php endforeach; endif; else: echo "" ;endif; endif; ?>
			</tbody>
		</table>
		<div class="input-item">
			<label for="attend_num">参评人数:
			<input type="text" name="attend_num" id="attend_num" value="<?php echo ($vote_info["attend_num"]); ?>" style="display:none" />
			<span id="attend-num-icon"></span>人
			</label>
		</div>
		<div class="input-item">
			<label for="prepare_num">拟投人数:</label>
			<input type="text" name="prepare_num" id="prepare_num" value="<?php echo ($vote_info["prepare_num"]); ?>" />
		</div>
		<div class="input-item">
			<label for="jury_num">评委人数:</label>
			<input type="text" name="jury_num" id="jury_num" value="<?php echo ($vote_info["jury_num"]); ?>"/>
			<?php if(count($jury_list) == 0): ?><button type="button" id="print-jury-list" class="disable" disabled="disabled" onclick="print_jury_list(<?php echo ($vote_info["id"]); ?>)">导出</button>
			<?php else: ?>
				<button type="button" id="print-jury-list" class="action" onclick="print_jury_list(<?php echo ($vote_info["id"]); ?>)">导出</button><?php endif; ?>
			<button type="button" id="create-jury-list" onclick="create_jury_list()">生成</button>
		</div>
		<table id="jury_table" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th id="jury-1">账号</th><th id="jury-1">密码</th><th id="jury-1">账号</th><th id="jury-1">密码</th><th id="jury-1">账号</th><th id="jury-1">密码</th>
			</tr>
		</thead>
		<tbody>
			<?php
 if($flag == 1){ $total = count($jury_list); $num = $total / 3; $i = 0; for( ;$i < $num; $i++){ echo '<tr>'; for($j = 0; $j < 3; $j ++){ echo "<td class = 'obj-1'>".$jury_list[$i*3+$j]['account']."</td>"; echo "<td class = 'obj-2'>".$jury_list[$i*3+$j]['password']."</td>"; } echo '</tr>'; } for($i = $i*3;$i < $total ;$i ++){ echo "<td class = 'obj-1'>".$jury_list[$i]['account']."</td>"; echo "<td class = 'obj-2'>".$jury_list[$i]['password']."</td>"; } $num = $total-$num*3; for($i = 0;$i < $num;$i ++){ echo '<td></td>'; } echo '</tr>'; } ?>
			</tbody>
		</table>
		<div id="button-box">
			<button type="button" id="to-launch" class="btn-sub" url="/NewVote/index.php/Admin/Index/submit?status=1&id=<?php echo ($vote_info["id"]); ?>">发起</button>
			<button type="button" id="save" class="btn-sub" url="/NewVote/index.php/Admin/Index/submit?status=2&id=<?php echo ($vote_info["id"]); ?>">保存</button>
		</div>
	</form>
	<input id="vote_type_val" value="<?php echo ($vote_info["vote_type"]); ?>" style="display:none" />
</div>
	<div id="footer">
		<p>CopyRight 　　　河南科技学院　　　 技术支持: 三月工作室</p>
	</div>
	<input class="hide" id="url" value="/NewVote/index.php/Admin/Index" style="display:none">
	<input class="hide" id="root" value="/NewVote" style="display:none">
</body>
</html>