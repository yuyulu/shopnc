<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=hao&op=lc" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>楼层快速直达 - 设置</h3>
        <h5>新增楼层快速直达用于前台楼层显示</h5>
      </div>
    </div>
  </div>

  <form id="lc_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="id" value='<?php echo $_GET['id']?>'>
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="lc_name"><em>*</em>楼层号</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['current_info']['value'];?>" name="lc_value" id="lc_value" class="input-txt">
          <span class="err"></span>
          <p class="notic">楼层号，例：1F</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="lc_name"><em>*</em>显示词</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['current_info']['name'];?>" name="lc_name" id="lc_name" class="input-txt">
          <span class="err"></span>
          <p class="notic">楼层显示词，例：手机</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#lc_form").valid()){
        $("#lc_form").submit();
    }
	});
});

$(document).ready(function(){
	$('#lc_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            lc_name : {
                required : true
            },
            lc_value : {
                required : true
            }
        },
        messages : {
            lc_name : {
                required : '<i class="fa fa-exclamation-circle"></i>请填写显示司'
            },
            lc_value : {
            	required : '<i class="fa fa-exclamation-circle"></i>请填写楼层号'
            }
        }
    });
});
</script>