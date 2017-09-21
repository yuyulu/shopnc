<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <a class="back" href="index.php?act=vr_groupbuy&op=class_list" title="返回列表">
        <i class="fa fa-arrow-circle-o-left"></i>
      </a>
      <div class="subject">
        <h3>虚拟抢购 - 编辑虚拟抢购分类“<?php echo $output['class']['class_name']; ?>”</h3>
        <h5>商家可设置其虚拟抢购活动的分类以便于会员检索</h5>
      </div>
    </div>
  </div>
  <form id="add_form" method="post" enctype="multipart/form-data" action="index.php?act=vr_groupbuy&op=class_edit">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="submit_type" id="submit_type" value="" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="class_name"><em>*</em>分类名称</label>
        </dt>
        <dd class="opt">
          <input type="text" name="class_name" id="class_name" class="input-txt" value="<?php echo $output['class']['class_name'];?>">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="class_sort"><em>*</em><?php echo $lang['nc_sort'];?></label>
        </dt>
        <dd class="opt">
          <input id="class_sort" name="class_sort" type="text" class="input-txt" value="<?php echo $output['class']['class_sort'];?>" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['class_sort_explain'];?></p>
        </dd>
      </dl>
      <div class="bot">
        <input type="hidden" name="class_id" value="<?php echo $output['class']['class_id'];?>">
        <a id="submit" href="javascript:void(0)" class="ncap-btn-big ncap-btn-green"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('#submit').click(function(){
        $('#add_form').submit();
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
            class_name: {
                required : true,
                maxlength : 10
            },
            class_sort: {
                required : true,
                digits: true,
                max: 255,
                min: 0
            }
        },
        messages : {
            class_name: {
                required : "<i class='fa fa-exclamation-circle'></i>分类名称不能为空",
                maxlength : jQuery.validator.format("分类名称长度最多10个字符")
            },
            class_sort: {
                required : "<i class='fa fa-exclamation-circle'></i>排序不能为空",
                digits: "<i class='fa fa-exclamation-circle'></i>排序必须是数字,且数值0-255",
                max : jQuery.validator.format("排序必须是数字,且数值0-255"),
                min : jQuery.validator.format("排序必须是数字,且数值0-255")
            }
        }
    });
});
</script>
