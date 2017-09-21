<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="eject_con">
  <?php if ($output['error']) {?>
  <div class="error">参数错误</div>
  <?php } else {?>
  <div class="chain-goods-id">
    <div class="pic-thumb"><img src="<?php echo thumb($output['goodscommon_info'], 60)?>"/></div>
    <dl>
      <dt><?php echo $output['goodscommon_info']['goods_name'];?></dt>
      <dd>SPU：<?php echo $output['goodscommon_info']['goods_commonid']?></dd>
    </dl>
  </div>
  <form method="post" action="<?php echo urlChain('goods', 'set_stock');?>" id="stock_form">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="goods_commonid" value="<?php echo $_GET['common_id']; ?>" />
    <div class="content">
      <table class="stock-table">
        <thead>
          <tr>
            <?php foreach ($output['spec_name'] as $val) {?>
            <th class="w60"><?php echo $val?></th>
            <?php }?>
            <th>-</th>
            <th class="w100 tl">商家货号</th>
            <th class="w100 tl">价格</th>
            <th class="w50">库存</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($output['goods_array'] as $key => $val) {?>
          <tr>
            <?php foreach ($output['spec_name'] as $k => $v) {?>
            <td class="stock"><?php echo $val['goods_spec'][$k];?></td>
            <?php }?>
            <td>-</td>
            <td class="tl"><?php echo $val['goods_serial'];?></td>
            <td class="tl"><?php echo $lang['currency'].ncPriceFormat($val['goods_price']); ?></td>
            <td><input type="text" class="text w40" name="stock[<?php echo $key?>]" id="stock" value="<?php echo intval($output['stock_info'][$key]['stock']);?>" /></td>
          </tr>
          <?php }?>
        </tbody>
      </table>
    </div>
    <div class="bottom">
      <label class="submit-border">
        <input type="submit" class="submit" value="提交门店库存设置"/>
      </label>
    </div>
  </form>
  <?php }?>
</div>
<script>
$(function(){
    $('#stock_form').validate({
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
               $('#warning').show();
        },
        submitHandler:function(form){
            ajaxpost('stock_form', '', '', 'onerror');
        },
        rules : {
            stock : {
                digits: true
            }
        },
        messages : {
            stock : {
                digits: '<i class="icon-exclamation-sign"></i>请填写整数'
            }
        }
    });
});
</script> 
