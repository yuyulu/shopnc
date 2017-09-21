<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=contract&op=applylist" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>消费者保障服务 - 编辑服务申请</h3>
        <h5>消费者保障服务查看与管理</h5>
      </div>
    </div>
  </div>
  <form id="apply_form" method="post" name="apply_form">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label>项目名称</label>
        </dt>
        <dd class="opt"><?php echo $output['item_info']['cti_name'];?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">
            <label>店铺名称</label>
        </dt>
        <dd class="opt"><?php echo $output['apply_info']['cta_storename'];?></dd>
      </dl>
      <dl class="row">
          <dt class="tit">
            <label>申请时间</label>
          </dt>
          <dd class="opt"><?php echo @date('Y-m-d H:i:s',$output['apply_info']['cta_addtime']);?></dd>
      </dl>
      <?php if($output['apply_info']['cta_auditstate_key'] == 'costpay'){ ?>
        <dl class="row">
            <dt class="tit">
                <label>付款凭证</label>
            </dt>
            <dd class="opt">
                <a href='<?php echo $output['apply_info']['cta_costimg_url']; ?>' target='_blank'><img width="95" height="95" src="<?php echo $output['apply_info']['cta_costimg_url']; ?>" onload="javascript:DrawImage(this,220,95);" ></a>
            </dd>
        </dl>
      <?php } ?>
      <dl class="row">
        <dt class="tit">
          <label>状态</label>
        </dt>
        <dd class="opt">
            <?php if($output['apply_info']['cta_auditstate_key'] == 'notaudit' || $output['apply_info']['cta_auditstate_key'] == 'costpay'){?>
                <?php foreach ($output['curr_applystatearr'] as $k=>$v){ ?>
                    <label for="apply_state<?php echo $v['sign'];?>"><input type="radio" value="<?php echo $k;?>" id="apply_state<?php echo $v['sign'];?>" name="apply_state" <?php echo $v['sign'] == $output['apply_info']['cta_auditstate']?'checked="checked"':'';?>><?php echo $v['name'];?></label>
                <?php } ?>
                <span class="err"></span>
                <p class="notic"></p>
            <?php }else{ ?>
                <?php echo $output['apply_info']['cta_auditstate_text']; ?>
            <?php } ?>
        </dd>
      </dl>
        <dl id="reason_dl" class="row" style="display: none;">
            <dt class="tit">
                <label for="apply_reason">原因备注</label>
            </dt>
            <dd class="opt">
                <?php if($output['apply_info']['cta_auditstate_key'] == 'notaudit' || $output['apply_info']['cta_auditstate_key'] == 'costpay'){?>
                <textarea id="apply_reason" name="apply_reason" class="w300"></textarea>
                <span class="err"></span>
                <p class="notic">原因备注小于200个字符</p>
                <?php } ?>
            </dd>
        </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){
    $("[name='apply_state']").click(function(){
        if ($(this).val() == 'auditfailure' || $(this).val() == 'costfailure') {
            $("#reason_dl").show();
        }else{
            $("#reason_dl").hide();
        }
    });
	$("#submitBtn").click(function(){
        if($("#apply_form").valid()){
            $("#apply_form").submit();
    	}
	});
    $('#apply_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            apply_reason : {
                rangelength:[1,200]
            }
        },
        messages : {
            apply_reason : {
                rangelength:'<i class="fa fa-exclamation-circle"></i>原因备注应小于200个字符'
            }
        }
    });

});
</script>