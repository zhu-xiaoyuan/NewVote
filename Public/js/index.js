$(function(){
	/*加载项*/
	var val = $('#theme').val();
	if(val !== ""){
		$('input[name=is_dislodge][value='+$('#rule').attr('value')+']').attr('checked','checked');
		$('#attend-num-icon').text($('#attend_num').val());
	}
	else{
		$('#attend-num-icon').text('0');
		$('#attend_num').val('0');
	}
	$('#check_show').click(function(){
		if($(this).attr('class') == 'check_on'){
			$('#is_show_name').val('0');
			$(this).removeClass('check_on').addClass('check_off');
		}else{
			$('#is_show_name').val('1');
			$(this).removeClass('check_off').addClass('check_on');
		}
	});
	$('#attend_table').page();
	$('#jury_table').page();
	/*选择打分模式时弹出是否去电最高分单选按钮*/
	$('input[name=vote_type]').click(function(){
		if($(this).val() == 1){
			$('#rule').css('display','inline');
		}else{
			$('#rule').hide();
		}
	});
	if($('#vote_type_val').val()){
		$('input[name=vote_type]')[parseInt($('#vote_type_val').val())-1].click();
	}
	/*添加参选人弹出层*/
	$('#add-attend-person').click(function(){
		if($('#login-status').attr('value') == '0'){
			$.layer({Type:'error',html :'请先登录'});
			return false;
		}
		$('#add-layout').show();
		$('input[name=code]').focus();
	});
	
	/*关闭弹出层*/
	$('.dialog-cancel').click(function(){
		var form =$(this).parent().find("form")[0];
		for(var i = 0; i< form.length;i++){
			form[i].value = "";
		}
		$('.error').removeClass("error");
		$(this).parent().parent().hide();
	});
	$('.close-icon').click(function(){
		$('.dialog-cancel').click();
	});

	$('.error').bind("focus",function(){
		$('.error').removeClass("error");
	});
	$('.obj-1').click(function(){
		$("#jury_table td").removeClass('trfocus');
		$(this).addClass('trfocus');
		$(this).next('.obj-2').addClass('trfocus');
	});
	$('.obj-2').click(function(){
		$("#jury_table td").removeClass('trfocus');
		$(this).addClass('trfocus');
		$(this).prev('.obj-1').addClass('trfocus');
	});
	$('td').click(function(){
		$('td').parent().css('backgroundColor','');
		$(this).parent().css('backgroundColor','#F39800');
	});
	$("td.attend-4").click(function(){
		var html = "<div class='layer_info_item'><label class='info_name'>代号：</label><label class='info_intro'>"+$(this).prev().prev().text()+"</label></div>"
				+"<div class='layer_info_item'><label class='info_name'>姓名：</label><label class='info_intro'>"+$(this).prev().text()+"</label></div>"
				+"<div class='layer_info_item'><label class='info_name'>简介：</label><label class='info_intro'>"+$(this).text()+"</label></div>";

		$.layer({width:450,height:220,html:html,Type:'info',Title:'信息显示'});
	});

	/*确认添加*/
	$('#add-dialog-sure').click(function(){
		var form = $("#add-form")[0];
		var tr = "<tr>";
		tr += "<td class='attend-1'>"+document.getElementById('attend_table').rows.length+"</td>"; 
		for(var i = 0; i< form.length;i++){
			var ele = form[i];
			if(ele.value == null || ele.value == ""){
				$(ele).addClass('error');
				$('.error').on("focus",function(){
					$('.error').removeClass("error");
				});
				$(".warning").text("*对不起,请填写完整");
				return;
			}else{
				tr += "<td class='attend-"+(i+2)+"'>"+ ele.value + "</td>";
			}
		}
		tr += "<td class='attend-5'><a class='operate-edit-attend' onclick='edit(this)'></a>"
			+"<a class='operate-del-attend' onclick='del(this)'></a></td></tr>";
		$("#attend-num-icon").text(parseInt($("#attend-num-icon").text()) + 1);
		$("#attend_num").val($("#attend-num-icon").text());
		$('#attend_table').append(tr);
		for(var i = 0; i< form.length;i++){
			form[i].value = "";
		}
		$('.error').removeClass("error");
		$(this).parent().parent().hide();
		$('#attend_table').page('create_page','end');
		$('#attend_table').find('tr:even').addClass('even');
		$('td').click(function(){
			$('td').parent().css('backgroundColor','');
			$(this).parent().css('backgroundColor','#F39800');
		});
		$(".attend-4").click(function(){
			var html = "<div class='layer_info_item'><label class='info_name'>代号：</label><label class='info_intro'>"+$(this).prev().prev().text()+"</label></div>"
				+"<div class='layer_info_item'><label class='info_name'>姓名：</label><label class='info_intro'>"+$(this).prev().text()+"</label></div>"
				+"<div class='layer_info_item'><label class='info_name'>简介：</label><label class='info_intro'>"+$(this).text()+"</label></div>";

		$.layer({width:450,height:220,html:html,Type:'info',Title:'信心显示'});
		});
	});
	/*确认编辑*/
	$('#edit-dialog-sure').click(function(){
		var form = $("#edit-form")[0];
		var num = $(this).attr('for');
		for(var i = 0; i< form.length;i++){
			var ele = form[i];
			if(ele.value == null || ele.value == ""){
				$(ele).addClass('error');
				$('.error').bind("focus",function(){
					$('.error').removeClass("error");
				});
				$(".warning").text("*对不起,请填写完整");
				return;
			}
		}
		var tr = document.getElementById("attend_table").rows[num];
		for(var i = 0; i < form.length; i ++){
			tr.cells[i+1].innerHTML = form[i].value;
		}
		
		for(var i = 0; i< form.length;i++){
			form[i].value = "";
		}
		$('.error').removeClass("error");
		$(this).parent().parent().hide();
	});
	
	/*发起或保存投票*/
	$(".btn-sub").click(function(){
		if($('#login-status').attr('value') == '0'){
			$.layer({Type:'error',html :'请先登录'});
			return;
		}
		var form = $("#center").find("form");
		var url = $(this).attr('url');
		var data = form.serializeArray();
		var json = serializeJson(data);
		json['attend_list'] = new Array();
		json['attend_list'] = getAttend();
		json['jury_list'] = new Array();
		json['jury_list'] = getJury();
		json['create_person'] = $('#login-status').attr('value');
		var is = $(this).attr('id');
		$.ajax({
			type : 'post',
			url : url,
			data: json,
			success: function(data){
				if(!data)return;
				data = eval("("+data+")");
				if(data.code == 0x0000){
					if(is == 'save'){
						$.layer({Type:'load',html:data.msg,autoHide:true,sure: function(){
								window.location.href = $('#url').val() + "/../Manage/index.html";
						}});
					}else {
						$.layer({Type:'load',html:data.msg,autoHide:true,sure: function(){
								window.location.href = $('#url').val() + "/../Callout/index.html";
						}});
					}
				}else if(data.code == 0xa008){
					$.layer({Type:'load',html:data.msg,autoHide:true,sure: function(){
						window.location.href = $('#url').val() + "/../Manage/index.html";
					}});
				}else{
					$.layer({Type:'error',html :data.msg});
				}
			}
		});
	});
	$('#center input,#center textarea').change(function(){
		if($('#login-status').attr('value') == '0'){
			$.layer({Type:'error',html :'请先登录'});
			return;
		}
	});
});
/*编辑参选人弹出层*/
function edit(that){
	var tr = that.parentNode.parentNode;
	var form = document.getElementById("edit-form");
	for(var i = 0; i < form.length; i ++){
		form[i].value = tr.cells[i+1].innerHTML;
	}
	$('#edit-dialog-sure').attr('for',tr.rowIndex);
	$('#edit-layout').show();
}
/*获取参选人列表(json对象数组)
*Array({json对象},{json对象},{json对象},...)
*/
function getAttend(){
	var arr = new Array();
	var table = document.getElementById("attend_table");
	for(var i = 1; i < table.rows.length; i ++){
		arr[i-1] = {'code':table.rows[i].cells[1].innerHTML,
					'name':table.rows[i].cells[2].innerHTML,
					'introduce':table.rows[i].cells[3].innerHTML
				};
	}
	return arr;
}
function getJury(){
	var arr = new Array();
	var table = document.getElementById("jury_table");
	var num = parseInt(document.getElementById("jury_num").value);
	for(var i = 1; i < table.rows.length && num > 0;i++){
		for(var j = 0; j < 3 && num > 0;j++)
			if(table.rows[i].cells[j*2].innerHTML != ""){
				arr[(i-1)*3 + j] = {'account':table.rows[i].cells[j*2].innerHTML,
					'password':table.rows[i].cells[j*2+1].innerHTML
				};
				num --;
			}
	}
	return arr;
}
/*将serializeArray()函数的返回结果解析成json对象
Array({"name":value},{"name":value},{"name":value},.....);
*/
function serializeJson(data){
	var json = {};
	for(var i = 0; i < data.length; i ++){
	    json[data[i]['name']] = data[i]['value'];
	}
	return json;
}
function create_jury_list(){
	if($('#login-status').attr('value') == '0'){
		$.layer({Type:'error',html :'请先登录'});
		return;
	}
	if($('#jury_num').val() == ''){
		return;
	}
	var num = parseInt($('#jury_num').val());
	if(num <= 0){
		$.layer({Type:'error',html :'评委人数应大于0'});
		$('#print-jury-list').attr('disabled','disabled');
		$('#print-jury-list').addClass('disable');
		return;
	}
	var table = document.getElementById('jury_table');
	var i=0;
	var len = table.rows.length;
	if(table.rows[1]){
		for(var i = 1;i<len;i ++){
			$(table.rows[1]).remove();
		}
	}
	var tbody = document.createDocumentFragment();
	var tr;
	var td;
	i = 0;
	while(i<num){
		if(i%3 == 0){
			tr = document.createElement('tr');
		}
		td = document.createElement('td');
		td.className = 'obj-1';
		td.innerHTML = i+1;
		tr.appendChild(td);
		td = document.createElement('td');
		td.innerHTML = getKey();
		td.className = 'obj-2';
		tr.appendChild(td);
		if(i%3 == 0){
			tbody.appendChild(tr);
		}
		i ++;
	}
	if(num % 3 != 0){
		for(i=0;i<(3 - num % 3)*2;i++){
			td = document.createElement('td');
			tr.appendChild(td);
		}
	}
	$(table).find('tbody')[0].appendChild(tbody);
	$(table).page('create_page');
	$(table).find('tr:even').addClass('even');
	$('#print-jury-list').attr('disabled',false);
	$('#print-jury-list').removeClass('disable');
	$('.obj-1').click(function(){
		$("#jury_table td").removeClass('trfocus');
		$(this).addClass('trfocus');
		$(this).next('.obj-2').addClass('trfocus');
	});
	$('.obj-2').click(function(){
		$("#jury_table td").removeClass('trfocus');
		$(this).addClass('trfocus');
		$(this).prev('.obj-1').addClass('trfocus');
	});
}

