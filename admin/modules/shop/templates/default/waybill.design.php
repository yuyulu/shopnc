<?php defined('In33hao') or exit('Access Invalid!');?>
<style>

.waybill_area { position: relative; width: <?php echo $output['waybill_info']['waybill_pixel_width'];
?>px; height: <?php echo $output['waybill_info']['waybill_pixel_height'];?>px;}

.waybill_back { position: relative; width: <?php echo $output['waybill_info']['waybill_pixel_width'];?>px; height: <?php echo $output['waybill_info']['waybill_pixel_height'];?>px;}

.waybill_back img { width: <?php echo $output['waybill_info']['waybill_pixel_width'];?>px; height: <?php echo $output['waybill_info']['waybill_pixel_height'];
?>px;}

.waybill_design { position: absolute; left: 0; top: 0; width: <?php echo $output['waybill_info']['waybill_pixel_width'];?>px; height: <?php echo $output['waybill_info']['waybill_pixel_height'];?>px;}

.waybill_item { background-color: #FEF5E6; width: 90px; height: 20px; padding: 1px 5px 4px 5px !important; border-color: #FFBEBC; border-style: solid; border-width: 1px 1px 1px 1px; cursor: move; position: absolute; left: 0; top: 0;}

.waybill_item:hover { padding: 1px 5px 1px 5px !important; border-color: #FF7A73 !important; border-width: 1px 1px 4px 1px !important;}
</style>
<div class="page"> 
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=waybill&op=waybill_list" title="返回运单模板列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>运单模板 - 设计“<?php echo $output['waybill_info']['waybill_name']?>”运单模板</h3>
        <h5>预设供商家选择的运单快递模板</h5>
      </div>
      
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>勾选需要打印的项目，勾选后可以用鼠标拖动确定项目的位置、宽度和高度，也可以点击项目后边的微调按钮手工录入</li>
      <li>设置完成后点击提交按钮完成设计</li>
    </ul>
  </div>
  <div class="ncap-form-default">
    <dl class="row">
      <dt class="tit">选择打印项</dt>
      <dd class="opt">
        <form id="design_form" action="<?php echo urlAdminShop('waybill', 'waybill_design_save');?>" method="post">
          <input type="hidden" name="waybill_id" value="<?php echo $output['waybill_info']['waybill_id'];?>">
          <ul id="waybill_item_list" class="nc-row ncap-waybill-list">
            <?php if(!empty($output['waybill_item_list']) && is_array($output['waybill_item_list'])) {?>
            <?php foreach($output['waybill_item_list'] as $key => $value) {?>
            <li>
              <input id="check_<?php echo $key;?>" class="check" type="checkbox" name="waybill_data[<?php echo $key;?>][check]" data-waybill-name="<?php echo $key;?>" data-waybill-text="<?php echo $value['item_text'];?>" <?php echo $value['check'];?>>
              <label for="check_<?php echo $key;?>" class="label"><?php echo $value['item_text'];?></label>
              <i nctype="btn_item_edit" data-item-name="<?php echo $key;?>" title="微调" class="fa fa-pencil-square-o"></i>
              <input id="left_<?php echo $key;?>" type="hidden" name="waybill_data[<?php echo $key;?>][left]" value="<?php echo $value['left'];?>">
              <input id="top_<?php echo $key;?>" type="hidden" name="waybill_data[<?php echo $key;?>][top]" value="<?php echo $value['top'];?>">
              <input id="width_<?php echo $key;?>" type="hidden" name="waybill_data[<?php echo $key;?>][width]" value="<?php echo $value['width'];?>">
              <input id="height_<?php echo $key;?>" type="hidden" name="waybill_data[<?php echo $key;?>][height]" value="<?php echo $value['height'];?>">
            </li>
            <?php } ?>
            <?php } ?>
          </ul>
        </form>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">打印项偏移校正</dt>
      <dd class="opt">
        <div class="waybill_area">
          <div class="waybill_back"> <img src="<?php echo $output['waybill_info']['waybill_image_url'];?>" alt=""> </div>
          <div class="waybill_design">
            <?php if(!empty($output['waybill_info_data']) && is_array($output['waybill_info_data'])) {?>
            <?php foreach($output['waybill_info_data'] as $key=>$waybill_data) {?>
            <?php if($waybill_data['check']) { ?>
            <div id="div_<?php echo $key;?>" data-item-name="<?php echo $key;?>" class="waybill_item" style="position: absolute; width:<?php echo $waybill_data['width'];?>px; height:<?php echo $waybill_data['height'];?>px; left:<?php echo $waybill_data['left'];?>px; top:<?php echo $waybill_data['top'];?>px;"><?php echo $waybill_data['item_text'];?></div>
            <?php } ?>
            <?php } ?>
            <?php } ?>
          </div>
        </div>
      </dd>
    </dl>
    <div class="bot"><a id="submit" href="javascript:;" class="ncap-btn-big ncap-btn-green"><?php echo $lang['nc_submit'];?></a></div>
  </div>
</div>
<div id="dialog_item_edit" style="display:none;">
  <input id="dialog_item_name" type="hidden">
  <div class="ncap-form-default">
    <dl class="row">
      <dt class="tit">左偏移量：</dt>
      <dd class="opt">
        <input id="dialog_left" type="text" value="" class="input-txt">
        <p class="notic">微调运单向左偏移量，单位为毫米(mm)</p>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">上偏移量：</dt>
      <dd class="opt">
        <input id="dialog_top" type="text" value="" class="input-txt">
        <p class="notic">微调运单向上偏移量，单位为毫米(mm)</p>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">宽：</dt>
      <dd class="opt">
        <input id="dialog_width" type="text" value="" class="input-txt">
        <p class="notic">微调运单宽度，单位为毫米(mm)</p>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">高：</dt>
      <dd class="opt">
        <input id="dialog_height" type="text" value="" class="input-txt">
        <p class="notic">微调运单高度，单位为毫米(mm)</p>
      </dd>
    </dl>
    <div class="bot"><a id="btn_dialog_submit" href="javascript:;" class="ncap-btn-big ncap-btn-green">确定调整</a> <a id="btn_dialog_cancel" href="javascript:;" class="ncap-btn-big ncap-btn-orange ml20">取消微调</a> </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    var draggable_event = {
        stop: function(event, ui) {
            var item_name = ui.helper.attr('data-item-name');
            var position = ui.helper.position();
            $('#left_' + item_name).val(position.left);
            $('#top_' + item_name).val(position.top);
        }
    };

    var resizeable_event = {
        stop: function(event, ui) {
            var item_name = ui.helper.attr('data-item-name');
            $('#width_' + item_name).val(ui.size.width);
            $('#height_' + item_name).val(ui.size.height);
        }
    };

    $('.waybill_item').draggable(draggable_event);
    $('.waybill_item').resizable(resizeable_event);

    $('#waybill_item_list input:checkbox').on('click', function() {
        var item_name = $(this).attr('data-waybill-name');
        var div_name = 'div_' + item_name;
        if($(this).prop('checked')) {
            var item_text = $(this).attr('data-waybill-text');
            var waybill_item = '<div id="' + div_name + '" data-item-name="' + item_name + '" class="waybill_item">' + item_text + '</div>';
            $('.waybill_design').append(waybill_item);
            $('#' + div_name).draggable(draggable_event);
            $('#' + div_name).resizable(resizeable_event);
            $('#left_' + item_name).val('0');
            $('#top_' + item_name).val('0');
            $('#width_' + item_name).val('100');
            $('#height_' + item_name).val('20');
        } else {
            $('#' + div_name).remove();
        }
    });

    $('.waybill_design').on('click', '.waybill_item', function() {
        console.log($(this).position());
    });

    //微调弹出窗口
    $('[nctype="btn_item_edit"]').on('click', function() {
        var item_name = $(this).attr('data-item-name');
        $('#dialog_item_name').val(item_name);
        $('#dialog_left').val($('#left_' + item_name).val());
        $('#dialog_top').val($('#top_' + item_name).val());
        $('#dialog_width').val($('#width_' + item_name).val());
        $('#dialog_height').val($('#height_' + item_name).val());
        $('#dialog_item_edit').nc_show_dialog({title:'微调'});
    });

    //微调保存
    $('#btn_dialog_submit').on('click', function() {
        var item_name = $('#dialog_item_name').val();
        $('#div_' + item_name).css('left', $('#dialog_left').val() + 'px');
        $('#div_' + item_name).css('top', $('#dialog_top').val() + 'px');
        $('#div_' + item_name).css('width', $('#dialog_width').val() + 'px');
        $('#div_' + item_name).css('height', $('#dialog_height').val() + 'px');
        $('#left_' + item_name).val($('#dialog_left').val());
        $('#top_' + item_name).val($('#dialog_top').val());
        $('#width_' + item_name).val($('#dialog_width').val());
        $('#height_' + item_name).val($('#dialog_height').val());
        $('#dialog_item_edit').hide();
    });

    //微调取消
    $('#btn_dialog_cancel').on('click', function() {
        $('#dialog_item_edit').hide();
    });

    $('#submit').on('click', function() {
        $('#design_form').submit();
    });
});
</script> 
