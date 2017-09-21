<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=contract&op=quitlist" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>消费者保障服务 - 编辑退出申请</h3>
        <h5>消费者保障服务查看与管理</h5>
      </div>
    </div>
  </div>
  <form id="quit_form" method="post" name="quit_form">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label>项目名称</label>
        </dt>
        <dd class="opt"><?php echo $output['quit_info']['ctq_itemname'];?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">
            <label>店铺名称</label>
        </dt>
        <dd class="opt"><?php echo $output['quit_info']['ctq_storename'];?></dd>
      </dl>
      <dl class="row">
          <dt class="tit">
            <label>申请时间</label>
          </dt>
          <dd class="opt"><?php echo @date('Y-m-d H:i:s',$output['quit_info']['ctq_addtime']);?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>状态</label>
        </dt>
        <dd class="opt">
            <?php foreach ($output['quit_auditstate_arr'] as $k=>$v){ ?>
            <label for="quit_state<?php echo $v['sign'];?>"><input type="radio" value="<?php echo $k;?>" id="quit_state<?php echo $v['sign'];?>" name="quit_state" <?php echo $v['sign'] == $output['quit_info']['ctq_auditstate']?'checked="checked"':'';?>><?php echo $v['name'];?></label>
            <?php } ?>
            <span class="err"></span>
            <p class="notic"></p>
        </dd>
      </dl>
        <dl id="refuse_dl" class="row">
            <dt class="tit">
                <label for="quit_remark">拒绝原因</label>
            </dt>
            <dd class="opt">
                <textarea id="quit_remark" name="quit_remark" class="w300"><?php echo $output['quit_info']['ctq_remark'];?></textarea>
                <span class="err"></span>
                <p class="notic">原因小于200个字符</p>
            </dd>
        </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){
    $("#refuse_dl").hide();
    $("[name='quit_state']").click(function(){
        var s_val = $(this).val();
        if (s_val == 'auditfailure') {
            $("#refuse_dl").show();
        }else{
            $("#refuse_dl").hide();
        }
    });

	$("#submitBtn").click(function(){
        if($("#quit_form").valid()){
            $("#quit_form").submit();
    	}
	});

    $('#quit_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            quit_remark : {
                rangelength:[1,200]
            }
        },
        messages : {
            quit_remark : {
                rangelength:'<i class="fa fa-exclamation-circle"></i>原因应小于200个字符'
            }
        }
    });

});
</script>