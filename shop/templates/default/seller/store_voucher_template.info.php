
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="ncsc-form-default">
<dl>
  <dt><em class="pngFix"></em><?php echo $lang['voucher_template_title'].$lang['nc_colon']; ?></dt>
  <dd><?php echo $output['t_info']['voucher_t_title'];?></dd>
</dl>
<dl>
  <dt><em class="pngFix"></em>店铺分类：</dt>
  <dd><?php echo $output['t_info']['voucher_t_sc_name'];?></dd>
</dl>
<dl>
  <dt><em class="pngFix"></em>领取方式：</dt>
  <dd><?php echo $output['t_info']['voucher_t_gettype_text'];?></dd>
</dl>
<dl>
  <dt><em class="pngFix"></em><?php echo $lang['voucher_template_enddate'].$lang['nc_colon']; ?></dt>
  <dd><?php echo $output['t_info']['voucher_t_end_date']?@date('Y-m-d',$output['t_info']['voucher_t_end_date']):'';?></dd>
</dl>
<dl>
  <dt><?php echo $lang['voucher_template_price'].$lang['nc_colon']; ?></dt>
  <dd><?php echo $output['t_info']['voucher_t_price'];?>&nbsp;<?php echo $lang['currency_zh'];?></dd>
</dl>
<?php if ($output['t_info']['voucher_t_gettype_key'] == 'points'){ ?>
<dl>
  <dt>兑换所需积分：</dt>
  <dd><?php echo $output['t_info']['voucher_t_points'];?>&nbsp;分</dd>
</dl>
<?php } ?>
<dl>
  <dt ><em class="pngFix"></em><?php echo $lang['voucher_template_total'].$lang['nc_colon']; ?></dt>
  <dd><?php echo $output['t_info']['voucher_t_total']; ?>&nbsp;<?php echo $lang['voucher_template_eachlimit_unit'];?></dd>
</dl>
<?php if ($output['t_info']['voucher_t_gettype_key'] <> 'pwd'){ ?>
<dl>
  <dt ><em class="pngFix"></em><?php echo $lang['voucher_template_eachlimit'].$lang['nc_colon']; ?></dt>
  <dd>
    <?php if ($output['t_info']['voucher_t_eachlimit'] > 0){?>
    <?php echo $output['t_info']['voucher_t_eachlimit'];?>&nbsp;<?php echo $lang['voucher_template_eachlimit_unit'];?>
    <?php } else {echo '不限'; } ?>
  </dd>
</dl>
<?php } ?>
<dl>
  <dt ><em class="pngFix"></em><?php echo $lang['voucher_template_orderpricelimit'].$lang['nc_colon']; ?></dt>
  <dd><?php echo $output['t_info']['voucher_t_limit'];?>&nbsp;<?php echo $lang['currency_zh'];?></dd>
</dl>
<?php if ($output['t_info']['voucher_t_gettype_key'] <> 'pwd'){ ?>
<dl>
  <dt ><em class="pngFix"></em>会员级别：</dt>
  <dd><?php echo $output['t_info']['voucher_t_mgradelimittext'];?></dd>
</dl>
<?php } ?>
<dl>
  <dt ><em class="pngFix"></em><?php echo $lang['voucher_template_describe'].$lang['nc_colon']; ?></dt>
  <dd>
    <textarea  name="txt_template_describe" rows="3" class="w300" readonly><?php echo $output['t_info']['voucher_t_desc'];?></textarea>
  </dd>
</dl>
<dl>
  <dt ><em class="pngFix"></em><?php echo $lang['voucher_template_image'].$lang['nc_colon']; ?></dt>
  <dd>
    <div style="clear:both; padding-top:10px;">
      <?php if ($output['t_info']['voucher_t_customimg']){?>
      <img onload="javascript:DrawImage(this,220,95);" src="<?php echo $output['t_info']['voucher_t_customimg'];?>"/>
      <?php }?>
    </div>
  </dd>
