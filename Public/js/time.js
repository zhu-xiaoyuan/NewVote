$(function(){
	/*所有的事件选择插件控制*/
    $('#search-date-start').datetimepicker({
        format: 'yyyy-mm-dd',
        language:"zh-CN",
        minView:2,
        autoclose:true
    });
     $('#search-date-end').datetimepicker({
        format: 'yyyy-mm-dd',
        language:"zh-CN",
        minView:2,
        autoclose:true
    });
     $('#search-date-end,#search-date-start').change(function(){
        var val = $(this).val();
        if(val == ''){
            return;
        }
        var regx = /^([0-9]{4})-([0-9]{2})-([0-9]{2})$/;
        if(!regx.test(val)){
            $.layer({Type:'error',html :'格式错误'});
            $(this).val('').focus();
        }
     });
     $('.calendar').click(function(){
        $(this).prev().focus();
     });
})