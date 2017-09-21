<?php defined('In33hao') or exit('Access Invalid!');?>

<style>
display: block;
font-size: 1.5em;
-webkit-margin-before: 0.83em;
-webkit-margin-after: 0.83em;
-webkit-margin-start: 0px;
-webkit-margin-end: 0px;
font-weight: bold;
</style>
<div class="tabmenu">
  <div style="color:#fff;float:left;margin:10px 0px 0px 20px;text-align: left;width:100%">
  <h2 style="float: left;">刷新计时：</h2><h2 id="id_time_view" style="float: left;">15</h2>
  <span style="float:right;color:#000;"><input type="radio" onclick="select_type(this);" name="print_type" value="0" <?php if($output['print_type'] == 0){ ?> checked <?php } ?> />A5打印&nbsp;&nbsp;<input type="radio" onclick="select_type(this);" name="print_type" value="1" <?php if($output['print_type'] == 1){ ?> checked <?php } ?> />小票打印&nbsp;&nbsp;</span>
  </div>
  <?php //include template('layout/submenu');?>
</div>


<form method="get" action="index.php" target="_self">
  <table class="search-form">
    <input type="hidden" name="act" value="order_call" />
    <input type="hidden" name="op" value="index" />
    <?php if ($_GET['state_type']) { ?>
    <input type="hidden" name="state_type" value="<?php echo $_GET['state_type']; ?>" />
    <?php } ?>
    <tr>
      <td>&nbsp;</td>
      <?php if ($_GET['state_type'] == 'store_order') { ?>
      <td><input type="checkbox" id="skip_off" value="1" <?php echo $_GET['skip_off'] == 1 ? 'checked="checked"' : null;?>  name="skip_off"> <label for="skip_off">不显示已关闭的订单</label></td>
      <?php } ?>
      <th><?php echo $lang['store_order_add_time'];?></th>
      <td class="w240"><input type="text" class="text w70" name="query_start_date" id="query_start_date" value="<?php echo $_GET['query_start_date']; ?>" /><label class="add-on"><i class="icon-calendar"></i></label>&nbsp;&#8211;&nbsp;<input id="query_end_date" class="text w70" type="text" name="query_end_date" value="<?php echo $_GET['query_end_date']; ?>" /><label class="add-on"><i class="icon-calendar"></i></label></td>
      <th><?php echo $lang['store_order_buyer'];?></th>
      <td class="w100"><input type="text" class="text w80" name="buyer_name" value="<?php echo $_GET['buyer_name']; ?>" /></td>
      <th><?php echo $lang['store_order_order_sn'];?></th>
      <td class="w160"><input type="text" class="text w150" name="order_sn" value="<?php echo $_GET['order_sn']; ?>" /></td>
      <td class="w70 tc"><label class="submit-border">
          <input type="submit" class="submit" value="<?php echo $lang['store_order_search'];?>" />
        </label></td>
    </tr>
  </table>
</form>

