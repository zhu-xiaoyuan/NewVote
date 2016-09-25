;(function($){
	var Page = function(element,options){
		this.element = $(element);
		this.num = 0;
		this.now_page = 1;
		this.limit = options.limit;
		this.roll = options.roll;
		this.create_page(options.page_num);
	}

	Page.prototype = {
		constructor: Page,
		create_page:function(page_num){
			var that = this;
			if(this.element.next().is('.page')){
				this.element.next('.page').remove();
			}
			this.count = this.element[0].rows.length-1;  //总记录条数
			if(this.count > this.limit){
				this.useless = false;
				this.num = Math.ceil(this.count / this.limit);
				this.page = document.createElement('div');
				this.page.className = 'page';
				this.page.innerHTML = "<span class='page_prev'></span><span class='un_prev'></span>";
				for(var i = 1; i <= this.num; i ++ ){
					this.page.innerHTML += "<span class='page_item'>"+i+"</span>";
				}
				this.page.innerHTML += "<span class='un_next'></span><span class='page_next'></span>";
				this.element.after(this.page);
				this.page = this.element.next('.page');
				this.items = this.page.find('.page_item');
				this.page.find('.page_item').click(function(){
					that.now_page = parseInt($(this).text());
					that.show_table();
				});
				this.page.find('.page_prev').click(function(){
					that.prev_table();
				});
				this.page.find('.page_next').click(function(){
					that.next_table();
				});
			}else{
				this.useless = true;
				return;
			}
			if (page_num == 'end'){
				this.now_page = this.num;
			}
			this.show_table();
		},
		show_table:function(){
			if(this.useless){
				return;
			}
			var first = (this.now_page - 1) * this.limit + 1;
		    var last = this.now_page * this.limit;

		    this.element.find('tbody').find('tr:visible').hide();
		    
			for(var i = first; i <= last; i ++){
				$(this.element[0].rows[i]).show();
			}
			this.show_page();
		},
		show_page:function(){
			var first = 1;
			var last = 1;
			if(this.num> this.roll){
				first = this.now_page - Math.floor(( this.roll - 1) / 2);
				last = first + this.roll - 1;
				if(first < 1){
					first = 1;
					last = this.roll;
				}
				if(last > this.num){
					last = this.num;
					first = last - this.roll + 1;
				}
			}else{
				first = 1;
				last = this.num;
			}

			this.page.find('.page_item:visible').hide();

			for(var i = first - 1; i < last; i++ ){
				$(this.items[i]).css("display","inline-block");
			}

			if(this.now_page == 1){
				this.page.find('.page_prev').hide();
				this.page.find('.un_prev').css('display','inline-block');
			}else{
				this.page.find('.page_prev').css('display','inline-block');
				this.page.find('.un_prev').hide();
			}
			if(this.now_page == this.num){
				this.page.find('.page_next').hide();
				this.page.find('.un_next').css('display','inline-block');
			}else{
				this.page.find('.page_next').css('display','inline-block');
				this.page.find('.un_next').hide();
			}
			this.page.find('.now_page').removeClass('now_page');
			$(this.items[this.now_page - 1]).addClass('now_page');
		},
		prev_table:function(){
			this.now_page--;
			this.show_table();
		},
		next_table:function (){
			this.now_page++;
			this.show_table();
		}
	}
	$.fn.page = function(option){
		var args = Array.apply(null,arguments);
		args.shift();
		return this.each(function(){
			var $this = $(this),
				data = $this.data('page'),
				options = typeof option == 'object' && option;
			if(!data){
				$this.data('page',(data = new Page(this,$.extend({},$.fn.page.defaults,options))));
			}
			if(typeof option == 'string' && typeof data[option] == 'function'){
				data[option].apply(data,args);
			} 
		});
	}
	$.fn.page.defaults = {
		limit : 8,   // 每页显示的条数
		roll : 5,     //  显示的 页码 个数
		page_num:1
	}
})(jQuery)