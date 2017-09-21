<?php defined('In33hao') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_goods.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/mz-packed.js" charset="utf-8"></script>
<div class="wrapper pr">
  <input type="hidden" id="lockcompare" value="unlock" />
  <div class="ncs-detail<?php if ($output['store_info']['is_own_shop']) echo ' ownshop'; ?>"> 
   <!-- 焦点图 -->
	    <div id="ncs-goods-picture" class="ncs-goods-picture">
      <div class="gallery_wrap">
        <div class="gallery"><img title="鼠标滚轮向上或向下滚动，能放大或缩小图片哦~" src="<?php echo $output["goods_image"]["0"]["1"] ?>" class="cloudzoom" data-cloudzoom="zoomImage: '<?php echo $output["goods_image"]["0"]["2"] ?>'"> </div>
      </div>
      <div class="controller_wrap">
        <div class="controller">
          <ul>
            <?php foreach ($output["goods_image"] as $key => $value) { ?>
            <li><img title="鼠标滚轮向上或向下滚动，能放大或缩小图片哦~" class='cloudzoom-gallery' src="<?php echo $value['0'] ?>" data-cloudzoom="useZoom: '.cloudzoom', image: '<?php echo $value['1'] ?>', zoomImage: '<?php echo $value['2'] ?>' " width="60" height="60"></li>
             <?php  }?>
          </ul>
        </div>
      </div>
    </div>
						<!-- //焦点图 -->

    <!-- S 商品基本信息 -->
    <div class="ncs-goods-summary">
      <div class="name">
        <h1><?php echo $output['goods']['goods_name']; ?></h1>
        <strong><?php echo str_replace("\n", "<br>", $output['goods']['goods_jingle']);?></strong> </div>
      <div class="ncs-meta">
        <?php if ($output['goods']['is_book'] == 0) {?>
        <!-- S 商品参考价格 -->
        <dl>
          <dt><?php echo $lang['goods_index_goods_cost_price'];?><?php echo $lang['nc_colon'];?></dt>
          <dd class="cost-price"><strong><?php echo $lang['currency'].ncPriceFormat($output['goods']['goods_marketprice']);?></strong></dd>
        </dl>
        <!-- E 商品参考价格 --> 
        <!-- S 商品发布价格 -->
        <dl>
          <dt><?php echo $lang['goods_index_goods_price'];?><?php echo $lang['nc_colon'];?></dt>
          <dd class="price">
            <?php if (isset($output['goods']['promotion_price']) && !empty($output['goods']['promotion_price'])) {?>
            <strong><i><?php echo $lang['currency'];?></i><?php echo ncPriceFormat($output['goods']['promotion_price']);?></strong><em>(原售价<?php echo $lang['nc_colon'];?><?php echo $lang['currency'].ncPriceFormat($output['goods']['goods_price']);?>)</em>
            <?php } else {?>
            <strong><?php echo $lang['currency'].ncPriceFormat($output['goods']['goods_price']);?></strong>
            <?php }?>
          </dd>
        </dl>
        <dl class="rate">
          <dt>商品评分：</dt>
          <!-- S 描述相符评分 -->
          <dd><span class="raty" data-score="<?php echo $output['goods_evaluate_info']['star_average'];?>"></span><a href="#ncGoodsRate">共有<?php echo $output['goods']['evaluation_count']; ?>条评价</a></dd>
          <!-- E 描述相符评分 -->
        </dl>
        <!-- E 商品发布价格 -->
        <?php } else {?>
        <!-- S 预定商品 -->
        
        <dl>
          <dt><?php echo $lang['goods_index_goods_price'];?><?php echo $lang['nc_colon'];?></dt>
          <dd class="cost-price"><strong><?php echo $lang['currency'].$output['goods']['goods_price'];?></strong></dd>
        </dl>
        <dl>
          <dt>预定价格<?php echo $lang['nc_colon'];?></dt>
          <dd class="price"><strong><?php echo $lang['currency'].ncPriceFormat((floatval($output['goods']['book_down_payment']) + floatval($output['goods']['book_final_payment'])));?></strong></dd>
        </dl>
        <div class="ncs-book-down">
          <div class="rule-price"><strong>定金</strong><?php echo $lang['currency'].ncPriceFormat($output['goods']['book_down_payment']);?></div>
          <div class="rule-symbol">+</div>
          <div class="rule-price"><strong>尾款</strong><?php echo $lang['currency'].ncPriceFormat($output['goods']['book_final_payment']);?> </div>
          <div class="rule-info"><a href="javascript:void(0);">预定规则<i class="icon-question-sign"></i>
            <ul>
              <span class="arrow"></span>
              <li>1.定金下单后，请在<?php echo ORDER_AUTO_CANCEL_TIME * 60;?>分钟内付款，超时将自动关闭订单。</li>
              <li>2.定金付款后，若非商城或商家责任（根据《售后政策》和客服判断为准），定金恕不退还；</li>
              <li>3.预售结束时，由系统自动更新尾款价格，先下单后下单均能享受同样的价格。请至商城“我的订单”内付尾款；</li>
              <li>4.尾款开始付款后，请在要求的时间（<?php echo BOOK_AUTO_END_TIME;?>小时）内支付尾款，若超时将自动关闭订单，且定金不予退还。请注意预售的结束时间，并及时支付尾款；</li>
              <li>5.预售商品不支持7天无理由退换货；</li>
              <li>6.发货时间请以预定商品详情页“发货时间”描述为准。</li>
            </ul>
            </a> </div>
        </div>
        <dl>
          <dt>预定人数<?php echo $lang['nc_colon'];?></dt>
          <dd><strong><?php echo $output['goods']['book_buyers']; ?></strong> 人</dd>
        </dl>
        <dl>
          <dt>预定结束<?php echo $lang['nc_colon'];?></dt>
          <dd>剩余<strong><?php echo $output['remain']['day'];?></strong>天<strong><?php echo $output['remain']['hour'];?></strong>时<strong><?php echo $output['remain']['minute'];?></strong>分</dd>
        </dl>
        <!-- S 预定商品 -->
        <?php }?>
        <div class="ncs-goods-code">
          <p><img src="<?php echo goodsQRCode($output['goods']);?>"  title="用商城手机客户端扫描二维码直达商品详情内容"></p>
          <span class="ncs-goods-code-note"><i></i>客户端扫购有惊喜</span> </div>
      </div>
      <?php if($output['goods']['goods_state'] != 10 && $output['goods']['goods_verify'] == 1){?>
      <!-- S 促销 -->
      <?php if (isset($output['goods']['promotion_type']) || $output['goods']['have_gift'] || !empty($output['goods']['jjg_explain']) || !empty($output['mansong_info'])) {?>
      <div class="ncs-sale">
      <?php if (isset($output['goods']['promotion_type']) || !empty($output['goods']['jjg_explain']) || !empty($output['mansong_info'])) {?>
        <dl>
          <dt>促&#12288;&#12288;销：</dt>
          <dd class="promotion-info">
            <?php if (isset($output['goods']['title']) && $output['goods']['title'] != '') {?>
            <span class="sale-name"><?php echo $output['goods']['title'];?></span>
            <?php }?>
            <!-- S 限时折扣 -->
            <?php if ($output['goods']['promotion_type'] == 'xianshi') {?>
            <span class="sale-rule w400">直降<em><?php echo $lang['currency'].ncPriceFormat($output['goods']['down_price']);?></em>
            <?php if($output['goods']['lower_limit']) {?>
            <?php echo sprintf('最低%s件起，',$output['goods']['lower_limit']);?><?php echo $output['goods']['explain'];?>
            <?php } ?>
            </span>
            <?php }?>
            <!-- E 限时折扣  --> 
            <!-- S 抢购-->
            <?php if ($output['goods']['promotion_type'] == 'groupbuy') {?>
            <?php if ($output['goods']['upper_limit']) {?>
            <em><?php echo sprintf('最多限购%s件',$output['goods']['upper_limit']);?></em>
            <?php } ?>
            <span><?php echo $output['goods']['remark'];?></span><br>
            <?php }?>
            <!-- E 抢购 --> 
            <!--S 满就送 -->
            <?php if($output['mansong_info']) { ?>
            <div class="ncs-mansong"> <span class="sale-name">满即送</span> <span class="sale-rule">
              <?php $rule = $output['mansong_info']['rules'][0]; echo $lang['nc_man'];?>
              <em><?php echo $lang['currency'].ncPriceFormat($rule['price']);?></em>
              <?php if(!empty($rule['discount'])) { ?>
              ，<?php echo $lang['nc_reduce'];?><em><?php echo $lang['currency'].ncPriceFormat($rule['discount']);?></em>
              <?php } ?>
              <?php if(!empty($rule['goods_id'])) { ?>
              ，<?php echo $lang['nc_gift'];?><a href="<?php echo $rule['goods_url'];?>" title="<?php echo $rule['mansong_goods_name'];?>" target="_blank">赠品</a>
              <?php } ?>
              </span> <span class="sale-rule-more" nctype="show-rule"><a href="javascript:void(0);">共<strong><?php echo count($output['mansong_info']['rules']);?></strong>项，展开查看<i></i></a></span>
              <div class="sale-rule-content" style="display: none;" nctype="rule-content">
                <div class="title"><span class="sale-name">满即送</span>共<strong><?php echo count($output['mansong_info']['rules']);?></strong>项，促销活动规则<a href="javascript:;" nctype="hide-rule">关闭</a></div>
                <div class="content">
                  <div class="mjs-tit"><?php echo $output['mansong_info']['mansong_name'];?>
                    <time>( <?php echo $lang['nc_promotion_time'];?><?php echo $lang['nc_colon'];?><?php echo date('Y-m-d',$output['mansong_info']['start_time']).'--'.date('Y-m-d',$output['mansong_info']['end_time']);?> )</time>
                  </div>
                  <ul class="mjs-info">
                    <?php foreach($output['mansong_info']['rules'] as $rule) { ?>
                    <li> <span class="sale-rule"><?php echo $lang['nc_man'];?><em><?php echo $lang['currency'].ncPriceFormat($rule['price']);?></em>
                      <?php if(!empty($rule['discount'])) { ?>
                      ， <?php echo $lang['nc_reduce'];?><em><?php echo $lang['currency'].ncPriceFormat($rule['discount']);?></em>
                      <?php } ?>
                      <?php if(!empty($rule['goods_id'])) { ?>
                      ， <?php echo $lang['nc_gift'];?> <a href="<?php echo $rule['goods_url'];?>" title="<?php echo $rule['mansong_goods_name'];?>" target="_blank" class="gift"> <img src="<?php echo cthumb($rule['goods_image'], 60);?>" alt="<?php echo $rule['mansong_goods_name'];?>"> </a>&nbsp;。
                      <?php } ?>
                      </span> </li>
                    <?php } ?>
                  </ul>
                  <div class="mjs-remark"><?php echo $output['mansong_info']['remark'];?></div>
                </div>
                <div class="bottom"><a href="<?php echo urlShop('show_store', 'mansong_goods', array('store_id' => $output['store_info']['store_id']));?>" class="url" target="_blank">查看更多店铺“满即送”活动商品</a></div>
              </div>
            </div>
            <?php } ?>
            <!--E 满就送 --> 
            <!-- S 加价购 -->
            <?php if ($output['goods']['jjg_explain']) { ?>
            <div class="ncs-jjg"> <span class="sale-name">加价购</span> <span class="sale-rule"><?php echo $output['goods']['jjg_explain']; ?></span> <span nctype="show-rule" class="sale-rule-more"><a href="javascript:void(0);">共<strong><?php echo count($output['goods']['jjg_info']['levels']);?></strong>项，展开查看<i></i></a></span>
              <div class="sale-rule-content" style="display: none;" nctype="rule-content">
                <div class="title"><span class="sale-name">加价购</span>共<strong><?php echo count($output['goods']['jjg_info']['levels']);?></strong>项，促销活动规则<a href="javascript:;" nctype="hide-rule">关闭</a></div>
                <div class="cou-rule-list">
                  <div class="couRuleScrollbar">
                    <?php $shownLevelSkus = array();
foreach ((array) $output['goods']['jjg_info']['levels'] as $levelId => $v) { ?>
                    <div class="cou-rule">
                      <h4>规则<?php echo $levelId; ?>：消费满<strong><?php echo $lang['currency'].$v['mincost']; ?></strong>
                        <?php if ($v['maxcou'] > 0) { ?>
                        可换购最多<strong><?php echo $v['maxcou']; ?></strong>种优惠商品
                        <?php } else { ?>
                        可换购任意多种优惠商品
                        <?php } ?>
                      </h4>
                      <ul>
                        <?php foreach ((array) $output['goods']['jjg_info']['levelSkus'][$levelId] as $sku => $vv) {
$g = $output['goods']['jjg_info']['items'][$sku];
if (empty($g) || isset($shownLevelSkus[$sku])) {
    continue;
}
$shownLevelSkus[$sku] = true; ?>
                        <li title="<?php echo $g['name']; ?>"><a target="_blank" href="<?php echo urlShop('goods', 'index', array('goods_id' => $sku)); ?>"> <img alt="" src="<?php echo cthumb($g['goods_image'], 60); ?>"/>
                          <h5><?php echo $g['name']; ?></h5>
                          <h6 title="换购价">￥<?php echo $vv['price']; ?></h6>
                          </a> </li>
                        <?php } ?>
                      </ul>
                    </div>
                    <?php } ?>
                  </div>
                </div>
                <div class="bottom"><a href="<?php echo urlShop('show_store', 'cou_goods', array('store_id' => $output['store_info']['store_id'], 'cou_id' => $output['goods']['jjg_info']['info']['id']));?>" class="url" target="_blank">查看更多店铺“加价购”活动商品</a></div>
              </div>
            </div>
            <?php } ?>
            <!-- E 加价购 --> 
          </dd>
        </dl>
        <?php }?>
        <!-- S 赠品 -->
        <?php if ($output['goods']['have_gift']) {?>
        <hr/>
        <dl>
          <dt>赠&#12288;&#12288;品：</dt>
          <dd class="goods-gift" id="ncsGoodsGift"> <span>数量有限，赠完为止。</span>
            <?php if (!empty($output['gift_array'])) {?>
            <ul>
              <?php foreach ($output['gift_array'] as $val){?>
              <li>
                <div class="goods-gift-thumb"><span><img src="<?php echo cthumb($val['gift_goodsimage'], '60', $output['goods']['store_id']);?>"></span></div>
                <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['gift_goodsid']));?>" class="goods-gift-name" target="_blank"><?php echo $val['gift_goodsname']?></a><em>x<?php echo $val['gift_amount'];?></em> </li>
              <?php }?>
            </ul>
            <?php }?>
          </dd>
        </dl>
        <?php }?>
        <!-- E 赠品 --> 
        
      </div>
      <?php }?>
      <!-- E 促销 -->
      
      <div class="ncs-logistics"><!-- S 物流与运费新布局展示  -->
        <?php if($output['goods']['goods_state'] == 1 && $output['goods']['goods_verify'] == 1 && $output['goods']['is_virtual'] == 0){ ?>
        <dl id="ncs-freight" class="ncs-freight">
          <dt>配&nbsp;&nbsp;送&nbsp;&nbsp;至：</dt>
          <dd class="ncs-freight_box">
            <div id="ncs-freight-selector" class="ncs-freight-select">
              <div class="text">
                <div><?php echo $output['store_info']['deliver_region'] ? str_replace(' ','',$output['store_info']['deliver_region'][1]) : '请选择地区'?></div>
                <b>∨</b> </div>
              <div class="content">
                <div id="ncs-stock" class="ncs-stock" data-widget="tabs">
                  <div class="mt">
                    <ul class="tab">
                      <li data-index="0" data-widget="tab-item" class="curr"><a href="#none" class="hover"><em><?php echo $output['store_info']['deliver_region_names'][0] ? $output['store_info']['deliver_region_names'][0] : '请选择'?></em><i> ∨</i></a></li>
                    </ul>
                  </div>
                  <div id="stock_province_item" data-widget="tab-content" data-area="0">
                    <ul class="area-list">
                    </ul>
                  </div>
                  <div id="stock_city_item" data-widget="tab-content" data-area="1" style="display: none;">
                    <ul class="area-list">
                    </ul>
                  </div>
                  <div id="stock_area_item" data-widget="tab-content" data-area="2" style="display: none;">
                    <ul class="area-list">
                    </ul>
                  </div>
                </div>
              </div>
              <a href="javascript:;" class="close" onclick="$('#ncs-freight-selector').removeClass('hover')">关闭</a> </div>
            <div id="ncs-freight-prompt"> <strong><?php echo $output['goods']['goods_storage'] > 0 ? '有货' : '无货'?></strong>
              <?php if (!$output['goods']['transport_id']) { ?>
              <?php echo $output['goods']['goods_freight'] > 0 ? '运费：'.ncPriceFormat($output['goods']['goods_freight']).' 元' : '免运费' ?>
              <?php } ?>
            </div>
          </dd>
        </dl>
        <!-- S 物流与运费新布局展示  -->
        <?php } ?>
        <!-- S 门店自提 -->
        <?php if($output['goods']['is_chain']){?>
        <hr/>
        <dl class="ncs-chain">
          <dt>门店服务：</dt>
          <dd><i class="icon-chain"></i><a href="javascript:void(0);" nctype="show_chain" data-goodsid="<?php echo $output['goods']['goods_id'];?>">门店自提</a>· 选择有现货的门店下单，可立即提货</dd>
        </dl>
        <?php }?>
        <!-- E 门店自提 --> 
      </div>
      <div class="ncs-key"> 
        
        <!-- S 商品规格值-->
        <?php if (is_array($output['goods']['spec_name'])) { ?>
        <hr/>
        <?php foreach ($output['goods']['spec_name'] as $key => $val) {?>
        <dl nctype="nc-spec">
          <dt><?php echo $val;?><?php echo $lang['nc_colon'];?></dt>
          <dd>
            <?php if (is_array($output['goods']['spec_value'][$key]) and !empty($output['goods']['spec_value'][$key])) {?>
            <ul nctyle="ul_sign">
              <?php foreach($output['goods']['spec_value'][$key] as $k => $v) {?>
              <?php if( $key == 1 ){?>
              <!-- 图片类型规格-->
              <li class="sp-img"><a href="javascript:void(0);" class="<?php if (isset($output['goods']['goods_spec'][$k])) {echo 'hovered';}?>" data-param="{valid:<?php echo $k;?>}" title="<?php echo $v;?>"><img src="<?php echo $output['spec_image'][$k];?>"/><?php echo $v;?><i></i></a></li>
              <?php }else{?>
              <!-- 文字类型规格-->
              <li class="sp-txt"><a href="javascript:void(0)" class="<?php if (isset($output['goods']['goods_spec'][$k])) { echo 'hovered';} ?>" data-param="{valid:<?php echo $k;?>}"><?php echo $v;?><i></i></a></li>
              <?php }?>
              <?php }?>
            </ul>
            <?php }?>
          </dd>
        </dl>
        <?php }?>
        <?php }?>
        <!-- E 商品规格值-->
        <?php if ($output['goods']['is_virtual'] == 1) {?>
        <dl>
          <dt>提货方式：</dt>
          <dd>
            <ul>
              <li class="sp-txt"><a href="javascript:void(0)" class="hovered">电子兑换券<i></i></a></li>
            </ul>
          </dd>
        </dl>
        <!-- 虚拟商品有效期 -->
        <dl>
          <dt>有&nbsp;效&nbsp;期：</dt>
          <dd>即日起 到 <?php echo date('Y-m-d H:i:s', $output['goods']['virtual_indate']);?></dd>
        </dl>
        <?php }else if ($output['goods']['is_presell'] == 1) {?>
        <!-- 预售商品发货时间 -->
        <dl>
          <dt>预&#12288;&#12288;售：</dt>
          <dd>
            <ul>
              <li class="sp-txt"><a href="javascript:void(0)" class="hovered"><?php echo date('Y-m-d', $output['goods']['presell_deliverdate']);?>&nbsp;日发货<i></i></a></li>
            </ul>
          </dd>
        </dl>
        <?php }?>
        <?php if ($output['goods']['is_fcode']) {?>
        <!-- 预售商品发货时间 -->
        <dl>
          <dt>购买类型：</dt>
          <dd>
            <ul>
              <li class="sp-txt"><a href="javascript:void(0)" class="hovered">F码优先购买<i></i></a></li>
            </ul>
          </dd>
        </dl>
        <?php }?>
      </div>
      <!-- S 购买数量及库存 -->
      <div class="ncs-buy">
        <?php if ($output['goods']['goods_state'] != 0 && $output['goods']['goods_storage'] >= 0) {?>
        <div class="ncs-figure-input">
          <input type="text" name="" id="quantity" value="1" size="3" maxlength="6" class="input-text" <?php if ($output['goods']['is_fcode'] == 1) {?>readonly<?php }?>>
          <a href="javascript:void(0)" class="increase" <?php if ($output['goods']['is_fcode'] != 1) {?>nctype="increase"<?php }?>>&nbsp;</a> <a href="javascript:void(0)" class="decrease" <?php if ($output['goods']['is_fcode'] != 1) {?>nctype="decrease"<?php }?>>&nbsp;</a> </div>
        <div class="ncs-point" style="display: none;"><i></i> 
          <!-- S 库存 --> 
          <span>您选择的商品库存<strong nctype="goods_stock"><?php echo $output['goods']['goods_storage']; ?></strong><?php echo $lang['nc_jian'];?></span> 
          <!-- E 库存 --> 
          <?php if ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_limit'] > 0) { ?> <!-- 虚拟商品限购数 -->
          <span class="look">，每人次限购<strong> 
          <!-- 虚拟抢购 设置了虚拟抢购限购数 该数小于原商品限购数 --> 
          <?php echo ($output['goods']['promotion_type'] == 'groupbuy' && $output['goods']['upper_limit'] > 0 && $output['goods']['upper_limit'] < $output['goods']['virtual_limit']) ? $output['goods']['upper_limit'] : $output['goods']['virtual_limit'];?> </strong>件商品。</span>
          <?php } else if ($output['goods']['promotion_type'] == 'groupbuy' && $output['goods']['upper_limit'] > 0){ ?> <!-- 抢购限购 -->
          <span class="look">，每人次限购<strong> 
          <?php echo $output['goods']['upper_limit'];?> </strong>件商品。</span>
          <?php }?>
          <?php if ($output['goods']['is_fcode'] == 1) {?>
          <span class="look">，每个F码可优先购买一件商品。</span>
          <?php }?>
        </div>
        <?php } ?>
        
        <!-- S 提示已选规格及库存不足无法购买 -->
        <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) {?>
        <div nctype="goods_prompt" class="ncs-point"><i></i> <span>您选择的商品<strong>库存不足</strong>；请选择店内其它商品或申请<a href="javascript:void(0);" nctype="arrival_notice" class="arrival" title="到货通知">到货通知</a>提示。</span> </div>
        <?php }?>
        <!-- E 提示已选规格及库存不足无法购买 --> 
        <!-- S 购买按钮 -->
        <div class="ncs-btn">
          <?php if ($output['goods']['cart'] == 1) {?>
          <!-- 加入购物车--> 
          <a href="javascript:void(0);" nctype="addcart_submit" class="addcart <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) {?>no-addcart<?php }?>" title="<?php echo $lang['goods_index_add_to_cart'];?>"><i class="icon-shopping-cart"></i><?php echo $lang['goods_index_add_to_cart'];?></a>
          <?php } ?>
          <?php if ($output['goods']['buynow'] == 1) {?>
          <!-- 立即购买--> 
          <a href="javascript:void(0);" nctype="buynow_submit" class="buynow <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0 || ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_indate'] < TIMESTAMP)) {?>no-buynow<?php }?>" title="<?php echo $output['goods']['buynow_text'];?>"><?php echo $output['goods']['buynow_text'];?></a>
          <?php }?>
          <!-- S 加入购物车弹出提示框 -->
          <div class="ncs-cart-popup">
            <dl>
              <dt><?php echo $lang['goods_index_cart_success'];?><a title="<?php echo $lang['goods_index_close'];?>" onClick="$('.ncs-cart-popup').css({'display':'none'});">X</a></dt>
              <dd><?php echo $lang['goods_index_cart_have'];?> <strong id="bold_num"></strong> <?php echo $lang['goods_index_number_of_goods'];?> <?php echo $lang['goods_index_total_price'];?><?php echo $lang['nc_colon'];?><em id="bold_mly" class="saleP"></em></dd>
              <dd class="btns"><a href="javascript:void(0);" class="ncbtn-mini ncbtn-mint" onClick="location.href='<?php echo SHOP_SITE_URL.DS?>index.php?act=cart'"><?php echo $lang['goods_index_view_cart'];?></a> <a href="javascript:void(0);" class="ncbtn-mini" value="" onClick="$('.ncs-cart-popup').css({'display':'none'});"><?php echo $lang['goods_index_continue_shopping'];?></a></dd>
            </dl>
          </div>
          <!-- E 加入购物车弹出提示框 --> 
        </div>
        <!-- E 购买按钮 --> 
      </div>
      <?php }else{?>
      <div class="ncs-buy">
        <div class="ncs-saleout">
          <dl>
            <dt><i class="icon-info-sign"></i><?php echo $lang['goods_index_is_no_show'];?></dt>
            <dd><?php echo $lang['goods_index_is_no_show_message_one'];?></dd>
            <dd><?php echo $lang['goods_index_is_no_show_message_two_1'];?>&nbsp;<a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$output['goods']['store_id']), $output['store_info']['store_domain']);?>" class="ncbtn-mini"><?php echo $lang['goods_index_is_no_show_message_two_2'];?></a>&nbsp;<?php echo $lang['goods_index_is_no_show_message_two_3'];?> </dd>
          </dl>
        </div>
      </div>
      <?php }?>
      <!-- E 购买数量及库存 --> 
      <!-- S消费者保障服务 -->
      <?php if($output['goods']["contractlist"]){?>
      <div class="ncs-cti">
        <hr/>
        <dl>
          <dt>服务承诺：</dt>
          <dd>
            <?php foreach($output['goods']["contractlist"] as $gcitem_k=>$gcitem_v){?>
            <span <?php if($gcitem_v['cti_descurl']){ ?>onclick="window.open('<?php echo $gcitem_v['cti_descurl'];?>');"<?php }?>> <img src="<?php echo $gcitem_v['cti_icon_url_60'];?>"/><?php echo $gcitem_v['cti_name'];?> </span>
            <?php }?>
          </dd>
        </dl>
      </div>
      <?php }?>
      <!-- E消费者保障服务 --> 
      <!--E 商品信息 --> 
    </div>
    <!-- E 商品图片及收藏分享 -->
    <div class="ncs-handle"> 
      <!-- S 分享 --> 
      <a href="javascript:void(0);" class="share" nc_type="sharegoods" data-param='{"gid":"<?php echo $output['goods']['goods_id'];?>"}'><i></i><?php echo $lang['goods_index_snsshare_goods'];?><span>(<em nc_type="sharecount_<?php echo $output['goods']['goods_id'];?>"><?php echo intval($output['goods']['sharenum'])>0?intval($output['goods']['sharenum']):0;?></em>)</span></a> 
      <!-- S 收藏 --> 
      <a href="javascript:collect_goods('<?php echo $output['goods']['goods_id']; ?>','count','goods_collect');" class="favorite"><i></i><?php echo $lang['goods_index_favorite_goods'];?><span>(<em nctype="goods_collect"><?php echo $output['goods']['goods_collect']?></em>)</span></a> 
      <!-- S 对比 --> 
      <a href="javascript:void(0);" class="compare" nc_type="compare_<?php echo $output['goods']['goods_id'];?>" data-param='{"gid":"<?php echo $output['goods']['goods_id'];?>"}'><i></i>加入对比</a><!-- S 举报 -->
      <?php if($output['inform_switch']) { ?>
      <a href="<?php if ($_SESSION['is_login']) {?>index.php?act=member_inform&op=inform_submit&goods_id=<?php echo $output['goods']['goods_id'];?><?php } else {?>javascript:login_dialog();<?php }?>" title="<?php echo $lang['goods_index_goods_inform'];?>" class="inform"><i></i><?php echo $lang['goods_index_goods_inform'];?></a>
      <?php } ?>
      <!-- End --> </div>
    
    <!--S 店铺信息-->
    <div style="position: absolute; z-index: 2; top: -1px; right: -1px;">
      <?php include template('store/info');?>
      
    
    <!--E 店铺信息 --> 
  </div>
  <div class="clear"></div>
