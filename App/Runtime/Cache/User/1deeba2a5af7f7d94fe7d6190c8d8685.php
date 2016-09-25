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
			'vote_id' : 0,
			'token' : '202cb962ac59075b964b07152d234b70',
			'vote_type' : 3,
			'result' : '5,6,8,7,4,2,3,1'
		};
		$.ajax({
			type : 'post',
			url  : '/vote/index.php/User/Submit/submit',
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