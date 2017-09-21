<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=contract&op=contractlist" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>消费者保障服务 - 修改店铺保障服务保证金</h3>
        <h5>消费者保障服务查看与管理</h5>
      </div>
    </div>
  </div>
  <form id="cost_form" method="post" name="cost_form">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="store_id" id="store_id" value='0'/>
    <div class="ncap-form-default">
      <dl class="row">
          <dt class="tit">
              <label>店铺名称</label>
          </dt>
          <dd class="opt"><?php echo $output['c_info']['ct_storename']; ?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>项目名称</label>
        </dt>
        <dd class="opt"><?php echo $output['item_info']['cti_name']; ?></dd>
      </dl>
      <dl class="row">
         <dt class="tit">
            <label>保证金余额</label>
         </dt>
         <dd class="opt"><?php echo $output['c_info']['ct_cost']; ?>&nbsp;<?php echo $lang['currency_zh']; ?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">
            <label><em>*</em>增减类型</label>
        </dt>
        <dd class="opt">
            <select id="operatetype" name="operatetype">
                <option value=""><?php echo $lang['nc_please_choose']; ?></option>
                <option value="1">减少</option>
                <option value="2">增加</option>
            </select>
            <span class="err"></span>
            <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
          <dt class="tit">
              <label><em>*</em>金额</label>
          </dt>
          <dd class="opt">
              <input type="text" name="price" id="price" class="input-txt">
              <span class="err"></span>
              <p class="notic">金额不能为空且为大于0数字</p>
          </dd>
      </dl>
        <dl class="row">
            <dt class="tit">
                <label for="clog_desc"><em>*</em>原因描述</label>
            </dt>
            <dd class="opt">
                <textarea id="clog_desc" name="clog_desc" class="w300"></textarea>
                <span class="err"></span>
                <p class="notic">原因描述必填且小于200个字符</p>
            </dd>
        </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){
	$("#submitBtn").click(function(){
        if($("#cost_form").valid()){
            $("#cost_form").submit();
    	}
	});
    $('#cost_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            operatetype :{
                required : true
            },
            price :{
                required : true,
                number : true,
                min : 0
            },
            clog_desc : {
                required : true,
                rangelength:[1,200]
            }
        },
        messages : {
            operatetype :{
                required : '<i class="fa fa-exclamation-circle"></i>请选择增减类型'
            },
            price :{
                required : '<i class="fa fa-exclamation-circle"></i>金额不能为空且为大于0数字',
                number : '<i class="fa fa-exclamation-circle"></i>金额不能为空且为大于0数字',
                min : '<i class="fa fa-exclamation-circle"></i>金额不能为空且为大于0数字'
            },
            clog_desc : {
                required : '<i class="fa fa-exclamation-circle"></i>原因描述必填且小于200个字符',
                rangelength:'<i class="fa fa-exclamation-circle"></i>原因描述必填且小于200个字符'
            }
        }
    });

});
</script>