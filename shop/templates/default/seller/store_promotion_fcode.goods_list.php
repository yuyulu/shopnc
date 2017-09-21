<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
<?php if ($output['isOwnShop']) { ?>
  <a class="ncbtn ncbtn-mint" href="javascript:void(0);" nctype="select_goods"><i class="icon-plus-sign"></i>添加商品</a>
<?php } else { ?>
  <?php if(empty($output['fcode_quota'])) { ?>
  <a class="ncbtn ncbtn-aqua" href="<?php echo urlShop('store_promotion_fcode', 'fcode_quota_add');?>" title="购买套餐"><i class="icon-money"></i>购买套餐</a>
  <?php } else { ?>
  <a class="ncbtn ncbtn-mint" href="javascript:void(0);" nctype="select_goods" style="right:100px"><i class="icon-plus-sign"></i>添加商品</a>
  <a class="ncbtn ncbtn ncbtn-aqua" href="<?php echo urlShop('store_promotion_fcode', 'fcode_renew');?>"><i class="icon-money"></i>套餐续费</a>
  <?php } ?>
<?php } ?>
</div>

<?php if ($output['isOwnShop']) { ?>
<div class="alert alert-block mt10">
  <ul>
    <li>1、建议首先发布、编辑您想要参加F码活动的商品库存为零，然后选择商品参加活动，最后再把库存修改成正常库存。这样可以防止商品被提前购买。</li>
  </ul>
</div>
<?php } else { ?>
<!-- 有可用套餐，发布活动 -->
<div class="alert alert-block mt10">
<?php if (empty($output['fcode_quota']) || $output['fcode_quota']['fcq_endtime'] <= TIMESTAMP) {?>
  <strong>你还没有购买套餐或套餐已经过期，请购买或续费套餐。</strong>
<?php } else {?>
  <strong>套餐过期时间<?php echo $lang['nc_colon'];?></strong> <strong style=" color:#F00;"><?php echo date('Y-m-d H:i:s',$output['fcode_quota']['fcq_endtime']);?></strong>
<?php }?>
  <ul>
    <li>1、点击购买套餐或续费套餐可以购买或续费套餐</li>
    <li>2、<strong style="color: red">相关费用会在店铺的账期结算中扣除</strong>。</li>
    <li>3、建议首先发布、编辑您想要参加F码活动的商品库存为零，然后选择商品参加活动，最后再把库存修改成正常库存。这样可以防止商品被提前购买。</li>
  </ul>
</div>
<?php } ?>

