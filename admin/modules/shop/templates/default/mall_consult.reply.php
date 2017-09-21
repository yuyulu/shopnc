<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="javascript:history.back(-1)" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>平台客服  - <?php echo $output['consult_info']['is_reply'] == 0 ? '回复' : '编辑';?></h3>
        <h5>商城对用户咨询类型设定与处理</h5>
      </div>
    </div>
  </div>
  <form id="reply_form" method="post" name="reply_form">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="mc_id" value="<?php echo $output['consult_info']['mc_id'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">咨询人</dt>
        <dd class="opt"><?php echo $output['consult_info']['member_name'];?><span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">咨询内容</dt>
        <dd class="opt"><?php echo $output['consult_info']['mc_content'];?><span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">咨询时间</dt>
        <dd class="opt"><?php echo date('Y-m-d H:i:s', $output['consult_info']['mc_addtime']);?><span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">回复</dt>
        <dd class="opt">
          <textarea name="reply_content" class="tarea" rows="6"><?php echo $output['consult_info']['mc_reply'];?></textarea>
          <span class="err"></span>
          <p class="notic">不能超过255个字符。</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(function(){
    //按钮先执行验证再提交表单
    $("#submitBtn").click(function(){
        if($("#reply_form").valid()){
            $("#reply_form").submit();
        }
    });
    $("#reply_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            reply_content : {
                required : true,
                maxlength : 255
            }
        },
        messages : {
            reply_content : {
                required : '<i class="fa fa-exclamation-circle"></i>请填写咨询内容',
                maxlength: '<i class="fa fa-exclamation-circle"></i>咨询内容不能超过255个字符'
            }
        }
    });
});
</script>