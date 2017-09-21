<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=payment" title="返回支付方式列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_pay_method'];?> - <?php echo $lang['nc_set'];?>“<?php echo $output['payment']['payment_name'];?>”</h3>
        <h5><?php echo $lang['nc_pay_method_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="post_form" method="post" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="payment_id" value="<?php echo $output['payment']['payment_id'];?>" />
    <div class="ncap-form-default">
      <?php if ($output['payment']['payment_code'] == 'chinabank') { ?>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_chinabank_account'];?></dt>
        <dd class="opt">
          <input type="hidden" name="config_name" value="chinabank_account,chinabank_key" />
          <input name="chinabank_account" id="chinabank_account" value="<?php echo $output['config_array']['chinabank_account'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_chinabank_key'];?></dt>
        <dd class="opt">
          <input name="chinabank_key" id="chinabank_key" value="<?php echo $output['config_array']['chinabank_key'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } elseif ($output['payment']['payment_code'] == 'tenpay') { ?>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_tenpay_account'];?></dt>
        <dd class="opt">
          <input type="hidden" name="config_name" value="tenpay_account,tenpay_key" />
          <input name="tenpay_account" id="tenpay_account" value="<?php echo $output['config_array']['tenpay_account'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_tenpay_key'];?></dt>
        <dd class="opt">
          <input name="tenpay_key" id="tenpay_key" value="<?php echo $output['config_array']['tenpay_key'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } elseif ($output['payment']['payment_code'] == 'alipay') { ?>
      <div class="row">
        <dd class="opt">支付宝在线退款功能要在支付宝网站输入该账号的“支付密码”，管理员进行确认后才能完成退款操作。</dd>
      </div>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_alipay_account'];?></dt>
        <dd class="opt">
          <input type="hidden" name="config_name" value="alipay_service,alipay_account,alipay_key,alipay_partner" />
          <input type="hidden" name="alipay_service" value="create_direct_pay_by_user" />
          <input name="alipay_account" id="alipay_account" value="<?php echo $output['config_array']['alipay_account'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_alipay_key'];?></dt>
        <dd class="opt">
          <input name="alipay_key" id="alipay_key" value="<?php echo $output['config_array']['alipay_key'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_alipay_partner'];?></dt>
        <dd class="opt">
          <input name="alipay_partner" id="alipay_partner" value="<?php echo $output['config_array']['alipay_partner'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"><a href="https://b.alipay.com/order/pidKey.htm?pid=2088001525694587&product=fastpay" target="_blank">获取PID和Key</a></p>
        </dd>
      </dl>
      <?php } elseif ($output['payment']['payment_code'] == 'wxpay') { ?>
      <div class="row">
        <dd class="opt">如果启用微信在线退款功能需要在服务器设置“证书”，证书文件不能放在web服务器虚拟目录，应放在有访问权限控制的目录中，防止被他人下载。</dd>
        <dd class="opt">证书路径在“admin\api\refund\wxpay\WxPay.Config.php”中。退款有一定延时，用零钱支付的20分钟内到账，银行卡支付的至少3个工作日。</dd>
      </div>
      <dl class="row">
        <dt class="tit">商户公众号APPID</dt>
        <dd class="opt">
          <input type="hidden" name="config_name" value="appid,mchid,key" />
          <input name="appid" id="appid" value="<?php echo $output['config_array']['appid'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">绑定支付的APPID（必须配置，开户邮件中可查看）</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">商户号</dt>
        <dd class="opt">
          <input name="mchid" id="mchid" value="<?php echo $output['config_array']['mchid'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">商户号（必须配置，开户邮件中可查看）</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">密钥</dt>
        <dd class="opt">
          <input name="key" id="key" value="<?php echo $output['config_array']['key'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）</p>
        </dd>
      </dl>
      <?php } ?>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_index_enable'];?></dt>
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
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn" onclick="document.form1.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(document).ready(function(){
	$('#post_form').validate({
		<?php if($output['payment']['payment_code'] == 'chinabank') { ?>
        rules : {
            chinabank_account : {
                required   : true
            },
            chinabank_key : {
                required   : true
            }
        },
        messages : {
            chinabank_account  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['payment_chinabank_account'];?><?php echo $lang['payment_edit_not_null']; ?>'
            },
            chinabank_key  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['payment_chinabank_key'];?><?php echo $lang['payment_edit_not_null']; ?>'
            }
        }
		<?php } elseif ($output['payment']['payment_code'] == 'tenpay') { ?>
        rules : {
            tenpay_account : {
                required   : true
            },
            tenpay_key : {
                required   : true
            }
        },
        messages : {
            tenpay_account  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['payment_tenpay_account'];?><?php echo $lang['payment_edit_not_null']; ?>'
            },
            tenpay_key  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['payment_tenpay_key'];?><?php echo $lang['payment_edit_not_null']; ?>'
            }
        }
			
		<?php } elseif ($output['payment']['payment_code'] == 'alipay') { ?>
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
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['payment_alipay_account'];?><?php echo $lang['payment_edit_not_null']; ?>'
            },
            alipay_key  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['payment_alipay_key'];?><?php echo $lang['payment_edit_not_null']; ?>'
            },
            alipay_partner  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['payment_alipay_partner'];?><?php echo $lang['payment_edit_not_null']; ?>'
            }
        }
		<?php } ?>
    });
});
</script>