<table class="ncsc-default-table order" id="new_order" style="border-top:solid 1px #E6E6E6">
  <thead>
    <tr>
      <th class="w10"></th>
      <th colspan="2"><?php echo $lang['store_order_goods_detail'];?></th>
      <th class="w100"><?php echo $lang['store_order_goods_single_price'];?></th>
      <th class="w40"><?php echo $lang['store_show_order_amount'];?></th>
      <th class="w110"><?php echo $lang['store_order_buyer'];?></th>
      <th class="w120"><?php echo $lang['store_order_sum'];?></th>
      <th class="w100">交易状态</th>
	  <th class="w100">打印状态</th> 
      <th class="w150">交易操作</th>
    </tr>
  </thead>
  
  
  <?php if (is_array($output['order_list']) and !empty($output['order_list'])) { ?>
  <?php foreach($output['order_list'] as $order_id => $order) { ?>
  <tbody>
    <tr>
      <td colspan="20" class="sep-row"></td>
    </tr>
    <tr>
      <th colspan="20"><span class="ml10"><?php echo $lang['store_order_order_sn'].$lang['nc_colon'];?><em><?php echo $order['order_sn']; ?></em>
        <?php if ($order['order_from'] == 2){?>
        <i class="icon-mobile-phone"></i>
        <?php }?>
</span> <span><?php echo $lang['store_order_add_time'].$lang['nc_colon'];?><em class="goods-time"><?php echo date("Y-m-d H:i:s",$order['add_time']); ?></em></span> 
<span class="fr mr5"> <a href="index.php?act=store_order_print&order_id=<?php echo $order_id;?>" class="ncsc-btn-mini" target="_blank" title="打印发货单"/><i class="icon-print"></i>A5打印</a></span>
<span class="fr mr5"> <a href="index.php?act=store_order_print&op=small&order_id=<?php echo $order_id;?>" class="ncsc-btn-mini" target="_blank" title="打印发货单"/><i class="icon-print"></i>小票打印</a></span>
 </th>
    </tr>
    <?php $i = 0;?>
    <?php foreach($order['goods_list'] as $k => $goods) { ?>
    <?php $i++;?>
    <tr>
      <td class="bdl"></td>
      <td class="w70"><div class="ncsc-goods-thumb"><a href="<?php echo $goods['goods_url'];?>" target="_blank"><img src="<?php echo $goods['image_60_url'];?>" onMouseOver="toolTip('<img src=<?php echo $goods['image_240_url'];?>>')" onMouseOut="toolTip()"/></a></div></td>
      <td class="tl"><dl class="goods-name">
          <dt><a target="_blank" href="<?php echo $goods['goods_url'];?>"><?php echo $goods['goods_name']; ?></a></dt>
          <dd>
            <?php if (!empty($goods['goods_type_cn'])){ ?>
            <span class="sale-type"><?php echo $goods['goods_type_cn'];?></span>
            <?php } ?>
          </dd>
        </dl></td>
      <td><?php echo $goods['goods_price']; ?></td>
      <td><?php echo $goods['goods_num']; ?></td>
      <!-- S 合并TD -->
      <?php if (($order['goods_count'] > 1 && $k ==0) || ($order['goods_count']) == 1){ ?>
      <td class="bdl" rowspan="<?php echo $order['goods_count'];?>"><div class="buyer"><?php echo $order['buyer_name'];?>
          <p member_id="<?php echo $order['buyer_id'];?>">
            <?php if(!empty($order['extend_member']['member_qq'])){?>
            <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $order['extend_member']['member_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $order['extend_member']['member_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $order['extend_member']['member_qq'];?>:52" style=" vertical-align: middle;"/></a>
            <?php }?>
            <?php if(!empty($order['extend_member']['member_ww'])){?>
            <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $order['extend_member']['member_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" ><img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $order['extend_member']['member_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="Wang Wang" style=" vertical-align: middle;" /></a>
            <?php }?>
          </p>
          <div class="buyer-info"> <em></em>
            <div class="con">
              <h3><i></i><span><?php echo $lang['store_order_buyer_info'];?></span></h3>
              <dl>
                <dt><?php echo $lang['store_order_receiver'].$lang['nc_colon'];?></dt>
                <dd><?php echo $order['extend_order_common']['reciver_name'];?></dd>
              </dl>
              <dl>
                <dt><?php echo $lang['store_order_phone'].$lang['nc_colon'];?></dt>
                <dd><?php echo $order['extend_order_common']['reciver_info']['phone'];?></dd>
              </dl>
              <dl>
                <dt>地址<?php echo $lang['nc_colon'];?></dt>
                <dd><?php echo $order['extend_order_common']['reciver_info']['address'];?></dd>
              </dl>
            </div>
          </div>
        </div></td>
      <td class="bdl" rowspan="<?php echo $order['goods_count'];?>"><p class="ncsc-order-amount"><?php echo $order['order_amount']; ?></p>
        <p class="goods-freight">
          <?php if ($order['shipping_fee'] > 0){?>
          (<?php echo $lang['store_show_order_shipping_han']?>运费<?php echo $order['shipping_fee'];?>)
          <?php }else{?>
          <?php echo $lang['nc_common_shipping_free'];?>
          <?php }?>
        </p>
        <p class="goods-pay" title="<?php echo $lang['store_order_pay_method'].$lang['nc_colon'];?><?php echo $order['payment_name']; ?>"><?php echo $order['payment_name']; ?></p></td>
		
	
	
      <td class="bdl bdr" rowspan="<?php echo $order['goods_count'];?>"><p><?php echo $order['state_desc']; ?>
          <?php if($order['evaluation_time']) { ?>
          <br/>
          <?php echo $lang['store_order_evaluated'];?>
          <?php } ?>
        </p>
        
        <!-- 订单查看 -->
        <p><a href="index.php?act=store_order&op=show_order&order_id=<?php echo $order_id;?>" target="_blank"><?php echo $lang['store_order_view_order'];?></a></p>
        
        <!-- 物流跟踪 -->
        <p>
          <?php if ($order['if_deliver']) { ?>
          <a href='index.php?act=store_deliver&op=search_deliver&order_sn=<?php echo $order['order_sn']; ?>'><?php echo $lang['store_order_show_deliver'];?></a>
          <?php } ?>
        </p>

	
	</td>
	
	<td><?php if($order['is_print'] == 1){ echo "已打印"; }else{ echo "未打印";} ?></td>

      <!-- 取消订单 -->
      <td class="bdl bdr" rowspan="<?php echo $order['goods_count'];?>">
        <?php if($order['if_cancel']) { ?>
        <p><a href="javascript:void(0)" class="ncsc-btn ncsc-btn-red mt5" nc_type="dialog" uri="index.php?act=store_order&op=change_state&state_type=order_cancel&order_sn=<?php echo $order['order_sn']; ?>&order_id=<?php echo $order['order_id']; ?>" dialog_title="<?php echo $lang['store_order_cancel_order'];?>" dialog_id="seller_order_cancel_order" dialog_width="400" id="order<?php echo $order['order_id']; ?>_action_cancel" /><i class="icon-remove-circle"></i><?php echo $lang['store_order_cancel_order'];?></a></p>
        <?php } ?>
        
        <!-- 修改运费 -->
        <?php if ($order['if_modify_price']) { ?>
        <p><a href="javascript:void(0)" class="ncsc-btn-mini ncsc-btn-orange mt10" uri="index.php?act=store_order&op=change_state&state_type=modify_price&order_sn=<?php echo $order['order_sn']; ?>&order_id=<?php echo $order['order_id']; ?>" dialog_width="480" dialog_title="<?php echo $lang['store_order_modify_price'];?>" nc_type="dialog"  dialog_id="seller_order_adjust_fee" id="order<?php echo $order['order_id']; ?>_action_adjust_fee" /><i class="icon-pencil"></i>修改运费</a></p>
        <?php }?>
        <!-- 修改价格 -->
		<?php if ($order['if_spay_price']) { ?>
        <p><a href="javascript:void(0)" class="ncsc-btn-mini ncsc-btn-green mt10" uri="index.php?act=store_order&op=change_state&state_type=spay_price&order_sn=<?php echo $order['order_sn']; ?>&order_id=<?php echo $order['order_id']; ?>" dialog_width="480" dialog_title="<?php echo $lang['store_order_modify_price'];?>" nc_type="dialog"  dialog_id="seller_order_adjust_fee" id="order<?php echo $order['order_id']; ?>_action_adjust_fee" /><i class="icon-pencil"></i>修改价格</a></p>
		<?php }?>
        
        <!-- 发货 -->
        <?php if ($order['if_send']) { ?>
        <p><a class="ncsc-btn ncsc-btn-green mt10" href="index.php?act=store_deliver&op=send&order_id=<?php echo $order['order_id']; ?>"/><i class="icon-truck"></i><?php echo $lang['store_order_send'];?></a></p>
        <?php } ?>
        
        <!-- 锁定 -->
        <?php if ($order['if_lock']) {?>
        <p><?php echo '退款退货中';?></p>
        <?php }?></td>

      <?php } ?>
      <!-- E 合并TD -->
    </tr>

    <!-- S 赠品列表 -->
    <?php if (!empty($order['zengpin_list']) && $i == count($order['goods_list'])) { ?>
    <tr>
      <td class="bdl"></td>
      <td colspan="4" class="tl"><div class="ncsc-goods-gift">赠品：
      <ul><?php foreach ($order['zengpin_list'] as $zengpin_info) { ?><li>
      <a title="赠品：<?php echo $zengpin_info['goods_name'];?> * <?php echo $zengpin_info['goods_num'];?>" href="<?php echo $zengpin_info['goods_url'];?>" target="_blank"><img src="<?php echo $zengpin_info['image_60_url'];?>" onMouseOver="toolTip('<img src=<?php echo $zengpin_info['image_240_url'];?>>')" onMouseOut="toolTip()"/></a></li></ul>
      <?php } ?>
      </div></td>
    </tr>
    <?php } ?>
    <!-- E 赠品列表 -->

    <?php }?>
    <?php } } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if (is_array($output['order_list']) and !empty($output['order_list'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
var timeval = 15;
var timeinterval;
var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';	
var charset = '<?php echo CHARSET;?>';
var print_type = '<?php echo $print_type;?>';
$(function(){
	
	setinterval();//自动刷新
	
    $('#query_start_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('#query_end_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('.checkall_s').click(function(){
        var if_check = $(this).attr('checked');
        $('.checkitem').each(function(){
            if(!this.disabled)
            {
                $(this).attr('checked', if_check);
            }
        });
        $('.checkall_s').attr('checked', if_check);
    });
    $('#skip_off').click(function(){
        url = location.href.replace(/&skip_off=\d*/g,'');
        window.location.href = url + '&skip_off=' + ($('#skip_off').attr('checked') ? '1' : '0');
    });
	
});

function setinterval()
{
	var obj = $("#id_time_view");
	obj.html(timeval);
	timeinterval = window.setInterval("timer()" , 1000);//自动刷新
	
}

function timer()
{
	var obj = $("#id_time_view");
	//var val = kj.toint(obj.html());
	var val = obj.html();
	if(val <= 0) {
		//获取新订单
		clearTimeout(timeinterval);
		obj.html("获取新订单");
		refresh();
		val = timeval;
	} else {
		val--;
		obj.html(val);
	}
}

//refresh();

//jsonp
//获取新订单
function refresh()
{
	$.ajax({
		type: "GET",
		url: SHOP_SITE_URL+'/index.php?act=order_call&op=getajaxorder',
		dataType:"json",
		async: true,
		success: function(data){
			//$("#new_order").html("");
			var html_str = '';
			html_str += '<thead>';
			html_str += '<tr>';
            html_str += '<th class="w10"></th>';
            html_str += '<th colspan="2">商品</th>';
            html_str += '<th class="w100">单价（元）</th>';
            html_str += '<th class="w40">数量</th>';
            html_str += '<th class="w110">买家</th>';
            html_str += '<th class="w120">订单金额</th>';
            html_str += '<th class="w100">交易状态</th>';
			html_str += '<th class="w100">打印状态</th>';
            html_str += '<th class="w150">交易操作</th>';
            html_str += '</tr>';
            html_str += '</thead>';
			
			
			var order_list = data.order_list;
		    //alert(order_list.length);
			if(order_list.length > 0)
		    {	
				for(var i = 0; i < order_list.length; i++)
				{
					html_str += '<tbody> <tr> <td colspan="20" class="sep-row"></td></tr>';
					html_str += '<tr><th colspan="20"><span class="ml10">订单编号：<em>'+order_list[i].order_sn+'</em></span> <span>下单时间：<em class="goods-time">'+order_list[i].add_time+'</em></span> <span class="fr mr5"> <a href="index.php?act=store_order_print&amp;order_id='+order_list[i].order_id+'" class="ncsc-btn-mini" target="_blank" title="打印发货单"><i class="icon-print"></i>打印发货单</a></span></th></tr>';
			
					var j = 0;
					for(var k = 0; k < order_list[i].goods_list.length; k++)
					{
						j++;
						html_str += " <tr>";
						
						//S 合并TD
						html_str += '<td class="bdl"></td>';
						html_str += '<td class="w70"><div class="ncsc-goods-thumb"><a href="'+order_list[i].goods_list[k].goods_url+'" target="_blank"><img src="'+order_list[i].goods_list[k].image_60_url+'" onMouseOver="toolTip(\'<img src='+order_list[i].goods_list[k].image_240_url+'>\')" onMouseOut="toolTip()"/></a></div></td>';
						
						html_str += '<td class="tl"><dl class="goods-name">';
						html_str += '  <dt><a target="_blank" href="'+order_list[i].goods_list[k].goods_url+'">'+order_list[i].goods_list[k].goods_name+'</a></dt>';
						html_str +='  <dd>';
						
						if(order_list[i].goods_list[k].goods_type_cn)
						{
							html_str +='<span class="sale-type">'+order_list[i].goods_list[k].goods_type_cn+'</span>';
						}
						
						html_str +='  </dd>';
						html_str +='</dl></td>';
						html_str +='<td>'+order_list[i].goods_list[k].goods_price+'</td>';			   
						html_str +='<td>'+order_list[i].goods_list[k].goods_num+'</td>';
						
						if ((order_list[i].goods_count > 0 && k==0) || (order_list[i].goods_count) == 1 )
						{
							html_str +='<td class="bdl" rowspan="'+order_list[i].goods_count+'"><div class="buyer">'+order_list[i].buyer_name+'';
							html_str +=' <p member_id="'+order_list[i].buyer_id+'">';
							
							if(order_list[i].extend_member.member_qq)
							{
								html_str +=' <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin='+order_list[i].extend_member.member_qq+'&site=qq&menu=yes" title="QQ: '+order_list[i].extend_member.member_qq+'"><img border="0" src="http://wpa.qq.com/pa?p=2:'+order_list[i].extend_member.member_qq+':52" style=" vertical-align: middle;"/></a>';	
							}	
							if(order_list[i].extend_member.member_ww)
							{
								html_str +='<a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid='+order_list[i].extend_member.member_ww+'&site=cntaobao&s=2&charset='+charset+'" ><img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid='+order_list[i].extend_member.member_ww+'&site=cntaobao&s=2&charset='+charset+'" alt="Wang Wang" style=" vertical-align: middle;" /></a>';
							}
							
							html_str +='  </p>';
							html_str +='  <div class="buyer-info"> <em></em>';
							html_str +='	<div class="con">';
							html_str +='	  <h3><i></i><span>联系信息</span></h3>';
							html_str +='	  <dl>';
							html_str +='		<dt>姓名：</dt>';
							html_str +='		<dd>'+order_list[i].extend_order_common.reciver_name+'</dd>';
							html_str +='	  </dl>';
							html_str +='	  <dl>';
							html_str +='		<dt>电话：</dt>';
							html_str +='		<dd>'+order_list[i].extend_order_common.reciver_info.phone+'</dd>';
							html_str +='	  </dl>';
							html_str +='	  <dl>';
							html_str +='		<dt>地址：</dt>';
							html_str +='		<dd>'+order_list[i].extend_order_common.reciver_info.address+'</dd>';
							html_str +='	  </dl>';
							html_str +='	</div>';
							html_str +='  </div>';
							html_str +='</div></td>';
							
							
							html_str += '<td class="bdl" rowspan="'+order_list[i].goods_count+'"><p class="ncsc-order-amount">'+order_list[i].order_amount+'</p>';
							html_str += '<p class="goods-freight">';
							if(order_list[i].shipping_fee > 0)
							{
								html_str += '(含运费'+order_list[i].shipping_fee+')';
							}
							else
							{
								html_str += '（免运费）';
							}
							html_str += '</p>';
							html_str += '<p class="goods-pay" title="支付方式：'+order_list[i].payment_name+'">'+order_list[i].payment_name+'</p></td>';
							
							
							html_str += '<td class="bdl bdr" rowspan="'+order_list[i].goods_count+'"><p>'+order_list[i].state_desc+'';
							if(order_list[i].evaluation_time)
							{
								html_str +='<br/>';
								html_str +='已评价';
							}
							html_str += '	</p>';
								
							//订单查看
							html_str += '	<p><a href="index.php?act=store_order&op=show_order&order_id='+order_list[i].order_id+'" target="_blank">订单详情</a></p>';
								
							//物流跟踪
							html_str += '		<p>';
							if(order_list[i].if_deliver)
							{
								html_str += '<a href=\'index.php?act=store_deliver&op=search_deliver&order_sn='+order_list[i].order_sn+'\'>查看物流</a>';
							}
								 
							html_str += '	</p>';						
							html_str += '</td>';
							
							
							html_str += '<td>';
							if (order_list[i].is_print == 1)
							{
								html_str += '已打印';
							}
							else
							{
								html_str += '未打印';
							}
							html_str += '</td>';
							
							//取消订单
							html_str += '<td class="bdl bdr" rowspan="'+order_list[i].goods_count+'">';
							if(order_list[i].if_cancel)
							{
								html_str += '<p><a href="javascript:void(0)" class="ncsc-btn ncsc-btn-red mt5" nc_type="dialog" uri="index.php?act=store_order&op=change_state&state_type=order_cancel&order_sn='+order_list[i].order_sn+'&order_id='+order_list[i].order_id+'" dialog_title="取消订单" dialog_id="seller_order_cancel_order" dialog_width="400" id="order'+order_list[i].order_id+'_action_cancel" /><i class="icon-remove-circle"></i>取消订单</a></p>';
							}
							
							//修改运费
							if(order_list[i].if_modify_price)
							{
								html_str += '<p><a href="javascript:void(0)" class="ncsc-btn-mini ncsc-btn-orange mt10" uri="index.php?act=store_order&op=change_state&state_type=modify_price&order_sn='+order_list[i].order_sn+'&order_id='+order_list[i].order_id+'" dialog_width="480" dialog_title="调整费用" nc_type="dialog"  dialog_id="seller_order_adjust_fee" id="order'+order_list[i].order_id+'_action_adjust_fee" /><i class="icon-pencil"></i>修改运费</a></p>';
							}
							
							//修改价格
							if(order_list[i].if_spay_price)
							{
								html_str += '<p><a href="javascript:void(0)" class="ncsc-btn-mini ncsc-btn-green mt10" uri="index.php?act=store_order&op=change_state&state_type=spay_price&order_sn='+order_list[i].order_sn+'&order_id='+order_list[i].order_id+'" dialog_width="480" dialog_title="调整费用" nc_type="dialog"  dialog_id="seller_order_adjust_fee" id="order'+order_list[i].order_id+'_action_adjust_fee" /><i class="icon-pencil"></i>修改价格</a></p>';
							}
							
							
							//发货
							if(order_list[i].if_send){
								html_str += '<p>';
								html_str += '<a class="ncsc-btn ncsc-btn-green mt10" href="index.php?act=store_deliver&op=send&order_id='+order_list[i].order_id+'">';
								html_str += '<i class="icon-truck"></i>设置发货';
								html_str += '</a>';
								html_str += '</p>';
							}
							
							//锁定
							if(order_list[i].if_lock){
								html_str += '<p>退款退货中</p>';
							}
							
							html_str += '</td>';
							
						}
		  
					   //E 合并TD
						
						html_str += ' </tr>';
						
						//S 赠品列表
						if(order_list[i].zengpin_list && j == order_list[i].goods_list.length)
						{
							html_str += '<tr>';
							html_str += '  <td class="bdl"></td>';
							html_str += '  <td colspan="4" class="tl"><div class="ncsc-goods-gift">赠品：';
							html_str += '  <ul>';
							
							for(var m = 0; m < order_list[i].zengpin_list.length; m++ )
							{
								html_str += '<li>';
								html_str += '  <a title="赠品：'+order_list[i].zengpin_list[m].goods_name+' * '+order_list[i].zengpin_list[m].goods_num+'" href="'+order_list[i].zengpin_list[m].goods_url+'" target="_blank"><img src="'+order_list[i].zengpin_list[m].image_60_url+'" onMouseOver="toolTip(\'<img src='+order_list[i].zengpin_list[m].image_240_url+'>\')" onMouseOut="toolTip()"/></a></li></ul>';
							}
							  
							html_str += '  </div></td>';
							html_str += '</tr>';
						
						}
						//E 赠品列表
						
						
					}
				}
			}
			else
			{
				html_str += '<tr>';
				html_str += '  <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span>暂无符合条件的数据记录</span></div></td>';
				html_str += '</tr>';	
			}
			
            html_str += '</tbody>';
				
					
			//}
				
			html_str += '<tfoot>';
			html_str += '	<tr>';
			html_str += '	  <td colspan="20"><div class="pagination">'+data.show_page+'</div></td>';
			html_str += '	</tr>';
			html_str += '</tfoot>';
				
		    //$("#new_order").html(html_str);
			
			var obj = $("#id_time_view");
			obj.html("获取到" + order_list.length + "笔新订单");
			window.setTimeout("setinterval()" , 3000);
			if(data.print_type == 0){
				window.setTimeout("order_print("+order_list.length+")" , 1000);
			}else{
				window.setTimeout("small_print("+order_list.length+")" , 1000);
			}
			
		}
	});
}

//A5打印处理
function order_print(length)
{
    if(length > 0){
		var url = SHOP_SITE_URL+'/index.php?act=order_call_print&op=index';
	    window.open(url,'_blank');
	}
}

//小票打印处理
function small_print(length)
{
	if(length > 0){
		var url = SHOP_SITE_URL+'/index.php?act=order_call_print&op=small';
	    window.open(url,'_blank');
	}
}

//选择打印类型
function select_type(obj)
{
    var type = $(obj).val();
	$.ajax({
		type: "GET",
		url: SHOP_SITE_URL+'/index.php?act=order_call&op=select_type&type='+type,
		dataType:"json",
		async: true,
		success: function(data){
			if(data == 1)
			{
				alert("打印类型更改成功！");
				return false;
			}
		}
	});	
}
</script> 
