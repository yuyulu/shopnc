<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=article_class&op=article_class" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['article_class_index_class'];?> - <?php echo $lang['nc_edit'];?>“<?php echo $output['class_array']['ac_name'];?>”</h3>
        <h5><?php echo $lang['article_class_index_class_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="article_class_form" method="post" name="articleClassForm">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="ac_id" value="<?php echo $output['class_array']['ac_id'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="ac_name"><em>*</em><?php echo $lang['article_class_index_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['class_array']['ac_name'];?>" name="ac_name" id="ac_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['article_class_index_name'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="ac_sort"><?php echo $lang['nc_sort'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['class_array']['ac_sort'];?>" name="ac_sort" id="ac_sort" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['article_class_add_update_sort'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#article_class_form").valid()){
     $("#article_class_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$('#article_class_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            ac_name : {
                required : true,
                remote   : {
                url :'index.php?act=article_class&op=ajax&branch=check_class_name',
                type:'get',
                data:{
                    ac_name : function(){
                        return $('#ac_name').val();
                    },
                    ac_parent_id : function() {
                        return $('#ac_parent_id').val();
                    },
                    ac_id : '<?php echo $output['class_array']['ac_id'];?>'
                  }
                }
            },
            ac_sort : {
                number   : true
            }
        },
        messages : {
            ac_name : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['article_class_add_name_null'];?>',
                remote   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['article_class_add_name_exists'];?>'
            },
            ac_sort  : {
                number   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['article_class_add_sort_int'];?>'
            }
        }
    });
});
</script>