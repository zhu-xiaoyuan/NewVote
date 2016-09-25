<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<title>投票管理</title>
	<link rel="stylesheet" type="text/css" href="/NewVote/Public/css/space.css">
	<link rel="stylesheet" type="text/css" href="/NewVote/Public/css/layer.css">
	<link rel="stylesheet" type="text/css" href="/NewVote/Public/css/page.css">
	<script type="text/javascript" src="/NewVote/Public/static/jquery-1.11.js"></script>
	<script type="text/javascript" src="/NewVote/Public/static/md5.js"></script>
	<script type="text/javascript" src="/NewVote/Public/js/layer.js"></script>
	<script type="text/javascript" src="/NewVote/Public/js/page2.js"></script>
	<script type="text/javascript" src="/NewVote/Public/js/tab.js"></script>
	
	
	<link href="/NewVote/Public/static/datetimepicker/css/datetimepicker.css" rel="stylesheet" type="text/css">
    <link href="/NewVote/Public/static/datetimepicker/css/dropdown.css" rel="stylesheet" type="text/css">
    <link href="/NewVote/Public/css/manage.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="/NewVote/Public/static/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="/NewVote/Public/static/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
    <script type="text/javascript" src="/NewVote/Public/js/time.js"></script>
    <script type="text/javascript" src="/NewVote/Public/js/manage.js"></script>

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
		<li id="manage" class="tab action"><a href="<?php echo U('Manage/index');?>">投票管理</a></li>
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
<div id="search_panel">
	<form id="search-form" action="<?php echo U('Manage/search');?>">
		<div class="search-item">
			<label for="search-date">发起时间:</label>
			<input type="text" name="search_date_start" id="search-date-start" value="<?php echo ($search_info["search_date_start"]); ?>" /><img class="calendar" src="/NewVote/Public/image/calendar.png">
			<label id="to">至</label><input type="text" name="search_date_end" id="search-date-end" value="<?php echo ($search_info["search_date_end"]); ?>" /><img class="calendar" src="/NewVote/Public/image/calendar.png">
		</div>
		<div class="search-item">

			<label for="search_state">状　　态:</label>
			<div class="ui">
			<span class='ui-select'>全部</span><span class='ui-down'></span>
			<ul class="ui-options">
				<li class="ui-option-item" for="0">全部</li>
				<li class="ui-option-item" for="1">正在投票</li>
				<li class="ui-option-item" for="2">待投票</li>
				<li class="ui-option-item" for="3">投票结束</li>
				<li class="ui-option-item" for="4">已关闭</li>
			</ul></div>
			<select name="search_state" id="search-state">
				<?php if($search_info["search_state"] == 0): ?><option value="0" selected='selected'>全　　部</option>
				<?php else: ?>
					<option value="0">全　　部</option><?php endif; ?>
				<?php if($search_info["search_state"] == 1): ?><option value="1"   selected='selected'>正在投票</option>
				<?php else: ?>
					<option value="1">正在投票</option><?php endif; ?>
				<?php if($search_info["search_state"] == '2'): ?><option value="2"  selected='selected'>待 投 票</option>
				<?php else: ?>
					<option value="2">待 投 票</option><?php endif; ?>
				<?php if($search_info["search_state"] == 3): ?><option value="3"  selected='selected'>投票结束</option>
				<?php else: ?>
					<option value="3">投票结束</option><?php endif; ?>
				<?php if($search_info["search_state"] == 4): ?><option value="4"  selected='selected'>已 关 闭</option>
				<?php else: ?>
					<option value="4">已 关 闭</option><?php endif; ?>
			</select>
		</div>
		<div class="search-item">
			<label for="search_theme">主　　题:</label>
			<input type="text" name="search_theme" id="search-theme" value="<?php echo ($search_info["search_theme"]); ?>" />
			<!-- <input type="text" name="search_theme" id="search-theme" /> -->
			<button id="search">搜索</button>
		</div>
		
	</form>
</div >
	<table  ellpadding="0" cellspacing="0">
		<tr>
			<th id="vote_1">序号</th><th id="vote_2">主题</th><th id="vote_3">发起时间</th><th id="vote_3">状态</th><th id="vote_3">操作</th>
		</tr>
		<?php if($login): if(is_array($vote_list)): $k = 0; $__LIST__ = $vote_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr value="<?php echo ($vo["id"]); ?>">
				<td><?php echo ($k); ?></td><td><a class="show_info"><?php echo ($vo["theme"]); ?></a></td>
				<?php if($vo['start_time']): ?><td><?php echo (date("Y-m-d",$vo["start_time"])); ?></td>
				<?php else: ?><td>未发起</td><?php endif; ?>
				<?php switch($vo["status"]): case "1": ?><td>正在投票</td><td><a  url="<?php echo U('Manage/changeStatus');?>"  class='operate-close-vote' onclick="changeStatus(this,4)">关闭</a></td><?php break;?>
				    <?php case "2": ?><td>待投票</td>
				    	<td>
					    	<a url="<?php echo U('Manage/changeStatus');?>" class='operate-open-vote' onclick="changeStatus(this,1)">启动</a>
					    	<a class='operate-edit-vote' href=<?php echo U("index/index",array('id'=>$vo['id'])); ?>>编辑</a>
					    	<a  url="<?php echo U('Manage/changeStatus');?>" class='operate-close-vote' onclick="changeStatus(this,4)">关闭</a>
				    	</td><?php break;?>
				    <?php case "3": ?><td>投票结束</td><td><a url="<?php echo U('Manage/rebowl');?>" class='operate-rebowl-vote'  onclick="rebowl(this,<?php echo ($vo["id"]); ?>)">重投</a></td><?php break;?>
				    <?php case "4": ?><td>已关闭</td><td><a url="<?php echo U('Manage/changeStatus');?>" class='operate-open-vote' onclick="changeStatus(this,2)">启动</a></td><?php break;?>
				    <?php default: endswitch;?>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		<?php else: ?>
		<tr><div>登录查看信息</div></tr><?php endif; ?>
	</table>
	<div class="page">
		<?php echo ($page); ?>
	</div>
</div>
	<div id="footer">
		<p>CopyRight 　　　河南科技学院　　　 技术支持: 三月工作室</p>
	</div>
	<input class="hide" id="url" value="/NewVote/index.php/Admin/Manage" style="display:none">
	<input class="hide" id="root" value="/NewVote" style="display:none">
</body>
</html>