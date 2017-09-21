$(function(){
	var key = getCookie('key');
	if(!key){
		location.href = 'login.html';
	}
	//渲染list
	var load_class = new ncScrollLoad();
	load_class.loadInit({
		'url':ApiUrl + '/index.php?act=member_favorites&op=favorites_list',
		'getparam':{'key':key},
		'tmplid':'sfavorites_list',
		'containerobj':$("#favorites_list"),
		'iIntervalId':true,
		'data':{WapSiteUrl:WapSiteUrl}
	});

	$("#favorites_list").on('click',"[nc_type='fav_del']",function(){
		var goods_id = $(this).attr('data_id');
		if (goods_id <= 0) {
			$.sDialog({skin: "red", content: '删除失败', okBtn: false, cancelBtn: false});
		}
		if(dropFavoriteGoods(goods_id)){
			$("#favitem_"+goods_id).remove();
			if (!$.trim($("#favorites_list").html())) {
				location.href = WapSiteUrl+'/tmpl/member/favorites.html';
			}
		}
	});
});