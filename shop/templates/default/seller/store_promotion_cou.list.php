<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
<?php if ($output['isOwnShop']) { ?>
  <a class="ncbtn ncbtn-mint" href="<?php echo urlShop('store_promotion_cou', 'cou_add');?>"><i class="icon-plus-sign"></i>添加活动</a>

<?php } else { ?>

  <?php if(!empty($output['current_cou_quota'])) { ?>
  <a class="ncbtn ncbtn-mint" style="right:100px" href="<?php echo urlShop('store_promotion_cou', 'cou_add');?>"><i class="icon-plus-sign"></i>添加活动</a> <a class="ncbtn ncbtn-aqua" href="<?php echo urlShop('store_promotion_cou', 'cou_quota_add');?>" title=""><i class="icon-money"></i>套餐续费</a>
  <?php } else { ?>
  <a class="ncbtn ncbtn-aqua" href="<?php echo urlShop('store_promotion_cou', 'cou_quota_add');?>" title=""><i class="icon-money"></i>购买套餐</a>
  <?php } ?>

<?php } ?>
</div>

<?php if ($output['isOwnShop']) { ?>
<div class="alert alert-block mt10">
  <ul>
    <li>1、点击添加活动按钮可以添加加价购活动，点击编辑按钮可以对加价购活动进行编辑</li>
    <li>2、点击删除按钮可以删除加价购活动</li>
 </ul>
</div>
<?php } else { ?>
<div class="alert alert-block mt10">
  <?php if(!empty($output['current_cou_quota'])) { ?>
  <strong>套餐过期时间<?php echo $lang['nc_colon'];?></strong><strong style="color:#F00;"><?php echo date('Y-m-d H:i:s', $output['current_cou_quota']['tend']);?></strong>
  <?php } else { ?>
  <strong>当前没有可用套餐，请先购买套餐</strong>
  <?php } ?>
  <ul>
    <li>1、点击购买套餐和套餐续费按钮可以购买或续费套餐</li>
    <li>2、点击添加活动按钮可以添加加价购活动，点击编辑按钮可以对加价购活动进行编辑</li>
    <li>3、点击删除按钮可以删除加价购活动</li>
    <li>4、<strong style="color: red">相关费用会在店铺的账期结算中扣除</strong>。</li>
 </ul>
</div>
<?php } ?>

<form method="get">
  <table class="search-form">
    <input type="hidden" name="act" value="store_promotion_cou" />
    <input type="hidden" name="op" value="cou_list" />
    <tr>
      <td>&nbsp;</td>
      <th>状态</th>
      <td class="w100"><select name="state">
          <?php foreach ((array) $output['couStates'] as $key=>$val) { ?>
          <option value="<?php echo $key;?>" <?php if(intval($key) === intval($_GET['state'])) echo 'selected';?>><?php echo $val;?></option>
          <?php } ?>
        </select></td>
      <th class="w110">活动名称</th>
      <td class="w160"><input type="text" class="text w150" name="cou_name" value="<?php echo $_GET['cou_name'];?>"/></td>
      <td class="w70 tc"><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>" /></label></td>
    </tr>
  </table>
</form>
<table class="ncsc-default-table">
  <thead>
    <tr>
      <th class="w30"></th>
      <th class="tl">活动名称</th>
      <th class="w180">开始时间</th>
      <th class="w180">结束时间</th>
      <th class="w80">状态</th>
      <th class="w150"><?php echo $lang['nc_handle'];?></th>
    </tr>
  </thead>
  <?php if(!empty($output['list']) && is_array($output['list'])){?>
  <?php foreach($output['list'] as $key=>$val){?>
  <tbody id="cou_list">
    <tr class="bd-line">
      <td></td>
      <td class="tl"><dl class="goods-name">
          <dt><?php echo $val['name'];?></dt>
        </dl></td>
      <td class="goods-time"><?php echo date("Y-m-d H:i",$val['tstart']);?></td>
      <td class="goods-time"><?php echo date("Y-m-d H:i",$val['tend']);?></td>
      <td><?php echo $output['couStates'][$val['state']];?></td>
      <td class="nscs-table-handle tr">

          <span>
              <a href="index.php?act=store_promotion_cou&op=cou_edit&cou_id=<?php echo $val['id'];?>" class="btn-bluejeans">
                  <i class="icon-edit"></i>
                  <p><?php echo $lang['nc_edit'];?></p>
              </a>
          </span>

          <span>
              <a href="javascript:;" nctype="btn_del_cou" data-cou-id=<?php echo $val['id'];?> class="btn-grapefruit">
                  <i class="icon-trash"></i>
                  <p><?php echo $lang['nc_delete'];?></p>
              </a>
          </span>
      </td>
  </tr>
  <?php } ?>
  <?php } else { ?>
  <tr id="cou_list_norecord">
      <td class="norecord" colspan="20"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
  </tr>
  <?php }?>
  </tbody>
  <tfoot>
    <?php if(!empty($output['list']) && is_array($output['list'])){?>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<form id="submit_form" action="" method="post" >
  <input type="hidden" id="cou_id" name="cou_id" value="">
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $('[nctype="btn_del_cou"]').on('click', function() {
            if(confirm('<?php echo $lang['nc_ensure_del'];?>')) {
                var action = "<?php echo urlShop('store_promotion_cou', 'cou_del');?>";
                var cou_id = $(this).attr('data-cou-id');
                $('#submit_form').attr('action', action);
                $('#cou_id').val(cou_id);
                ajaxpost('submit_form', '', '', 'onerror');
            }
        });
    });
</script>
