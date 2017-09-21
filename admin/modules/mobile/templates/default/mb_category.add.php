<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=mb_category&op=mb_category_list" title="返回分类图片列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['link_index_mb_category'];?> - <?php echo $lang['nc_new'];?></h3>
        <h5><?php echo $lang['link_index_mb_category_subhead'];?></h5>
      </div>
      
    </div>
  </div>
  <form id="link_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="link_catetory"><em>*</em><?php echo $lang['link_index_category'];?></label>
        </dt>
        <dd class="opt">
          <select name="link_category" id="link_category">
            <?php foreach($output['goods_class'] as $k=>$v){?>
            <?php if ($v['gc_parent_id'] != '0') break;?>
            <option value="<?php echo $v['gc_id']?>"><?php echo $v['gc_name']?></option>
            <?php }?>
          </select>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['link_add_href'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="link_pic"><em>*</em><?php echo $lang['link_index_pic_sign'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"> <span class="type-file-box">
            <input name="link_pic" type="file" class="type-file-file" id="link_pic" size="30" hidefocus="true">
            </span></div>
          <span class="err"></span>
          <p class="notic">展示图片，建议大小90x90像素PNG图片。</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(function(){
	//图片上传
	var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text'/><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />"
	$(textButton).insertBefore("#link_pic");
	$("#link_pic").change(function(){
	$("#textfield1").val($("#link_pic").val());
	});
	//按钮先执行验证再提交表单
	$("#submitBtn").click(function(){
    if($("#link_form").valid()){
     $("#link_form").submit();
	}
    });
//
	$('#link_form').validate({
        errorPlacement: function(error, element){
        	var error_td = element.parents('dl').find('span.err');
            error_td.append(error);
        },
        success: function(label){
            label.addClass('valid');
        },
        rules : {
            link_category  : {
                required : true
            },
            link_pic  : {
                required : true
            }
        },
        messages : {
            link_category  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['link_add_category_null'];?>'
            },
            link_pic  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['link_add_pic_null'];?>'
            }
        }
    });
});
</script> 
