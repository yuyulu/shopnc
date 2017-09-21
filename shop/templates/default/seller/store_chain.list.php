<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="<?php echo urlShop('store_chain', 'chain_add');?>" class="ncbtn ncbtn-mint" title="添加门店"><i class="icon-plus-sign"></i>添加门店</a>
  <a href="<?php echo CHAIN_SITE_URL;?>" class="ncbtn ncbtn-aqua" style="right:90px" target="_blonk" title="进入门店系统"><i class="icon-building"></i>进入门店系统</a>
</div>
<!-- div class="alert mt15 mb5"><strong>操作提示：</strong>
  <ul>
    <li></li>
  </ul>
</div -->
<!-- form method="get">
<input type="hidden" name="act" value="store_plate">
<table class="search-form">
    <tr>
      <td>&nbsp;</td>
      <th>版式位置</th>
      <td class="w80">
        <select name="p_position">
          <option>请选择</option>
        </select>
      </td><th>版式名称</th>
      <td class="w160"><input type="text" class="text w150" name="p_name" value="<?php echo $_GET['p_name']; ?>"/></td>
      <td class="w70 tc"><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>" /></label></td>
    </tr>
</table>
</form -->
<table class="ncsc-default-table">
  <thead>
    <tr>
      <th class="w30"></th>
      <th class="w200 tl">门店名称</th>
      <th class="w200">所在地区</th>
      <th class="tc">门店地址</th>
      <th class="w110"><?php echo $lang['nc_handle'];?></th>
    </tr>
    <?php if (!empty($output['chain_list'])) { ?>
    <tr>
      <td class="tc"><input type="checkbox" id="all" class="checkall"/></td>
      <td colspan="10"><label for="all" ><?php echo $lang['nc_select_all'];?></label>
        <a href="javascript:void(0);" nc_type="batchbutton" uri="<?php echo urlShop('store_chain', 'chain_del');?>" name="chain_id" confirm="<?php echo $lang['nc_ensure_del'];?>" class="ncbtn-mini"><i class="icon-trash"></i><?php echo $lang['nc_del'];?></a>
      </td>
    </tr>
    <?php } ?>
  </thead>
  <tbody>
    <?php if (!empty($output['chain_list'])) { ?>
    <?php foreach($output['chain_list'] as $val) { ?>
    <tr class="bd-line">
      <td class="tc"><input type="checkbox" class="checkitem tc" value="<?php echo $val['chain_id']; ?>"/></td>
      <td class="tl"><?php echo $val['chain_name'];?></td>
      <td><?php echo $val['area_info'];?></td>
      <td><?php echo $val['chain_address'];?></td>
      <td class="nscs-table-handle">
        <span><a href="<?php echo urlShop('store_chain', 'chain_edit', array('chain_id' => $val['chain_id']));?>" class="btn-bluejeans"><i class="icon-edit"></i><p><?php echo $lang['nc_edit'];?></p></a></span>
        <span><a href="javascript:void(0)" onclick="ajax_get_confirm('<?php echo $lang['nc_ensure_del'];?>', '<?php echo urlShop('store_chain', 'chain_del', array('chain_id' => $val['chain_id']));?>');" class="btn-grapefruit"><i class="icon-trash"></i><p><?php echo $lang['nc_del'];?></p></a></span>
      </td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if (!empty($output['chain_list'])) { ?>
    <tr>
      <th class="tc"><input type="checkbox" id="all" class="checkall"/></th>
      <th colspan="10"><label for="all" ><?php echo $lang['nc_select_all'];?></label>
        <a href="javascript:void(0);" nc_type="batchbutton" uri="<?php echo urlShop('store_chain', 'chain_del');?>" name="chain_id" confirm="<?php echo $lang['nc_ensure_del'];?>" class="ncbtn-mini"><i class="icon-trash"></i><?php echo $lang['nc_del'];?></a>
       </th>
    </tr>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
