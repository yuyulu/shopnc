<?php defined('In33hao') or exit('Access Invalid!');?>
<?php include template('layout/common_layout');?>
<?php include template('layout/cur_local');?>
<link href="<?php echo MEMBER_TEMPLATES_URL;?>/css/member.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/member.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ToolTip.js"></script>
<style>.nch-breadcrumb-layout { display: block !important;}</style>
<div class="ncm-container">
    <?php require_once($tpl_file);?>
  <div class="clear"></div>
</div>
<?php require_once template('layout/footer');?>
</body></html>