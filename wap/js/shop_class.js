/**
 * 所有店铺分类 v3-b12
 */


$(function() {
    $.ajax({
        url:ApiUrl+"/index.php?act=shop_class",
        type:'get',
        jsonp:'callback',
        dataType:'jsonp',
        success:function(result){
            var data = result.datas;
            data.WapSiteUrl = WapSiteUrl;
            var html = template.render('category-one', data);
            $("#categroy-cnt").html(html);
        }
    });
});