</div>
<!-- S 优惠套装 -->
 <?php if(!empty($output['goods_commend']) && is_array($output['goods_commend']) && count($output['goods_commend'])>1){?>
    <div class="ncs-recommend">
      <div class="title">
        <h4>店铺热卖</h4>
      </div>
      <div class="content">
        <ul>
          <?php foreach($output['goods_commend'] as $goods_commend){?>
          <?php if($output['goods']['goods_id'] != $goods_commend['goods_id']){?>
          <li>
            <dl>
              <dt class="goods-name"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $goods_commend['goods_id']));?>" target="_blank" title="<?php echo $goods_commend['goods_jingle'];?>"><?php echo $goods_commend['goods_name'];?><em><?php echo $goods_commend['goods_jingle'];?></em></a></dt>
              <dd class="goods-pic"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $goods_commend['goods_id']));?>" target="_blank" title="<?php echo $goods_commend['goods_jingle'];?>"><img src="<?php echo thumb($goods_commend, 240);?>" alt="<?php echo $goods_commend['goods_name'];?>"/></a></dd>
              <dd class="goods-price"><?php echo $lang['currency'];?><?php echo $goods_commend['goods_promotion_price'];?></dd>
            </dl>
          </li>
          <?php }?>
          <?php }?>
        </ul>
        <div class="clear"></div>
      </div>
    </div>
    <?php }?>
