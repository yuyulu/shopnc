<?php defined('In33hao') or exit('Access Invalid!');?>
<?php if((!empty($output['bundling_array']) && !empty($output['b_goods_array'])) || !empty($output['gcombo_list'])){?>

<div class="ncs-goods-title-nav" nctype="gbc_nav">
  <ul>
    <?php if ((!empty($output['bundling_array']) && !empty($output['b_goods_array']))) {?>
    <li class="current"><a href="javascript:void(0);">优惠套装</a></li>
    <?php $current = true;}?>
    <?php if (!empty($output['gcombo_list'])) {?>
    <li <?php if (!$current) {?>class="current"<?php }?>><a href="javascript:void(0);">推荐组合</a></li>
    <?php }?>
  </ul>
</div>
<div class="ncs-goods-info-content" nctype="gbc_content">
  <?php if (!empty($output['bundling_array']) && !empty($output['b_goods_array'])) {?>
  <div class="ncs-bundling-box"> 
    <!--S 组合销售 -->
    <div class="ncs-bundling-tab">
      <?php $i=0;foreach($output['bundling_array'] as $val){?>
      <span <?php if ($i == 0) {?>class="selected"<?php }?> data-id="<?php echo $val['id'];?>"><a href="javascript:void(0);"><?php echo $val['name'];?></a></span>
      <?php $i++;}?>
    </div>
    <div class="ncs-bundling-container">
      <?php $i=0;foreach($output['bundling_array'] as $val){?>
      <?php if(!empty($output['b_goods_array'][$val['id']]) && is_array($output['b_goods_array'][$val['id']])){?>
      <div <?php if ($i != 0) {?>style="display: none;"<?php }?> data-id="<?php echo $val['id'];?>">
        <ul class="ncs-bundling-list">
          <?php ksort($output['b_goods_array'][$val['id']]);foreach($output['b_goods_array'][$val['id']] as $v){?>
          <li>
            <div class="goods-thumb"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['id']));?>" target="block"><img src="<?php echo $v['image'];?>" title="<?php echo $v['name'];?>" alt="<?php echo $v['name'];?>"/></a></div>
            <dl>
              <dt title="<?php echo $v['name'];?>"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['id']));?>" target="block"><?php echo $v['name'];?></a></dt>
              <dd>原&nbsp;&nbsp;&nbsp;&nbsp;价：<em class="o-price"><?php echo $lang['currency'].ncPriceFormat($v['shop_price']);?></em></dd>
              <dd>优惠价：<em class="b-price"><?php echo $lang['currency'].ncPriceFormat($v['price']);?></em></dd>
            </dl>
            <span class="plus"></span> </li>
          <?php }?>
        </ul>
        <div class="ncs-bundling-price">
        <ul>
          <li>已选套装：<strong><?php echo $val['name'];?></strong></li>
            <li>套装原价：<em><?php echo $lang['currency'].ncPriceFormat($val['cost_price']);?></em></li>
            <li>优惠价格：<em class="bundling-price"><?php echo $lang['currency'].ncPriceFormat($val['price']);?></em></li>
            <li>立刻节省：<em class="bundling-save" ><?php echo $lang['currency'].ncPriceFormat(floatval($val['cost_price'])-floatval($val['price']));?></em></li>
            <?php if ($val['freight'] > 0) {?>
            <li class="">运&emsp;费：<span><?php echo $lang['currency'].$val['freight'];?></span></li>
            <?php }?>
            <li class="mt10"><a href="javascript:void(0);"  nctype="addblcart_submit" bl_id="<?php echo $val['id']?>" class="ncbtn ncbtn-grapefruit"><i class="icon-th-large"></i><?php echo $lang['bundling_buy'];?></a></li>
          </ul>
        </div>
      </div>
      <?php }?>
      <?php $i++;}?>
    </div>
    
    <!--E 组合销售 --> 
    <script>
    $(function(){
        $('span[data-id]').click(function(){
            $('span[data-id]').removeClass('selected');
            $(this).addClass('selected');
            $('div[data-id]').hide('slow');
            $('div[data-id="' + $(this).attr('data-id') + '"]').show('slow');
        });
        $('a[nctype="addblcart_submit"]').click(function(){
            addblcart($(this).attr('bl_id'));
         });	
    });
    
    /* add one bundling to cart */ 
    function addblcart(bl_id)
    {
    	<?php if ($_SESSION['is_login'] !== '1'){?>
    	   login_dialog();
        <?php } else {?>
            var url = 'index.php?act=cart&op=add';
            $.getJSON(url, {'bl_id':bl_id}, function(data){
            	if(data != null){
            		if (data.state)
                    {
                        $('#bold_num').html(data.num);
                        $('#bold_mly').html(price_format(data.amount));
                        $('.ncs-cart-popup').fadeIn('fast');
                        // 头部加载购物车信息
                        load_cart_information();
						$("#rtoolbar_cartlist").load('index.php?act=cart&op=ajax_load&type=html');
                    }
                    else
                    {
                        showDialog(data.msg, 'error','','','','','','','','',2);
                    }
            	}
            });
        <?php } ?>
    }
    </script> 
  </div>
  <?php }?>
  <?php if (!empty($output['gcombo_list'])) {?>
  <div <?php if ($current) {?>style="display:none;"<?php }?> class="ncs-combo-box">
    <div class="default-goods">
      <div class="goods-thumb"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $output['goods_info']['goods_id']));?>" target="block"><img src="<?php echo thumb($output['goods_info'], 240);?>" title="<?php echo $output['goods_info']['goods_name'];?>" alt="<?php echo $output['goods_info']['goods_name'];?>"/></a></div>
      <dl>
        <dt title="<?php echo $output['goods_info']['goods_name'];?>"><?php echo $output['goods_info']['goods_name'];?></dt>
        <dd class="goods-price"><?php echo $lang['currency'].ncPriceFormat($output['goods_info']['goods_promotion_price']);?></dd>
      </dl>
      <span class="plus"></span>
    </div>
    <div class="combo-goods">
      <div class="ncs-combo-tab"> <span class="selected"><a href="javascript:;" data-id="all">全部</a></span>
        <?php foreach ($output['gcombo_list'] as $key=>$value) {?>
        <span><a href="javascript:;" data-id="<?php echo $key;?>"><?php echo $value['name'];?></a></span>
        <?php }?>
      </div>
      <div class="combo-goods-box" nctype="combo_list">
        <?php if (!empty($output['gcombo_list'])) {$j=0;?>
        <div class="combo-goods-list">
        <ul class="F-center">
          <?php foreach ($output['gcombo_list'] as $key => $value) {?>
          <?php foreach ($value['goods'] as $combo) {?>
          <li <?php if ($j == 0) {?>class="combo-goods-first"<?php $j++;}?> data-id="<?php echo $key;?>">
            <div class="goods-thumb"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $combo['goods_id']));?>" target="block"><img src="<?php echo thumb($combo, 240);?>" title="<?php echo $combo['goods_name'];?>" alt="<?php echo $combo['goods_name'];?>" onload="javascript:DrawImage(this,100,100);" /></a></div>
            <dl>
              <dt title="<?php echo $combo['goods_name'];?>"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $combo['goods_id']));?>" target="block"><?php echo $combo['goods_name'];?></a></dt>
              <dd class="goods-price"><?php echo $lang['currency'].ncPriceFormat($combo['goods_promotion_price']);?></dd>
              <dd class="rp">参考价<?php echo $lang['currency'].ncPriceFormat($combo['goods_marketprice']);?></dd>
            </dl>
            <input type="checkbox" class="checkbox" nctype="comb"  name="<?php echo $combo['goods_id'];?>" data-param="{price:<?php echo ncPriceFormat($combo['goods_promotion_price']);?>,marketprice:<?php echo ncPriceFormat($combo['goods_marketprice']);?>}">
            <span class="plus"></span>
          </li>
          <?php }?>
          <?php }?>
        </ul>
        </div>
        <?php }?>
        <div class="F-prev"><i></i></div>
    	<div class="F-next"><i></i></div>
      </div>
    </div>
    <div class="combo-price">
      <ul>
        <li>组合商品：<strong nctype="combo_choose_count">1</strong>&nbsp;件</li>
        <li>参&nbsp;&nbsp;考&nbsp;&nbsp;价：<?php echo $lang['currency'];?><em nctype="gbcc_mp"><?php echo $output['goods_info']['goods_marketprice'];?></em></li>
        <li>组&nbsp;&nbsp;合&nbsp;&nbsp;价：<?php echo $lang['currency'];?><em nctype="gbcc_p" class="price"><?php echo ncPriceFormat($output['goods_info']['goods_promotion_price']);?></em></li>        
        <li class="mt10"><a class="ncbtn ncbtn-grapefruit" nctype="addblcart_submit_comb" data-param="<?php echo $comb_ids.$output['goods_info']['goods_id'];?>" href="javascript:void(0);"><i class="icon-th-large"></i><?php echo $lang['bundling_buy'];?></a></li>
      </ul>
    </div>
    <script type="text/javascript">
  $(function(){
      var g_p = <?php echo $output['goods_info']['goods_promotion_price'];?>;
      var mg_p = <?php echo $output['goods_info']['goods_marketprice'];?>;
      $('div[nctype="combo_list"]').find('input[type="checkbox"]').click(function(){
          var gbcc_p = g_p;
          var gbcc_mp = mg_p;
          $('div[nctype="combo_list"]').find('input[type="checkbox"]:checked').each(function(){
              eval( 'data_str =' + $(this).attr('data-param'));
              gbcc_p += data_str.price;
              gbcc_mp += data_str.marketprice;
          });
          $('em[nctype="gbcc_p"]').html(number_format(gbcc_p,2));
          $('em[nctype="gbcc_mp"]').html(number_format(gbcc_mp,2));

          var _count = $('div[nctype="combo_list"]').find('input[type="checkbox"]:checked').length;
          $('strong[nctype="combo_choose_count"]').html(_count + 1);
      });
      $('a[nctype="addblcart_submit_comb"]').click(function(){
          addcombcart($(this).attr('data-param'));
       });

      // 点击分类切换所属商品
      $('a[data-id]').click(function(){
          $('a[data-id]').parent().removeClass('selected');
          $(this).parent().addClass('selected');
          _data_id = $(this).attr('data-id');
          if (_data_id == 'all') {
              $('div[nctype="combo_list"]').find('li').show().removeClass('combo-goods-first').first().addClass('combo-goods-first');
          } else {
              $('div[nctype="combo_list"]').find('li').hide().removeClass('combo-goods-first')
              .end().find('li[data-id="' + _data_id + '"]').show().first().addClass('combo-goods-first');
          }
          combo_slider(true);
      });
      combo_slider(false);
});
/* add one bundling to cart */ 
function addcombcart(goods_ids)
{
	var goods_ids = '';
	<?php if ($_SESSION['is_login'] !== '1'){?>
	   login_dialog();
    <?php } else {?>
    $('input[nctype="comb"]').each(function(){
        if ($(this).attr('checked')) {
            goods_ids = goods_ids + $(this).attr('name') + '|';
        }
    });
    goods_ids += '<?php echo $output['goods_info']['goods_id'];?>';
    var url = 'index.php?act=cart&op=add_comb';
    $.getJSON(url, {'goods_ids':goods_ids}, function(data){
    	if(data != null){
    		if (data.state)
            {
                $('#bold_num').html(data.num);
                $('#bold_mly').html(price_format(data.amount));
                $('.ncs-cart-popup').fadeIn('fast');
                // 头部加载购物车信息
                load_cart_information();
				$("#rtoolbar_cartlist").load('index.php?act=cart&op=ajax_load&type=html');
            }
            else
            {
                showDialog(data.msg, 'error','','','','','','','','',2);
            }
    	}
    });
    <?php } ?>
}

function combo_slider(visible) {
    if (visible) {
        var _len = parseInt($('div[nctype="combo_list"]').find('.F-center').find('li:visible').length);
    } else {
        var _len = parseInt($('div[nctype="combo_list"]').find('.F-center').find('li').length);
    }
    if (_len > 4) { 
        $('div[nctype="combo_list"]').find('.F-prev').removeClass('no-slider').end()
            .find('.F-next').removeClass('no-slider').end()
            .F_slider({len:_len, axis:'x', width : '177'});
    } else {
        $('div[nctype="combo_list"]').find('.F-prev').addClass('no-slider').end()
            .find('.F-next').addClass('no-slider').end()
            .F_no_slider();
    }
}
</script> 
  </div>
</div>
<?php }?>
<script type="text/javascript">
$(function(){
    $('div[nctype="gbc_nav"]').find('li').click(function(){
        $('div[nctype="gbc_nav"]').find('li').removeClass('current');
        $(this).addClass('current');
        $('div[nctype="gbc_content"]').children().hide().eq($(this).index()).show();

        combo_slider(true);
    });
});
</script>
<?php }?>
