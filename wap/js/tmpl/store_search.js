$(function() {
    var store_id = getQueryString("store_id");

    $("#goods_search_all").attr('href',WapSiteUrl + '/tmpl/store_goods.html?store_id='+store_id);
    //$('#keywords').val(decodeURIComponent(getQueryString('keyword')));

    //搜索提交
    $('#search_btn').click(function(){
        var search_keyword = $('#search_keyword').val();
        if (search_keyword != '') {
            window.location.href = WapSiteUrl + '/tmpl/store_goods.html?store_id='+store_id+'&keyword=' + encodeURIComponent(search_keyword);
        }
    });

    // 初始化页面
    $.ajax({
        type: 'post',
        url: ApiUrl + "/index.php?act=store&op=store_goods_class",
        data: {store_id: store_id},
        dataType: 'json',
        success: function(result) {
            var data = result.datas;
            //显示页面title
            var title = data.store_info.store_name + ' - 店内搜索';
            document.title = title;
            //渲染店铺分类
            var html = template.render('store_category_tpl', data);
            $("#store_category").html(html);
        }
    });
});