<div class="ncs-promotion" id="nc-bundling" style="display:none;"></div>
<!-- E 优惠套装 -->
<div id="content" class="ncs-goods-layout expanded" >
  <div class="ncs-goods-main" id="main-nav-holder">
    <div class="tabbar pngFix" id="main-nav">
      <div class="ncs-goods-title-nav">
        <ul id="categorymenu">
          <li class="current"><a id="tabGoodsIntro" href="#content"><?php echo $lang['goods_index_goods_info'];?></a></li>
          <?php if ($output['goods']['is_virtual'] == 1) {?>
          <li><a id="tabStoreMap" href="#content">商家位置</a></li>
          <?php }?>
          <li><a id="tabGoodsRate" href="#content"><?php echo $lang['goods_index_evaluation'];?><em>(<?php echo $output['goods_evaluate_info']['all'];?>)</em></a></li>
          <li><a id="tabGoodsTraded" href="#content"><?php echo $lang['goods_index_sold_record'];?><em>(<?php echo $output['goods']['goods_salenum']; ?>)</em></a></li>
          <li><a id="tabGuestbook" href="#content"><?php echo $lang['goods_index_goods_consult'];?></a></li>
        </ul>
        <div class="switch-bar"><a href="javascript:void(0)" id="fold">&nbsp;</a></div>
      </div>
    </div>
    <div class="ncs-intro">
      <div class="content bd" id="ncGoodsIntro">
        <?php if(is_array($output['goods']['goods_attr']) || isset($output['goods']['brand_name'])){?>
        <ul class="nc-goods-sort">
          <li>商家货号：<?php echo $output['goods']['goods_serial'];?></li>
          <?php if(isset($output['goods']['brand_name'])){echo '<li>'.$lang['goods_index_brand'].$lang['nc_colon'].$output['goods']['brand_name'].'</li>';}?>
          <?php if(is_array($output['goods']['goods_attr']) && !empty($output['goods']['goods_attr'])){?>
          <?php foreach ($output['goods']['goods_attr'] as $val){ $val= array_values($val);echo '<li>'.$val[0].$lang['nc_colon'].$val[1].'</li>'; }?>
          <?php }?>
          <?php if (is_array($output['goods']['goods_custom'])) {
            foreach ($output['goods']['goods_custom'] as $val) {
                if ($val['value']!= '') {
                    echo '<li>'.$val['name'].$lang['nc_colon'].$val['value'].'</li>';
                }
            }
            }?>
        </ul>
        <?php }?>
        <div class="ncs-goods-info-content">
          <?php if (isset($output['plate_top'])) {?>
          <div class="top-template"><?php echo $output['plate_top']['plate_content']?></div>
          <?php }?>
          <div class="default"><?php echo $output['goods']['goods_body']; ?></div>
          <?php if (isset($output['plate_bottom'])) {?>
          <div class="bottom-template"><?php echo $output['plate_bottom']['plate_content']?></div>
          <?php }?>
        </div>
      </div>
    </div>
    <?php if ($output['goods']['is_virtual'] == 1) {?>
    <!-- S 店铺地址地图 -->
    <div class="ncs-shop-map">
      <div class="ncs-goods-title-bar hd">
        <h4><a href="javascript:void(0);">商家位置</a></h4>
      </div>
      <div class="bd" id="ncStoreMap" style="border: solid #E6E6E6; border-width: 0 1px 1px; margin-bottom: 20px; display:none; overflow: hidden;"> </div>
    </div>
    <!-- E 店铺地址地图 -->
    <?php }?>
    <div class="ncs-comment">
      <div class="ncs-goods-title-bar hd">
        <h4><a href="javascript:void(0);"><?php echo $lang['goods_index_evaluation'];?></a></h4>
      </div>
      <div class="ncs-goods-info-content bd" id="ncGoodsRate">
        <div class="top">
          <div class="rate">
            <p><strong><?php echo $output['goods_evaluate_info']['good_percent'];?></strong><sub>%</sub>好评</p>
            <span>共有<?php echo $output['goods_evaluate_info']['all'];?>人参与评分</span></div>
          <div class="percent">
            <dl>
              <dt>好评<em>(<?php echo $output['goods_evaluate_info']['good_percent'];?>%)</em></dt>
              <dd><i style="width: <?php echo $output['goods_evaluate_info']['good_percent'];?>%"></i></dd>
            </dl>
            <dl>
              <dt>中评<em>(<?php echo $output['goods_evaluate_info']['normal_percent'];?>%)</em></dt>
              <dd><i style="width: <?php echo $output['goods_evaluate_info']['normal_percent'];?>%"></i></dd>
            </dl>
            <dl>
              <dt>差评<em>(<?php echo $output['goods_evaluate_info']['bad_percent'];?>%)</em></dt>
              <dd><i style="width: <?php echo $output['goods_evaluate_info']['bad_percent'];?>%"></i></dd>
            </dl>
          </div>
          <div class="btns"><span>您可对已购商品进行评价</span>
            <p><a href="<?php if ($output['goods']['is_virtual']) { echo urlShop('member_vr_order', 'index');} else { echo urlShop('member_order', 'index');}?>" class="ncbtn ncbtn-grapefruit" target="_blank"><i class="icon-comment-alt"></i>评价商品</a></p>
          </div>
        </div>
        <div class="ncs-goods-title-nav">
          <ul id="comment_tab">
            <li data-type="all" class="current"><a href="javascript:void(0);"><?php echo $lang['goods_index_evaluation'];?>(<?php echo $output['goods_evaluate_info']['all'];?>)</a></li>
            <li data-type="1"><a href="javascript:void(0);">好评(<?php echo $output['goods_evaluate_info']['good'];?>)</a></li>
            <li data-type="2"><a href="javascript:void(0);">中评(<?php echo $output['goods_evaluate_info']['normal'];?>)</a></li>
            <li data-type="3"><a href="javascript:void(0);">差评(<?php echo $output['goods_evaluate_info']['bad'];?>)</a></li>
            <li data-type="4"><a href="javascript:void(0);">有图(<?php echo $output['goods_evaluate_info']['img'];?>)</a></li>
          </ul>
        </div>
        <!-- 商品评价内容部分 -->
        <div id="goodseval" class="ncs-commend-main"></div>
      </div>
    </div>
    <div class="ncg-salelog">
      <div class="ncs-goods-title-bar hd">
        <h4><a href="javascript:void(0);"><?php echo $lang['goods_index_sold_record'];?></a></h4>
      </div>
      <div class="ncs-goods-info-content bd" id="ncGoodsTraded">
        <div class="top">
          <div class="price"><?php echo $lang['goods_index_goods_price'];?><strong><?php echo ncPriceFormat($output['goods']['goods_price']);?></strong><?php echo $lang['goods_index_yuan'];?><span><?php echo $lang['goods_index_price_note'];?></span></div>
        </div>
        <!-- 成交记录内容部分 -->
        <div id="salelog_demo" class="ncs-loading"> </div>
      </div>
    </div>
    <div class="ncs-consult">
      <div class="ncs-goods-title-bar hd">
        <h4><a href="javascript:void(0);"><?php echo $lang['goods_index_goods_consult'];?></a></h4>
      </div>
      <div class="ncs-goods-info-content bd" id="ncGuestbook"> 
        <!-- 咨询留言内容部分 -->
        <div id="consulting_demo" class="ncs-loading"> </div>
      </div>
    </div>
    <?php if(!empty($output['goods_commend']) && is_array($output['goods_commend']) && count($output['goods_commend'])>1){?>
    <div class="ncs-recommend">
      <div class="title">
        <h4><?php echo $lang['goods_index_goods_commend'];?></h4>
      </div>
      <div class="content">
        <ul>
          <?php foreach($output['goods_commend'] as $goods_commend){?>
          <?php if($output['goods']['goods_id'] != $goods_commend['goods_id']){?>
          <li>
            <dl>
              <dt class="goods-name"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $goods_commend['goods_id']));?>" target="_blank" title="<?php echo $goods_commend['goods_jingle'];?>"><?php echo $goods_commend['goods_name'];?><em><?php echo $goods_commend['goods_jingle'];?></em></a></dt>
              <dd class="goods-pic"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $goods_commend['goods_id']));?>" target="_blank" title="<?php echo $goods_commend['goods_jingle'];?>"><img src="<?php echo thumb($goods_commend, 240);?>" alt="<?php echo $goods_commend['goods_name'];?>"/></a></dd>
              <dd class="goods-price"><?php echo $lang['currency'];?><?php echo $goods_commend['goods_promotion_price'];?></dd>
            </dl>
          </li>
          <?php }?>
          <?php }?>
        </ul>
        <div class="clear"></div>
      </div>
    </div>
    <?php }?>
  </div>
  <div class="ncs-sidebar">
    <?php include template('store/callcenter'); ?>
    <?php if ($output['left_bar_type_mall_related']) {
        include template('store/left_mall_related');
    } else {
        include template('store/left');
    } ?>
    <?php if ($output['viewed_goods']) { ?>
    <!-- 最近浏览 -->
    <div class="ncs-sidebar-container ncs-top-bar">
      <div class="title">
        <h4>最近浏览</h4>
      </div>
      <div class="content">
        <div id="hot_sales_list" class="ncs-top-panel">
          <ol>
            <?php foreach ((array) $output['viewed_goods'] as $g) { ?>
            <li>
              <dl>
                <dt><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $g['goods_id'])); ?>"><?php echo $g['goods_name']; ?></a></dt>
                <dd class="goods-pic"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $g['goods_id'])); ?>"><span class="thumb size60"><i></i><img src="<?php echo thumb($g, 60); ?>"  onload="javascript:DrawImage(this,60,60);"></span></a>
                  <p><span class="thumb size100"><i></i><img src="<?php echo thumb($g, 240); ?>" onload="javascript:DrawImage(this,100,100);" title="<?php echo $g['goods_name']; ?>"><big></big><small></small></span></p>
                </dd>
                <dd class="price pngFix"><?php echo ncPriceFormat($g['goods_promotion_price']); ?></dd>
              </dl>
            </li>
            <?php } ?>
          </ol>
        </div>
        <p><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_goodsbrowse&op=list" class="nch-sidebar-all-viewed">全部浏览历史</a></p> </div>
    </div>
    <?php } ?>
  </div>
