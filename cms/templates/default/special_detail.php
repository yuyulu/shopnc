<?php defined('In33hao') or exit('Access Invalid!');?>
<div id="body">
  <div id="cms_special_content" class="cms-content">
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $("#cms_special_content").load("<?php echo $output['special_file']; ?>");
});
</script> 