</dl>
<dl>
  <dt><em class="pngFix"></em>最后修改时间：</dt>
  <dd><?php echo @date('Y-m-d H:i:s',$output['t_info']['voucher_t_add_date']);?></dd>    
</dl>
<dl>
  <dt><em class="pngFix"></em><?php echo $lang['nc_status'].$lang['nc_colon']; ?></dt>
  <dd>
    <?php foreach ($output['templatestate_arr'] as $k=>$v){?>
    <?php if ($v[0] == $output['t_info']['voucher_t_state']){ echo $v[1];}?>
    <?php }?>
  </dd>
</dl>
<dl>
  <dt><em class="pngFix"></em><?php echo $lang['voucher_template_giveoutnum'].$lang['nc_colon']; ?></dt>
  <dd><?php echo $output['t_info']['voucher_t_giveout'];?>&nbsp;<?php echo $lang['voucher_template_eachlimit_unit'];?></dd>
</dl>
<dl>
  <dt><em class="pngFix"></em><?php echo $lang['voucher_template_usednum'].$lang['nc_colon']; ?></dt>
  <dd><?php echo $output['t_info']['voucher_t_used'];?>&nbsp;<?php echo $lang['voucher_template_eachlimit_unit'];?></dd>
</dl>

<?php if($output['t_info']['voucher_t_gettype_key'] == 'pwd' && $output['t_info']['voucher_t_isbuild'] == 0){?>
<dl>
  <dt><em class="pngFix"></em>卡密生成状态：</dt>
  <dd>未生成&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="javascript:void(0);" id="build_voucher">点击生成代金券卡密</a></dd>
</dl>
<?php } ?>

<?php if($output['t_info']['voucher_t_gettype_key'] == 'free'){ ?>
<dl>
  <dt><em class="pngFix"></em>推广链接：</dt>
  <dd>
    <input type="text" class="w340" onclick="oCopy(this)" value="<?php echo SHOP_SITE_URL.DS;?>index.php?act=voucher&op=getvoucher&tid=<?php echo $output['t_info']['voucher_t_id'];?>" readonly/>
<script>
	function oCopy(obj){
      obj.select();
      if (!!window.ActiveXObject || "ActiveXObject" in window){
        js=obj.createTextRange();
        js.execCommand("Copy")
        alert("复制成功!");
      }else{
        alert('在“推广链接”文本框上右击鼠标，选择“复制”将推广链接复制到剪切板');
      }
    }
</script>
    <p class="hint">可以复制该链接对免费领取代金券进行推广</p>
  </dd>
</dl>
<?php } ?>

<?php if(($output['t_info']['voucher_t_gettype'] == 2 && $output['t_info']['voucher_t_isbuild'] == 1) || $output['t_info']['voucher_t_giveout']>0){?>
<h3>已生成代金券
  <a id="voucher_exportbtn" class="ncbtn-mini" href="javascript:void(0);" onclick="javascript:go('index.php?act=store_voucher&op=voucher_export&tid=<?php echo $output['t_info']['voucher_t_id']; ?>');" title="导出Excel" style="float: right; margin-right: 10px; display: none;">导出Excel</a>
</h3>

<div id="voucher_list_div"></div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
<script type="text/javascript">
  $("#voucher_list_div").load('index.php?act=store_voucher&op=voucherlist&tid=<?php echo $output['t_info']['voucher_t_id']; ?>');
</script>
<?php } ?>
<div class="bottom">
  <a href="javascript:void(0);" class="submit" onclick="window.location='index.php?act=store_voucher&op=templatelist'" > <?php echo $lang['voucher_template_backlist'];?></a>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#build_voucher").click(function(){
		ajaxget('index.php?act=store_voucher&op=bulidvoucher&tid=<?php echo $output['t_info']['voucher_t_id'];?>');
	});
});
</script>