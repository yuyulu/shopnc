<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=hao&op=rc" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>首页推荐词 - 设置</h3>
        <h5>首页推荐词用于首页面显示的链接推送</h5>
      </div>
    </div>
  </div>

  <form id="rc_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="id" value='<?php echo $_GET['id']?>'>
    <div class="ncap-form-default">
       
       <dl class="row">
        <dt class="tit">
          <label for="rc_name"><em>*</em>推荐词名称</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['current_info']['name'];?>" name="rc_name" id="rc_name" class="input-txt">
          <span class="err"></span>
          <p class="notic">推荐词显示的名称</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="rc_name"><em>*</em>推荐词链接</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['current_info']['value'];?>" name="rc_value" id="rc_value" class="input-txt">
          <span class="err"></span>
          <p class="notic">推荐词链接必须包含‘http://’</p>
        </dd>
      </dl>

      <dl class="row">
        <dt class="tit">
          <label>是否高亮显示</label>
        </dt>
        <dd class="opt">
          <label>
            <input name="rc_blod"  type="radio" value="1" <?php if($output['current_info']['is_blod']==1) echo 'checked="checked"'?>>
            是</label>
          <label>
            <input type="radio" name="rc_blod" value="2" <?php if($output['current_info']['is_blod']==2) echo 'checked="checked"'?>>
            否</label>
            <span class="err"></span>
          <p class="notic">高亮这里一定要选择哦 否则无法保存</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#rc_form").valid()){
        $("#rc_form").submit();
    }
	});
});

$(document).ready(function(){
	$('#rc_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            rc_name : {
                required : true
            },
            rc_value : {
                required : true
            },
			rc_blod : {
                required : true
            }
        },
        messages : {
            rc_name : {
                required : '<i class="fa fa-exclamation-circle"></i>请填写推荐词名称'
            },
            rc_value : {
            	required : '<i class="fa fa-exclamation-circle"></i>请填写推荐词链接'
            },
			rc_blod : {
            	required : '<i class="fa fa-exclamation-circle"></i>请选择是否高亮'
            }
        }
    });
});
</script>