</div>
</div>
<form id="buynow_form" method="post" action="<?php echo SHOP_SITE_URL;?>/index.php">
  <input id="act" name="act" type="hidden" value="buy" />
  <input id="op" name="op" type="hidden" value="buy_step1" />
  <input id="cart_id" name="cart_id[]" type="hidden"/>
</form>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.charCount.js"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js" type="text/javascript"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js" type="text/javascript" charset="utf-8"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.F_slider.js" type="text/javascript" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
/** 辅助浏览 **/

	//产品图片
	jQuery(function($){
	// 放大镜效果 产品图片
     CloudZoom.quickStart();
     
     		    // 图片切换效果
                $(".controller li").first().addClass('current');
                $('.controller').find('li').mouseover(function(){
                $(this).first().addClass("current").siblings().removeClass("current");
                });
});

    //收藏分享处下拉操作
    jQuery.divselect = function(divselectid,inputselectid) {
      var inputselect = $(inputselectid);
      $(divselectid).mouseover(function(){
          var ul = $(divselectid+" ul");
          ul.slideDown("fast");
          if(ul.css("display")=="none"){
              ul.slideDown("fast");
          }
      });
      $(divselectid).live('mouseleave',function(){
          $(divselectid+" ul").hide();
      });
    };
$(function(){
	//赠品处滚条
	$('#ncsGoodsGift').perfectScrollbar({suppressScrollX:true});
    <?php if ($output['goods']['goods_state'] == 1 && $output['goods']['goods_storage'] > 0 ) {?>
    // 加入购物车
    $('a[nctype="addcart_submit"]').click(function(){
    	if (typeof(allow_buy) != 'undefined' && allow_buy === false) return ;
        addcart(<?php echo $output['goods']['goods_id'];?>, checkQuantity(),'addcart_callback');
    });
        <?php if (!($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_indate'] < TIMESTAMP)) {?>
        // 立即购买
        $('a[nctype="buynow_submit"]').click(function(){
        	if (typeof(allow_buy) != 'undefined' && allow_buy === false) return ;
            buynow(<?php echo $output['goods']['goods_id']?>,checkQuantity());
        });
        <?php }?>
    <?php }?>
    // 到货通知
    <?php if ($output['goods']['goods_storage'] == 0 || $output['goods']['goods_state'] == 0) {?>
    $('a[nctype="arrival_notice"]').click(function(){
        <?php if ($_SESSION['is_login'] !== '1'){?>
        login_dialog();
        <?php }else{?>
        ajax_form('arrival_notice', '到货通知','<?php echo urlShop('goods', 'arrival_notice', array('goods_id' => $output['goods']['goods_id']));?>', 350);
        <?php }?>
    });
    <?php }?>
    //浮动导航  waypoints.js
    $('#main-nav').waypoint(function(event, direction) {
        $(this).parent().parent().parent().toggleClass('sticky', direction === "down");
        event.stopPropagation();
    });

    // 分享收藏下拉操作
    $.divselect("#handle-l");
    $.divselect("#handle-r");

    // 规格选择
    $('dl[nctype="nc-spec"]').find('a').each(function(){
        $(this).click(function(){
            if ($(this).hasClass('hovered')) {
                return false;
            }
            $(this).parents('ul:first').find('a').removeClass('hovered');
            $(this).addClass('hovered');
            checkSpec();
        });
    });

});

function checkSpec() {
    var spec_param = <?php echo $output['spec_list'];?>;
    var spec = new Array();
    $('ul[nctyle="ul_sign"]').find('.hovered').each(function(){
        var data_str = ''; eval('data_str =' + $(this).attr('data-param'));
        spec.push(data_str.valid);
    });
    spec1 = spec.sort(function(a,b){
        return a-b;
    });
    var spec_sign = spec1.join('|');
    $.each(spec_param, function(i, n){
        if (n.sign == spec_sign) {
            window.location.href = n.url;
        }
    });
}

// 验证购买数量
function checkQuantity(){
    var quantity = parseInt($("#quantity").val());
    if (quantity < 1) {
        alert("<?php echo $lang['goods_index_pleaseaddnum'];?>");
        $("#quantity").val('1');
        return false;
    }
    max = parseInt($('[nctype="goods_stock"]').text());
    <?php if ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_limit'] > 0) {?>
    max = <?php echo $output['goods']['virtual_limit'];?>;
    if(quantity > max){
        alert('最多限购'+max+'件');
        return false;
    }
    <?php } ?>
    <?php if (!empty($output['goods']['upper_limit'])) {?>
    max = <?php echo $output['goods']['upper_limit'];?>;
    if(quantity > max){
        alert('最多限购'+max+'件');
        return false;
    }
    <?php } ?>
    if(quantity > max){
        alert("<?php echo $lang['goods_index_add_too_much'];?>");
        return false;
    }
    return quantity;
}

