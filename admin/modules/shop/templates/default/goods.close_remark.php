<?php defined('In33hao') or exit('Access Invalid!');?>

<form method="post" name="form1" id="form1" action="<?php echo urlAdminShop('goods', 'goods_lockup');?>">
  <input type="hidden" name="form_submit" value="ok" />
  <input type="hidden" value="<?php echo $output["common_info"]["goods_commonid"];?>" name="commonid">

  <div class="ncap-form-default">
  <dl class="row">
      <dt class="tit">违规商品货号</dt><dd class="opt"><?php echo $output['common_info']['goods_commonid'];?></dd></dl>
      <dl class="row">
      <dt class="tit">违规商品名称</dt><dd class="opt"><?php echo $output['common_info']['goods_name'];?></dd></dl>
    <dl class="row">
      <dt class="tit">
        <label for="close_reason">违规下架理由</label>
      </dt>
      <dd class="opt">
        <textarea rows="6" class="tarea" cols="60" name="close_reason" id="close_reason"></textarea>
      </dd>
    </dl>
    <div class="bot"><a href="javascript:void(0);" class="ncap-btn-big ncap-btn-green" nctype="btn_submit"><?php echo $lang['nc_submit'];?></a></div>
  </div>
</form>
<script>
$(function(){
    $('a[nctype="btn_submit"]').click(function(){
        ajaxpost('form1', '', '', 'onerror');
    });
});
</script>