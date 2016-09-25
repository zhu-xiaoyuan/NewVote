$(function(){
	$('#login').click(function(){
		document.body.onclick = function(e){
	         e = e || window.event;
	         var target = e.target || e.srcElement;
	         if($(target).attr('id') == "login"){
	         	return;
	         }
	         var minh = $('#header').height()-document.body.scrollTop;
	         var maxh = $('#header').height() + $('#login_bar').height()-document.body.scrollTop;
	         var minw = $(document.body).width() - $('#login_bar').width();
	         var maxw = $(document.body).width();
	         if(e.clientX < minw || e.clientX > maxw|| e.clientY > maxh ||e.clientY < minh){
	         	$('#login_bar').hide();
	         	document.body.onclick = function(e){};
	         	$(window).unbind('keypress');
	         	return;
	         }
	    };
	    $('#login_bar').show();
	    $(window).bind('keypress',function(){
	    	if(event.keyCode == 13)
	    	$('#login_submit').click();
	    });
	});
	$('#add_user').click(function(){
		window.location.href = $('#url').val() + "/../User/add_user.html";
	});
	
	/*登录*/
	$('#login_submit').click(function(){
		var url = $('#url').val()+'/../Login/login';
		var data ={
			account : $('input[name=login_name]').val(),
			password : $.md5($('input[name=login_password]').val())
		}
		$.post(url,data,function(data){
			if(!data)return;
			data = eval("("+data+")");
			//console.log(data);
			if(data.code == 0){
				window.location.reload();
			}else{
				$.layer({Type:'error',html :data.msg});
				$('input[name=login_password]').val('');
			}   
		})
	});

	/*登出*/
	$('#logout').click(function(){
		$.layer({Type:'notice',html:"<p style='margin:10px 0px'>是否确定退出</p>",
			Title:'退出',buttonSureText:"是",buttonCancelText:"否",
			sure:function(){
			var url = $('#url').val()+'/../Login/logout';
			$.post(url,null,function(data){
				if(!data)return;
				data = eval("("+data+")");
				if(data.code == 0){
					window.location =  $('#url').val()+'/../index/index.html';
				}else{
					$.layer({Type:'error',html :data.msg});
					$('input[name=password]').val('');
				}
				
			});
		}});
		
	});
	/*弹出层位置*/
	$(window).resize(function(){
		
		if (window.innerWidth)
			winWidth = window.innerWidth;
		else if ((document.body) && (document.body.clientWidth))
			winWidth = document.body.clientWidth;
		if (window.innerHeight)
			winHeight = window.innerHeight;
		else if ((document.body) && (document.body.clientHeight))
			winHeight = document.body.clientHeight;
		var top = (winHeight - $('.dialog').height()) / 2 + $('body')[0].scrollTop;
			if (top < 0){
				top = 0;
			}
			var left = (winWidth - $('.dialog').width()) / 2;
		$('.dialog').css({
			"top" :top,
			"left" :left
		});
		var winW = $(window).width();
		$('.layout').css({
			"width":winW,
			"height":document.body.scrollHeight + 'px'
		});
		var mHeigth = winHeight - $('#footer').height() - $('#header').height() - 115;
		
		if($('#center').height() < mHeigth){
			mHeigth = mHeigth + 'px';
			$('#center').css('min-height',mHeigth);
		}
	}).resize();
	
});



