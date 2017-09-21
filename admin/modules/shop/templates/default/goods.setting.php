<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>商品管理</h3>
        <h5>商城所有商品索引及管理</h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <form method="post" name="form_goodsverify">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['goods_is_verify']?></label>
        </dt>
      <dd class="opt">
        <div class="onoff">
        <label for="rewrite_enabled"  class="cb-enable <?php if($output['list_setting']['goods_verify'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_yes'];?>"><?php echo $lang['nc_yes'];?></label>
        <label for="rewrite_disabled" class="cb-disable <?php if($output['list_setting']['goods_verify'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_no'];?>"><?php echo $lang['nc_no'];?></label>
        <input id="rewrite_enabled" name="goods_verify" <?php if($output['list_setting']['goods_verify'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
        <input id="rewrite_disabled" name="goods_verify" <?php if($output['list_setting']['goods_verify'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
        </div>
        <p class="notic"><?php echo $lang['open_rewrite_tips'];?></p>
      </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.form_goodsverify.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
