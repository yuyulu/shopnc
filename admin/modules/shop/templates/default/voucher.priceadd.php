<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <a class="back" href="<?php echo urlAdminShop('voucher', 'pricelist'); ?>" title="返回列表">
        <i class="fa fa-arrow-circle-o-left"></i>
      </a>
      <div class="subject">
        <h3>店铺代金券 - 面额设置</h3>
        <h5>新建/编辑商家发放代金券时可选的代金券面额</h5>
      </div>
    </div>
  </div>

  <form id="add_form" method="post" action="index.php?act=voucher&op=<?php echo $output['menu_key']; ?>">
    <input type="hidden" id="form_submit" name="form_submit" value="ok"/>
    <input type="hidden" name="priceid" value="<?php echo $output['info']['voucher_price_id'];?>"/>
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['admin_voucher_price_title'];?>(<?php echo $lang['currency_zh'];?>)</label>
        </dt>
        <dd class="opt">
          <input type="text" id="voucher_price" name="voucher_price" class="input-txt" value="<?php echo $output['info']['voucher_price'];?>">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['admin_voucher_price_describe'];?></label>
        </dt>
        <dd class="opt">
          <textarea name="voucher_price_describe" rows="6" class="tarea" id="voucher_price_describe"><?php echo $output['info']['voucher_price_describe'];?></textarea>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['admin_voucher_price_points'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="voucher_points" name="voucher_points" class="input-txt" value="<?php echo $output['info']['voucher_defaultpoints'] >0?$output['info']['voucher_defaultpoints']:0;?>">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['admin_voucher_price_points_tip'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){
	$("#submitBtn").click(function(){
		$("#add_form").submit();
	});
	//页面输入内容验证
	$("#add_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },

        rules : {
        	voucher_price_describe: {
                required : true,
                maxlength : 255
        	},
        	voucher_price: {
                required : true,
                digits : true,
                min : 1
            },
            voucher_points: {
                digits : true
            }
        },
        messages : {
      		voucher_price_describe: {
       			required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_voucher_price_describe_error'];?>',
       			maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_voucher_price_describe_lengtherror'];?>'
	    	},
	    	voucher_price: {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_voucher_price_error'];?>',
                digits : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_voucher_price_error'];?>',
                min : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_voucher_price_error'];?>'
		    },
		    voucher_points: {
		    	digits : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_voucher_price_points_error'];?>'
            }
        }
	});
});
</script>