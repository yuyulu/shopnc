<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=contract&op=contractlist" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>消费者保障服务 - 编辑店铺保障服务</h3>
        <h5>消费者保障服务查看与管理</h5>
      </div>
    </div>
  </div>
  <form id="c_form" method="post" name="c_form">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
            <label>店铺名称</label>
        </dt>
        <dd class="opt"><?php echo $output['c_info']['ct_storename'];?></dd>
      </dl>
    <dl class="row">
        <dt class="tit">
            <label>项目名称</label>
        </dt>
        <dd class="opt"><?php echo $output['item_info']['cti_name'];?></dd>
    </dl>
      <dl class="row">
        <dt class="tit">
            <label>状态</label>
        </dt>
        <dd class="opt">
            <?php if ($output['c_info']['ct_joinstate_key'] == 'applying') { ?>
                <?php echo $output['c_info']['ct_joinstate_text']."（{$output['c_info']['ct_auditstate_text']}）";?>
            <?php }else{ ?>
                <?php echo $output['c_info']['ct_joinstate_text'];?>
            <?php } ?>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>关闭状态</label>
        </dt>
        <dd class="opt">
            <?php foreach ($output['contractclosestate_arr'] as $k=>$v){ ?>
            <label for="c_state<?php echo $v['sign'];?>"><input type="radio" value="<?php echo $k;?>" id="c_state<?php echo $v['sign'];?>" name="c_state" <?php echo $v['sign'] == $output['c_info']['ct_closestate']?'checked="checked"':'';?>><?php echo $v['name'];?></label>
            <?php } ?>
            <span class="err"></span>
            <p class="notic"></p>
        </dd>
      </dl>
        <dl id="reason_dl" class="row" style="display: none;">
            <dt class="tit">
                <label for="c_reason">原因备注</label>
            </dt>
            <dd class="opt">
                <textarea id="c_reason" name="c_reason" class="w300"></textarea>
                <span class="err"></span>
                <p class="notic">原因备注小于200个字符</p>
            </dd>
        </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){
    $("[name='c_state']").click(function(){
        if ($(this).val() == 'close') {
            $("#reason_dl").show();
        }else {
            $("#reason_dl").hide();
        }
    });
	$("#submitBtn").click(function(){
        if($("#c_form").valid()){
            $("#c_form").submit();
    	}
	});
    $('#c_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            c_reason : {
                rangelength:[1,200]
            }
        },
        messages : {
            c_reason : {
                rangelength:'<i class="fa fa-exclamation-circle"></i>原因备注应小于200个字符'
            }
        }
    });
});
</script>