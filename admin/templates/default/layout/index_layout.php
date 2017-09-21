<?php defined('In33hao') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<title><?php echo $output['html_title'];?></title>
<link href="<?php echo ADMIN_RESOURCE_URL?>/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo ADMIN_TEMPLATES_URL?>/css/index.css" rel="stylesheet" type="text/css">
<link href="<?php echo ADMIN_RESOURCE_URL?>/font/css/font-awesome.min.css" rel="stylesheet" />
<script type="text/javascript">
var ADMIN_SITE_URL = '<?php echo ADMIN_SITE_URL;?>';
var ADMIN_TEMPLATES_URL = '<?php echo ADMIN_TEMPLATES_URL;?>';
var ADMIN_RESOURCE_URL = '<?php echo ADMIN_RESOURCE_URL;?>';
var SITEURL = '<?php echo ADMIN_SITE_URL;?>';
</script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.bgColorSelector.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/admincp.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js" id="cssfile2"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="admincp-map ui-widget-content" nctype="map_nav" style="display:none;" id="draggable" >
  <div class="title ui-widget-header" >
    <h3><?php echo $lang['nc_admin_navigation'];?></h3>
    <h5><?php echo $lang['nc_admin_navigation_subhead'];?></h5>
    <span><a nctype="map_off" href="JavaScript:void(0);">X</a></span> </div>
  <div class="content"> <?php echo $output['map_nav'];?> </div>
  <script>
