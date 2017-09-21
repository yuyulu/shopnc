<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="alert alert-block mt10">
  <ul class="mt5">
    <li>1、根据线上在售商品列表内容设置门店库存量；门店库存默认值为“0”时，该商品详情页面“门店自提”选项将不会出现您的门店信息，只有当您按所在门店的实际库存情况与线上商品对照设置库存后，才可作为线上销售门店自取点候选。</li>
    <li>2、选择“库存设置”按钮，如该商品具有多项规格值，请根据规格值内容进行逐一“门店库存”设置，并保存提交。</li>
    <li>3、如您的门店某商品线下销售引起库存不足，请及时手动调整该商品的库存量，以免消费者在线上下单后到门店自提时产生交易纠纷。</li>
    <li>4、特殊商品不能设置为门店自提商品（如：虚拟商品、定金预售商品、F码商品等）</li>
  </ul>
</div>
<form method="get" action="index.php">
  <table class="search-form">
    <input type="hidden" name="act" value="goods" />
    <input type="hidden" name="op" value="index" />
    <tr>
      <td>&nbsp;</td>
      <th> <select name="search_type">
          <option value="0" <?php if ($_GET['search_type'] == 0) {?>selected<?php }?>>商品名称</option>
          <option value="1" <?php if ($_GET['search_type'] == 1) {?>selected<?php }?>>商家货号</option>
          <option value="2" <?php if ($_GET['search_type'] == 2) {?>selected<?php }?>>SPU</option>
        </select>
      </th>
      <td class="w160"><input type="text" class="text w150" name="keyword" value="<?php echo $_GET['keyword']; ?>"/></td>
      <td class="tc w70"><label class="submit-border">
          <input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>" />
        </label></td>
    </tr>
  </table>
</form>
<table class="ncsc-default-table">
  <thead>
    <tr nc_type="table_header">
      <th>&nbsp;</th>
      <th class="w50">&nbsp;</th>
      <th coltype="editable" column="goods_name" checker="check_required" inputwidth="230px">商品名称</th>
      <th class="w150">SPU</th>
      <th class="w150">商家货号</th>
      <th class="w150">商品价格</th>
      <th class="w120">门店库存</th>
      <th class="w120">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['goods_list'])) { ?>
    <?php foreach ($output['goods_list'] as $val) { ?>
    <tr>
      <td></td>
      <td><div class="pic-thumb"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $output['goodsid_array'][$val['goods_commonid']]['goods_id']));?>" target="_blank"><img src="<?php echo thumb($val, 60);?>"/></a></div></td>
      <td class="tl"><dl class="goods-name">
          <dt style="max-width: 450px !important;"> <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $output['goodsid_array'][$val['goods_commonid']]['goods_id']));?>" target="_blank"><?php echo $val['goods_name']; ?></a></dt>
        </dl></td>
      <td><?php echo $val['goods_commonid'];?></td>
      <td><?php echo $val['goods_serial'];?></td>
      <td><span><?php echo $lang['currency'].ncPriceFormat($val['goods_price']); ?></span></td>
      <td><span><?php echo intval($output['stock_list'][$val['goods_commonid']]['stock']).$lang['piece']; ?></span></td>
      <td class="nscs-table-handle"><span><a href="javascript:void(0);" class="btn-bluejeans" nctype="set_stock" data-commonid="<?php echo $val['goods_commonid'];?>"><i class="icon-foursquare"></i>
        <p>设置库存</p>
        </a></span></td>
    </tr>
    <tr style="display:none;">
      <td colspan="20"><div class="ncsc-goods-sku ps-container"></div></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php  if (!empty($output['goods_list'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script> 
<script type="text/javascript">
$(function(){
    $('a[nctype="set_stock"]').click(function(){
        var _common_id = $(this).attr('data-commonid');
        ajax_form('set_stock', '设置库存', '<?php echo urlChain('goods', 'set_stock');?>&common_id=' + _common_id, '800');
    });
});
</script> 
