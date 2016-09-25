;(function($){
	$.fn.page = function(options){
		var defaults = {
			limit : 8,   // 每页显示的条数
			roll : 3,     //  显示的 页码 个数
			page_num:1
		}
		var options = $.extend(defaults,options);
		var $table = $(this);
		var page;
		var aaa = $table[0];
		var bb = $table[0].rows;
		var ccc = $table[0].rows.length;
		var count = $table[0].rows.length-1;  //总记录条数
		var num = 0;   //总页数
		var items;     // 页码 的 数组
		var now_page;
		var useless = false;
		create_page();
		create_table()

		function create_table(){
			if(useless){
				return;
			}
			$($table).find('tbody').find('tr:visible').hide();
			if (options.page_num == 'end'){
				options.page_num = num;
			}
			now_page = options.page_num;
			show_table();
		}

		
		function create_page(){
			if($table.next().is('.page')){
				$table.next('.page').remove();
			}
			if(count > options.limit){
				num = Math.ceil(count / options.limit);
				page = document.createElement('div');
				page.className = 'page';
				page.innerHTML = "<span class='page_prev'></span><span class='un_prev'></span>";
				for(var i = 1; i <= num; i ++ ){
					page.innerHTML += "<span class='page_item'>"+i+"</span>";
				}
				page.innerHTML += "<span class='un_next'></span><span class='page_next'></span>";
				$table.after(page);
				page = $table.next('.page');
				items = $(page).find('.page_item');
				$(page).find('.page_item').click(function(){
					now_page = parseInt($(this).text());
					show_table();
				});
				$(page).find('.page_prev').click(function(){
					prev_table();
				});
				$(page).find('.page_next').click(function(){
					next_table();
				});
			}else{
				useless = true;
			}
		}

		function show_table(){
			var first = (now_page - 1) * options.limit + 1;
		    var last = now_page * options.limit;

		    $table.find('tbody').find('tr:visible').hide();
		    
			for(var i = first; i <= last; i ++){
				$($table[0].rows[i]).show();
			}
			show_page();
		};

		function show_page(){
			var first = 1;
			var last = 1;
			if(num> options.roll){
				first = now_page - Math.floor(( options.roll - 1) / 2);
				last = first + options.roll - 1;
				if(first < 1){
					first = 1;
					last = options.roll;
				}
				if(last > num){
					last = num;
					first = last -  options.roll + 1;
				}
			}else{
				first = 1;
				last = num;
			}

			$(page).find('.page_item:visible').hide();

			for(var i = first - 1; i < last; i++ ){
				$(items[i]).css("display","inline-block");
			}

			if(now_page == 1){
				$(page).find('.page_prev').hide();
				$(page).find('.un_prev').css('display','inline-block');
			}else{
				$(page).find('.page_prev').css('display','inline-block');
				$(page).find('.un_prev').hide();
			}
			if(now_page == num){
				$(page).find('.page_next').hide();
				$(page).find('.un_next').css('display','inline-block');
			}else{
				$(page).find('.page_next').css('display','inline-block');
				$(page).find('.un_next').hide();
			}
			$(page).find('.now_page').removeClass('now_page');
			$(items[now_page - 1]).addClass('now_page');
		};

		function prev_table(){
			now_page--;
			show_table();
		}
		function next_table(){
			now_page++;
			show_table();
		}
		$.fn.page.show_table = function (){
			var first = (now_page - 1) * options.limit + 1;
		    var last = now_page * options.limit;

		    $table.find('tbody').find('tr:visible').hide();
		    
			for(var i = first; i <= last; i ++){
				$($table[0].rows[i]).show();
			}
			show_page();
		};
	}
})(jQuery)