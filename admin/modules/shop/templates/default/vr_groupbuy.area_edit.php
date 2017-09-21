<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <a class="back" href="javascript:history.go(-1);" title="返回">
        <i class="fa fa-arrow-circle-o-left"></i>
      </a>
      <div class="subject">
        <h3>虚拟抢购 - 编辑虚拟抢购区域“<?php echo $output['area']['area_name']; ?>”</h3>
        <h5>商家可设置其虚拟抢购活动的区域以便于会员检索</h5>
      </div>
    </div>
  </div>
  <form id="add_form" method="post" action="index.php?act=vr_groupbuy&op=area_edit">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="area_name"><em>*</em>区域名称</label>
        </dt>
        <dd class="opt">
          <input type="text" name="area_name" id="area_name" class="input-txt" value="<?php echo $output['area']['area_name']; ?>" >
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
            <?php foreach($output['letter'] as $lv){?>
            <option value='<?php echo $lv;?>' <?php if($lv==$output['area']['first_letter']){ echo 'selected';}?> ><?php echo $lv;?></option>
            <?php }?>
          </select>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="area_number">区号</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['area']['area_number'];?>" name="area_number" id="area_number" class="input-txt">
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="post">邮编</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['area']['post'];?>" name="post" id="post" class="input-txt">
          <p class="notic"> </p>
        </dd>
      </dl>
      <?php if ($output['area']['parent_area_id']) { ?>
      <dl class="row">
        <dt class="tit">
          <label for="area_class"> 上级区域</label>
        </dt>
        <dd class="opt"> <?php echo $output['parent_area_name'];?>
          <input type='hidden' name='parent_area_id' value="<?php echo $output['area_id'];?>">
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
            <label for="hot1" class="cb-enable <?php if($output['area']['hot_city']=='1'){ echo 'selected';}?>"><?php echo $lang['open'];?></label>
            <label for="hot0" class="cb-disable <?php if($output['area']['hot_city']=='0'){ echo 'selected';}?>" ><?php echo $lang['close'];?></label>
            <input id="hot1" name="is_hot"  value="1" type="radio" <?php if($output['area']['hot_city']=='1'){ echo 'checked';}?> >
            <input id="hot0" name="is_hot"  value="0" type="radio" <?php if($output['area']['hot_city']=='0'){ echo 'checked';}?> >
          </div>
          <p class="notic"> </p>
        </dd>
      </dl>
      <?php } ?>
      <div class="bot"><a id="submit" href="javascript:void(0)" class="ncap-btn-big ncap-btn-green"><?php echo $lang['nc_submit'];?></a>
        <input type="hidden" name="area_id" value="<?php echo $output['area']['area_id'];?>">
      </div>
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
