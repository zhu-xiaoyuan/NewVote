<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<title>唱票</title>
	<link rel="stylesheet" type="text/css" href="/NewVote/Public/css/space.css">
	<link rel="stylesheet" type="text/css" href="/NewVote/Public/css/layer.css">
	<link rel="stylesheet" type="text/css" href="/NewVote/Public/css/page.css">
	<script type="text/javascript" src="/NewVote/Public/static/jquery-1.11.js"></script>
	<script type="text/javascript" src="/NewVote/Public/static/md5.js"></script>
	<script type="text/javascript" src="/NewVote/Public/js/layer.js"></script>
	<script type="text/javascript" src="/NewVote/Public/js/page2.js"></script>
	<script type="text/javascript" src="/NewVote/Public/js/tab.js"></script>
	
	
	<script type="text/javascript" src="/NewVote/Public/js/callout.js"></script>
	<link rel="stylesheet" type="text/css" href="/NewVote/Public/css/callout.css">

</head>
<body>
	
	<div id="revote_bar">
		<h4>重新投票</h4>
		<div class="revote_form_item">
			<label>参评人数:</label><label id="revote_attend_num">10人</label>
		</div>
		<div class="revote_form_item">
			<label>拟投人数:</label><albel id="revote_prepare_num">3人</albel>
		</div>
		<button id="sure_revote">确定</button>
		<button id="cancel_revote" >取消</button>
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
		<li id="launch" class="tab static"><a href="<?php echo U('index/index');?>">发起投票</a></li>
		<li id="call-out" class="tab action"><a href="<?php echo U('Callout/index');?>">唱　　票</a></li>
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
	<input id="vote_id" value="<?php echo ($info["id"]); ?>" style="display:none" />
	<div id="call-out-panel">
		<div id="num-panel">
			<label id="notice">已投票评委数</label>
			<span class="show-num">0</span>
			<label id="notice_2">请点击刷新</label>
			<button id="refresh">刷新</button>
		</div>
		<div id="status-panel">
			<p>主　　题:　<a><?php echo ($info["theme"]); ?></a>
			<?php if($info['jury_result_num']): ?><span id="jury_revote_num">第<?php echo ($info["jury_result_num"]); ?>次重投</span>
			<?php else: ?>
				<span id="jury_revote_num"></span><?php endif; ?>
			<p>参选人数:　<span id='attend_num'><?php echo ($info["attend_num"]); ?></span></p>
			<p>拟投人数:　<span id="prepare_num"><?php echo ($info["prepare_num"]); ?></span></p>
			<p>评委人数:　<span id="jury_num"><?php echo ($info["jury_num"]); ?></span></p>
			<p>投票状态:　<span>正在投票</span><button id="finish">完成</button></p>
		</div>
	</div>
	<button id="check-connection">检查排名是否相同</button>
	<button id="rebowl">重投</button>
	<div id="result_panel">
	<table id="result" cellpadding="0" cellspacing="1" type="<?php echo ($info["vote_type"]); ?>">
		<thead>
			<tr id="table_head">
				<th id='result-1'><input type="checkbox" name="choose" disabled='disabled' />
				<?php switch($info["vote_type"]): case "1": ?><th id='result-3'>排名</th>
						<th id='result-4'>平均分</th><th id='result-5'>总分</th><?php break;?>
				    <?php case "2": ?><th id='result-2'>排名</th>
				    	<th id='result-6'>票数</th><?php break;?>
				    <?php case "3": ?><th id='result-2'>排名</th>
				    	<th id='result-6'>排名分</th><?php break;?>
				    <?php case "4": ?><th id='result-2'>排名</th>
				    	<th id='result-6'>等级分</th><?php break;?>
				    <?php default: ?>
				    	<th id='result-2'>排名</th>
				    	<th id='result-6'>票数</th><?php endswitch;?>
				<th id='result-7'>代号</th><th id='result-8'>姓名</th><th id='result-9'>简介</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
	</div>
	<input id="vote_type_val" value="<?php echo ($info["vote_type"]); ?>" style="display:none" />
	<!-- <div id="page"></div> -->
	<!-- <input id="revote_num" value="<?php echo ($info["revote_num"]); ?>" /> -->
</div>
	<div id="footer">
		<p>CopyRight 　　　河南科技学院　　　 技术支持: 三月工作室</p>
	</div>
	<input class="hide" id="url" value="/NewVote/index.php/Admin/Callout" style="display:none">
	<input class="hide" id="root" value="/NewVote" style="display:none">
</body>
</html>