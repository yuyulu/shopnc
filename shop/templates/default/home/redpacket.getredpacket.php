<?php defined('In33hao') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_point.css" rel="stylesheet" type="text/css">
<div class="nch-container wrapper">
  <div class="ncp-voucher w400 mt20 mb20">
    <div class="info">
      <div class="pic"><img src="<?php echo $output['template_info']['rpacket_t_customimg_url'];?>" onerror="this.src='<?php echo UPLOAD_SITE_URL.DS.defaultGoodsImage(240);?>'"/></div>
    </div>
    <dl class="value">
      <dt><?php echo $lang['currency'];?><em><?php echo $output['template_info']['rpacket_t_price'];?></em></dt>
      <dd>
        <?php if ($output['template_info']['rpacket_t_limit'] > 0){?>
        购物满<?php echo $output['template_info']['rpacket_t_limit'];?>元可用
        <?php } else { ?>
        无限额代金券
        <?php } ?>
      </dd>
      <dd class="time">有效期：
        <?php echo @date('Y-m-d',$output['template_info']['rpacket_t_start_date']);?>~<?php echo @date('Y-m-d',$output['template_info']['rpacket_t_end_date']);?></dd>
    </dl>
    <div class="point">
      <p><em><?php echo $output['template_info']['rpacket_t_giveout'];?></em>人兑换</p>
      <?php if ($output['template_info']['rpacket_t_mgradelimit'] > 0){ ?>
      <span> <?php echo $output['template_info']['rpacket_t_mgradelimittext'];?> </span>
      <?php } ?>
    </div>
    <div class="button"><a href="javascript:void(0);" id="getvoucherbtn" data-param='{"tid":"<?php echo $output['template_info']['rpacket_t_id'];?>"}' class="ncbtn ncbtn-grapefruit">确认领取</a></div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#getvoucherbtn").click(function(){
		ajaxget('index.php?act=redpacket&op=getredpacketsave&tid=<?php echo $output['template_info']['rpacket_t_id'];?>');
	});
});
</script>