<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<title>无标题</title>
	<script type="text/javascript" src="/vote/Public/static/jquery-2.1.1.js"></script>
</head>
<body>
	<script type="text/javascript">
	$(function(){
		var json = {
			'vote_id' : 1,
			'token' : '202cb962ac59075b964b07152d234b70',
			'accont' : 1,
			'password' : '202cb962ac59075b964b07152d234b70'
		};
		$.ajax({
			type : 'post',
			url  : 'login',
			data : json,
			dataType : 'json',
			success : function (data){
				$('div').html(data);
			}
		});
	});
		
	</script>
</body>
<div></div>
</html>