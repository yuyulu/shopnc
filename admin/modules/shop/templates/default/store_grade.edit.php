<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=store_grade&op=store_grade" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['store_grade'];?> - 编辑“<?php echo $output['grade_array']['sg_name'];?>”选项</h3>
        <h5><?php echo $lang['store_grade_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="grade_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="sg_id" value="<?php echo $output['grade_array']['sg_id'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="sg_name"><em>*</em><?php echo $lang['store_grade_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['grade_array']['sg_name'];?>" id="sg_name" name="sg_name" class="input-txt">
          </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="sg_goods_limit"><?php echo $lang['allow_pubilsh_product_num'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['grade_array']['sg_goods_limit'];?>" id="sg_goods_limit" name="sg_goods_limit" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['zero_said_no_limit'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label> <?php echo $lang['allow_upload_album_num'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['grade_array']['sg_album_limit'];?>" id="sg_album_limit" name="sg_album_limit" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['zero_said_no_limit'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="skin_limit"><?php echo $lang['optional_template_num'];?></label>
        </dt>
        <dd class="opt"><?php echo $output['grade_array']['sg_template_number'];?> <span class="grey">(<?php echo $lang['in_store_grade_list_set'];?>)</span><span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="skin_limit"><?php echo $lang['additional_features'];?></label>
        </dt>
        <dd class="opt">
          <ul class="nofloat">
            <li>
              <input type="checkbox" <?php if(@in_array('editor_multimedia',$output['grade_array']['sg_function'])){ ?>checked="checked"<?php } ?> id="function_editor_multimedia" value="editor_multimedia" name="sg_function[]">
              <label for="function_editor_multimedia"><?php echo $lang['editor_media_features'];?></label>
            </li>
          </ul>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="sg_price"><em>*</em><?php echo $lang['charges_standard'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['grade_array']['sg_price'];?>" id="sg_price" name="sg_price" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['charges_standard_notice'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['application_note'];?></label>
        </dt>
        <tr>
          <dd class="opt">
            <textarea id="sg_description" rows="6" class="tarea" name="sg_description"><?php echo $output['grade_array']['sg_description'];?></textarea>
            <span class="err"></span>
            <p class="notic"><?php echo $lang['application_note_notice'];?></p>
          </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="sg_sort"><?php echo $lang['grade_sortname']; //级别?></label>
        </dt>
        <dd class="opt">
          <?php if ($output['grade_array']['sg_id'] == 1){?>
          <input type="text" value="<?php echo $output['grade_array']['sg_sort'];?>" id="sg_sort" name="sg_sort" class="input-txt" readonly>
          <?php }else{?>
          <input type="text" value="<?php echo $output['grade_array']['sg_sort'];?>" id="sg_sort" name="sg_sort" class="input-txt">
          <?php }?>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['grade_sort_tip']; //数值越大表明级别越高?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#grade_form").valid()){
     $("#grade_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$('#grade_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },

        rules : {
            sg_name : {
                required : true,
                remote   : {
                url :'index.php?act=store_grade&op=ajax&branch=check_grade_name',
                type:'get',
                data:{
                        sg_name : function(){
                        	return $('#sg_name').val();
                        },
                        sg_id  : '<?php echo $output['grade_array']['sg_id'];?>'
                    }
                }
            },
			sg_price : {
                required  : true,
                number : true,
                min : 0
            },
            sg_goods_limit : {
                digits  : true
            },
            sg_space_limit : {
                digits : true
            },
            sg_sort : {
            	required  : true,
                digits  : true,
                remote   : {
	                url :'index.php?act=store_grade&op=ajax&branch=check_grade_sort',
	                type:'get',
	                data:{
	                        sg_sort : function(){
	                        	return $('#sg_sort').val();
	                        },
	                        sg_id  : '<?php echo $output['grade_array']['sg_id']; ?>'
	                    }
                }
            }
        },
        messages : {
            sg_name : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['store_grade_name_no_null'];?>',
                email   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['now_store_grade_name_is_there'];?>'
            },
			sg_price : {
                required  : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['charges_standard_no_null'];?>',
                number : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['charges_standard_no_null'];?>',
                min : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['charges_standard_no_null'];?>'
            },
            sg_goods_limit : {
                digits : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['only_lnteger'];?>'
            },
            sg_space_limit : {
                digits  : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['only_lnteger'];?>'
            },
            sg_sort  : {
            	required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['grade_add_sort_null_error']; //级别信息不能为空?>',
                digits   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['only_lnteger'];?>',
                remote   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['add_gradesortexist']; //级别已经存在?>'
            }
        }
    });
});
</script> 
