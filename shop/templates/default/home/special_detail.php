<?php defined('In33hao') or exit('Access Invalid!');?>
<style type="text/css">
.no-content{ font: normal 16px/20px Arial, "microsoft yahei"; color: #999999; text-align: center; padding: 150px 0; 
}
.nc-appbar-tabs a.compare { display: none !important;}
</style>
<div id="body">
  <div id="cms_special_content" class="cms-content">
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $("#cms_special_content").load("<?php echo $output['special_file']; ?>");
});
</script> 

