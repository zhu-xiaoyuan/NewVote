<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<title>添加用户</title>
	<link rel="stylesheet" type="text/css" href="/NewVote/Public/css/space.css">
	<link rel="stylesheet" type="text/css" href="/NewVote/Public/css/layer.css">
	<link rel="stylesheet" type="text/css" href="/NewVote/Public/css/page.css">
	<script type="text/javascript" src="/NewVote/Public/static/jquery-1.11.js"></script>
	<script type="text/javascript" src="/NewVote/Public/static/md5.js"></script>
	<script type="text/javascript" src="/NewVote/Public/js/layer.js"></script>
	<script type="text/javascript" src="/NewVote/Public/js/page2.js"></script>
	<script type="text/javascript" src="/NewVote/Public/js/tab.js"></script>
	
	
	<link rel="stylesheet" type="text/css" href="/NewVote/Public/css/user.css">
	<script type="text/javascript" src="/NewVote/Public/js/user.js"></script>

</head>
<body>
	

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
<div class="main">
	<div class="theme"><h2>添加用户<a id="manage_user" href="/NewVote/index.php/Admin/User/index">管理用户</a></h2></div>
	<form class="user_form">
		<div class="form-item">
		<label class="item_name">用户名</label><input type="text" name="account" class="" /><label class='form_info null_info'>用户名为5-12位字符</label><label class='form_info_1'>用户名已存在</label>
		</div>
		<div class="form-item">
		<label class="item_name">密码</label><input type="password" name="password" class="" /><label class='form_info null_info'>请输入密码</label><label class='form_info_block'>
		<div class="password_info">
		<span class='em_bar'><em class='em_a'>◆</em><em class='em_b'>◆</em></span>
			<ul class="check_bar">
				<li class="check_bar_1">长度为6-14位字符</li>
				<li class="check_bar_2">支持数字,大小写字母和下划线</li>
				<li class="check_bar_3">不允许有空格</li>
			</ul>
		</div>
		</label>
		</div>
		<div class="form-item">
		<label class="item_name">确认密码</label><input type="password" name="password_again" class="" /><label class='form_info'>密码不一致</label><label class='null_info'>请再次确认密码</label>
		</div>
		<button type="button" id="submit">添　加</button>
	</form>
</div>
</div>
	<div id="footer">
		<p>CopyRight 　　　河南科技学院　　　 技术支持: 三月工作室</p>
	</div>
	<input class="hide" id="url" value="/NewVote/index.php/Admin/User" style="display:none">
	<input class="hide" id="root" value="/NewVote" style="display:none">
</body>
</html>