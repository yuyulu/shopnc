<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <a class="back" href="javascript:history.go(-1);" title="返回">
        <i class="fa fa-arrow-circle-o-left"></i>
      </a>
      <div class="subject">
        <h3>虚拟抢购 - 新增虚拟抢购区域</h3>
        <h5>商家可设置其虚拟抢购活动的区域以便于会员检索</h5>
      </div>
    </div>
  </div>
  <form id="add_form" method="post" action="index.php?act=vr_groupbuy&op=area_add">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="area_name"><em>*</em>区域名称</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="area_name" id="area_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="first_letter"><em>*</em>首字母</label>
        </dt>
        <dd class="opt">
          <select name='first_letter'>
            <?php foreach($output['letter'] as $lk=>$lv){?>
            <option value='<?php echo $lv;?>'><?php echo $lv;?></option>
            <?php }?>
          </select>
          </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="area_number">区号</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="area_number" id="area_number" class="input-txt">
          <span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="post">邮编</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="post" id="post" class="input-txt">
          <span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <?php if ($output['area_id']) { ?>
      <dl class="row">
        <dt class="tit">
          <label for="area_class">上级区域</label>
        </dt>
        <dd class="opt"><?php echo $output['area_name']; ?>
          <input type='hidden' name='parent_area_id' value="<?php echo $output['area_id'];?>">
          <span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <?php } else { ?>
      <dl class="row">
        <dt class="tit">
          <label for="area_class">显示</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="hot1" class="cb-enable" ><?php echo $lang['open'];?></label>
            <label for="hot0" class="cb-disable selected" ><?php echo $lang['close'];?></label>
            <input id="hot1" name="is_hot"  value="1" type="radio">
            <input id="hot0" name="is_hot"  checked="checked" value="0" type="radio">
          </div>
          <p class="notic"> </p>
        </dd>
      </dl>
      <?php } ?>
      <div class="bot"><a id="submit" href="javascript:void(0)" class="ncap-btn-big ncap-btn-green"><?php echo $lang['nc_submit'];?></a></div>
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
        success: function(label){
            label.addClass('valid');
        },
        rules : {
            area_name: {
                required : true
            },
            area_number:{
                number: true
            },
            post:{
                number: true
            }
        },
        messages : {
            area_name: {
                required : '<i class="fa fa-exclamation-circle"></i>区域名称不能为空'
            },
            area_number:{
                number:'<i class="fa fa-exclamation-circle"></i>区号必须是数字'
            },
            post:{
                number:'<i class="fa fa-exclamation-circle"></i>邮编必须是数字'
            }
        }
    });
});
</script>
