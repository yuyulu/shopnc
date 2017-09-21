<?php defined('In33hao') or exit('Access Invalid!');?>
<style>
    .success { width: 100%; text-align: center; padding: 5rem 0 5rem 0; color: green; }
    .fail { width: 100%; text-align: center; padding: 5rem 0 5rem 0; color: red; }
    .return { width: 100%; text-align: center; }
</style>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-touch-fullscreen" content="yes" />
<meta name="format-detection" content="telephone=no"/>
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<meta name="msapplication-tap-highlight" content="no" />
<meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
<script>window.demo.checkPaymentAndroid("<?php echo $output['result'];?>");</script>
<div class="<?php echo $output['result'];?>" >
<?php echo $output['message'];?>
</div>
<div class="return" >
    <a href="<?php echo WAP_SITE_URL;?>/tmpl/member/order_list.html"><img src="<?php echo WAP_SITE_URL;?>/images/pay_ok.png"></a>
</div>
