$(function (){ 
    var id = getQueryString("id");
    //渲染页面
    $.ajax({
       url:ApiUrl+"/index.php?act=red_packet&op=index",
       type:"get",
       data:{id:id},
       dataType:"json",
       success:function(result){
          var data = result.datas;
          if(data.error){
			  $.sDialog({
                content: data.error + '~',
                okBtn:false,
                cancelBtnText:'去首页看看',
                cancelFn: function() { location.href = WapSiteUrl; }
            });
          }else{
			  //var html = template.render('packet_detail', data);
			  //$("packet_detail").html(html);
		  }
       }
    });

	$('#rush_get').click(function(){//领取红包
    
		var key = getCookie("key");//登录标记
        if(!key){
             window.location.href = WapSiteUrl+'/tmpl/member/login.html';
			 return;
        }else{
			$.ajax({
			   url:ApiUrl+"/index.php?act=member_redpacket&op=getpack",
			   type:"get",
			   data:{id:id,key:key},
			   dataType:"json",
			   success:function(result){
				  var data = result.datas;
				  if(data.error){
					  //更改样式
					  document.getElementById('chaihongbao').style.display="none";
					  document.getElementById('fenxiang').style.display="block";
					  $.sDialog({
						content: data.error,
						okBtn:false,
						cancelBtnText:'查看我的红包',
						cancelFn: function() { location.href = WapSiteUrl+'/tmpl/member/redpacket_list.html'; }
					 });
				  }else{
					  //更改样式
					  document.getElementById('chaihongbao').style.display="none";
					  document.getElementById('fenxiang').style.display="block";
					  $.sDialog({
						content:'恭喜您获得'+data.packet_price+'元红包~',
						okBtn:false,
						cancelBtnText:'手气不错',
						cancelFn: function() { location.href = WapSiteUrl+'/tmpl/member/redpacket_list.html'; }
					 });
				  }
			   }
			});
		}

	});

});