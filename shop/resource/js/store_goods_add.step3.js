$(function(){
    // 商品图片ajax上传
    $('.ncsc-upload-btn').find('input[type="file"]').unbind().bind('change', function(){
        var id = $(this).attr('id');
        ajaxFileUpload(id);
    });
    //凸显鼠标触及区域、其余区域半透明显示
    $(".container > div").jfade({
        start_opacity:"1",
        high_opacity:"1",
        low_opacity:".5",
        timing:"200"
    });
    //浮动导航  waypoints.js
    $("#uploadHelp").waypoint(function(event, direction) {
        $(this).parent().toggleClass('sticky', direction === "down");
        event.stopPropagation();
    }); 
    // 关闭相册
    $('a[nctype="close_album"]').click(function(){
        $(this).hide();
        $(this).prev().show();
        $(this).parent().next().html('');
    });
    // 绑定点击事件
    $('div[nctype^="file"]').each(function(){
        if ($(this).prev().find('input[type="hidden"]').val() != '') {
            selectDefaultImage($(this));
        }
    });
});

// 图片上传ajax
function ajaxFileUpload(id, o) {
    $('img[nctype="' + id + '"]').attr('src', SHOP_TEMPLATES_URL + "/images/loading.gif");

    $.ajaxFileUpload({
        url : SITEURL + '/index.php?act=store_goods_add&op=image_upload',
        secureuri : false,
        fileElementId : id,
        dataType : 'json',
        data : {name : id},
        success : function (data, status) {
                    if (typeof(data.error) != 'undefined') {
                        alert(data.error);
                        $('img[nctype="' + id + '"]').attr('src',DEFAULT_GOODS_IMAGE);
                    } else {
                        $('input[nctype="' + id + '"]').val(data.name);
                        $('img[nctype="' + id + '"]').attr('src', data.thumb_name);
                        selectDefaultImage($('div[nctype="' + id + '"]'));      // 选择默认主图
                        checkDefaultImage($('div[nctype="' + id + '"]'));
                    }
                    $.getScript(SHOP_RESOURCE_SITE_URL+ '/js/store_goods_add.step3.js');
                },
        error : function (data, status, e) {
                    alert(e);
                    $.getScript(SHOP_RESOURCE_SITE_URL+ '/js/store_goods_add.step3.js');
                }
    });
    return false;

}

// 选择默认主图&&删除
function selectDefaultImage($this) {
    // 默认主题
    $this.click(function(){
        $(this).parents('ul:first').find('.show-default').removeClass('selected').find('input').val('0');
        $(this).addClass('selected').find('input').val('1');
    });
    // 删除
    $this.parents('li:first').find('a[nctype="del"]').click(function(){
        $this.unbind('click').removeClass('selected').find('input').val('0');
        $this.prev().find('input').val('').end().find('img').attr('src', DEFAULT_GOODS_IMAGE);
        checkDefaultImage($this);
    });
}

// 验证是否存在默认主题，没有选择第一个图片
function checkDefaultImage($this) {
    if ($this.parents('ul:first').find('.show-default').find('input[value="1"]').length == 0) {
        $_thumb = $this.parents('ul:first').find('.upload-thumb').each(function(){
            if ($(this).find('input').val() != '') {
                $(this).next().parents('ul:first').find('.show-default').removeClass('selected').find('input').val('0');
                $(this).next().addClass('selected').find('input').val('1');
                return false;
            }
        });
    }
}

// 从图片空间插入主图
function insert_img(name, src, color_id) {
    $_thumb = $('ul[nctype="ul'+ color_id +'"]').find('.upload-thumb').each(function(){
        if ($(this).find('input').val() == '') {
            $(this).find('img').attr('src', src);
            $(this).find('input').val(name);
            selectDefaultImage($(this).next());      // 选择默认主图
            checkDefaultImage($(this).next());
            return false;
        }
    });
}