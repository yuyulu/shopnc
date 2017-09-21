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
		<dt><em class="pngFix"></em>所需保证金：</dt>
		<dd><?php echo $output['item_info']['cti_cost'].'&nbsp;'.$lang['currency_zh'];?>&nbsp;
			<?php if($output['costlog_count'] > 0){?>
			<a href="index.php?act=store_contract&op=costlog&itemid=<?php echo $output['item_info']['cti_id']; ?>" style="margin-left: 30px;">查看保证金日志</a>
			<?php } ?>
		</dd>
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
	<h3>保障服务日志</h3>
	<table class="ncsc-default-table">
		<thead>
			<tr>
				<th class="w30"></th>
				<th class="w120 tl">操作人</th>
				<th class="w200">操作时间</th>
				<th class="tl">操作描述</th>
			</tr>
		</thead>
		<tbody>
		  <?php if (count($output['log_list'])>0) { ?>
		  <?php foreach($output['log_list'] as $v) { ?>
		  <tr class="bd-line">
				<td>&nbsp;</td>
			  	<td class="tl">
					<?php if($v['log_role'] == 'admin'){ ?>
						<?php echo $output['logrole_arr'][$v['log_role']]; ?>
					<?php }else{ ?>
						<?php echo "{$v['log_username']}（{$output['logrole_arr'][$v['log_role']]}）";?>
				  	<?php } ?>
				</td>
			  	<td><?php echo @date('Y-m-d H:i:s',$v['log_addtime']);?></td>
				<td class="tl"><?php echo $v['log_msg'];?></td>
			</tr>
		  <?php }?>
		  <?php } else { ?>
		  <tr><td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td></tr>
		  <?php } ?>
		</tbody>
		<tfoot>
		  <?php if (count($output['log_list'])>0) { ?>
		  <tr><td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td></tr>
		  <?php } ?>
		</tfoot>
	</table>
</div>