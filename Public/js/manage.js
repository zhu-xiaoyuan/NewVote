/*关闭或开启一次投票*/
function changeStatus(that,status){
	var vote_id = that.parentNode.parentNode.getAttribute("value");
	var data = {
		status : status,
		vote_id : vote_id
	};
	$.ajax({
		type: 'post',
		url: $('#url').val()+'/changeStatus',
		data : data,
		success: function(data){
			if(!data)return;
			data = eval("("+data+")");
			if(data.code == 0){
				if (status == 1){$(that).parent().prev().text('正在投票');
					$(that).parent().html("<a  class='operate-close-vote' onclick='changeStatus(this,4)'>关闭</a>");
					$.layer({Type:'load',html:data.msg,autoHide:true,sure: function(){
						window.location.href = $('#url').val() + "/../Callout/index.html";
					}});
				} else if(status == 4){$(that).parent().prev().text('已关闭');
					$(that).parent().html("<a  class='operate-close-vote' onclick='changeStatus(this,2)'>启动</a>");
				}else if(status == 2){
					$(that).parent().prev().text('待投票');
					var ht = "<a class='operate-open-vote' onclick='changeStatus(this,1)'>启动</a> "
							+"<a class='operate-edit-vote' href='"+ $('#url').val()+"/../Index/index?id="+ vote_id +"'>编辑</a> "
					    	+"<a  class='operate-close-vote' onclick='changeStatus(this,4)'>关闭</a>";
					$(that).parent().html(ht);
				}
			}else {
				$.layer({Type:'error',html :data.msg});
			}
		}
	});
}
/*重投*/
function rebowl(that,id){
	$.ajax({
		type: 'post',
		url: $('#url').val()+'/rebowl',
		data : {
			'vote_id' : id
		},
		success: function(data){ 
			if(!data)return;
			data = eval("("+data+")");
			if(data.code == 0){
				$(that).parent().prev().text('正在投票');
				$.layer({Type:'load',html:data.msg,autoHide:true,sure: function(){
						window.location.href = $('#url').val() + "/../Callout/index.html";
					}});
			}else {
				$.layer({Type:'error',html :data.msg});
			}
		}
	});
}
// function changeLayout(){
// 	var optionObj = document.getElementById("search-state").options;
// 	for(i = 0; i < optionObj.length; i++){
// 	    var length = optionObj[i].innerHTML.length;
// 	    for(j = 0; j < 6-length/2; j++){
// 	    	optionObj[i].innerHTML = '　' + optionObj[i].innerHTML;
// 	    }
// 	}
// }
$(function(){
	//changeLayout();
	$('table').find('tr:even').addClass('even');
	$(".show_info").click(function(){
		var id = $(this).parent().parent().attr('value');
		$.ajax({
			url : $('#url').val() + '/showInfo',
			type:'post',
			data :{
				id:id
			},
			success:function(data){
				if(!data)return;
				data = eval("("+data+")");
				var info = data.info;
				switch(parseInt(info.vote_type)){
					case 1: info.vote_type = '打分制';break;
					case 2: info.vote_type = '选举制';break;
					case 3: info.vote_type = '排名制';break;
					case 4: info.vote_type = '等级制';break;
				}
				switch(parseInt(info.status)){
					case 1: info.status = '正在投票';break;
					case 2: info.status = '待投票';break;
					case 3: info.status = '投票结束';break;
					case 4: info.status = '已关闭';break;
				}
				var html=
				"<div class='info-item'><label class='info-name'>主　　题:</label><label class='info-con'>"+info.theme+"</label></div>"
				+"<div class='info-item'><label class='info-intro'>描　　述:</label><label class='info-con'>"+info.describe+"</label></div>"
				+"<div class='info-item'><label class='info-name'>投票方式:</label><label class='info-con'>"+info.vote_type+"</label></div>"
				+"<div class='info-item'><label class='info-name'>参投人数:</label><label class='info-con'>"+info.attend_num+"人</label></div>"
				+"<div class='info-item'><label class='info-name'>拟投人数:</label><label class='info-con'>"+info.prepare_num+"人</label></div>"
				+"<div class='info-item'><label class='info-name'>评委人数:</label><label class='info-con'>"+info.jury_num+"人</label></div>"
				+"<div class='info-item'><label class='info-name'>状　　态:</label><label class='info-con'>"+info.status+"</label></div>";
				if(info.jury_list){
					var table = "<div id='jury_bar'><h5>评委人员</h5><table class='jury_table'><thead><tr><th>账号</th><th>密码</th>"
							+"<th>账号</th><th>密码</th><th>账号</th><th>密码</th></tr></thead><tbody>";
				
					i = 0;
					num = info.jury_list.length;
					while(i<num){
						if(i%3 == 0){
							table += "<tr>";
						}
						table += "<td>" +info.jury_list[i]['account'] + "</td><td>" 
									+info.jury_list[i]['password'] + "</td>";
						if(i%3 == 2){
							table += "</tr>";
						}
						i ++;
					}
					if(num % 3 != 0){
						for(i=0;i<(3 - num % 3)*2;i++){
							table +="<td></td>";
						}
						table +="</tr>";
					}
					table += '</tbody></table></div>';
					html = html + table;
				}

				if(info.result){
					table = '<div id="result_bar">';
					for(var i = 0; i < info.result.length; i++){
						table += "<h5>第"+(i+1)+"投票结果</h5>"+
								"<table class='result_table'><thead><tr><th class='info-1'>排名</th><th class='info-1'>代号</th>"
								+"<th class='info-1'>姓名</th><th class='info-2'>简介</th></tr></thead><tbody>";
						for(var j= 0; j < info.result[i].length; j ++){
							if(j<=info.prepare_num-1){
								table += "<tr class='score'>";
							}else{
								table += "<tr>";
							}
							table += "<td>"+info.result[i][j].sort
									+"</td><td>"+info.result[i][j].code
									+"</td><td>"+info.result[i][j].name
									+"</td><td class='intro'>"+info.result[i][j].introduce
									+"</td></tr>";
						}
						table +="</tbody></table>";
					}
					table += '</div>';
					html = html + table;
				}
				
				if(info.attend_list){
					var table = "<div id='attend_bar'><h5>参选人员</h5><table class='attend_table'><thead><tr>"
							+"<th class='info-1'>序号</th><th class='info-1'>代号</th>"
							+"<th  class='info-1'>姓名</th><th class='info-2'>简介</th></tr></thead><tbody>";
					for(var i = 0; i < info.attend_list.length; i ++){
						table += "<tr><td>"+(i+1)+"</td>";
						table += "<td>"+info.attend_list[i].code
								+"</td><td>"+info.attend_list[i].name
								+"</td><td class='intro'>"+info.attend_list[i].introduce
								+"</td></tr>";
					}
					table += '</tbody></table></div>';
					html = html + table;
				}

				$.layer({width:800,html:html,Type:'info',Title:'信心显示'});
				$('.result_table').page();
				$('.jury_table').page();
				$('.attend_table').page();
				$('.tip_info_content').find('tr:even').addClass('tip_even');
			}
		});
	});
	$('.dialog-sure').click(function(){
		$(this).parent().parent().hide();
	});
	$('.close-icon').click(function(){
		$('.dialog-sure').click();
	});
	$('#search').click(function(){		
		if($('#login-status').attr('value') == '0'){
			$.layer({Type:'error',html :'请先登录'});
			return false;
		}
		if($("#search-date-end").val() != "")
			if($("#search-date-start").val() > $("#search-date-end").val()){
				$.layer({Type:'error',html :'查询日期错误'});
				return false;
			}
	});
	$('td').click(function(){
		$('td').parent().css('backgroundColor','');
		$(this).parent().css('backgroundColor','#F39800');
	});
	$('.ui-select').text($(".ui-option-item").eq($('option:selected').val()).text());
	$('.ui-select').click(function(){
		$(this).addClass('ui-focus');
		$(".ui-options").show();
	});
	$('.ui-down').click(function(){
		$('.ui-select').addClass('ui-focus');
		$(".ui-options").show();
	});
	document.body.onclick = function(e){
		e = e || window.event;
	    var target = e.target || e.srcElement;
	    if(!$(target).is('.ui-select') && !$(target).is('.ui-option-item') && !$(target).is('.ui-down')){
	    	$('.ui-select').removeClass('ui-focus');
			$(".ui-options").hide();
	    }
	}
	$('.ui-option-item').click(function(){
		$(this).parent().hide();
		$('.ui-select').text($(this).text());
		$('#search-state').find('option:eq('+$(this).attr('for')+')').attr('selected',true);
	});
});