<?php if ($output['isOwnShop'] || (!empty($output['fcode_quota']) && $output['fcode_quota']['fcq_endtime'] > TIMESTAMP)) { ?>
<!-- 商品搜索 -->
<div nctype="div_goods_select" class="div-goods-select" style="display: none;">
    <table class="search-form">
      <tr><th class="w150"><strong>第一步：搜索店内商品</strong></th><td class="w160"><input nctype="search_goods_name" type="text w150" class="text" name="goods_name" value=""/></td>
        <td class="w70 tc"><label class="submit-border"><input nctype="btn_search_goods" type="button" value="<?php echo $lang['nc_search'];?>" class="submit"/></label></td><td class="w10"></td><td><p class="hint">不输入名称直接搜索将显示店内所有出售中的商品</p></td>
      </tr>
    </table>
  <div nctype="div_goods_search_result" class="search-result"></div>
  <a nctype="btn_hide_goods_select" class="close" href="javascript:void(0);">X</a> </div>
<table class="ncsc-default-table">
  <thead>
    <tr>
      <th class="w10"></th>
      <th class="w50"></th>
      <th class="tl">商品名称</th>
      <th class="w110">商城价格</th>
      <th class="w110"><?php echo $lang['nc_handle'];?></th>
    </tr>
  </thead>

  <tbody nctype="choose_goods_list">
    <tr nctype="tr_no_promotion" style="display:none;">
      <td colspan="20" class="norecord"><div class="no-promotion"><i class="zw"></i><span>F码商品列表暂无内容，请选择添加平台推荐展位商品。</span></div></td>
    </tr>
    <?php if(!empty($output['goods_list'])) { ?>
    <?php foreach($output['goods_list'] as $key=>$val){ ?>
    <tr class="bd-line">
      <td></td>
      <td><div class="pic-thumb"><a href="<?php echo $val['url'];?>" target="black"><img src="<?php echo $val['goods_image'];?>"/></a></div></td>
      <td class="tl">
        <dl class="goods-name">
          <dt><a href="<?php echo $val['url'];?>" target="_blank"><?php echo $val['goods_name'];?></a></dt>
          <dd><?php echo $output['goodsclass_list'][$val['gc_id']]['gc_name'];?></dd>
        </dl>
      </td>
      <td class="goods-price">￥<?php echo ncPriceFormat($val['goods_price']);?></td>
      <td class="nscs-table-handle">
        <span><a class="btn-bittersweet" href='<?php echo urlShop('store_promotion_fcode', 'download_f_code_excel', array('gid' => $val['goods_id']));?>' title="下载F码"><i class="icon-download"></i><p>下载</p></a></span>
        <span><a class="btn-grapefruit" href='javascript:void(0);' nctype="del_choosed" data-param="{gid:<?php echo $val['goods_id'];?>}"><i class="icon-trash"></i><p><?php echo $lang['nc_del'];?></p></a></span></td>
    </tr>
    <?php } ?>
    <?php } ?>
  </tbody>
</table>
<?php }else{?>
<!-- 没有可用套餐，购买 -->
<table class="ncsc-default-table ncsc-promotion-buy">
  <tbody>
    <tr>
      <td colspan="20" class="norecord"><div class="no-promotion"><i class="zw"></i><span>您还没有购买套餐，或该促销活动已经关闭。<br />请先购买套餐，再查看活动列表。</span></div></td>
    </tr>
  </tbody>
</table>
<?php }?>
<script>
$(function(){
    // 验证是否已经选择商品
    checked_no_promotion();

    // 显示搜索框
    $('a[nctype="select_goods"]').click(function(){
        $('div[nctype="div_goods_select"]').show();
    });
    // 隐藏搜索框
    $('a[nctype="btn_hide_goods_select"]').click(function(){
        $('div[nctype="div_goods_select"]').hide();
    });

    // 搜索商品
    $('input[nctype="btn_search_goods"]').click(function(){
        _url = '<?php echo urlShop('store_promotion_fcode', 'fcode_select_goods');?>';
        $('div[nctype="div_goods_search_result"]').html('').load(_url+ '&goods_name='+$('input[nctype="search_goods_name"]').val());
    });
    $('div[nctype="div_goods_select"]').on('click', '.demo', function(){
        $('div[nctype="div_goods_search_result"]').load($(this).attr('href'));
        return false;
    });

    $('div[nctype="div_goods_select"]').on('click', 'a[nctype="a_choose_goods"]', function(){
        _gid = $(this).attr('data-gid');
        CUR_DIALOG =ajax_form('choose_goods', 'F码商品规则设定', '<?php echo urlShop('store_promotion_fcode', 'choosed_goods');?>&gid='+_gid, 640);
    });

    // 删除商品
    $('tbody[nctype="choose_goods_list"]').on('click','a[nctype="del_choosed"]', function(){
        $this = $(this);
        _url = '<?php echo urlShop('store_promotion_fcode', 'del_choosed_goods');?>';
        eval('var data_str = ' + $(this).attr('data-param'));
        $.getJSON(_url, {gid : data_str.gid}, function(data){
            if (data.result == 'true') {
                $this.parents('tr:first').fadeOut("slow",function(){
                    $(this).remove();
                    checked_no_promotion();
                });
            } else {
                showError(data.msg);
            }
        });
    });
});


function choose_goods(data) {
    // 插入数据
    $('<tr class="bd-line"></tr>')
        .append('<td></td>')
        .append('<td><div class="pic-thumb"><a target="_blank" href="' + data.url + '"><img src="' + data.goods_image + '"></a></div></td>')
        .append('<td class="tl"><dl class="goods-name"><dt><a target="_blank" href="' + data.url + '">' + data.goods_name + '</a></dt><dd>' + data.gc_name + '</dd></dl></td>')
        .append('<td>￥' + data.goods_price + '</td>')
        .append('<td class="nscs-table-handle"><span><a class="btn-bittersweet" href="<?php echo urlShop('store_promotion_fcode', 'download_f_code_excel');?>&gid=' + data.goods_id + '" title="下载F码"><i class="icon-download"></i><p>下载</p></a></span><span><a class="btn-grapefruit" href="javascript:void(0);" data-param="{gid:'+ data.goods_id +'}" nctype="del_choosed"><i class="icon-trash"></i><p>删除</p></a></span></td>')
        .appendTo('tbody[nctype="choose_goods_list"]');
    // 验证是否已经选择商品
    checked_no_promotion();
}
// 验证是否已经选择商品
function checked_no_promotion() {
    if ($('tbody[nctype="choose_goods_list"]').children('tr').length == 1) {
        $('tr[nctype="tr_no_promotion"]').show();
    } else {
        $('tr[nctype="tr_no_promotion"]').hide();
    }
}
</script>