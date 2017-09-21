<?php defined('In33hao') or exit('Access Invalid!');?>

<div>
  <form id="tag_form" method="post" action="<?php echo urlAdminShop('goods_class', 'tag_edit');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="id" value="<?php echo $output['tag_info']['gc_tag_id'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="attr_name">TAG名称</label>
        </dt>
        <dd class="opt"><?php echo $output['tag_info']['gc_tag_name'];?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="attr_sort">TAG值</label>
        </dt>
        <dd class="opt">
          <textarea name="tag_value" rows="6" class="tarea"><?php echo $output['tag_info']['gc_tag_value'];?></textarea>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a id="attrSubmitBtn" class="ncap-btn-big ncap-btn-green" href="JavaScript:void(0);"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
//按钮先执行验证再提交表单
$("#attrSubmitBtn").click(function(){
    ajaxpost('tag_form', '', '', 'onerror');
});
</script> 