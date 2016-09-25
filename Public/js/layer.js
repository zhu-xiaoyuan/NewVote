;(function($){
	$.layer = function(options){
		var defaults = {
			width : 265,
			height : 400,
			opacity : 0.5,
			backColor : '#000',
			Title : '错误',
			Type : 'info',  // info 只有信息, errer 错误提示, notice 提醒 退出或删除,load 加载页面 自动消失
			html : '',
			autoHide : false,
			timeout : 1300,
			buttonSure: true,
			buttonCancel: true,
			buttonSureText: "确定",
			buttonCancelText: "取消",
			close : function(){
				$('.layer').fadeOut(20).remove();
			},
			sure:function(){},
			cancel:function(){}
		};
		/*初始化配置*/
		var tipHtml = '';
		var button = '';
		if(options.Type == 'load'){
			defaults.buttonSure = false;
			defaults.buttonCancel = false;
			defaults.Title = "跳转页面";
		}
		if(options.Type == 'info' || options.Type == 'error'){
			defaults.buttonSure = true;
			defaults.buttonCancel = false;
		}
		options = $.extend(defaults,options);

		/*组装html*/
		if(options.buttonSure){
			button += "<button class='tip_sure'>"+options.buttonSureText+"</button>";
		}
		if(options.buttonCancel){
			button += "<button class='tip_cancel'>"+options.buttonCancelText+"</button>";
		}
	
		if(options.Type == 'info' || options.Type == 'load'){
			tipHtml = options.html;
		}else if(options.Type == 'error'){
			tipHtml = "<span class='tip_error'></span>" + "<div class='tip_error_msg'>" + options.html + "</div>";
		}else if(options.Type == 'notice'){
			tipHtml = "<span class='tip_notice'></span>" + "<div class='tip_notice_msg'>" + options.html + "</div>";
		}

		/*清除其他layer*/
		if($('.layer')){
			$('.layer').remove();
		}
		if(st){
			clearTimeout(st);
		}

		/*插入layer*/
		$('body').prepend("<div class='layer'><div class='tip_back'></div><div class='tip'>"
			+"<p class='tip_title'>"+options.Title+"<span class='close-icon'>×</span></p>"
			+"<div class='tip_content'>"+tipHtml+"</div>"
			+"<div class='tip_button_bar'>"+button+"</div>"
			+"</div></div>");

		/*调整layer样式*/
		$('.layer').show();
		if(options.Type == 'info'){
			$('.tip').css('width',options.width + 'px');
			$('.tip').css('backgroundColor','#ececec');
			$('.tip_content').css('height',options.height+'px');
			$('.tip_back').css({opacity:options.opacity,background:options.backColor});
			$('.tip_content').addClass('tip_info_content');
			$('.tip_button_bar button').css('width','93px');
		}
		$(window).resize(function(){
			if (window.innerWidth){
				winWidth = window.innerWidth;
				winHeight = window.innerHeight;
			}
			else if ((document.body) && (document.body.clientWidth)){
				winWidth = document.body.clientWidth;
				winHeight = document.body.clientHeight;
			}
			var top = (winHeight - $('.tip').height()) / 2 + $('body')[0].scrollTop;
			if (top < 0){
				top = 0;
			}
			var left = (winWidth - options.width) / 2;
			$('.tip').css({
				top : top + 'px',
				left : left + 'px'
			});
			$('.layer').css('height',document.body.scrollHeight + 'px');
		}).resize();

		/*添加事件*/
		if(options.autoHide){
			var st = setTimeout(function(){
				$('.layer').fadeOut(300,function(){
					options.sure();
					$('.layer').remove();
				});
				clearTimeout(st);
			},options.timeout);
		}
		$('.tip_sure').click(function(){
			options.sure();
			options.close();
		});
		$('.tip_cancel').click(function(){
			options.cancel();
			options.close();
		});
		$('.close-icon').click(options.close);
	}
})(jQuery);