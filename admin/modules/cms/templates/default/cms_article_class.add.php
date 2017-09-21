<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=cms_article_class&op=cms_article_class_list" title="返回文章分类列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_cms_article_class'];?> - <?php echo $lang['nc_new'];?></h3>
        <h5><?php echo $lang['nc_cms_article_class_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="add_form" method="post" action="index.php?act=cms_article_class&op=cms_article_class_save">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="class_name"><em>*</em><?php echo $lang['class_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="class_name" id="class_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['class_name_error'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="class_sort"><em>*</em><?php echo $lang['nc_sort'];?></label>
        </dt>
        <dd class="opt">
          <input id="class_sort" name="class_sort" type="text" class="input-txt" value="255" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['class_sort_explain'];?></p>
        </dd>
      </dl>
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
                required : "<i class='fa fa-exclamation-circle'></i><?php echo $lang['class_name_required'];?>",
                maxlength : jQuery.validator.format("<i class='fa fa-exclamation-circle'></i><?php echo $lang['class_name_maxlength'];?>")
            },
            class_sort: {
                required : "<i class='fa fa-exclamation-circle'></i><?php echo $lang['class_sort_required'];?>",
                digits: "<i class='fa fa-exclamation-circle'></i><?php echo $lang['class_sort_digits'];?>",
                max : jQuery.validator.format("<i class='fa fa-exclamation-circle'></i><?php echo $lang['class_sort_max'];?>"),
                min : jQuery.validator.format("<i class='fa fa-exclamation-circle'></i><?php echo $lang['class_sort_min'];?>")
            }
        }
    });
});
</script>