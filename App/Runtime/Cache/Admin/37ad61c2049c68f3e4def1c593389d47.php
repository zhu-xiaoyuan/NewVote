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
	
	
	<link rel="stylesheet" type="text/css" href="/NewVote/Public/css/user.css">
	<script type="text/javascript" src="/NewVote/Public/js/user.js"></script>
	<style type="text/css">
	.help{display: none}
	.include{text-align: left;}
	.section{margin: 20px 0px;}
	h7{display: block;margin-bottom: 15px;font-size: 18px;font-family: "微软雅黑";}
	h8{display: block;padding-left: 20px;margin-bottom: 10px;}
	h9{display: block;padding-left: 40px;margin-bottom: 10px;}
	</style>

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
	<div class="theme">
		<h2>使用说明</h2>
	</div>
	<div class="include">
		<div class="section">
			<h7>一、登录：在右上角的位置点击”登录”，填写用户名和密码，点击登录按钮或者敲击回车。</h7>
			<h8>1. 登陆后可添加或发起投票、查看和操作正在经行的投票、管理自己所发起的投票</h8>
			<h8>2. 管理员(admin)登录后可以添加和管理其他用户</h8>
		</div>
		<div class="section">
			<h7>二、发起投票：在发起投票页面可以添加新的投票，填写信息时注意</h7>
			<h8>1. 主题不能超过30个字</h8>
			<h8>2. 描述不超过200个字</h8>
			<h8>3. 点击“添加”按钮，添加参选人。其中‘代号’为数字和字母的组合</h8>
			<h8>4. 拟投人数不能超过参选人数</h8>
			<h8>5. 评委人数 在打分制并选择去掉最高分最低分时要大于2</h8>
			<h8>6. 填写诶评委人数后点击生成会自动生成账号和密码</h8>
			<h8>7. 导出可生成相应的Excel文档</h8>
			<h8>8. 可以直接发起投票也可以先保存为待投票项目，如果点击发起按钮时有正在投票项目，系统会自动为您转为待投票项目。</h8>
		</div>
		<div class="section">
			<h7>三、唱票：实时展示正在投票的结果，所用评委打完分后可以完成投票生成结果</h7>
			<h8>1. 当所有评委都投过票之后才能经行操作</h8>
			<h8>2. 点击“检查排名是否相同”按钮会标记出有矛盾的结果（无效结果）</h8>
			<h8>3. 检查排名是否相同 之后若有无效结果可重投，系统会自动算出重投时的人员和拟选	人数，这时的重投针对个别无效结果，产生本次投票的个别有效结果。</h8>
			<h8>4. 点击“完成”按钮可结束投票，在投票管理中查看结果。存在无效结果时也可强制完	成投票。</h8>
		</div>
		<div class="section">
			<h7>四、投票管理：搜索用户自己添加的投票，查看投票的详细信息和结果，操作投票项目的状态</h7>
			<h8>1. 输入搜索条件，点击“搜索”按钮可组合查询</h8>
			<h8>2. 点击投票主题可查看投票的具体信息，如果投票处于投票结束状态时可查看其结果</h8>
			<h8>3. 状态转换：</h8>
			<h9>1）保存一次投票后，投票处于“待投票”状态。“待投票”状态下可编辑投票信息，可发起或关闭投票</h9>
			<h9>2）发起投票或重投后，投票处于“正在投票”状态，可在唱票页面查看过程，可点“关闭”停止投票，但不产生结果</h9>
			<h9>3）处于“正在投票”的投票在唱票页面正常完成后，投票处于“投票结束”状态，这时可查看其投票结果，也可重投。这时的重投针对所有参评人员，产生新的一轮投票结果。</h9>
			<h9>4）“待投票”，“正在投票”状态时都可点击“关闭”，投票将进入“关闭”状态。此时可点击“开启”，投票进入“待投票”状态。</h9>
		</div>
		<div class="section">
			<h7>五、用户管理：admin可进行用户管理。</h7>
			<h8>1. 点击右上角的 <img style="vertical-align: middle" src="/NewVote/Public/image/add_user.png"> 按钮可添加用户。</h8>
			<h8>2. 在添加用户界面可点击“用户管理”，该界面可删除用户。</h8>
		</div>
	</div>
</div>
</div>
	<div id="footer">
		<p>CopyRight 　　　河南科技学院　　　 技术支持: 三月工作室</p>
	</div>
	<input class="hide" id="url" value="/NewVote/index.php/Admin/Index" style="display:none">
	<input class="hide" id="root" value="/NewVote" style="display:none">
</body>
</html>