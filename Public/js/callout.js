var had_complete = 0;
var had_check = 0;
var had_valid = 0;
var timer;
$(function(){
	if($('#login-status').attr('value') != '0'){
		if($('#vote_id').val() == ''){
			$.layer({html : '没有正在投票项目',Type:'error'});
			return;
		}
		$("#result").page();
		first_request_data();
		
		setTimeout(function(){
		 	timer = setInterval("request_data()",1000);
		},1000);
	}
	
	
	$('#refresh').click(function(){
		if($('#login-status').attr('value') == '0'){
			$.layer({html : '请先登录'});
			return;
		}
		request_data();
	});
	$('#finish').click(function(){
		if($('#login-status').attr('value') == '0'){
			$.layer({Type:'error',html :'请先登录'});
			return;
		}
		if(had_complete){
			if(had_check){
				if(had_valid)
					complete();
				else
					$.layer({Type:'error',Title:"提示",buttonCancel:true,html :'存在无效结果,是否确定完成投票',sure:complete});
			}else{
				$.layer({Type:'error',Title:"提示",buttonCancel:true,html :'还未检查结果有效性,是否确定完成投票',sure:complete});
			}
		}else{
			$.layer({Type:'error',html :'请等待评委投票结束'});
		}
	});
	$('#check-connection').click(function(){
		if($('#login-status').attr('value') == '0'){
			$.layer({Type:'error',html :'请先登录'});
			return;
		}
		if(had_complete){
			if(!mark()){
				had_valid = 1;
			}
			else {
				$.layer({Type:'error',Title:'重投',autoHide:true,html :'存在无效结果,请重投'});
			}
			had_check = 1;
		}else{
			$.layer({Type:'error',html :'请等待评委投票结束'});
		}
	});
	$('#rebowl').click(function(){
		if($('#login-status').attr('value') == '0'){
			$.layer({Type:'error',html :'请先登录'});
			return;
		}
		if(!had_check){
			$.layer({Type:'error',Tilte:"提示",html :'请先检查结果有效性'});
			return;
		}
		if(had_valid){
			$.layer({Type:'error',Tilte:"提示",html :'结果有效,不须重投'});
			return;
		}
		var bar =  document.getElementById('revote_bar');
		var top = this.offsetTop + this.offsetHeight;
		$(bar).show();
		var left = this.offsetLeft + this.offsetWidth - bar.offsetWidth;
		$(bar).css({'top':top,'left':left});
	});
	$('#cancel_revote').click(function(){
		$('#revote_bar').hide();
	});
	$('#sure_revote').click(function(){
		if($('#login-status').attr('value') == '0'){
			$.layer({Type:'error',html :'请先登录'});
			return;
		}
		if(!had_check){
			$.layer({Type:'error',Tilte:"提示",html :'请先检查结果有效性'});
			return;
		}
		if(had_valid){
			$.layer({Type:'error',Tilte:"提示",html :'结果有效,不须重投'});
			return;
		}
		var data = [];
		var valid_attend_list = $('input[name=choose]').not(':checked');
		for(var i = 0 ; i < valid_attend_list.length; i ++){
			data[i] = valid_attend_list[i].value;
		}
		var result =  getValidInfo();
		var table = document.getElementById("result");
		var sort_num = table.rows[table.rows.length - 1].cells[1].innerHTML;
		
		$.ajax({
			type: 'post',
			url:$('#url').val()+'/revote',
			data:{
				vote_id:$('#vote_id').val(),
				valid_attend_list:data,
				result:result,
				sort_num:sort_num
			},
			success : function(data){
				data = eval("("+data+")");
				if(data.code == 0){
					revote(data['info']);
				}else{
					$.layer({Type:'error',html :data.msg});
				}
			}
		});
		$('#revote_bar').hide();
		had_check = 0;
		had_complete = 0;
	});
});
function revote(data){
	$('.show-num').text(0);
	$('#jury_revote_num').text('第'+data+'次重投');
	$('#prepare_num').text($('#revote_prepare_num').text());
	$('#attend_num').text($('#revote_attend_num').text());
	first_request_data();
	
	setTimeout(function(){
	 	timer = setInterval("request_data()",1000);
	},1000);
}
function mark(){
	var table = document.getElementById('result');
	var rows = table.rows;
	var prepare = parseInt($('#prepare_num').text());
	var start = 0;
	var end = rows.length;
	var flag = rows[prepare].cells[2].innerHTML;
	for(var i = 0; i < rows.length ; i ++){
		if(rows[i].cells[2].innerHTML == flag){
			start = i;
			break;
		}
	}
	for(i ++;i < rows.length ; i ++){
		if(rows[i].cells[2].innerHTML != flag){
			end = i;
			break;
		}
	}
	if(end <= prepare + 1){
		$.layer({Type:'error',html :'结果有效,不须重投'});
		return false;
	}
	for(i = start; i < end ; i ++){
		rows[i].cells[0].childNodes[0].checked = 'checked';
		$(rows[i]).addClass('mark');
	}
	$('#revote_prepare_num')[0].innerHTML = prepare - start + 1;
	$('#revote_attend_num')[0].innerHTML = end - start;
	return true;
}
function complete(){
	var data = getValidInfo();
	var table = document.getElementById("result");
	var sort_num = table.rows[table.rows.length - 1].cells[1].innerHTML;
	$.ajax({
		url : $('#url').val()+'/complete',
		type : 'post',
		data : {
			vote_id : $('#vote_id').val(),
			data : data,
			sort_num : sort_num 
		},
		success: function(data){
			data = eval("("+data+")");
			$.layer({Type:'load',Title:"跳转页面",html:data.msg,autoHide:true,sure: function(){
				if(data.code == 0){
					window.location.href = $('#url').val() + "/../Manage";
				}
			}});
		}
	});
}
function getValidInfo(){
	var data=[];
	var table = document.getElementById('result');
	var rows = table.rows;
	if($('#result').attr('type') == '1'){
		for(var i = 1; i < rows.length; i ++){
			if(rows[i].cells[0].childNodes[0].checked){
				continue;
			}
			data.push({
				attend_id : rows[i].cells[0].childNodes[0].value,
				sort : rows[i].cells[1].innerHTML,
				grade : rows[i].cells[2].innerHTML,
				total_grade : rows[i].cells[3].innerHTML
			}); 
		}
	}else{
		for(var i = 1; i < rows.length; i ++){
			if(rows[i].cells[0].childNodes[0].checked){
				continue;
			}
			data.push({
				attend_id : rows[i].cells[0].childNodes[0].value,
				sort : rows[i].cells[1].innerHTML,
				grade : rows[i].cells[2].innerHTML,
				total_grade : 0
			}); 
		}
	}
	return data;
}
function request_data(){
	$.ajax({
		url : $('#url').val()+'/get_now_result',
		type:'post',
		data:{
			vote_id:$('#vote_id').val()
		},
		success:function(data){
			if(!data)return;
			data = eval("("+data+")");
			if(data.code != 0){
				$.layer({Type:'info',html :'获取结果失败'});
			}else{
				$('.show-num').text(data['info'].voted_num);
				create_table(data['info'].result);
				$("#result").page('show_table');
				var a = parseInt($('.show-num').text());
				var b = parseInt($('#jury_num').text());
				if(a >= b){
					had_complete = 1;
					clearInterval(timer);
				}
			} 
		}
	});
	
}
function first_request_data(){
	$.ajax({
		url : $('#url').val()+'/get_now_result',
		type:'post',
		data:{
			vote_id:$('#vote_id').val()
		},
		success:function(data){
			if(!data)return;
			data = eval("("+data+")");
			if(data.code != 0){
				$.layer({Type:'info',html :'获取结果失败'});
			}else{
				$('.show-num').text(data['info'].voted_num);
				create_table(data['info'].result);
				$("#result").page("create_page");
			} 
		}
	});
}
function create_table(result){
	var count = result.length;
	var table = document.getElementById('result');
	var html="";
	var sort = 1;
	if($('#result').attr('type') == '1'){
		for(var i = 0; i < count; i ++){
			if(result[i]['total_grade'] === null){
				result[i]['total_grade'] = 0;
			}
			if(result[i]['grade'] === null){
				result[i]['grade'] = 0;
			}
			if(i>0 &&result[i]['grade'] != result[i-1]['grade']){
				sort ++;
			}
			html += "<tr class='nomal'><td><input type='checkbox' name='choose' disabled='disabled' value='"+result[i]['id']+"'/></td><td>"
						+sort+"</td><td>"+parseFloat(result[i]['grade']).toFixed(2)
						+"</td><td>"+result[i]['total_grade']+"</td><td>"+result[i]['code']+"</td><td>"+result[i]['name']
						+"</td><td>"+result[i]['introduce']+"</td></tr>";
		}
	}else{
		for(var i = 0; i < count; i ++){
			if(result[i]['total_grade'] === null){
				result[i]['total_grade'] = 0;
			}
			if(i>0 && result[i]['total_grade'] != result[i-1]['total_grade']){
				sort ++;
			}
			html += "<tr class='nomal'><td><input type='checkbox' name='choose' disabled='disabled' value='"+result[i]['id']+"'/></td><td>"+sort
						+"</td><td>"+result[i]['total_grade']+"</td><td>"+result[i]['code']+"</td><td>"+result[i]['name']
						+"</td><td>"+result[i]['introduce']+"</td></tr>";
		}
	}
	$(table).find('tbody')[0].innerHTML = html;
	$(table).find('tr:even').addClass('even');
	$('td').click(function(){
		$('td').parent().removeClass('trfocus');
		$(this).parent().addClass('trfocus');
	});
	$('tr').click(function(){
		var vote_type;
		var html;
		if($("#vote_type_val").val() == 1){
			html
			="<div class='info-item'><label class='info-name'>排　　名:</label><label class='info-con'>"+$(this).children(":eq(1)").text()+"</label></div>"
			+"<div class='info-item'><label class='info-name'>平 均 分:</label><label class='info-con'>"+$(this).children(":eq(2)").text()+"</label></div>"
			+"<div class='info-item'><label class='info-name'>总　　分:</label><label class='info-con'>"+$(this).children(":eq(3)").text()+"</label></div>"
			+"<div class='info-item'><label class='info-name'>代　　号:</label><label class='info-con'>"+$(this).children(":eq(4)").text()+"</label></div>"
			+"<div class='info-item'><label class='info-name'>姓　　名:</label><label class='info-con'>"+$(this).children(":eq(5)").text()+"</label></div>"
			+"<div class='info-item'><label class='info-intro'>简　　介:</label><label class='info-con'>"+$(this).children(":eq(6)").text()+"</label></div>";
		}else{
			if($("#vote_type_val").val() == 2){
			vote_type = "票　　数";
			}else if($("#vote_type_val").val() == 3){
				vote_type = "排 名 分";
			}else if($("#vote_type_val").val() == 4){
				vote_type = "等 级 分";
			}
			html
				="<div class='info-item'><label class='info-name'>排　　名:</label><label class='info-con'>"+$(this).children(":eq(1)").text()+"</label></div>"
				+"<div class='info-item'><label class='info-name'>"+vote_type+":</label><label class='info-con'>"+$(this).children(":eq(2)").text()+"</label></div>"
				+"<div class='info-item'><label class='info-name'>代　　号:</label><label class='info-con'>"+$(this).children(":eq(3)").text()+"</label></div>"
				+"<div class='info-item'><label class='info-name'>姓　　名:</label><label class='info-con'>"+$(this).children(":eq(4)").text()+"</label></div>"
				+"<div class='info-item'><label class='info-intro'>简　　介:</label><label class='info-con'>"+$(this).children(":eq(5)").text()+"</label></div>";
		}
		$.layer({width:500,height:220,html:html,Type:'info',Title:'信心显示'});
	});
}
