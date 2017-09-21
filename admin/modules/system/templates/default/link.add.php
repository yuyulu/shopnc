<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
 <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=link&amp;op=link" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>连接管理 - 新增友情连接</h3>
        <h5>网站系统友情连接</h5>
      </div>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="link_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
	<dl class="row">
        <dt class="tit">
          <label><em>*</em>连接名称</label>
        </dt>
        <dd class="opt">
		<input type="text" value="" name="link_title" id="link_title" class="input-txt">
          <span class="err"></span>
          <p class="notic">合作伙伴的名称</p>
        </dd>
      </dl>
	  
	  <dl class="row">
        <dt class="tit">
          <label><em>*</em>连接地址</label>
        </dt>
        <dd class="opt">
          <input type="text" value="http://" name="link_url" id="link_url" class="input-txt">
          <span class="err"></span>
          <p class="notic">合作伙伴的链接地址</p>
        </dd>
      <dl class="row" id="link_pic">
        <dt class="tit">
          <label for="file_link_pic">连接图片</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="type-file-box">
            <input type="file" class="type-file-file" id="file_link_pic" name="link_pic" size="30" hidefocus="true"  nc_type="upload_file_link_pic" title="点击按钮选择文件并提交表单后上传生效">
            </span></div>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['adv_edit_support'];?>gif,jpg,jpeg,png</p>
        </dd>
      </dl>
	  	  <dl class="row">
        <dt class="tit">
          <label><em>*</em>排序</label>
        </dt>
        <dd class="opt">
          <input type="text" value="255" name="link_sort" id="link_sort" class="input-txt">
          <span class="err"></span>
          <p class="notic">数字越小越靠前</p>
        </dd></dl>
<div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#link_form").valid()){
     $("#link_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$('#link_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        success: function(label){
            label.addClass('valid');
        },
        rules : {
            link_title : {
                required : true
            },
            link_url  : {
                required : true,
                url      : true
            },
            link_sort : {
                number   : true
            }
        },
        messages : {
            link_title : {
                required : '<?php echo $lang['link_add_title_null'];?>'
            },
            link_url  : {
                required : '<?php echo $lang['link_add_url_null'];?>',
                url      : '<?php echo $lang['link_add_url_wrong'];?>'
            },
            link_sort  : {
                number   : '<?php echo $lang['link_add_sort_int'];?>'
            }
        }
    });
});
</script> 
<script type="text/javascript">
$(function(){
	var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />"
    $(textButton).insertBefore("#file_link_pic");
    $("#file_link_pic").change(function(){
	$("#textfield1").val($("#file_link_pic").val());
    });
});
</script>