//固定层移动
$(function(){
	//管理显示与隐藏
            $("#admin-manager-btn").click(function () {
                if ($(".manager-menu").css("display") == "none") {
                    $(".manager-menu").css('display', 'block'); 
					$("#admin-manager-btn").attr("title","关闭快捷管理"); 
					$("#admin-manager-btn").removeClass().addClass("arrow-close");
                }
                else {
                    $(".manager-menu").css('display', 'none');
					$("#admin-manager-btn").attr("title","显示快捷管理");
					$("#admin-manager-btn").removeClass().addClass("arrow");
                }           
            });
	
	$("#draggable").draggable({
		handle: "div.title"
	});
	$("div.title").disableSelection()

	$('#_pic').change(uploadChange);
	function uploadChange(){
		var filepath=$(this).val();
		var extStart=filepath.lastIndexOf(".");
		var ext=filepath.substring(extStart,filepath.length).toUpperCase();
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			alert("file type error");
			$(this).attr('value','');
			return false;
		}
		if ($(this).val() == '') return false;
		ajaxFileUpload();
	}
	function ajaxFileUpload()
	{
		$.ajaxFileUpload
		(
			{
				url:'<?php echo ADMIN_SITE_URL?>/index.php?act=common&op=pic_upload&type=admin_avatar&form_submit=ok&uploadpath=<?php echo ATTACH_ADMIN_AVATAR;?>',
				secureuri:false,
				fileElementId:'_pic',
				dataType: 'json',
				success: function (data, status)
				{
					if (data.status == 1){
						ajax_form('cutpic','<?php echo $lang['nc_cut'];?>','<?php echo ADMIN_SITE_URL?>/index.php?act=common&op=pic_cut&type=admin_avatar&x=100&y=100&resize=1&ratio=1&url='+data.url,690);
					}else{
						alert(data.msg);
					}
					$('#_pic').bind('change',uploadChange);
				},
				error: function (data, status, e)
				{
					alert('上传失败');
					$('#_pic').bind('change',uploadChange);
				}
			}
		)
	};
});
//裁剪图片后返回接收函数
function call_back(picname){
    $.getJSON('index.php?act=index&op=save_avatar&avatar=' + picname, function(data){
        if (data) {
            $('img[nctype="admin_avatar"]').attr('src', '<?php echo UPLOAD_SITE_URL . '/' . ATTACH_ADMIN_AVATAR?>/' + picname);
        }
    });
}
</script> 
</div>
<div class="admincp-header">
  <div class="bgSelector"></div>
  <div id="foldSidebar"><i class="fa fa-outdent " title="展开/收起侧边导航"></i></div>
  <div class="admincp-name">
    <h2>好商城V5<br>平台系统管理中心</h2>
  </div>
  <div class="nc-module-menu">
    <ul class="nc-row">
      <?php echo $output['top_nav'];?>
    </ul>
  </div>
  <div class="admincp-header-r">
    <div class="manager">
      <dl>
        <dt class="name"><?php echo $output['admin_info']['name'];?></dt>
        <dd class="group"><?php echo $output['admin_info']['gname'];?></dd>
      </dl>
      <span class="avatar">
      <input name="_pic" type="file" class="admin-avatar-file" id="_pic" title="设置管理员头像"/>
      <img alt="" nctype="admin_avatar" src="<?php if (cookie('admin_avatar') != '') { echo UPLOAD_SITE_URL . '/' . ATTACH_ADMIN_AVATAR . '/' . cookie('admin_avatar'); } else {echo ADMIN_TEMPLATES_URL.'/images/login/admin.png';}?>"> </span><i class="arrow" id="admin-manager-btn" title="显示快捷管理菜单"></i>
      <div class="manager-menu">
        <div class="title">
          <h4>最后登录</h4>
          <a href="javascript:void(0);" onclick="CUR_DIALOG = ajax_form('modifypw', '修改密码', '<?php echo urlAdmin('index', 'modifypw');?>');" class="edit-password">修改密码</a> </div>
        <div class="login-date">
          <?php if($output['admin_info']['time'] > 0) { echo date('Y-m-d H:i:s', $output['admin_info']['time']);} else { echo '--';}?>
          <span>(IP:
          <?php if (!empty($output['admin_info']['ip'])) { echo $output['admin_info']['ip'];} else { echo '--';}?>
          )</span> </div>
        <div class="title">
          <h4>常用操作</h4>
          <a href="javascript:void(0)" class="add-menu">添加菜单</a> </div>
        <?php if (is_array($output['quicklink'])) {?>
        <ul class="nc-row" nctype="quick_link">
          <?php foreach ($output['quicklink'] as $key => $val) {?>
          <li><a href="javascript:void(0);" onclick="openItem('<?php echo $key;?>')"><?php echo $val;?></a></li>
          <?php }?>
        </ul>
        <?php }?>
      </div>
    </div>
    <ul class="operate nc-row">
      <li style="display: none !important;" nctype="pending_matters"><a class="toast show-option" href="javascript:void(0);" onclick="$.cookie('commonPendingMatters', 0, {expires : -1});ajax_form('pending_matters', '待处理事项', '<?php echo urlAdmin('common', 'pending_matters');?>', '480');" title="查看待处理事项">&nbsp;<em>0</em></a></li>
      <li><a class="sitemap show-option" nctype="map_on" href="javascript:void(0);" title="查看全部管理菜单">&nbsp;</a></li>
      <li><a class="style-color show-option" id="trace_show" href="javascript:void(0);" title="给管理中心换个颜色">&nbsp;</a></li>
      <li><a class="homepage show-option" target="_blank" href="<?php echo SHOP_SITE_URL;?>" title="新窗口打开商城首页">&nbsp;</a></li>
      <li><a class="login-out show-option" href="index.php?act=index&op=logout" title="安全退出管理中心">&nbsp;</a></li>
    </ul>
  </div>
  <div class="clear"></div>
</div>
<div class="admincp-container unfold">
  <div class="admincp-container-left">
    <div class="top-border"><span class="nav-side"></span><span class="sub-side"></span></div>
    <?php echo $output['left_nav'];?>
    <div class="about" title="关于系统" onclick="ajax_form('about', '', '<?php echo urlAdmin('aboutus');?>', 640);"><i class="fa fa-copyright"></i><span>33HAO.com</span></div>
  </div>
  <div class="admincp-container-right">
    <div class="top-border"></div>
    <iframe src="" id="workspace" name="workspace" style="overflow: visible;" frameborder="0" width="100%" height="94%" scrolling="yes" onload="window.parent"></iframe>
  </div>
</div>
</body>
</html>
