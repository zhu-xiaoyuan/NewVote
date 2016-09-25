$(function(){
	$('#submit').click(function(){
		var form = $("form")[0];
		var success = true;
		for(var i = 0; i < form.length-1;i ++){
			if(form[i].value == ''){
				$(form[i]).addClass('input_error');
				$(form[i]).nextAll('.null_info').css('display','inline-block').removeClass('item_success').addClass('item_error');
				success = false;
			}
		}
		if($('.input_error').length > 0) success = false;
		if(!success)return;
		$.post($('#url').val() + "/insert_user",{account:$('input[name=account]').val(),password:$.md5($('input[name=password]').val())},function(data){
			if(!data)return;
			data = eval("("+data+")");
			if(data.code == 0){
				$.layer({Type:'load',Title:"信息",autoHide:true,html:data.msg,sure:function(){
					$('input').val("");
					$('.null_info').removeClass('item_error').removeClass('item_success').hide();
				}});
			}else{
				$.layer({Type:'error',Title:"失败",html:data.msg});
			}
		})
	});
	$('input').on('focus',function(){
		$(this).removeClass('input_error');
		if($(this).val() == ''){
			$(this).nextAll('.null_info').css('display','inline-block')
							.removeClass('item_error')
							.removeClass('item_success');
		}
	});
	$('input').on('blur',function(){
		if($(this).val() == '')
		$(this).nextAll('label').hide();
	});

	/*用户名*/
	$('input[name=account]').on('focus',function(){
		$('.form_info_1').hide();
	});
	$('input[name=account]').on('input',function(){
		var val = $(this).val();
		if(val.length > 12){
			$(this).val(val.slice(0,12));
			$(this).nextAll('.form_info').css('display','inline-block').removeClass('item_success').addClass('item_error');
		}else{
			$(this).nextAll('.form_info').css('display','inline-block').removeClass('item_error').removeClass('item_success');
		}
	});
	if(document.all){
	    $('input[name=account]').each(function() {
	        var that=this;
	        if(this.attachEvent) {
	            this.attachEvent('onpropertychange',function(e) {
	                if(e.propertyName!='value') return;
	                var val = $(that).val();
					if(val.length > 12){
						$(that).val(val.slice(0,12));
						$(that).nextAll('.form_info').css('display','inline-block').removeClass('item_success').addClass('item_error');
					}else{
						$(that).nextAll('.form_info').css('display','inline-block').removeClass('item_error').removeClass('item_success');
					}
	            });
	        }
	    })
	}
	$('input[name=account]').on('blur',function(){
		var val = $(this).val();
		if(val == ''){
			return;
		}
		if(val.length > 12 || val.length < 5){
			$(this).addClass('input_error');
			$(this).nextAll('.form_info').css('display','inline-block').removeClass('item_success').addClass('item_error');
		}else{
			var that = this;
			$.post($('#url').val() + "/same_account",{account:$('input[name=account]').val()},function(data){
				if(data == '0'){
					$(that).addClass('input_error');
					$(that).nextAll('.form_info').hide();
					$(that).nextAll('.form_info_1').css('display','inline-block').removeClass('item_success').addClass('item_error');
				}else{
					$(that).removeClass('input_error');
					$(that).nextAll('.form_info').removeClass('item_error').addClass('item_success');
				}
			})
		}
	});

	/*再次输入密码*/
	$('input[name=password_again]').on('input',function(){
		var regx = new RegExp('^' + $(this).val());
		if(!regx.test( $('input[name=password]').val())){
			$(this).nextAll('.null_info').hide();
			$(this).nextAll('.form_info').css('display','inline-block').removeClass('item_success').addClass('item_error');
		}else{
			$(this).nextAll('.form_info').hide();
			$(this).nextAll('.null_info').css('display','inline-block').removeClass('item_success').removeClass('item_error');
		}
	});
	$('input[name=password_again]').on('blur',function(){
		if($(this).val() == '')return;
		if($(this).val() != $('input[name=password]').val()){
			$(this).addClass('input_error');
			$(this).nextAll('.form_info').removeClass('item_success').addClass('item_error');
		}else{
			$(this).removeClass('input_error');
			$(this).nextAll('.null_info').hide();
			$(this).nextAll('.form_info').css('display','inline-block').removeClass('item_error').addClass('item_success');
		}
	});
	if(document.all){
	    $('input[name=password_again]').each(function() {
	        var that=this;
	        if(this.attachEvent) {
	            this.attachEvent('onpropertychange',function(e) {
	                if(e.propertyName!='value') return;
	               var regx = new RegExp('^' + $(this).val());
					if(!regx.test( $('input[name=password]').val())){
						$(this).nextAll('.null_info').hide();
						$(this).nextAll('.form_info').css('display','inline-block').removeClass('item_success').addClass('item_error');
					}else{
						$(this).nextAll('.form_info').hide();
						$(this).nextAll('.null_info').css('display','inline-block').removeClass('item_success').removeClass('item_error');
					}
	            });
	        }
	    })
	}
	/*密码*/
	$('input[name=password]').on('focus',function(){
		$(this).nextAll('.form_info').hide();
		if($(this).val() == ''){
			$('.check_bar li').removeClass('ul_item_success').removeClass('ul_item_error');
		}
		$(this).nextAll('.form_info_block').css('display','inline-block');
	});
	$('input[name=password]').on('input',function(){
		var val = $(this).val();
		if(val.length < 5){
			$('.check_bar_1').removeClass('ul_item_success').addClass('ul_item_error');
		}else if(val.length > 14){
			$(this).val(val.slice(0,14));
			$('.check_bar_1').removeClass('ul_item_error').addClass('ul_item_success');
		}else{
			$('.check_bar_1').removeClass('ul_item_error').addClass('ul_item_success');
		}
		var regx = /^\w+$/;
		if(regx.test(val)){
			$('.check_bar_2').removeClass('ul_item_error').addClass('ul_item_success');
		}else{
			$('.check_bar_2').removeClass('ul_item_success').addClass('ul_item_error');
		}
		regx = /\s/;
		if(regx.test(val)){
			$('.check_bar_3').removeClass('ul_item_success').addClass('ul_item_error');
		}else{
			$('.check_bar_3').removeClass('ul_item_error').addClass('ul_item_success');
		}
	});
	$('input[name=password]').on('blur',function(){
		if($(this).val() == '')return;
		if($('.ul_item_success').length == 3){
			$(this).nextAll('.form_info_block').hide();
			$(this).nextAll('.form_info').css('display',"inline-block").addClass('item_success');
		}else{
			$(this).addClass('input_error');
			$(this).nextAll('.form_info').hide();
		}
	});
	if(document.all){
	    $('input[name=password]').each(function() {
	        var that=this;
	        if(this.attachEvent) {
	            this.attachEvent('onpropertychange',function(e) {
	                if(e.propertyName!='value') return;
	                var val = $(that).val();
					if(val.length < 5){
						$('.check_bar_1').removeClass('ul_item_success').addClass('ul_item_error');
					}else if(val.length > 14){
						$(that).val(val.slice(0,14));
						$('.check_bar_1').removeClass('ul_item_error').addClass('ul_item_success');
					}else{
						$('.check_bar_1').removeClass('ul_item_error').addClass('ul_item_success');
					}
					var regx = /^\w+$/;
					if(regx.test(val)){
						$('.check_bar_2').removeClass('ul_item_error').addClass('ul_item_success');
					}else{
						$('.check_bar_2').removeClass('ul_item_success').addClass('ul_item_error');
					}
					regx = /\s*/g;
					if(regx.test(val)){
						$('.check_bar_3').removeClass('ul_item_success').addClass('ul_item_error');
					}else{
						$('.check_bar_3').removeClass('ul_item_error').addClass('ul_item_success');
					}
	            });
	        }
	    })
	}

	/*用户管理*/
	$('tr:even').addClass('even');
	$('table').page();
	$('.del_icon').click(function(){
		var that = this;
		$.post($('#url').val() + "/del_user",{data:$(this).attr('data')},function(data){
			if(!data)return;
			data = eval("("+data+")");
			if(data.code == 0){
				$.layer({Type:'load',Title:"信息",autoHide:true,html:data.msg,sure:function(){
					$(that).parent().parent().remove();
					$('table').page('create_page');
					$('.even').removeClass('even');
					$('tr:even').addClass('even');
				}});
			}else{
				$.layer({Type:'error',Title:"失败",html:data.msg});
			}
		})
	});
	$('.fun').click(function(){
		var data="";
		var items = $('input[name=item]');
		for(var i = 0 ; i < items.length; i ++){
			if(items[i].checked == true) data += items[i].value + ",";
		}
		$.post($('#url').val() + "/del_user",{data:data.slice(0,data.length-1)},function(data){
			if(!data)return;
			data = eval("("+data+")");
			if(data.code == 0){
				$.layer({Type:'load',Title:"信息",autoHide:true,html:data.msg,sure:function(){
					$('input[name=item]:checked').parent().parent().remove();
					$('table').page('create_page');
					$('.even').removeClass('even');
					$('tr:even').addClass('even');
				}});
			}else{
				$.layer({Type:'error',Title:"失败",html:data.msg});
			}
		})
	});
	$('input[name=all]').on('change',function(){
		if($(this).is(':checked')){
			var items = $('input[name=item]');
			for(var i = 0 ; i < items.length; i ++){
				items[i].checked = true;
			}
		}else{
			$('input[name=item]').attr("checked",false);
		}
	});
	$('input[name=item]').on('change',function(){
		if(!$(this).is(':checked')){
			$('input[name=all]').attr("checked",false);
		}
		var items = $('input[name=item]');
		var flag = true;
		for(var i = 0 ; i < items.length; i ++){
			if(items[i].checked == false){
				flag = false;
				break;
			}
		}
		if(flag) $('input[name=all]')[0].checked = true;
	});
	

});