function getKey(){
	var num=""; 
	for(var i=0;i<6;i++) 
	{ 
	num+=Math.floor(Math.random()*10); 
	} 
	return num;
}
function checkSame(val){
	var table = document.getElementById('attend_table');
	var rows = table.rows;
	for(var i = 1; i < rows.length; i ++){
		if(rows[i].cells[1].innerHTML == val){
			return true;
		}
	}
	return false;
}
/*删除参选人*/
function del(that){
	var name = $(that).parent().prev().prev();
	var code = name.prev().text();
	name = name.text();
	$.layer({Type:'error',Title:'删除',buttonCancel:true,buttonSureText:"是",buttonCancelText:"否",
		html :"<p class='del_a'>您确定要删除?</p><p  class='del_b'>代号: "+code+"</label><br/><p class='del_b'>姓名: "+name+"</p>",
		sure:function(ele){
		return function (){
			$(ele).parent().parent().remove();
			$("#attend-num-icon").text(parseInt($("#attend-num-icon").text()) - 1);
			$("#attend_num").val($("#attend-num-icon").text());
		}
	}(that)});
}
function print_jury_list(id){
	$.ajax({
		type: 'post',
		data: {
			data : getJury()
		},
		url : $('#url').val() + "/import",
		success : function (data) {
			if(data == 0){
				$.layer({Type:'error',html :'导出失败'});
			}
			window.location = $('#root').val() + "/" + data;
		}
	});
}