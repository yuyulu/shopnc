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

<link href="<?php echo ADMIN_TEMPLATES_URL?>/css/index.css" rel="stylesheet" type="text/css">
<link href="<?php echo ADMIN_RESOURCE_URL?>/font/css/font-awesome.min.css" rel="stylesheet" />
<link href="<?php echo ADMIN_RESOURCE_URL?>/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
<style type="text/css">html, body { overflow: visible;}</style>
<script type="text/javascript">
var SITEURL = '<?php echo SHOP_SITE_URL;?>';
var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';
var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';
var MICROSHOP_SITE_URL = '<?php echo MICROSHOP_SITE_URL;?>';
var CIRCLE_SITE_URL = '<?php echo CIRCLE_SITE_URL;?>';
var ADMIN_TEMPLATES_URL = '<?php echo ADMIN_TEMPLATES_URL;?>';
var LOADING_IMAGE = "<?php echo ADMIN_TEMPLATES_URL.DS.'images/loading.gif';?>";
var ADMIN_RESOURCE_URL = '<?php echo ADMIN_RESOURCE_URL;?>';
</script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery-ui/i18n/zh-CN.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/admin.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/dialog/dialog.js" id="dialog_js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/flexigrid.js"></script>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
</head>
<body style="background-color: #FFF; overflow: auto;">
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/jquery.picTip.js"></script>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<?php
	require_once($tpl_file);
?>
<?php if ($output['setting_config']['debug'] == 1){?>
<div id="think_page_trace" class="trace">
  <fieldset id="querybox">
    <legend><?php echo $lang['nc_debug_trace_title'];?></legend>
    <div> <?php print_r(Tpl::showTrace());?> </div>
  </fieldset>
</div>
<?php }?>
<div id="goTop"> <a href="JavaScript:void(0);" id="btntop"><i class="fa fa-angle-up"></i></a><a href="JavaScript:void(0);" id="btnbottom"><i class="fa fa-angle-down"></i></a></div>
</body>
</html>