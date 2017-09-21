$(function(){
	var key = getCookie('key');
	if(!key){
		location.href = 'login.html';
	}
	//渲染list
	var load_class = new ncScrollLoad();
	load_class.loadInit({
		'url':ApiUrl + '/index.php?act=member_favorites_store&op=favorites_list',
		'getparam':{'key':key},
		'tmplid':'sfavorites_list',
		'containerobj':$("#favorites_list"),
		'iIntervalId':true,
		'data':{WapSiteUrl:WapSiteUrl}
	});

	$("#favorites_list").on('click',"[nc_type='fav_del']",function(){
		var store_id = $(this).attr('data_id');
		if (store_id <= 0) {
			$.sDialog({skin: "red", content: '删除失败', okBtn: false, cancelBtn: false});
		}
		if(dropFavoriteStore(store_id)){
			$("#favitem_"+store_id).remove();
			if (!$.trim($("#favorites_list").html())) {
				location.href = WapSiteUrl+'/tmpl/member/favorites_store.html';
			}
		}
	});
});