// 立即购买js
function buynow(goods_id,quantity,chain_id,area_id,area_name,area_id_2){
<?php if ($_SESSION['is_login'] !== '1'){?>
	login_dialog();
<?php }else{?>
    if (!quantity) {
        return;
    }
    <?php if ($_SESSION['store_id'] == $output['goods']['store_id']) { ?>
    alert('不能购买自己店铺的商品');return;
    <?php } ?>
    $("#cart_id").val(goods_id+'|'+quantity);
	if (typeof chain_id == 'number') {
		$('#buynow_form').append('<input type="hidden" name="ifchain" value="1"><input type="hidden" name="chain_id" value="'+chain_id+'"><input type="hidden" name="area_id" value="'+area_id+'"><input type="hidden" name="area_name" value="'+area_name+'"><input type="hidden" name="area_id_2" value="'+area_id_2+'">');
	}
    $("#buynow_form").submit();
<?php }?>
}

$(function(){
    //选择地区查看运费
    $('#transport_pannel>a').click(function(){
    	var id = $(this).attr('nctype');
    	if (id=='undefined') return false;
    	var _self = this,tpl_id = '<?php echo $output['goods']['transport_id'];?>';
	    var url = 'index.php?act=goods&op=calc&rand='+Math.random();
	    $('#transport_price').css('display','none');
	    $('#loading_price').css('display','');
	    $.getJSON(url, {'id':id,'tid':tpl_id}, function(data){
	    	if (data == null) return false;
	        if(data != 'undefined') {$('#nc_kd').html('运费<?php echo $lang['nc_colon'];?><em>' + data + '</em><?php echo $lang['goods_index_yuan'];?>');}else{'<?php echo $lang['goods_index_trans_for_seller'];?>';}
	        $('#transport_price').css('display','');
	    	$('#loading_price').css('display','none');
	        $('#ncrecive').html($(_self).html());
	    });
    });
    $("#nc-bundling").load('index.php?act=goods&op=get_bundling&goods_id=<?php echo $output['goods']['goods_id'];?>', function(){
        if($(this).html() != '') {
            $(this).show();
        }
    });
    <?php if ($output['goods']['is_virtual'] == 1) {?>
    $("#ncStoreMap").load('index.php?act=show_map&op=index&w=600&h=400&store_id=<?php echo $output['goods']['store_id'];?>', function(){
        if($(this).html() != '') {
            $(this).show();
        }
    });
    <?php } ?>
    $("#salelog_demo").load('index.php?act=goods&op=salelog&goods_id=<?php echo $output['goods']['goods_id'];?>&store_id=<?php echo $output['goods']['store_id'];?>&vr=<?php echo $output['goods']['is_virtual'];?>', function(){
        // Membership card
        $(this).find('[nctype="mcard"]').membershipCard({type:'shop'});
    });
	$("#consulting_demo").load('index.php?act=goods&op=consulting&goods_id=<?php echo $output['goods']['goods_id'];?>&store_id=<?php echo $output['goods']['store_id'];?>', function(){
		// Membership card
		$(this).find('[nctype="mcard"]').membershipCard({type:'shop'});
	});

/** goods.php **/
	// 商品内容部分折叠收起侧边栏控制
	$('#fold').click(function(){
  		$('.ncs-goods-layout').toggleClass('expanded');
	});
	// 商品内容介绍Tab样式切换控制
	$('#categorymenu').find("li").click(function(){
		$('#categorymenu').find("li").removeClass('current');
		$(this).addClass('current');
	});
	// 商品详情默认情况下显示全部
	$('#tabGoodsIntro').click(function(){
		$('.bd').css('display','');
		$('.hd').css('display','');
	});
	// 点击地图隐藏其他以及其标题栏
	$('#tabStoreMap').click(function(){
		$('.bd').css('display','none');
		$('#ncStoreMap').css('display','');
		$('.hd').css('display','none');
	});
	// 点击评价隐藏其他以及其标题栏
	$('#tabGoodsRate').click(function(){
		$('.bd').css('display','none');
		$('#ncGoodsRate').css('display','');
		$('.hd').css('display','none');
	});
	// 点击成交隐藏其他以及其标题
	$('#tabGoodsTraded').click(function(){
		$('.bd').css('display','none');
		$('#ncGoodsTraded').css('display','');
		$('.hd').css('display','none');
	});
	// 点击咨询隐藏其他以及其标题
	$('#tabGuestbook').click(function(){
		$('.bd').css('display','none');
		$('#ncGuestbook').css('display','');
		$('.hd').css('display','none');
	});
	//商品排行Tab切换
	$(".ncs-top-tab > li > a").mouseover(function(e) {
		if (e.target == this) {
			var tabs = $(this).parent().parent().children("li");
			var panels = $(this).parent().parent().parent().children(".ncs-top-panel");
			var index = $.inArray(this, $(this).parent().parent().find("a"));
			if (panels.eq(index)[0]) {
				tabs.removeClass("current ").eq(index).addClass("current ");
				panels.addClass("hide").eq(index).removeClass("hide");
			}
		}
	});
	//信用评价动态评分打分人次Tab切换
	$(".ncs-rate-tab > li > a").mouseover(function(e) {
		if (e.target == this) {
			var tabs = $(this).parent().parent().children("li");
			var panels = $(this).parent().parent().parent().children(".ncs-rate-panel");
			var index = $.inArray(this, $(this).parent().parent().find("a"));
			if (panels.eq(index)[0]) {
				tabs.removeClass("current ").eq(index).addClass("current ");
				panels.addClass("hide").eq(index).removeClass("hide");
			}
		}
	});

//触及显示缩略图
	$('.goods-pic > .thumb').hover(
		function(){
			$(this).next().css('display','block');
		},
		function(){
			$(this).next().css('display','none');
		}
	);

	/* 商品购买数量增减js */
	// 增加
	$('a[nctype="increase"]').click(function(){
		num = parseInt($('#quantity').val());
	    <?php if ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_limit'] > 0) {?>
	    max = <?php echo $output['goods']['virtual_limit'];?>;
	    if(num >= max){
	        alert('最多限购'+max+'件');
	        return false;
	    }
	    <?php } ?>
	    <?php if (!empty($output['goods']['upper_limit'])) {?>
	    max = <?php echo $output['goods']['upper_limit'];?>;
	    if(num >= max){
	        alert('最多限购'+max+'件');
	        return false;
	    }
	    <?php } ?>
		max = parseInt($('[nctype="goods_stock"]').text());
		if(num < max){
			$('#quantity').val(num+1);
		}
	});
	//减少
	$('a[nctype="decrease"]').click(function(){
		num = parseInt($('#quantity').val());
		if(num > 1){
			$('#quantity').val(num-1);
		}
	});

    //评价列表
    $('#comment_tab').on('click', 'li', function() {
        $('#comment_tab li').removeClass('current');
        $(this).addClass('current');
        load_goodseval($(this).attr('data-type'));
    });
    load_goodseval('all');
    function load_goodseval(type) {
        var url = '<?php echo urlShop('goods', 'comments', array('goods_id' => $output['goods']['goods_id']));?>';
        url += '&type=' + type;
        $("#goodseval").load(url, function(){
            $(this).find('[nctype="mcard"]').membershipCard({type:'shop'});
        });
    }

    //记录浏览历史
	$.get("index.php?act=goods&op=addbrowse",{gid:<?php echo $output['goods']['goods_id'];?>});
	//初始化对比按钮
	initCompare();

    $('[nctype="show_chain"]').click(function(){
        _goods_id = $(this).attr('data-goodsid');
        ajax_form('show_chain', '查看门店', 'index.php?act=goods&op=show_chain&goods_id=' + _goods_id, 640);
    });

    <?php if ($output['goods']['jjg_explain']) { ?>
        $('.couRuleScrollbar').perfectScrollbar({suppressScrollX:true});
    <?php }?>

    // 满即送、加价购显示隐藏
    $('[nctype="show-rule"]').click(function(){
        $(this).parent().find('[nctype="rule-content"]').show();
    });
    $('[nctype="hide-rule"]').click(function(){
        $(this).parents('[nctype="rule-content"]:first').hide()
    });

    $('.ncs-buy').bind({
        mouseover:function(){$(".ncs-point").show();},
        mouseout:function(){$(".ncs-point").hide();}
    });
    
});

