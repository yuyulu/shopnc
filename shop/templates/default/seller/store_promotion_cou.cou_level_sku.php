<?php
$inPageProducts = array();
?>
<div class="div-goods-select">
  <table class="search-form">
    <tbody>
      <tr>
        <td>&nbsp;</td>
        <th>店铺分类</th>
        <td class="w160"><select id="cou_level_sku_stc_id_<?php echo $output['level']; ?>" class="w150">
            <option value="0"><?php echo $lang['nc_please_choose'];?></option>
            <?php if (!empty($output['store_goods_class'])){?>
            <?php foreach ($output['store_goods_class'] as $val) { ?>
            <option value="<?php echo $val['stc_id']; ?>" <?php if($val['stc_id'] == $_GET['stc_id']) echo 'selected="selected"';?>><?php echo $val['stc_name']; ?></option>
            <?php if (is_array($val['child']) && count($val['child'])>0){?>
            <?php foreach ($val['child'] as $child_val){?>
            <option value="<?php echo $child_val['stc_id']; ?>" <?php if($child_val['stc_id'] == $_GET['stc_id']) echo 'selected="selected"';?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_val['stc_name']; ?></option>
            <?php }}}}?>
          </select></td>
        <th>商品名称</th>
        <td class="w160"><input type="text" id="cou_level_sku_keyword_<?php echo $output['level']; ?>" class="text" value="<?php echo $_GET['keyword'];?>" /></td>
        <td class="tc w70"><a href="index.php?act=store_promotion_cou&op=cou_level_sku&level=<?php echo $output['level']; ?>" nctype="search_a" class="ncs-btn"><i class="icon-search"></i><?php echo $lang['nc_search'];?></a></td>
        <td class="w10"></td>
      </tr>
    </tbody>
  </table>
  <div class="search-result" style="width:739px;">
    <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){ ?>
    <ul class="goods-list" nctype="bundling_goods_add_tbody" style=" width:760px;text-align:left;">
      <?php foreach ($output['goods_list'] as $val){?>
<?php
$inPageProducts[$val['goods_id']] = array(
    'id' => $val['goods_id'],
    'name' => $val['goods_name'],
    'price' => $val['goods_price'],
    'imgUrl' => cthumb($val['goods_image'], 60, $_SESSION['store_id']),
);
?>
      <li nctype="<?php echo $val['goods_id'];?>">
        <div class="goods-thumb"><img src="<?php echo cthumb($val['goods_image'], 240, $_SESSION['store_id']);?>" nctype="<?php echo $val['goods_image'];?>" /></div>
        <dl class="goods-info">
          <dt><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id'], )); ?>" target="_blank" title="<?php echo $lang['bundling_goods_name'].'/'.$lang['bundling_goods_code'];?><?php echo $val['goods_name'];?><?php  if($val['goods_serial'] != ''){ echo $val['goods_serial'];}?>"><?php echo $val['goods_name'];?></a></dt>
          <dd><?php echo $lang['bundling_goods_price'];?>¥<?php echo ncPriceFormat($val['goods_price']);?></dd>
          <dd><?php echo $lang['bundling_goods_storage'];?><?php echo $val['goods_storage'].$lang['piece'];?></dd>
        </dl>
        <div data-cou-level-sku-switch-disabled="<?php echo $val['goods_id']; ?>" style="display:none;">
          已设置为换购商品
        </div>
        <div data-cou-level-sku-switch-enabled="<?php echo $val['goods_id']; ?>">
          <a href="javascript:;" class="ncbtn-mini ncbtn-mint" data-cou-level-sku-add-button="<?php echo $val['goods_id']; ?>" data-level="<?php echo $output['level']; ?>"><i class="icon-plus"></i>设置为换购商品</a>
        </div>
      </li>
      <?php }?>
    </ul>
    <?php }else{?>
    <div class="norecord">
      <div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div>
    </div>
    <?php }?>
    <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){?>
    <div class="pagination"><?php echo $output['show_page']; ?></div>
    <?php }?>
  </div>
</div>
<script>
$.extend(window.couLevelSkuInSearch, <?php echo json_encode($inPageProducts); ?>);

</script>
