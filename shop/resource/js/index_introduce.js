var intro = {
	width:$(window).width(),
	height:$(window).height(),
	init:function(){
		$(window).on("resize",function(){
			intro.width = $(window).width();
			intro.height = $(window).height();
			intro.pos();
		});
		this.pos();
		this.onmousemove();
		this.shake();
		this.backTop();	
		this.flooring();
		this.showPic();
	},
	//top
	pos:function(){
		$(".picMask").css("width",intro.width);
		if(intro.width<1600){
			$(".back-top").css({
				"right":"50px",
				"bottom":"100px"
			});
		}else{
			$(".back-top").css({
				"right":"200px",
				"bottom":"200px"
			});
		} 
    	objLeft = (intro.width - $(".imgBox").width())/2 ;  
    	objTop = (intro.height - $(".imgBox").height())/2 + $(document).scrollTop();  
    	$(".imgBox").css({left: objLeft + 'px', top: objTop + 'px'});  
	},
	//入驻onmousemove
	onmousemove:function(){
		$(".login").hover(function(){
			$(".item-l2").css("background","#35b7ac");	
		},function(){
			$(".item-l2").css("background","#01cbbb");	
		})
	},
	//板块抖动
	shake:function(){
		$(".list-item").addClass("shake shake-slow");
	},
	//按钮出现
	backTop:function(){	
		var scroll_top,screenWidth,screenHeight;	
		$(window).on("scroll",function(){

			scroll_top = $(document).scrollTop(),
			screenWidth = $(window).width(),
			screenHeight = $(window).height();

			//判断显示悬浮导航条
			if(scroll_top>=$(".platform_advantage").offset().top){
				$(".suspension-box").css({"display":"block"});
			}else{
				$(".suspension-box").css({"display":"none"});
			}
			
			if(scroll_top>screenHeight){
				$(".back-top").fadeIn("slow");
			}else{
				$(".back-top").fadeOut("slow");
			};
    		objLeft = (intro.width - $(".imgBox").width())/2 ,  
    		objTop = (intro.height - $(".imgBox").height())/2 + scroll_top;
    		$(".imgBox").css({left: objLeft + 'px', top: objTop + 'px'});  
		});
		$(".back-top").on("click",function(){
			
			$(".suspension-box").animate({
				display:"none"		
			},10,function(){
				$("html,body").animate({
					scrollTop:0
				},500)
			})
			
		})
	},
	//滚动到相应楼层
	flooring:function(){
		var arr_h = [];
		for(var i=0;i<$(".sb_floor").length;i++){
			var top = $(".sb_floor").eq(i).offset().top;
			arr_h.push(top);
		}
		$(".joinin-index-step").find(".step").on("click",function(){
			var index = $(this).index();
			if(index==1 || index==2 || index==3)
			{
				$("html,body").animate({
					scrollTop:arr_h[1]
				},500)
			}else if(index==4)
			{
				$("html,body").animate({
					scrollTop:arr_h[2]
				},500)	
			}else if(index==5)
			{
				$("html,body").animate({
					scrollTop:arr_h[3]
				},500)	
			}else
			{
				$("html,body").animate({
					scrollTop:arr_h[0]
				},500)
			};
			$(".suspension-box").css({"display":"block"});	
		});
		$(".suspension-step").find(".step").hover(function(){
			$(this).addClass("active");
		},function(){
			$(this).removeClass("active");
		});		
	},
	dataName:'',
	//图片层显示
	showPic:function(){
		$(".showImg").on("click",function(){
			intro.dataName  = $(this).attr('data-name');
			var img  = new Image();
			img.src = "./templates/default/images/prove/" + intro.dataName + ".png";
			img.onload = function(){
				intro.callBack(img);	
			}	
		})	
	},
	callBack:function(_img){
		var width = $(window).width(),
			viewHeight = $(window).height(),
			height = $(document).height(),
			scrollTop = $(window).scrollTop();
			mask = '<div class="picMask"></div>',
			imgBox = '<div class="imgBox"><p style="padding:15px 25px;height:50px;border-bottom:1px solid #ddd;"><i class="close"></i></p><div class="picture"></div></div>';
		$(mask).insertAfter($("#footer"));
		$(imgBox).insertAfter($(".picMask"));
		$(".picture").append(_img);
		$(".picMask").css({
			"position":"absolute",
			"left":0,
			"top":0,
			"z-index":9998,
			"width":width,
			"height":height,
			"background":"#000",
			"opacity":0.6,
			"filter":"alpha(opacity=60)"			
		});
		$(".imgBox").css({
			"position":"absolute",
			"left":0,
			"top":0,
			"z-index":9999,
			"width":"800px",
			"height":"605px",
			"background":"#fff"
		});
		//handle position
		var left = (width - $(".imgBox").width())/2,
			top = (viewHeight - $(".imgBox").height())/2 + scrollTop;
		$(".imgBox").css({"left":left+"px","top":top+"px"});
		//绑定关闭事件
		$(".close").on("click",function(){
			$(".imgBox").remove();
			$(".picMask").remove();				
		});
	}	
}
intro.init();