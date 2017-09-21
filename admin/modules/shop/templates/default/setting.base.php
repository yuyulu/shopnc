<?php defined('In33hao') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3><?php echo $lang['nc_mall_set']; ?></h3>
                <h5><?php echo $lang['nc_mall_set_subhead']; ?></h5>
            </div>
            <?php echo $output['top_link']; ?></div>
    </div>
    <form method="post" enctype="multipart/form-data" name="form1">
        <input type="hidden" name="form_submit" value="ok"/>
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="site_logo"><?php echo $lang['site_logo']; ?></label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL . '/' . (ATTACH_COMMON . DS . $output['list_setting']['site_logo']); ?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL . '/' . (ATTACH_COMMON . DS . $output['list_setting']['site_logo']); ?>>')" onMouseOut="toolTip()"/></i> </a></span><span class="type-file-box">
            <input type="text" name="textfield" id="textfield1" class="type-file-text"/>
            <input type="button" name="button" id="button1" value="选择上传..." class="type-file-button"/>
            <input class="type-file-file" id="site_logo" name="site_logo" type="file" size="30" hidefocus="true" nc_type="change_site_logo" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            </span></div>
                    <span class="err"></span>
                    <p class="notic">默认网站LOGO,通用头部显示，最佳显示尺寸为240*60像素</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="site_logo"><?php echo $lang['member_logo']; ?></label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL . '/' . (ATTACH_COMMON . DS . $output['list_setting']['member_logo']); ?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL . '/' . (ATTACH_COMMON . DS . $output['list_setting']['member_logo']); ?>>')" onMouseOut="toolTip()"/></i> </a></span><span class="type-file-box">
            <input type="text" name="textfield2" id="textfield2" class="type-file-text"/>
            <input type="button" name="button2" id="button2" value="选择上传..." class="type-file-button"/>
            <input class="type-file-file" id="member_logo" name="member_logo" type="file" size="30" hidefocus="true" nc_type="change_member_logo" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            </span></div>
                    <span class="err"></span>
                    <p class="notic">网站小尺寸LOGO，会员个人主页显示，最佳显示尺寸为200*40像素</p>
                </dd>
            </dl>
            <!-- 商家中心logo -->
            <dl class="row">
                <dt class="tit">
                    <label for="seller_center_logo">商家中心Logo</label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL . '/' . (ATTACH_COMMON . DS . $output['list_setting']['seller_center_logo']); ?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL . '/' . (ATTACH_COMMON . DS . $output['list_setting']['seller_center_logo']); ?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input type="text" name="textfield3" id="textfield3" class="type-file-text"/>
            <input type="button" name="button3" id="button3" value="选择上传..." class="type-file-button"/>
            <input class="type-file-file" id="seller_center_logo" name="seller_center_logo" type="file" size="30" hidefocus="true" nc_type="change_seller_center_logo" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            </span></div>
                    <span class="err"></span>
                    <p class="notic">商家中心LOGO，最佳显示尺寸为150*40像素，请根据背景色选择使用图片色彩</p>
                </dd>
            </dl>
            <!-- 商家中心logo -->
            <dl class="row">
                <dt class="tit">
                    <label for="site_phone"><?php echo $lang['site_phone']; ?></label>
                </dt>
                <dd class="opt">
                    <input id="site_phone" name="site_phone" value="<?php echo $output['list_setting']['site_phone']; ?>" class="input-txt" type="text"/>
                    <span class="err"></span>
                    <p class="notic"><?php echo $lang['site_phone_notice']; ?></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="site_email"><?php echo $lang['site_email']; ?></label>
                </dt>
                <dd class="opt">
                    <input id="site_email" name="site_email" value="<?php echo $output['list_setting']['site_email']; ?>" class="input-txt" type="text"/>
                    <span class="err"></span>
                    <p class="notic"><?php echo $lang['site_email_notice']; ?></p>
                </dd>
            </dl>
            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.form1.submit()"><?php echo $lang['nc_submit']; ?></a></div>
        </div>
    </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL; ?>/js/jquery.nyroModal.js"></script>
<script type="text/javascript">
    // 模拟网站LOGO上传input type='file'样式
    $(function () {
        $("#site_logo").change(function () {
            $("#textfield1").val($(this).val());
        });
        $("#member_logo").change(function () {
            $("#textfield2").val($(this).val());
        });
        $("#seller_center_logo").change(function () {
            $("#textfield3").val($(this).val());
        });
// 上传图片类型
        $('input[class="type-file-file"]').change(function () {
            var filepath = $(this).val();
            var extStart = filepath.lastIndexOf(".");
            var ext = filepath.substring(extStart, filepath.length).toUpperCase();
            if (ext != ".PNG" && ext != ".GIF" && ext != ".JPG" && ext != ".JPEG") {
                alert("<?php echo $lang['default_img_wrong'];?>");
                $(this).attr('value', '');
                return false;
            }
        });
// 点击查看图片
        $('.nyroModal').nyroModal();
        $('#time_zone').attr('value', '<?php echo $output['list_setting']['time_zone'];?>');
    });
</script> 
