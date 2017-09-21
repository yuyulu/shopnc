$(function (){
    if (getQueryString('key') != '') {
        var key = getQueryString('key');
        var username = getQueryString('username');
        addCookie('key', key);
        addCookie('username', username);
    } else {
        var key = getCookie('key');
    }
    var html = '<div class="nctouch-footer-wrap posr">'
        +'<div class="nav-text">';
    if(key){
        html += '<a href="'+WapSiteUrl+'/tmpl/member/member.html">我的商城</a>'
            + '<a id="logoutbtn" href="javascript:void(0);">注销</a>'
            + '<a href="'+WapSiteUrl+'/tmpl/member/member_feedback.html">反馈</a>'
	    + '<a href="' + WapSiteUrl + '/tmpl/article_list.html?ac_id=2">帮助</a>';
            
    } else {
        html += '<a href="'+WapSiteUrl+'/tmpl/member/login.html">登录</a>'
            + '<a href="'+WapSiteUrl+'/tmpl/member/register.html">注册</a>'
            + '<a href="'+WapSiteUrl+'/tmpl/member/login.html">反馈</a>'
	    + '<a href="' + WapSiteUrl + '/tmpl/article_list.html?ac_id=2">帮助</a>';
    }
        html += '<a href="javascript:void(0);" class="gotop">返回顶部</a>' + "</div>" + '<!--<div class="copyright">' + 'Copyright&nbsp;&copy;&nbsp;2005-2016 <a href="javascript:void(0);">好商城V5 www.33hao.com</a>版权所有' + "</div>--></div>";
	var fnav = '<div id="footnav" class="footnav clearfix"><ul>'
		+'<li><a href="'+WapSiteUrl+'"><i class="home"></i><p>首页</p></a></li>'
		+'<li><a href="'+WapSiteUrl+'/tmpl/product_first_categroy.html"><i class="categroy"></i><p>分类</p></a></li>'
		+'<li><a href="'+WapSiteUrl+'/tmpl/search.html"><i class="search"></i><p>搜索</p></a></li>'
		+'<li><a href="'+WapSiteUrl+'/tmpl/cart_list.html"><i class="cart"></i><p>购物车</p></a></li>'
		+'<li><a href="'+WapSiteUrl+'/tmpl/member/member.html"><i class="member"></i><p>我的商城</p></a></li></ul>'
		+'</div>';
	$("#footer").html(html+fnav);
    var key = getCookie('key');
	$('#logoutbtn').click(function(){
		var username = getCookie('username');
		var key = getCookie('key');
		var client = 'wap';
		$.ajax({
			type:'get',
			url:ApiUrl+'/index.php?act=logout',
			data:{username:username,key:key,client:client},
			success:function(result){
				if(result){
					delCookie('username');
					delCookie('key');
					location.href = WapSiteUrl;
				}
			}
		});
	});
});