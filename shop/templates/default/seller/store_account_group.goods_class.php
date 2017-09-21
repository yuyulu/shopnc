<?php defined('In33hao') or exit('Access Invalid!');?>
<?php if(!empty($output['bind_class_list']) && is_array($output['bind_class_list'])) {?>
<?php foreach($output['bind_class_list'] as $key => $value) {?>

<div class="ncsc-account-container">
  <h4>
    <input id="<?php echo $key;?>" value="1" name="cate[<?php echo $key;?>]" <?php if (in_array($key,$output['gc_id_use_list'])) echo 'checked="checked"'; ?> class="checkbox" nctype="btn_select_module_class" type="checkbox" />
    <label for="<?php echo $key;?>"><?php echo $value['gc_name'];?></label>
  </h4>
  <?php if(!empty($value['class2']) && is_array($value['class2'])) {?>
  <ul class="ncsc-account-container-list">
    <?php foreach($value['class2'] as $key2 => $value2) {?>
    <li>
      <input id="<?php echo $key2;?>" class="checkbox" parent_id="<?php echo $key;?>" deep_type="2" name="cate[<?php echo $key;?>][<?php echo $key2;?>]" value="1" type="checkbox" <?php if (in_array($key2,$output['gc_id_use_list']) || (is_array($output['class_2_use_list'][$key2]) && $output['class_2_use_list'][$key2]['ccount'] == count($value2['class3']))) echo 'checked="checked"'; ?> />
      <label for="<?php echo $key2;?>"><?php echo $value2['gc_name'];?></label>
      <?php if (!empty($value2['class3']) && is_array($value2['class3'])) { ?>
      <span childcount="<?php echo count($value2['class3']); ?>" id="count_<?php echo $key2; ?>" title="已选下级分类">(
      <?php if (is_array($output['class_2_use_list'][$key2])) echo $output['class_2_use_list'][$key2]['ccount']; else echo '0';?>
      )</span> <a cate_id="<?php echo $key2; ?>" nc_title="<?php echo $value2['gc_name']; ?>" parent_id="<?php echo $key; ?>" nc_type="edit" href="javascript:void(0);" title="选择下级分类"><i class="icon-pencil"></i></a> 
      <!-- 三级分类循环到隐藏域 -->
      <?php foreach ($value2['class3'] as $key3 => $value3) { ?>
      <input style="display: none" type="checkbox" value="1" id="<?php echo $key3;?>" name="cate[<?php echo $key;?>][<?php echo $key2;?>][<?php echo $key3;?>]" <?php if (in_array($key3,$output['gc_id_use_list'])) echo 'checked="checked"'; ?> >
      <?php } ?>
      <?php } ?>
    </li>
    <?php } ?>
  </ul>
  <?php } ?>
</div>
<?php } ?>
<?php } ?>
<script>
$(document).ready(function(){
	//输出全分类JSON
	var bind_class_json = $.parseJSON('<?php echo $output['bind_class_list_json'];?>');

    //二级分类点击事件
    $('input[deep_type="2"]').on('click',function(){
        if ($(this).attr('checked') == 'checked') {
            $(this).nextAll('input').prop('checked',true);
            $(this).nextUntil(this,'span').html("("+$(this).nextAll('input').size()+")");

            //父级选框是否也选中功能注释
//             tmp = $(this).parents('.ncsc-account-container').find('input[deep_type="2"]');
//             if (tmp.size() == tmp.filter(':checked').size()){
//                 $('#'+$(this).attr('parent_id')).prop('checked',true);
//             }
        } else {
        	$(this).nextAll().prop('checked',false);
        	$(this).nextUntil(this,'span').html("(0)");
        	$('#gc_select_all').prop('checked',false);
//         	$('#'+$(this).attr('parent_id')).prop('checked',false);
        }
    });

    //二级分类对子分类选择编辑事件
	$('a[nc_type="edit"]').on('click',function(){
	    //保存当前正在编辑的ID，其它地用
		cur_edit_id = $(this).attr('cate_id');

		var cate3_array = bind_class_json[$(this).attr('parent_id')]['class2'][$(this).attr('cate_id')]['class3'];
    	cate3_html = '<div id="table_cate_box_edit" class="eject_con"><ul class="eject-con-list" cate_id="'+$(this).attr('cate_id')+'" parent_id="'+$(this).attr('parent_id')+'">';
        for(gc_id in cate3_array){
            cate3_html += '<li><input type="checkbox" class="checkbox" id="edit'+gc_id+'" value="'+gc_id+'"';
            if ($('#'+gc_id).prop('checked')) {
                cate3_html += ' checked ' ;
            }
            cate3_html += ('><label for="edit'+gc_id+'"> ' + cate3_array[gc_id]['gc_name'] + '</label></li>');
        }
        cate3_html += '</ul><div class="bottom"><label for="cate_3_selall" class="fl ml10 mt5"><input type="checkbox" id="cate_3_selall"> 全选</label><a id="cate_3_submit" class="ncbtn ncbtn-mint" href="javascript:void(0);">确认</a> <span style="color:#f30;">确认后，还需要点击页面底部的“提交保存”按钮完成保存操作</span></div></div>';
        html_form('select_cate', '选择 '+ $(this).attr('nc_title') +' 子分类', cate3_html, 600,1);
	});

	//三级分类选择框保存事件
	$('body').on('click','#cate_3_submit', function(){
		$('#cate_3_selall').remove();
		$('#table_cate_box_edit').find('input[type="checkbox"]').each(function(){
			$('#'+$(this).val()).prop('checked',$(this).prop('checked'));
		});
		var tmp = $('#table_cate_box_edit').find('input[type="checkbox"]:checked').size();
		var tmp1 = $('#table_cate_box_edit').find('input[type="checkbox"]').size();
		$('#'+cur_edit_id).prop('checked',tmp == tmp1)
		$('#count_'+cur_edit_id).html("("+tmp+")");
		if (tmp != tmp1) $('#gc_select_all').prop('checked',false);

		//父级选框是否也选中功能注释
//         tmp = $('#'+cur_edit_id).parents('.ncsc-account-container').find('input[deep_type="2"]');
//         if (tmp.size() == tmp.filter(':checked').size()){
//             $('#'+$('#'+cur_edit_id).attr('parent_id')).prop('checked',true);
//         } else {
//         	$('#'+$('#'+cur_edit_id).attr('parent_id')).prop('checked',false);
//         }

		DialogManager.close('select_cate');
	});

	//三级分类框全选事件
	$('body').on('click','#cate_3_selall', function(){
		$('#table_cate_box_edit').find('input[type="checkbox"]').prop('checked',$(this).prop('checked'));
	});

	//一级分类选择事件
    $('[nctype="btn_select_module_class"]').on('click', function() {
        if($(this).prop('checked')) {
            $(this).parents('.ncsc-account-container').find('input:checkbox').prop('checked', true);
            $(this).parents('.ncsc-account-container').find('span[id^="count_"]').each(function(){
                $(this).html("("+$(this).attr('childcount')+")");
            });
        } else {
            $(this).parents('.ncsc-account-container').find('input:checkbox').prop('checked', false);
            $(this).parents('.ncsc-account-container').find('span[id^="count_"]').html('(0)');
            $('#gc_select_all').prop('checked',false);
        }
    });
});
</script>