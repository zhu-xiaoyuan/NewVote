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

			<?php if($login): if($admin): ?><p id="login-status" value="<?php echo ($login); ?>">
						<span id="add_user" title="添加用户"></span>
						<span id="logout" title="退出"></span>
				<?php else: ?>
					<p id="login-status" value="<?php echo ($login); ?>">
						<span id="logout" title="退出"></span><?php endif; ?>
			<?php else: ?>
			<p id="login-status" value="0">
				<span id="login">登录</span><?php endif; ?>
			</p>
			<li class="ip static">IP 地址 : <?php echo ($ip_address); ?></li>
		
	</div>
	<div id="center">
<div class="main">
	<div class="theme">
		<h2>用户管理</h2>
	</div>
	<div class="include">
	<div class="menu">
		<label class="operate">批量操作</label><button class="fun">删 除</button>
	</div>
	<table>
		<thead><tr><th class="user_1"><input type="checkbox" name="all"></th><th class="user_2">用户名</th><th class="user_3">操作</th></tr></thead>
		<tbody>
		<?php if(is_array($user_list)): $k = 0; $__LIST__ = $user_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr><td><input type="checkbox" name="item" value="<?php echo ($vo["id"]); ?>"></td><td class="user_2"><?php echo ($vo["account"]); ?></td><td><a class="del_icon" data='<?php echo ($vo["id"]); ?>'></a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</tbody>
	</table>
	</div>
</div>
</div>
	<div id="footer">
		<p>CopyRight 　　　河南科技学院　　　 技术支持: 三月工作室</p>
	</div>
	<input class="hide" id="url" value="/NewVote/index.php/Admin/User" style="display:none">
	<input class="hide" id="root" value="/NewVote" style="display:none">
</body>
</html>