$(function() {
    var store_id = getQueryString("store_id");
    var key = getCookie('key');

    // 初始化页面
    $.ajax({
        type: 'post',
        url: ApiUrl + "/index.php?act=store&op=store_intro",
        data: {key: key, store_id: store_id},
        dataType: 'json',
        success: function(result) {
            var data = result.datas;
            //渲染店铺分类
            var html = template.render('store_intro_tpl', data);
            $("#store_intro").html(html);

            //显示收藏按钮
            if (data.store_info.is_favorate) {
                $("#store_notcollect").hide();
                $("#store_collected").show();
            }else{
                $("#store_notcollect").show();
                $("#store_collected").hide();
            }
        }
    });

    //收藏店铺
    $("#store_notcollect").live('click',function() {
        //添加收藏
        var f_result = favoriteStore(store_id);
        if (f_result) {
            $("#store_notcollect").hide();
            $("#store_collected").show();
            var t;
            var favornum = (t = parseInt($("#store_favornum_hide").val())) > 0?t+1:1;
            $('#store_favornum').html(favornum);
            $('#store_favornum_hide').val(favornum);
        }
    });
    //取消店铺收藏
    $("#store_collected").live('click',function() {
        //取消收藏
        var f_result = dropFavoriteStore(store_id);
        if (f_result) {
            $("#store_collected").hide();
            $("#store_notcollect").show();
            var t;
            var favornum = (t = parseInt($("#store_favornum_hide").val())) > 1?t-1:0;
            $('#store_favornum').html(favornum);
            $('#store_favornum_hide').val(favornum);
        }
    });
});
