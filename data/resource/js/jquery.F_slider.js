(function($) {
	$.fn.F_slider = function(options){
		var defaults = {
			page : 1,
			len : 0,		// 滚动篇幅
			axis : 'y',		// y为上下滚动，x为左右滚动
			width : 0        // 每次滚动宽度，0为滚动显示区域
		}
		var options = $.extend(defaults,options);
		return this.each(function(){
			var $this = $(this);
			var _f_center = $(this).find('.F-center');
            _f_center.removeAttr('style');
			var len = options.len;
			var page = options.page;
			if(options.axis == 'y'){
				var Val = (options.width == 0) ? _f_center.height() : options.width;
				var Param = 'top';
			}else if(options.axis == 'x'){
				var Val = (options.width == 0) ? _f_center.parent().width() : options.width;
				var Param = 'left';
			}
			$this.find('.F-prev').unbind().click(function(){
				if( page == 1){
					eval("_f_center.animate({"+Param+":'-=' + Val*(len-1)},'slow');");
					page=len;
				}else{
					eval("_f_center.animate({"+Param+":'+=' + Val},'slow');");
					page--;
				}
			});
			$this.find('.F-next').unbind().click(function(){
				if(page == len){
					eval("_f_center.animate({"+Param+":0},'slow');");
					page=1;
				}else{
					eval("_f_center.animate({"+Param+":'-=' + Val},'show');");
					page++;
				}
			});
		});
	}
	$.fn.F_no_slider = function() {
        return this.each(function(){
            var $this = $(this);
            var _f_center = $(this).find('.F-center');
            _f_center.removeAttr('style');
            $this.find('.F-prev').unbind();
            $this.find('.F-next').unbind();
        });
    };
})(jQuery);