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
<meta charset="<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<link href="<?php echo ADMIN_TEMPLATES_URL?>/css/index.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="msgpage">
  <div class="msgbox">
    <div class="pic"></div>
    <div class="con">
      <?php require_once($tpl_file); ?>
    </div>
    <?php if ($output['is_show'] == 1){ ?>
    <div class="scon"><?php echo $lang['nc_auto_redirect'];?></div>
    <div class="button">
      <?php if (is_array($output['url'])){ foreach($output['url'] as $k => $v){ ?>
      <a href="<?php echo $v['url'];?>" class="ncap-btn"><?php echo $v['msg'];?></a>
      <?php } ?>
      <script type="text/javascript"> window.setTimeout("javascript:location.href='<?php echo $output['url'][0]['url'];?>'", <?php echo $time;?>); </script>
      <?php }else { if ($output['url'] != ''){ ?>
      <a href="<?php echo $output['url'];?>" class="ncap-btn"><?php echo $lang['nc_back_to_pre_page'];?></a> 
      <script type="text/javascript"> window.setTimeout("javascript:location.href='<?php echo $output['url'];?>'", <?php echo $time;?>); </script>
      <?php }else { ?>
      <a href="javascript:history.back()" class="ncap-btn"><?php echo $lang['nc_back_to_pre_page'];?></a> 
      <script type="text/javascript"> window.setTimeout("javascript:history.back()", <?php echo $time;?>); </script>
      <?php } } ?>
    </div>
    <?php } ?>
    <div class="powerby"><?php echo $lang['nc_33hao_message'];?></div>
  </div>
</div>
</body>
</html>