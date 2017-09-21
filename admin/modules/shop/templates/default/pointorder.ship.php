<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <a class="back" href="<?php echo urlAdminShop('pointprod', 'pointorder_list'); ?>" title="返回列表">
        <i class="fa fa-arrow-circle-o-left"></i>
      </a>
      <div class="subject">
        <h3>积分兑换 - 设置发货</h3>
        <h5>设置商城会员积分礼品兑换的发货信息</h5>
      </div>
    </div>
  </div>

  <?php if (is_array($output['order_info']) && count($output['order_info'])>0){ ?>
  <form id="ship_form" method="post" name="ship_form" action="index.php?act=pointprod&op=order_ship&id=<?php echo $_GET['id']; ?>">
    <input type="hidden" name="form_submit" value="ok"/>
    <div class="ncap-form-default">

      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_pointorder_membername']; ?></label>
        </dt>
        <dd class="opt"><?php echo $output['order_info']['point_buyername']; ?><span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>

      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_pointorder_ordersn']; ?></label>
        </dt>
        <dd class="opt">
          <?php echo $output['order_info']['point_ordersn']; ?>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>

      <dl class="row">
        <dt class="tit">
          <label for="shippingcode"><em>*</em><?php echo $lang['admin_pointorder_shipping_code']; ?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="shippingcode" name="shippingcode" class="input-txt" value="<?php echo $output['order_info']['point_shippingcode']; ?>">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>

      <dl class="row">
        <dt class="tit">
          <label><em>*</em>配送公司</label>
        </dt>
        <dd class="opt">
          <select name="e_code">
              <option value="">不使用配送公司</option>
              <?php foreach($output['express_list'] as $v) {?>
              <option value="<?php echo $v['e_code'];?>" <?php echo $output['order_info']['point_shipping_ecode']==$v['e_code']?'selected':'';?> ><?php echo $v['e_name'];?></option>
              <?php } ?>
          </select>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>

      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
  <?php } else { ?>
  <div class='msgdiv'> <?php echo $output['errormsg']; ?> <br>
    <br>
    <a class="forward" href="index.php?act=pointprod&amp;op=pointorder_list"><?php echo $lang['admin_pointorder_gobacklist']; ?></a> </div>
  <?php } ?>
</div>
<script type="text/javascript">
$(function(){
	//按钮先执行验证再提交表单
	$("#submitBtn").click(function(){
	    if($("#ship_form").valid()){
	     $("#ship_form").submit();
		}
	});
	$('#ship_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            shippingcode  : {
                required : true
            },
            shippingdesc  : {
                required : true
            }
        },
        messages : {
            shippingcode  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_pointorder_ship_code_nullerror']; ?>'
            },
            shippingdesc  : {
                required : '<i class="fa fa-exclamation-circle"></i>请填写发货描述'
            }
        }
    });
});
</script>