/* 加入购物车后的效果函数 */
function addcart_callback(data){
	$('#bold_num').html(data.num);
    $('#bold_mly').html(price_format(data.amount));
    $('.ncs-cart-popup').fadeIn('fast');
}

<?php if($output['goods']['goods_state'] == 1 && $output['goods']['goods_verify'] == 1 && $output['goods']['is_virtual'] == 0 && $output['goods']['goods_storage'] > 0){ ?>
var $cur_area_list,$cur_tab,next_tab_id = 0,cur_select_area = [],calc_area_id = '',calced_area = [],cur_select_area_ids = [];
$(document).ready(function(){
	$("#ncs-freight-selector").hover(function() {
		//如果店铺没有设置默认显示区域，马上异步请求
		<?php if (!$output['store_info']['deliver_region']) { ?>
		if (typeof nc_a === "undefined") {
	 		$.getJSON(SITEURL + "/index.php?act=index&op=json_area&callback=?", function(data) {
	 			nc_a = data;
	 			$cur_tab = $('#ncs-stock').find('li[data-index="0"]');
	 			_loadArea(0);
	 		});
		}
		<?php } ?>
		$(this).addClass("hover");
		$(this).on('mouseleave',function(){
			$(this).removeClass("hover");
		});
	});

	$('ul[class="area-list"]').on('click','a',function(){
		$('#ncs-freight-selector').unbind('mouseleave');
		var tab_id = parseInt($(this).parents('div[data-widget="tab-content"]:first').attr('data-area'));
		if (tab_id == 0) {cur_select_area = [];cur_select_area_ids = []};
		if (tab_id == 1 && cur_select_area.length > 1) {
			cur_select_area.pop();
			cur_select_area_ids.pop();
			if (cur_select_area.length > 1) {
				cur_select_area.pop();
				cur_select_area_ids.pop();
			}
		}
		next_tab_id = tab_id + 1;
		var area_id = $(this).attr('data-value');
		$cur_tab = $('#ncs-stock').find('li[data-index="'+tab_id+'"]');
		$cur_tab.find('em').html($(this).html());
		$cur_tab.find('i').html(' ∨');
		if (tab_id < 2) {
			calc_area_id = area_id;
			cur_select_area.push($(this).html());
			cur_select_area_ids.push(area_id);
			$cur_tab.find('a').removeClass('hover');
			$cur_tab.nextAll().remove();
			if (typeof nc_a === "undefined") {
    	 		$.getJSON(SITEURL + "/index.php?act=index&op=json_area&callback=?", function(data) {
    	 			nc_a = data;
    	 			_loadArea(area_id);
    	 		});
			} else {
				_loadArea(area_id);
			}
		} else {
			//点击第三级，不需要显示子分类
			if (cur_select_area.length == 3) {
				cur_select_area.pop();
				cur_select_area_ids.pop();
			}
			cur_select_area.push($(this).html());
			cur_select_area_ids.push(area_id);
			$('#ncs-freight-selector > div[class="text"] > div').html(cur_select_area.join(''));
			$('#ncs-freight-selector').removeClass("hover");
			_calc();
		}
		$('#ncs-stock').find('li[data-widget="tab-item"]').on('click','a',function(){
			var tab_id = parseInt($(this).parent().attr('data-index'));
			if (tab_id < 2) {
				$(this).parent().nextAll().remove();
				$(this).addClass('hover');
				$('#ncs-stock').find('div[data-widget="tab-content"]').each(function(){
					if ($(this).attr("data-area") == tab_id) {
						$(this).show();
					} else {
						$(this).hide();
					}
				});
			}
		});
	});
	function _loadArea(area_id){
		if (nc_a[area_id] && nc_a[area_id].length > 0) {
			$('#ncs-stock').find('div[data-widget="tab-content"]').each(function(){
				if ($(this).attr("data-area") == next_tab_id) {
					$(this).show();
					$cur_area_list = $(this).find('ul');
					$cur_area_list.html('');
				} else {
					$(this).hide();
				}
			});
			var areas = [];
			areas = nc_a[area_id];
			for (i = 0; i < areas.length; i++) {
				if (areas[i][1].length > 8) {
					$cur_area_list.append("<li class='longer-area'><a data-value='" + areas[i][0] + "' href='#none'>" + areas[i][1] + "</a></li>");
				} else {
				    $cur_area_list.append("<li><a data-value='" + areas[i][0] + "' href='#none'>" + areas[i][1] + "</a></li>");
				}
			}
			if (area_id > 0){
				$cur_tab.after('<li data-index="' + (next_tab_id) + '" data-widget="tab-item"><a class="hover" href="#none" ><em>请选择</em><i> ∨</i></a></li>');
			}
		} else {
			//点击第一二级时，已经到了最后一级
			$cur_tab.find('a').addClass('hover');
			$('#ncs-freight-selector > div[class="text"] > div').html(cur_select_area);
			$('#ncs-freight-selector').removeClass("hover");
			_calc();
		}
	}
	//计算运费，是否配送
	function _calc() {
		$.cookie('dregion', cur_select_area_ids.join(' ')+'|'+cur_select_area.join(' '), { expires: 30 });
		<?php if (! $output['goods']['transport_id']) { ?>
		return;
		<?php } ?>
		var _args = "&tid=<?php echo $output['goods']['transport_id']?>&area_id=" + calc_area_id;
		if (typeof calced_area[calc_area_id] == 'undefined') {
			//需要请求配送区域设置
			$.getJSON(SITEURL + "/index.php?act=goods&op=calc&" + _args + "&myf=<?php echo $output['store_info']['store_free_price']?>&callback=?", function(data){
				allow_buy = data.total ? true : false;
				calced_area[calc_area_id] = data.total;
				if (data.total === false) {
					$('#ncs-freight-prompt > strong').html('无货').next().remove();
					$('a[nctype="buynow_submit"]').addClass('no-buynow');
					$('a[nctype="addcart_submit"]').addClass('no-buynow');
				} else {
					$('#ncs-freight-prompt > strong').html('有货 ').next().remove();
					$('#ncs-freight-prompt > strong').after('<span>' + data.total + '</span>');
					$('a[nctype="buynow_submit"]').removeClass('no-buynow');
					$('a[nctype="addcart_submit"]').removeClass('no-buynow');
				}
			});	
		} else {
			if (calced_area[calc_area_id] === false) {
				$('#ncs-freight-prompt > strong').html('无货').next().remove();
				$('a[nctype="buynow_submit"]').addClass('no-buynow');
				$('a[nctype="addcart_submit"]').addClass('no-buynow');
			} else {
				$('#ncs-freight-prompt > strong').html('有货 ').next().remove();
				$('#ncs-freight-prompt > strong').after('<span>' + calced_area[calc_area_id] + '</span>');
				$('a[nctype="buynow_submit"]').removeClass('no-buynow');
				$('a[nctype="addcart_submit"]').removeClass('no-buynow');
			}
		}
	}
	//如果店铺设置默认显示配送区域
	<?php if ($output['store_info']['deliver_region']) { ?>
	if (typeof nc_a === "undefined") {
 		$.getJSON(SITEURL + "/index.php?act=index&op=json_area&callback=?", function(data) {
 			nc_a = data;
 			$cur_tab = $('#ncs-stock').find('li[data-index="0"]');
 			_loadArea(0);
 			$('ul[class="area-list"]').find('a[data-value="<?php echo $output['store_info']['deliver_region_ids'][0]?>"]').click();
 		    <?php if ($output['store_info']['deliver_region_ids'][1]) { ?>
 			$('ul[class="area-list"]').find('a[data-value="<?php echo $output['store_info']['deliver_region_ids'][1]?>"]').click();
 		    <?php } ?>
  		    <?php if ($output['store_info']['deliver_region_ids'][2]) { ?>
 			$('ul[class="area-list"]').find('a[data-value="<?php echo $output['store_info']['deliver_region_ids'][2]?>"]').click();
 			<?php } ?>
 		});
	}
	<?php } ?>
});
<?php }?>
</script> 
