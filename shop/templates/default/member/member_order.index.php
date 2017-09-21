<?php defined('In33hao') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <form method="get" action="index.php" target="_self">
    <table class="ncm-search-table">
      <input type="hidden" name="act" value="member_order" />
      <input type="hidden" name= "recycle" value="<?php echo $_GET['recycle'];?>" />
      <tr>
        <td>&nbsp;</td>
        <th><?php echo $lang['member_order_state'];?></th>
        <td class="w100"><select name="state_type">
            <option value="" <?php echo $_GET['state_type']==''?'selected':''; ?>><?php echo $lang['member_order_all'];?></option>
            <option value="state_new" <?php echo $_GET['state_type']=='state_new'?'selected':''; ?>>待付款</option>
            <option value="state_pay" <?php echo $_GET['state_type']=='state_pay'?'selected':''; ?>>待发货</option>
            <option value="state_send" <?php echo $_GET['state_type']=='state_send'?'selected':''; ?>>待收货</option>
            <option value="state_success" <?php echo $_GET['state_type']=='state_success'?'selected':''; ?>>已完成</option>
            <option value="state_noeval" <?php echo $_GET['state_type']=='state_noeval'?'selected':''; ?>>待评价</option>
            <option value="state_notakes" <?php echo $_GET['state_type']=='state_notakes'?'selected':''; ?>>待自提</option>
            <option value="state_cancel" <?php echo $_GET['state_type']=='state_cancel'?'selected':''; ?>>已取消</option>
          </select></td>
        <th><?php echo $lang['member_order_time'];?></th>
        <td class="w240"><input type="text" class="text w70" name="query_start_date" id="query_start_date" value="<?php echo $_GET['query_start_date']; ?>"/><label class="add-on"><i class="icon-calendar"></i></label>&nbsp;&#8211;&nbsp;<input type="text" class="text w70" name="query_end_date" id="query_end_date" value="<?php echo $_GET['query_end_date']; ?>"/><label class="add-on"><i class="icon-calendar"></i></label></td>
        <td class="w240 tr"><input type="text" class="text w200" placeholder="输入商品标题或订单号进行搜索" name="keyword" value="<?php echo $_GET['keyword']; ?>"></td>
        <td class="w70 tc"><label class="submit-border">
            <input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>"/>
          </label></td>
      </tr>
    </table>
  </form>
  <table class="ncm-default-table order">
    <thead>
      <tr>
        <th class="w10"></th>
        <th colspan="2">商品</th>
        <th class="w90">单价（元）</th>
        <th class="w40">数量</th>
        <th class="w90">优惠活动</th>
        <th class="w90">售后维权</th>
        <th class="w110">订单金额</th>
        <th class="w90">交易状态</th>
        <th class="w120">交易操作</th>
      </tr>
    </thead>
    <?php if ($output['order_group_list']) { ?>
    <?php foreach ($output['order_group_list'] as $order_pay_sn => $group_info) { ?>
    <?php $p = 0;?>
    <tbody<?php if (!empty($group_info['pay_amount']) && $p == 0) {?> class="pay" <?php }?>>
      <?php foreach($group_info['order_list'] as $order_id => $order_info) {?>
      <?php if ($group_info['pay_amount'] <= 0) {?>
      <tr>
        <td colspan="19" class="sep-row"></td>
      </tr>
      <?php } ?>
      <?php if ($group_info['pay_amount'] > 0 && $p == 0) {?>
      <tr>
        <td colspan="19" class="sep-row"></td>
      </tr>
      <tr>
        <td colspan="19" class="pay-td"><span class="ml15">在线支付金额：<em>￥<?php echo ncPriceFormat($group_info['pay_amount']);?></em></span> <a class="ncbtn ncbtn-bittersweet fr mr15" href="index.php?act=buy&op=pay&pay_sn=<?php echo $order_pay_sn; ?>"><i class="icon-shield"></i>订单支付</a></td>
      </tr>
      <?php }?>
      <?php $p++;?>
      <tr>
        <th colspan="19"> <span class="ml10"> 
          <!-- order_sn --> 
          <?php echo $lang['member_order_sn'].$lang['nc_colon'];?><?php echo $order_info['order_sn']; ?>
          <?php if ($order_info['order_from'] == 2){?>
          <i class="icon-mobile-phone"></i>
          <?php }?>
          </span> 
          <!-- order_time --> 
          <span><?php echo $lang['member_order_time'].$lang['nc_colon'];?><?php echo date("Y-m-d H:i:s",$order_info['add_time']); ?></span> 
          
          <!-- store_name --> 
          <span><a href="<?php echo urlShop('show_store','index',array('store_id'=>$order_info['store_id']), $order_info['extend_store']['store_domain']);?>" title="<?php echo $order_info['store_name'];?>"><?php echo $order_info['store_name']; ?></a></span> 
          
          <!-- QQ --> 
          <span member_id="<?php echo $order_info['extend_store']['member_id'];?>">
          <?php if(!empty($order_info['extend_store']['store_qq'])){?>
          <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $order_info['extend_store']['store_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $order_info['extend_store']['store_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $order_info['extend_store']['store_qq'];?>:52" style=" vertical-align: middle;"/></a>
          <?php }?>
          
          <!-- wang wang -->
          <?php if(!empty($order_info['extend_store']['store_ww'])){?>
          <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $order_info['extend_store']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>"  class="vm" ><img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $order_info['extend_store']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="Wang Wang"  style=" vertical-align: middle;"/></a>
          <?php }?>
          </span> <a href="javascript:void(0)" class="share-goods" nc_type="sharegoods" data-param='{"gid":"<?php echo $order_info['goods_list'][0]['goods_id'];?>"}'><i class="icon-share"></i><?php echo $lang['member_order_snsshare'];?></a>
          <?php if ($order_info['chain_id']) { ?>
          <span>取货方式：门店自提</span>
          <?php } ?>
          
          <!-- 放入回收站 -->
          
          <?php if ($order_info['if_delete']) { ?>
          <a href="javascript:void(0);" class="order-trash" onclick="ajax_get_confirm('您确定要删除吗?删除后该订单可以在回收站找回，或彻底删除', 'index.php?act=member_order&op=change_state&state_type=order_delete&order_id=<?php echo $order_info['order_id']; ?>');"><i class="icon-trash"></i>删除</a>
          <?php } ?>
          
          <!-- 还原订单 -->
          
          <?php if ($order_info['if_restore']) { ?>
          <a href="javascript:void(0);" class="order-trash" onclick="ajax_get_confirm('您确定要还原吗?', 'index.php?act=member_order&op=change_state&state_type=order_restore&order_id=<?php echo $order_info['order_id']; ?>');"><i class="icon-refresh"></i>还原</a>
          <?php } ?>
        </th>
      </tr>
      
      <!-- S 商品列表 -->
      <?php $i = 0;?>
      <?php if (is_array($order_info['goods_list'])) { ?>
      <?php foreach ($order_info['goods_list'] as $k => $goods_info) {?>
      <?php $i++;?>
      <tr>
        <td class="bdl"></td>
        <td class="w70"><div class="ncm-goods-thumb"><a href="<?php echo $goods_info['goods_url'];?>" target="_blank"><img src="<?php echo $goods_info['image_60_url'];?>" onMouseOver="toolTip('<img src=<?php echo $goods_info['image_240_url'];?>>')" onMouseOut="toolTip()"/></a></div></td>
        <td class="tl"><dl class="goods-name">
            <dt><a href="<?php echo $goods_info['goods_url'];?>" target="_blank"><?php echo $goods_info['goods_name']; ?></a><span class="rec"><a target="_blank" href="<?php echo urlShop('snapshot', 'index', array('rec_id' => $goods_info['rec_id']));?>">[交易快照]</a></span></dt>
            <?php if (!empty($goods_info['goods_spec'])) { ?>
          <dd><?php echo $goods_info['goods_spec'];?></dd>
          <?php } ?>
            <!-- 消费者保障服务 -->
            <?php if($goods_info["contractlist"]){?>
           <dd class="goods-cti"> 
              <!-- S消费者保障服务 -->              
              <?php foreach($goods_info["contractlist"] as $gcitem_v){?>
              <span <?php if($gcitem_v['cti_descurl']){ ?>onclick="window.open('<?php echo $gcitem_v['cti_descurl'];?>');" style="cursor: pointer;"<?php }?> title="<?php echo $gcitem_v['cti_name']; ?>"> <img src="<?php echo $gcitem_v['cti_icon_url_60'];?>"/> </span>
              <?php }?>              
              <!-- E消费者保障服务 --> 
            </dd><?php }?>
          </dl></td>
        <td><?php echo ncPriceFormat($goods_info['goods_price']);?>
          <p class="green">
            <?php if (is_array($order_info['refund_all']) && !empty($order_info['refund_all']) && $order_info['refund_all']['admin_time'] > 0) {?>
            <?php echo $goods_info['goods_pay_price'];?>(退)
            <?php } elseif ($goods_info['extend_refund']['admin_time'] > 0) { ?>
            <?php echo $goods_info['extend_refund']['refund_amount'];?>(退)
            <?php } ?>
          </p></td>
        <td><?php echo $goods_info['goods_num']; ?></td>
        <td><?php if (!empty($goods_info['goods_type_cn'])) { ?>
          <span class="goods-type"><?php echo $goods_info['goods_type_cn'];?></span>
          <?php } ?></td>
        <td><!-- 退款 -->
          
          <?php if ($goods_info['refund'] == 1){?>
          <p><a href="index.php?act=member_refund&op=add_refund&order_id=<?php echo $order_info['order_id']; ?>&goods_id=<?php echo $goods_info['rec_id']; ?>">退款/退货</a></p>
          <?php }?>
          <?php if (is_array($order_info['refund_all']) && !empty($order_info['refund_all'])) {?>
          <dd><a target="_blank" href="index.php?act=member_refund&op=view&refund_id=<?php echo $order_info['refund_all']['refund_id'];?>">查看退款</a></dd>
          <?php }else if($goods_info['extend_refund']['refund_type'] == 1) {?>
          <dd><a target="_blank" href="index.php?act=member_refund&op=view&refund_id=<?php echo $goods_info['extend_refund']['refund_id'];?>">查看退款</a></dd>
          <?php }else if($goods_info['extend_refund']['refund_type'] == 2) {?>
          <dd><a target="_blank" href="index.php?act=member_return&op=view&return_id=<?php echo $goods_info['extend_refund']['refund_id'];?>">查看退货</a></dd>
          <?php } ?>
          
          <!-- 投诉 -->
          
          <?php if ($order_info['if_complain']){ ?>
          <p><a href="index.php?act=member_complain&op=complain_new&order_id=<?php echo $order_info['order_id']; ?>&goods_id=<?php echo $goods_info['rec_id']; ?>">交易投诉</a></p>
          <?php } ?></td>
        
        <!-- S 合并TD -->
        <?php if (($order_info['goods_count'] > 1 && $k ==0) || ($order_info['goods_count'] == 1)){?>
        <td class="bdl" rowspan="<?php echo $order_info['goods_count'];?>"><p class=""><strong><?php echo $order_info['order_amount']; ?></strong></p>
          <p class="goods-freight">
            <?php if ($order_info['shipping_fee'] > 0){?>
            (<?php echo $lang['member_order_shipping_han'];?>运费<?php echo $order_info['shipping_fee'];?>)
            <?php }else{?>
            <?php echo $lang['nc_common_shipping_free'];?>
            <?php }?>
          </p>
          <p title="<?php echo $lang['member_order_pay_method'].$lang['nc_colon'];?><?php echo $order_info['payment_name']; ?>"><?php echo $order_info['payment_name']; ?></p></td>
        <td class="bdl" rowspan="<?php echo $order_info['goods_count'];?>"><p><?php echo $order_info['state_desc']; ?> <?php echo $order_info['evaluation_status'] ? $lang['member_order_evaluated'] : '';?></p>
          
          <!-- 订单查看 -->
          
          <p><a href="index.php?act=member_order&op=show_order&order_id=<?php echo $order_info['order_id']; ?>" target="_blank"><?php echo $lang['member_order_view_order'];?></a></p>
          
          <!-- 物流跟踪 -->
          
          <?php if ($order_info['if_deliver']){ ?>
          <p><a href='index.php?act=member_order&op=search_deliver&order_id=<?php echo $order_info['order_id']; ?>&order_sn=<?php echo $order_info['order_sn']; ?>' target="_blank"><?php echo $lang['member_order_show_deliver']?></a></p>
          <?php } ?></td>
        <td class="bdl bdr" rowspan="<?php echo $order_info['goods_count'];?>"><!-- 永久删除 --> 
          
          <!-- 锁定-->
          
          <?php if ($order_info['if_lock']) { ?>
          <p>退款退货中</p>
          <?php } ?>
          
          <!-- 取消订单 -->
          
          <?php if ($order_info['if_buyer_cancel']) { ?>
          <p><a href="javascript:void(0)" class="ncbtn ncbtn-grapefruit" nc_type="dialog" dialog_width="480" dialog_title="<?php echo $lang['member_order_cancel_order'];?>" dialog_id="buyer_order_cancel_order" uri="index.php?act=member_order&op=change_state&state_type=order_cancel&order_id=<?php echo $order_info['order_id']; ?>"  id="order<?php echo $order_info['order_id']; ?>_action_cancel"><i class="icon-ban-circle"></i> <?php echo $lang['member_order_cancel_order'];?></a></p>
          <?php } ?>
          
          <!-- 退款取消订单 -->
          
          <?php if ($order_info['if_refund_cancel']){ ?>
          <p><a href="index.php?act=member_refund&op=add_refund_all&order_id=<?php echo $order_info['order_id']; ?>" class="ncbtn"><i class="icon-legal"></i>订单退款</a></p>
          <?php } ?>
          
          <!-- 收货 -->
          
          <?php if ($order_info['if_receive']) { ?>
          <p><a href="javascript:void(0)" class="ncbtn" nc_type="dialog" dialog_id="buyer_order_confirm_order" dialog_width="480" dialog_title="<?php echo $lang['member_order_ensure_order'];?>" uri="index.php?act=member_order&op=change_state&state_type=order_receive&order_sn=<?php echo $order_info['order_sn']; ?>&order_id=<?php echo $order_info['order_id']; ?>" id="order<?php echo $order_info['order_id']; ?>_action_confirm"><?php echo $lang['member_order_ensure_order'];?></a></p>
          <?php } ?>
          
          <!-- 评价 -->
          
          <?php if ($order_info['if_evaluation']) { ?>
          <p><a class="ncbtn ncbtn-aqua" href="index.php?act=member_evaluate&op=add&order_id=<?php echo $order_info['order_id']; ?>"><i class="icon-thumbs-up-alt"></i><?php echo $lang['member_order_want_evaluate'];?></a></p>
          <?php } ?>
          
          <!-- 追加评价 -->
          
          <?php if ($order_info['if_evaluation_again']) { ?>
          <p><a class="ncbtn" href="index.php?act=member_evaluate&op=add_again&order_id=<?php echo $order_info['order_id']; ?>"><i class="icon-thumbs-up-alt"></i>追加评价</a></p>
          <?php } ?>
          <?php if ($order_info['if_drop']) { ?>
          <p><a href="javascript:void(0);" onclick="ajax_get_confirm('您确定要永久删除吗?永久删除后您将无法再查看该订单，也无法进行投诉维权，请谨慎操作！', 'index.php?act=member_order&op=change_state&state_type=order_drop&order_id=<?php echo $order_info['order_id']; ?>');" class="ncbtn ncbtn-grapefruit mt5"><i class="icon-trash"></i>永久删除</a></p>
          <?php } ?></td>
        <!-- E 合并TD -->
        <?php } ?>
      </tr>
      
      <!-- S 赠品列表 -->
      <?php if (!empty($order_info['zengpin_list']) && $i == count($order_info['goods_list'])) { ?>
      <tr>
        <td class="bdl"></td>
        <td colspan="6" class="tl"><div class="ncm-goods-gift"><strong>赠品：</strong>
			<ul><?php foreach ($order_info['zengpin_list'] as $zengpin_info) { ?>
              <li><a title="赠品：<?php echo $zengpin_info['goods_name'];?> * <?php echo $zengpin_info['goods_num'];?>" href="<?php echo $zengpin_info['goods_url'];?>" target="_blank"><img src="<?php echo $zengpin_info['image_60_url'];?>" onMouseOver="toolTip('<img src=<?php echo $zengpin_info['image_240_url'];?>>')" onMouseOut="toolTip()"/></a></li>
              <?php } ?>
            </ul>
          </div></td>
      </tr>
      <?php } ?>
      <!-- E 赠品列表 --> 
      
      <!-- S 预定时段 -->
      <?php if ($order_info['order_type'] == 2 && $i == count($order_info['goods_list'])) { ?>
      <?php if (is_array($order_info['book_list'])) { ?>
      <?php foreach($order_info['book_list'] as $book_info) {?>
      <tr>
        <td class="bdl"></td>
        <td colspan="2"><?php echo $book_info['book_step'];?></td>
        <td colspan="2"><?php echo $book_info['book_amount'].$book_info['book_amount_ext'];?></td>
        <td colspan="3"><?php echo $book_info['book_state'];?></td>
        <td class="bdr" colspan="2"><?php echo $book_info['book_operate'];?></td>
      </tr>
      <?php } ?>
      <?php } ?>
      <?php } ?>
      <!-- E 预定时段 -->
      
      <?php } ?>
      <?php } ?>
      <!-- E 商品列表 -->
      
      <?php } ?>
    </tbody>
    <?php } ?>
    <?php } else { ?>
    <tbody>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
    </tbody>
    <?php } ?>
    <?php if($output['order_group_list']) { ?>
    <tfoot>
      <tr>
        <td colspan="19"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
      </tr>
    </tfoot>
    <?php } ?>
  </table>
</div>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script> 
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js" ></script> 
<script type="text/javascript">
$(function(){
    $('#query_start_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('#query_end_date').datepicker({dateFormat: 'yy-mm-dd'});
});
</script> 
