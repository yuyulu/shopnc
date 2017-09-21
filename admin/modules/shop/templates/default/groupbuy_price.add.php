<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <a class="back" href="<?php echo urlAdminShop('groupbuy', 'price_list'); ?>" title="返回列表">
        <i class="fa fa-arrow-circle-o-left"></i>
      </a>
      <div class="subject">
        <h3>抢购管理 - 新增/编辑抢购价格区间</h3>
        <h5>商家可设置其抢购活动的价格区间以便于会员检索</h5>
      </div>
    </div>
  </div>

  <form id="add_form" method="post" action="index.php?act=groupbuy&op=price_save">
    <input name="range_id" type="hidden" value="<?php echo $output['price_info']['range_id'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="range_name"><em>*</em><?php echo $lang['range_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['price_info']['range_name'];?>" name="range_name" id="range_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['price_range_tip'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="range_start"><em>*</em><?php echo $lang['range_start'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['price_info']['range_start'];?>" name="range_start" id="range_start" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['price_range_price_tip'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="range_end"><em>*</em><?php echo $lang['range_end'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['price_info']['range_end'];?>" name="range_end" id="range_end" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['price_range_price_tip'];?></p>
        </dd>
      </dl>
      <div class="bot"><a id="submit" href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $("#submit").click(function(){
        $("#add_form").submit();
    });

	$('#add_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            range_name : {
                required : true
            },
            range_start : {
                required : true,
                digits : true
            },
            range_end : {
                required : true,
                digits : true
            }
        },
        messages : {
            range_name : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['range_name_error'];?>'
            },
            range_start : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['range_start_error'];?>',
                digits : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['range_start_error'];?>'
            },
            range_end : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['range_end_error'];?>',
                digits : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['range_end_error'];?>'
            }
        }
    });
});
</script>
