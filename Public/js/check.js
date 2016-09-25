$(function(){
	/*输入检验*/
	bindinput('#theme',function(){
		return function(){
			var val = $(this).val();
			if(val.length > 30){
				$(this).val(val.slice(0,30));
				$(this).next(".notice_label").css('color','#fc4343');
			}
		}
	});
	$('#theme').blur(function(){
		var val = $(this).val();
		if(val == ''){
            return;
        }
		var regx = /(^\s*)|(\s*$)/g;
		val = val.replace(regx,'');
		if(val == ''){
			$.layer({Type:'error',html :'标题不能为空',sure:function(that){
				return function(){
					$(that).val('').focus();
				}
			}(this)});
			return;
		}
		if(val.length > 30){
			$.layer({Type:'error',html :'标题不能超过30个字',sure:function(that,v){
				return function(){
					v = v.slice(0,30);
					$(that).val(v).focus();
				}
			}(this,val)});
			return;
		}
		$(this).next(".notice_label").css('color','#999');
		$(this).val(val);
	});
	bindinput('#describe',function(){
		return function(){
			var val = $(this).val();
			if(val.length > 200){
				$(this).val(val.slice(0,200));
				$(this).next(".notice_label").css('color','#fc4343');
			}
		}
	});
	$('#describe').blur(function(){
		var val = $(this).val();
		if(val == ''){
            return;
        }
		var regx = /(\s*$)/;
		val = val.replace(regx,'');
		if(val.length > 200){
			$.layer({Type:'error',html :'描述不能超过200个字',sure:function(that,v){
				return function(){
					v = v.slice(0,200);
					$(that).val(v).focus();
				}
			}(this,val)});
			return;
		}
		if(val == ''){
			$.layer({Type:'error',html :'描述不能为空',sure:function(that){
				return function(){
					$(that).val('').focus();
				}
			}(this)});
			return;
		}
		$(this).next(".notice_label").css('color','#999');
		$(this).val(val);
	});
	bindinput('textarea[name=introduce]',function(){
		return function(){
			var val = $(this).val();
			if(val.length > 200){
				$(this).val(val.slice(0,200));
				$(".warning").text("*简介不能超过200个字");
			}
		}
	});
	$('textarea[name=introduce]').blur(function(){
		var val = $(this).val();
		if(val == ''){
            return;
        }
		var regx = /(\s*$)/g;
		val = val.replace(regx,'');
		if(val == ''){
			$.layer({Type:'error',html :'简介不能为空',sure:function(that){
				return function(){
					$(that).val('').focus();
				}
			}(this)});
			return;
		}
		$(this).val(val);
	});
	bindinput('input[name=code]',function(){
		return function(){
			var val = $(this).val();
			if(val.length > 8){
				$(this).val(val.slice(0,8));
				$(".warning").text("*代号不能超过8个字符");
			}
		}
	});
	$('input[name=code]').blur(function(){
		var val = $(this).val();
		if(val == ''){
            return;
        }
		var regx = /(^\s*)|(\s*$)/g;
		val = val.replace(regx,'');
		regx = /^[0-9a-zA-Z]+$/;
		if(!regx.test(val)){
			$.layer({Type:'error',html :'代号由数字和字母组成',sure:function(that){
				return function(){
					$(that).val('').focus();
				}
			}(this)});
			return;
		}
		$(this).val(val);
	});
	bindinput('input[name=name]',function(){
		return function(){
			var val = $(this).val();
			if(val.length > 6){
				$(this).val(val.slice(0,6));
				$(".warning").text("*姓名不能超过6个字符");
			}
		}
	});
	
	
	$('input[name=is_dislodge]').change(function(){
		if($(this).val() == '1')
		if($('#jury_num').val() != ''){
			var val = parseInt($('#jury_num').val());
			if(val <= 2){
				$.layer({Type:'error',html :'去掉最高分最低分时评委人数应大于二',sure:function(that){
					return function(){
						$('#jury_num').val('').focus();
					}
				}(this)});
			}
		}
	});
	
	
	$('#prepare_num').change(function(){
		var val = $(this).val();
		if(val == ''){
            return;
        }
		var regx = /^[0-9]*$/;
		if(!regx.test(val)){
			$.layer({Type:'error',html :'输入不合法',sure:function(that){
				return function(){
					$(that).val('').focus();
				}
			}(this)});
			return;
		}
		var data = parseInt($(this).val());
		var val = parseInt($('#attend_num').val());
		if(data > val){
			$.layer({Type:'error',html :'拟投人数应小于参评人数',sure:function(that){
				return function(){
					$(that).val('').focus();
				}
			}(this)});
		}
	});
	$('#jury_num').change(function(){
		var val = $(this).val();
		if(val == ''){
            return;
        }
		var regx = /^[0-9]*$/;
		if(!regx.test(val)){
			$.layer({Type:'error',html :'输入不合法',sure:function(that){
				return function(){
					$(that).val('').focus();
				}
			}(this)});
			return;
		}
		if($("input[name=vote_type]:checked").val() == '1'){
			if($("input[name=is_dislodge]:checked").val() == '1'){
				if(parseInt($(this).val()) <= 2){
					$.layer({Type:'error',html :'去掉最高分最低分时评委人数应大于二',sure:function(that){
						return function(){
							$(that).val('').focus();
						}
					}(this)});
				}
			}
		}
	});
});
function bindinput(name,fun){
	$(name).on('input',fun());
	if(document.all){
	    $(name).each(function() {
	        var that=this;
	        if(this.attachEvent) {
	            this.attachEvent('onpropertychange',function(e) {
	                if(e.propertyName!='value') return;
	                fun().apply(that);
	            });
	        }
	    })
	}
}