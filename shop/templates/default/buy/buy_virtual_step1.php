<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="ncc-main">
  <div class="ncc-title">
    <h3>购买兑换码</h3>
    <h5>设置购买数量</h5>
  </div>
  <form action="<?php echo urlShop('buy_virtual','buy_step2');?>" method="POST" id="form_buy" name="form_buy">
    <input type="hidden" name="goods_id" value="<?php echo $output['goods_info']['goods_id'];?>">
    <table class="ncc-table-style" nc_type="table_cart">
      <thead>
        <tr>
          <th colspan="3">商品</th>
          <th class="w150">单价(<?php echo $lang['currency_zh'];?>)</th>
          <th class="w100">数量</th>
          <th class="w150">小计(<?php echo $lang['currency_zh'];?>)</th>
          <th class="w80 tl">操作</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th colspan="20"><!-- S 店铺名称 -->
            
            <div class="ncc-store-name">店铺：<a href="<?php echo urlShop('show_store','index',array('store_id'=>$output['store_info']['store_id']));?>"><?php echo $output['store_info']['store_name'];?></a> <span member_id="<?php echo $output['store_info']['member_id'];?>"></span></div>
          
          <!-- E 店铺名称 --> 
          </th>
        </tr>
        <tr class="shop-list">
          <td class="w10 td-border-left "></td>
          <td class="w100"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$output['goods_info']['goods_id']));?>" target="_blank" class="ncc-goods-thumb"><img src="<?php echo thumb($output['goods_info']);?>" alt="<?php echo $output['goods_info']['goods_name']; ?>" /></a></td>
          <td class="tl"><dl class="ncc-goods-info">
              <dt><a href="<?php echo urlShop('goods','index',array('goods_id'=>$output['goods_info']['goods_id']));?>" target="_blank"><?php echo $output['goods_info']['goods_name']; ?></a></dt>
              <?php if ($output['goods_info']['goods_spec']) { ?>
              <dd class="goods-spec"><?php echo $output['goods_info']['goods_spec'];?></dd>
              <?php } ?>
              <!-- S消费者保障服务 -->
              <?php if($output['goods_info']["contractlist"]){?>
              <dd class="goods-cti">
                <?php foreach($output['goods_info']["contractlist"] as $gcitem_k=>$gcitem_v){?>
                <span <?php if($gcitem_v['cti_descurl']){ ?>onclick="window.open('<?php echo $gcitem_v['cti_descurl'];?>');" style="cursor: pointer;"<?php }?> title="<?php echo $gcitem_v['cti_name']; ?>"> <img src="<?php echo $gcitem_v['cti_icon_url_60'];?>"/> </span>
                <?php }?>
              </dd>
              <?php }?>
              <!-- E消费者保障服务 -->
              
            </dl></td>
          <td><em id="item_price" class="goods-price"><?php echo $output['goods_info']['goods_price'];?></em> 
            <!-- S 商品促销-抢购 -->
            
            <?php if ($output['goods_info']['ifgroupbuy']) { ?>
            <dl class="ncc-goods-sale">
              <dt>商家促销<i class="icon-angle-down"></i></dt>
              <dd>
                <p>活动名称：抢购</p>
                <?php if ($cart_info['upper_limit']) {?>
                <p>最多限购：<strong><?php echo $output['goods_info']['virtual_limit'];?></strong>件 </p>
                <?php } ?>
              </dd>
            </dl>
            <?php }?>
            
            <!-- E 商品促销-抢购 --></td>
          <td class="ws0"><a href="JavaScript:void(0);" onclick="decrease_quantity();" class="add-substract-key ">-</a>
            <input id="quantity" name="quantity" value="<?php echo $output['goods_info']['quantity'];?>" maxvalue="<?php echo $output['goods_info']['virtual_limit'];?>" price="<?php echo $output['goods_info']['goods_price'];?>" onkeyup="change_quantity(this);" type="text" class="text w20"/>
            <a href="JavaScript:void(0);" title="最多允许购买<?php echo $output['goods_info']['virtual_limit'];?>个" onclick="add_quantity();" class="add-substract-key tip" >+</a></td>
          <td><em id="item_subtotal" class="goods-subtotal"><?php echo $output['goods_info']['goods_total'];?></em></td>
          <td class="tl td-border-right"><a href="javascript:void(0)" onclick="collect_goods('<?php echo $output['goods_info']['goods_id']; ?>');">移入收藏夹</a></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20"><div class="ncc-all-account">订单总金额：<em id="cartTotal"><?php echo $output['goods_info']['goods_total']; ?></em>元</div>
            <a id="next_submit" href="javascript:void(0)" class="ncc-next-submit ok">确认订单</a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
$(document).ready(function(){
	$('#next_submit').on('click',function(){
		$('#form_buy').submit();
	});
});
</script> 
