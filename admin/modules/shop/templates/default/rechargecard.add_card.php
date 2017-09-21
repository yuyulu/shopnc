<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="<?php echo urlAdminShop('rechargecard', 'index'); ?>" title="返回平台充值卡列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>平台充值卡 - 新增</h3>
        <h5>商城充值卡设置生成及用户充值使用明细</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>平台发布充值卡有3种方式：</li>
      <li>1. 输入总数，以及可选输入的卡号前缀，由系统自动生成指定总数、前缀的充值卡卡号（系统自动生成部分长度为32）；</li>
      <li>2. 上传文本文件导入充值卡卡号，文件中每行为一个卡号。</li>
      <li>3. 在文本框中手动输入多个充值卡卡号，每行为一个卡号；</li>
      <li>充值卡卡号为50位之内的字母数字组合；可以设置本批次添加卡号的批次标识，方便检索。</li>
      <li>如新增的充值卡卡号与已有的卡号冲突，则系统自动忽略它们。</li>
    </ul>
  </div>
  <form method="post" enctype="multipart/form-data" name="form_add" id="form_add">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>请选择发布方式</label>
        </dt>
        <dd class="opt">
          <label class="mr15">
            <input type="radio" name="type" value="0" checked="checked" class="radio tabswitch" />
            输入总数，自动生成 </label>
          <label class="mr15">
            <input type="radio" name="type" value="1" class="radio tabswitch" />
            上传文件，导入卡号 </label>
          <label>
            <input type="radio" name="type" value="2" class="radio tabswitch" />
            手动输入，每行一号 </label>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>相关内容</label>
        </dt>
        <dd class="opt tabswitch-target"> 总数：
          <input type="text" class="txt" name="total" style="width:40px;" />
          前缀：
          <input type="text" class="txt" name="prefix" style="width:130px;" />
          <span class="err"></span>
          <p class="notic">请输入总数，总数为1~9999之间的整数；可以输入随机生成卡号的统一前缀，16字之内字母数字的组合</p>
        </dd>
        <dd class="opt tabswitch-target" style="display:none;">
          <div class="input-file-show"><span class="type-file-box">
            <input class="type-file-file" id="_textfile" name="_textfile" type="file" size="30" hidefocus="true" onchange="$('#textfile').val(this.value);"  title="点击按钮选择文件并提交表单后上传生效">
            <input type="text" name="textfile" id="textfile" class="type-file-text" />
            <input type="button" name="button" id="button" value="选择上传..." class="type-file-button" />
            </span></div>
          <p class="notic">请上传卡号文件，文件为纯文本格式，每行一个卡号；卡号为字母数字组合，限制50字之内；不合法卡号将被自动过滤</p>
        </dd>
        <dd class="opt tabswitch-target" style="display:none;">
          <textarea name="manual" class="tarea" rows="6" ></textarea>
          <span class="err"></span>
          <p class="notic">请输入卡号，每行一个卡号；卡号为字母数字组合，限制50字之内；不合法卡号将被自动过滤</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>面额(元)</label>
        </dt>
        <dd class="opt">
          <input class="input-txt" type="text" name="denomination" />
          <span class="err"></span>
          <p class="notic">请输入面额，面额不可超过1000</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>批次标识</label>
        </dt>
        <dd class="opt">
          <input class="input-txt" type="text" name="batchflag" />
          <p class="notic">可以输入20字之内“批次标识”，用于标识和区分不同批次添加的充值卡，便于检索</p>
        </dd>
      </dl>
      <div class="bot"><a href="javascript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){

$('.tabswitch').click(function() {
    var i = parseInt(this.value);
    $('.tabswitch-target').hide().eq(i).show();
});

$("#submitBtn").click(function(){
    $("#form_add").submit();
});

jQuery.validator.addMethod("r0total", function(value, element) {
    var v = parseInt(value);
    return $(":radio[name='type']:checked").val() != '0' || (value == v && v >= 1 && v <= 9999);
}, "<i class='fa fa-exclamation-circle'></i>总数必须是1~9999之间的整数");

jQuery.validator.addMethod("r0prefix", function(value, element) {
    return $(":radio[name='type']:checked").val() != '0' || this.optional(element) || /^[0-9a-zA-Z]{0,16}$/.test(value);
}, "<i class='fa fa-exclamation-circle'></i>前缀必须是16字之内字母数字的组合");

jQuery.validator.addMethod("r1textfile", function(value, element) {
    return $(":radio[name='type']:checked").val() != '1' || value;
}, "<i class='fa fa-exclamation-circle'></i>请选择纯文本格式充值卡卡号文件");

jQuery.validator.addMethod("r2manual", function(value, element) {
    return $(":radio[name='type']:checked").val() != '2' || value;
}, "<i class='fa fa-exclamation-circle'></i>请输入充值卡卡号");

$("#form_add").validate({
     errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
    rules : {
        denomination : {
            required : true,
            min: 0.01,
            max: 1000
        },
        batchflag : {
            maxlength: 20
        },
        total : {
            r0total : true
        },
        prefix : {
            r0prefix : true
        },
        textfile : {
            r1textfile : true
        },
        manual : {
            r2manual : true
        }
    },
    messages : {
        denomination : {
            required : '<i class="fa fa-exclamation-circle"></i>请填写面额',
            min : '<i class="fa fa-exclamation-circle"></i>面额不能小于0.01',
            max: '<i class="fa fa-exclamation-circle"></i>面额不能大于1000'
        },
        batchflag : {
            maxlength: '<i class="fa fa-exclamation-circle"></i>请输入20字之内的批次标识'
        }
    }
});
});
</script> 
