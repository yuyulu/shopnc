<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="<?php echo urlAdminMobile('mb_payment', 'payment_list');?>" title="返回手机支付方式列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>手机支付方式 - <?php echo $lang['nc_set'];?>“<?php echo $output['payment']['payment_name'];?>”</h3>
        <h5>手机客户端可使用支付方式/接口设置</h5>
      </div>
    </div>
  </div>
  <form id="post_form" method="post" name="form1" action="<?php echo urlAdminMobile('mb_payment', 'payment_save');?>">
    <input type="hidden" name="payment_id" value="<?php echo $output['payment']['payment_id'];?>" />
    <input type="hidden" name="payment_code" value="<?php echo $output['payment']['payment_code'];?>" />
    <div class="ncap-form-default">
      <?php if ($output['payment']['payment_code'] == 'alipay') { ?>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>支付宝账号</label>
        </dt>
        <dd class="opt">
          <input name="alipay_account" id="alipay_account" value="<?php echo $output['payment']['payment_config']['alipay_account'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>交易安全校验码(key)</label>
        </dt>
        <dd class="opt">
          <input name="alipay_key" id="alipay_key" value="<?php echo $output['payment']['payment_config']['alipay_key'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>合作者身份(partner ID)</label>
        </dt>
        <dd class="opt">
          <input name="alipay_partner" id="alipay_partner" value="<?php echo $output['payment']['payment_config']['alipay_partner'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } ?>
      <?php if ($output['payment']['payment_code'] == 'alipay_native') { ?>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>支付宝账号</label>
        </dt>
        <dd class="opt">
          <input name="alipay_account" id="alipay_account" value="<?php echo $output['payment']['payment_config']['alipay_account'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>合作者身份(partner ID)</label>
        </dt>
        <dd class="opt">
          <input name="alipay_partner" id="alipay_partner" value="<?php echo $output['payment']['payment_config']['alipay_partner'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } ?>
      <?php if ($output['payment']['payment_code'] == 'wxpay') { ?>
      <div class="row">
        <dd class="opt">如果启用微信在线退款功能需要在服务器设置“证书”，证书文件不能放在web服务器虚拟目录，应放在有访问权限控制的目录中，防止被他人下载。</dd>
        <dd class="opt">证书路径在“admin\api\refund\wxpay\WxPayApp.Config.php”中。退款有一定延时，用零钱支付的20分钟内到账，银行卡支付的至少3个工作日。</dd>
      </div>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>APP唯一凭证(appid)</label>
        </dt>
        <dd class="opt">
          <input name="wxpay_appid" id="wxpay_appid" value="<?php echo $output['payment']['payment_config']['wxpay_appid'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">APP唯一凭证，需要到微信开放平台进行申请</p>
        </dd>
      </dl>
      <!-- 新版微信支付已经不需要此信息
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>应用密钥(appsecret)</label>
        </dt>
        <dd class="opt">
          <input name="wxpay_appsecret" id="wxpay_appsecret" value="<?php echo $output['payment']['payment_config']['wxpay_appsecret'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>应用校验码(appkey)</label>
        </dt>
        <dd class="opt">
          <input name="wxpay_appkey" id="wxpay_appkey" value="<?php echo $output['payment']['payment_config']['wxpay_appkey'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">V3版本微信支付不需要填写此项</p>
        </dd>
      </dl>
-->
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>商户号(Mchid/partnerid)</label>
        </dt>
        <dd class="opt">
          <input name="wxpay_partnerid" id="wxpay_partnerid" value="<?php echo $output['payment']['payment_config']['wxpay_partnerid'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>商户密钥(APIKEY/partnerkey)</label>
        </dt>
        <dd class="opt">
          <input name="wxpay_partnerkey" id="wxpay_partnerkey" value="<?php echo $output['payment']['payment_config']['wxpay_partnerkey'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">到微信商户平台(账户设置-安全设置-API安全)进行设置</p>
        </dd>
      </dl>
      <?php } ?>
      <?php if ($output['payment']['payment_code'] == 'wxpay_jsapi') { ?>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>APPID</label>
        </dt>
        <dd class="opt">
          <input name="appId" id="appId" value="<?php echo $output['payment']['payment_config']['appId'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">appid是微信公众账号或开放平台APP的唯一标识，在公众平台申请公众账号或者在开放平台申请APP账号后，微信会自动分配对应的appid，用于标识该应用。商户的微信支付审核通过邮件中也会包含该字段值。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>Appsecret</label>
        </dt>
        <dd class="opt">
          <input name="appSecret" id="appSecret" value="<?php echo $output['payment']['payment_config']['appSecret'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">AppSecret是APPID对应的接口密码，用于获取接口调用凭证access_token时使用。在微信支付中，先通过OAuth2.0接口获取用户openid，此openid用于微信内网页支付模式下单接口使用。在开发模式中获取AppSecret（成为开发者且帐号没有异常状态）。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>微信支付商户号</label>
        </dt>
        <dd class="opt">
          <input name="partnerId" id="partnerId" value="<?php echo $output['payment']['payment_config']['partnerId'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">商户申请微信支付后，由微信支付分配的商户收款账号。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>API密钥</label>
        </dt>
        <dd class="opt">
          <input name="apiKey" id="apiKey" value="<?php echo $output['payment']['payment_config']['apiKey'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">交易过程生成签名的密钥，仅保留在商户系统和微信支付后台，不会在网络中传播。商户妥善保管该Key，切勿在网络中传输，不能在其他客户端中存储，保证key不会被泄漏。商户可根据邮件提示登录微信商户平台进行设置。</p>
        </dd>
      </dl>
      <?php } ?>
      <dl class="row">
        <dt class="tit">启用</dt>
        <dd class="opt">
          <div class="onoff">
            <label for="payment_state1" class="cb-enable <?php if($output['payment']['payment_state'] == '1'){ ?>selected<?php } ?>" ><?php echo $lang['nc_yes'];?></label>
            <label for="payment_state2" class="cb-disable <?php if($output['payment']['payment_state'] == '0'){ ?>selected<?php } ?>" ><?php echo $lang['nc_no'];?></label>
            <input type="radio" <?php if($output['payment']['payment_state'] == '1'){ ?>checked="checked"<?php }?> value="1" name="payment_state" id="payment_state1">
            <input type="radio" <?php if($output['payment']['payment_state'] == '0'){ ?>checked="checked"<?php }?> value="0" name="payment_state" id="payment_state2">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="btn_submit" ><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(document).ready(function(){
	$('#post_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
		<?php if ($output['payment']['payment_code'] == 'alipay') { ?>
        rules : {
            alipay_account : {
                required   : true
            },
            alipay_key : {
                required   : true
            },
            alipay_partner : {
                required   : true
            }
        },
        messages : {
            alipay_account  : {
                required : '<i class="fa fa-exclamation-circle"></i>支付宝账号不能为空'
            },
            alipay_key  : {
                required : '<i class="fa fa-exclamation-circle"></i>交易安全校验码不能为空'
            },
            alipay_partner  : {
                required : '<i class="fa fa-exclamation-circle"></i>合作者身份不能为空'
            }
        }
		<?php } ?>
		<?php if ($output['payment']['payment_code'] == 'alipay_native') { ?>
        rules : {
            alipay_account : {
                required   : true
            },
            alipay_key : {
                required   : true
            },
            alipay_partner : {
                required   : true
            }
        },
        messages : {
            alipay_account  : {
                required : '<i class="fa fa-exclamation-circle"></i>支付宝账号不能为空'
            },
            alipay_key  : {
                required : '<i class="fa fa-exclamation-circle"></i>商户方私钥不能为空'
            },
            alipay_partner  : {
                required : '<i class="fa fa-exclamation-circle"></i>合作者身份不能为空'
            }
        }
		<?php } ?>
		<?php if ($output['payment']['payment_code'] == 'wxpay') { ?>
        rules : {
            wxpay_key : {
                required   : true
            },
            wxpay_partner : {
                required   : true
            }
        },
        messages : {
            wxpay_key  : {
                required : '<i class="fa fa-exclamation-circle"></i>交易安全校验码不能为空'
            },
            wxpay_partner  : {
                required : '<i class="fa fa-exclamation-circle"></i>合作者身份不能为空'
            }
        }
		<?php } ?>
		<?php if ($output['payment']['payment_code'] == 'wxpay_jsapi') { ?>
        rules : {
            appId : {
                required   : true
            },
            appSecret : {
                required   : true
            },
            partnerId : {
                required   : true
            },
            apiKey : {
                required   : true
            }
        },
        messages : {
            appId  : {
                required : '<i class="fa fa-exclamation-circle"></i>不能为空'
            },
            appSecret  : {
                required : '<i class="fa fa-exclamation-circle"></i>不能为空'
            },
            partnerId  : {
                required : '<i class="fa fa-exclamation-circle"></i>不能为空'
            },
            partnerId  : {
                apiKey : '<i class="fa fa-exclamation-circle"></i>不能为空'
            }
        }
		<?php } ?>
    });

    $('#btn_submit').on('click', function() {
        $('#post_form').submit();
    });
});
</script>
