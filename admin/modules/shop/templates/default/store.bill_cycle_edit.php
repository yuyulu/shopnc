<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=store&op=bill_cycle" title="返回<?php echo $lang['manage'];?>列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_store_manage'];?> - 设置“<?php echo $output['store_array']['store_name'];?>”的结算周期</h3>
        <h5><?php echo $lang['nc_store_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="homepage-focus" nctype="editStoreContent">
  <div class="title">
  <h3>设置结算周期</h3>
    </div>
    <form id="store_form" method="post">
    <input type="hidden" name="act" value="store" />
    <input type="hidden" name="op" value="bill_cycle_edit" />
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="store_id" value="<?php echo $output['store_array']['store_id'];?>" />
      <input type="hidden" name="store_name" value="<?php echo $output['store_array']['store_name'];?>" />
      <div class="ncap-form-default">
        <dl class="row">
          <dt class="tit">
            <label>店铺账号</label>
          </dt>
          <dd class="opt"><?php echo $output['store_array']['seller_name'];?><span class="err"></span>
            <p class="notic"></p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label for="store_name"><em>*</em>店铺名称</label>
          </dt>
          <dd class="opt">
            <?php echo $output['store_array']['store_name'];?>
            <span class="err"></span>
            <p class="notic"> </p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label for="bill_cycle">结算周期</label>
          </dt>
          <dd class="opt"><input type="text" maxlength="3" value="<?php echo $output['store_array']['bill_cycle'];?>" id="bill_cycle" name="bill_cycle" class="input-txt">
            <span class="err"></span>
            <p class="notic">结算周期单：天，留空时表示结算周基本为一个自然月，输入内容时格式须为大于0的整数，最大不得超过180天</p>
          </dd>
        </dl>

        <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';
$(function(){

    //按钮先执行验证再提交表单
    $("#submitBtn").click(function(){
        if($("#store_form").valid()){
            $("#store_form").submit();
        }
    });

    $('#store_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
        	bill_cycle: {
        		digits : true,
        		max : 180,
        		min : 1
                 
              }
        },
        messages : {
        	bill_cycle: {
            	digits : '<i class="fa fa-exclamation-circle"></i>请正确输入结算周期',
            	max   : '<i class="fa fa-exclamation-circle"></i>结算周期不得大于180天',
            	min   : '<i class="fa fa-exclamation-circle"></i>结算周期不得小于1天'
            }
        }
    });
});
</script>