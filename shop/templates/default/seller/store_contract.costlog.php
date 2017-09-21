<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="tabmenu">
    <?php include template('layout/submenu');?>
</div>

<div class="ncsc-form-default">
	<dl>
		<dt><em class="pngFix"></em>项目名称：</dt>
		<dd><?php echo $output['item_info']['cti_name'];?></dd>
	</dl>
	<dl>
		<dt><em class="pngFix"></em>保证金余额：</dt>
		<dd><?php echo $output['contract_info']['ct_cost'].'&nbsp;'.$lang['currency_zh'];?></dd>
	</dl>
	<dl>
		<dt><em class="pngFix"></em>状态：</dt>
		<dd>
			<?php if ($output['contract_info']['ct_state_sign'] == 'applying') { ?>
				<?php echo $output['contract_info']['ct_state_text']."（{$output['contract_info']['ct_auditstate_text']}）";?>
			<?php }else{ ?>
				<?php echo $output['contract_info']['ct_state_text'];?>
			<?php } ?>
		</dd>
	</dl>
	<h3>保证金日志</h3>
	<table class="ncsc-default-table">
		<thead>
			<tr>
				<th class="w50"></th>
				<th class="w200">金额（<?php echo $lang['currency_zh']; ?>）</th>
				<th class="w200">添加时间</th>
				<th class="tl">描述</th>
			</tr>
		</thead>
		<tbody>
		  <?php if (count($output['costlog_list'])>0) { ?>
		  <?php foreach($output['costlog_list'] as $v) { ?>
		  <tr class="bd-line">
				<td class="w50">&nbsp;</td>
			  	<td><?php echo $v['clog_price'];?></td>
			  	<td><?php echo @date('Y-m-d H:i:s',$v['clog_addtime']);?></td>
				<td class="tl"><?php echo $v['clog_desc'];?></td>
			</tr>
		  <?php }?>
		  <?php } else { ?>
		  <tr><td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td></tr>
		  <?php } ?>
		</tbody>
		<tfoot>
		  <?php if (count($output['costlog_list'])>0) { ?>
		  <tr><td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td></tr>
		  <?php } ?>
		</tfoot>
	</table>
</div>