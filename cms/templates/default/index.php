<?php defined('In33hao') or exit('Access Invalid!');?>
<div id="cms_index_content" class="cms-content"></div>
<script type="text/javascript">
$(document).ready(function() {
    $("#cms_index_content").load("<?php echo UPLOAD_SITE_URL.DS.ATTACH_CMS.DS.'index_html'.DS.'index.html'; ?>");
});
</script> 

