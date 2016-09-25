<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<title>无标题</title>
	<link rel="stylesheet" type="text/css" href="/vote/Public/css/space.css">
	<script type="text/javascript" src="/vote/Public/static/jquery-2.1.1.js"></script>
	<script type="text/javascript" src="/vote/Public/js/tab.js"></script>
</head>
<body>
	<div id="left"></div>
	<div id="right"></div>
	<div id="header">
		<div id="header-icon">
			<span id="welcome">欢迎您，Admin</span>
			<span id="edit-key">修改密码</span>
			<span id="logout">退出</span>
		</div>
	</div>
	<div id="center">
		<ul id="bar">
			<li id="launch" class="tab action" >发起投票</li>
			<li id="call-out" class="tab static">唱　　票</li>
			<li id="manage" class="tab static">投票管理</li>
		</ul>
		<div id="block-box">
			<div id="tab-launch" style="display:block;">
				<form>
					<div class="input-item">
						<label for="theme">主　　题:</label>
						<input type="text" name="theme" id="theme" />
					</div>
					<div class="input-item">
						<label for="describe">描　　述:</label>
						<textarea name="describe" id="describe"></textarea>
					</div>
					<div class="input-item">
						<label for="vote-model">投票模式:</label>
						<input type="text" name="vote-model"  id="vote-model" style="display:none" />
						<div class="button-group">
							<button type="button" value="1" class="group-item left">打分制</button>
							<button type="button" value="2" class="group-item middle">选举制</button>
							<button type="button" value="3" class="group-item middle">排序制</button>
							<button type="button" value="4" class="group-item right">等级制</button>
						</div>
					</div>
					<div class="input-item">
						<label for="is_show_name">参选人员:</label>
						<div class="checkbox-bar">
							<input type="checkbox" name="is_show_name" id="is_show_name" value="1" /><span>显示姓名</span>
						</div>
						<button id="add-attend-person"></button>
					</div>
					<table  cellpadding="0" cellspacing="0">
						<tr>
							<th class="code">代号</th><th>姓名</th><th>简介</th><th>操作</th>
						</tr>
						<tr class="first-row">
							<td>代号</td><td>姓名</td><td>简介</td><td>操作</td>
						</tr>
						<tr>
							<td>代号</td><td>姓名</td><td>简介</td><td>操作</td>
						</tr>
						<tr>
							<td>代号</td><td>姓名</td><td>简介</td><td>操作</td>
						</tr>
						<tr>
							<td>代号</td><td>姓名</td><td>简介</td><td>操作</td>
						</tr>
					</table>
					<div class="input-item">
						<label for="attend_num">参评人数:</label>
						<span id="attend_num">80</span>
					</div>
					<div class="input-item">
						<label for="prepare_num">拟投人数:</label>
						<input type="text" name="prepare_num" id="prepare_num" />
					</div>
					<div class="input-item">
						<label for="jury_num">评委人数:</label>
						<input type="text" name="jury_num" id="jury_num" />
						<button id="print-jury-list"></button>
						<button id="create-jury-list"></button>
					</div>
					<table cellpadding="0" cellspacing="1">
						<tr>
							<th>账号</th><th>密码</th>
						</tr>
					</table>
					<div id="button-box">
						<button id="to-launch"></button>
						<button id="save"></button>
					</div>
					
				</form>
			</div>
			<div id="tab-call-out">
				<div id="call-out-panel">
					<div id="num-panel">
						<label>已投票评委数</label>
						<span class="show-num">20</span>
						<button id="refresh"></button>
					</div>
					<div id="status-panel">
						<p>主　　题:　<a href="">软件学院教师职称评选</a>
						<p>参选人数:　<span>20</span></p>
						<p>拟投人数:　<span>20</span></p>
						<p>评委人数:　<span>20</span></p>
						<p>投票状态:　<span>正在投票</span><button id="finish"></button></p>
					</div>
				</div>
					<button id="rebowl"></button>
					<button id="check-connection"></button>
					<table  cellpadding="0" cellspacing="1">
						<tr>
							<th>排名</th><th>平均分</th><th>代号</th><th>姓名</th><th>简介</th>
						</tr>
					</table>
			</div>
			<div id="tab-manage">投票管理
				<form id="search-form">
					<div class="search-item">
						<label for="search-date">发起日期:</label>
						<input type="textarea" name="search-date-start" id="search-date-start" />
						<label>到</label>
						<input type="textarea" name="search-date-end" id="search-date-end" />
					</div>
					<div class="search-item">
						<label for="search-state">　　状态:</label>
						<select name="search-state" id="search-state">
							<option value="">===全部===</option>
							<option value="">待投票</option>
							<option value="">正在投票</option>
							<option value="">投票结束</option>
							<option value="">已关闭</option>
						</select>
					</div>
					<div class="search-item">
						<label for="search-theme">　　主题:</label>
						<input type="text" name="search-theme" id="search-theme" />
					</div>
					<button id="search">搜索</button>
				</form>
				<table  ellpadding="0" cellspacing="1">
					<tr>
						<th>序号</th><th>主题</th><th>创建时间</th><th>状态</th><th>操作</th>
					</tr>
				</table>
			</div>
	</div>
	<div id="footer">
		<p>CopyRight 河南科技学院 技术支持: 三月软件工作室</p>
	</div>
</div>
<div id="footer"></div>
</body>
</html>