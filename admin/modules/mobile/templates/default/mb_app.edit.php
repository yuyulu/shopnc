<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>应用安装</h3>
        <h5>手机客户端应用安装包下载地址等设置</h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>当前安卓安装包版本用于安卓包在线升级，请保证所填版本号与提供下载的apk文件保持一致</li>
      <li>下载地址为完整的网址，以“http://”开头，“生成二维码”中网址为程序自动生成</li>
    </ul>
  </div>
  <form id="post_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label class="" for="mobile_apk">安卓安装包</label>
        </dt>
        <dd class="opt">
          <input type="text" name="mobile_apk" id="mobile_apk" value="<?php echo $output['mobile_apk']['value'];?>" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label class="" for="mobile_apk">当前安卓安装包版本</label>
        </dt>
        <dd class="opt">
          <input type="text" name="mobile_apk_version" id="mobile_apk_version" value="<?php echo $output['mobile_version']['value'];?>" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label class="" for="mobile_ios">iOS版</label>
        </dt>
        <dd class="opt">
          <input type="text" name="mobile_ios" id="mobile_ios" value="<?php echo $output['mobile_ios']['value'];?>" class="input-txt" >
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"> <a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a><a href="index.php?act=mb_app&op=mb_qr"  class="ncap-btn-big ncap-btn-orange ml10">生成二维码</a> </div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#post_form").valid()){
     $("#post_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$('#post_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            mobile_apk : {
                url      : true
            },
            mobile_ios  : {
                url      : true
            }
        },
        messages : {
            mobile_apk  : {
                url      : '<i class="fa fa-exclamation-circle"></i>链接格式不正确'
            },
            mobile_ios  : {
                url      : '<i class="fa fa-exclamation-circle"></i>链接格式不正确'
            }
        }
    });
});
</script> 
