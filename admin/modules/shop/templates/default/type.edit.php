<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=type&op=type" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_type_manage'];?> - <?php echo $lang['nc_edit'];?>“<?php echo $output['type_info']['type_name'];?>”</h3>
        <h5><?php echo $lang['nc_type_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['type_add_prompts_one'];?></li>
      <li><?php echo $lang['type_add_prompts_two'];?></li>
      <li><?php echo $lang['type_add_prompts_three'];?></li>
      <li><?php echo $lang['type_add_prompts_four'];?></li>
      <li>自定义属性只需要填写属性名称，属性值由商家自行填写。注意：自定义属性不作为商品检索项使用。</li>
      <li>选中“删”后复选框提交后该条信息将被删除。</li>
    </ul>
  </div>
  <form id="type_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="t_id" value="<?php echo $output['type_info']['type_id'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="t_mane"><em>*</em><?php echo $lang['type_index_type_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="input-txt" name="t_mane" id="t_mane" value="<?php echo $output['type_info']['type_name'];?>" />
          <span class="err"></span>
          <p class="notic"></p>
      </dl>
      <dl class="row">
        <dt class="tit" colspan="2">
          <label class="" for="s_sort"><?php echo $lang['type_common_belong_class'];?></label>
        </dt>
        <dd class="opt">
          <div id="gcategory">
            <input type="hidden" value="<?php echo $output['type_info']['class_id'];?>" class="mls_id" name="class_id" />
            <input type="hidden" value="<?php echo $output['type_info']['class_name'];?>" class="mls_name" name="class_name" />
            <label class="mr10"><?php echo $output['type_info']['class_name'];?></label>
            <?php if (!empty($output['type_info']['class_id'])) {?>
            <input class="edit_gcategory" type="button" value="<?php echo $lang['nc_edit'];?>">
            <?php }?>
            <select <?php if (!empty($output['type_info']['class_id'])) {?>style="display:none;"<?php }?> class="class-select">
              <option value="0"><?php echo $lang['nc_please_choose'];?></option>
              <?php if(!empty($output['gc_list'])){ ?>
              <?php foreach($output['gc_list'] as $k => $v){ ?>
              <?php if ($v['gc_parent_id'] == 0) {?>
              <option value="<?php echo $v['gc_id'];?>"><?php echo $v['gc_name'];?></option>
              <?php } ?>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
          <p class="notic"><?php echo $lang['type_common_belong_class_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="t_sort"><em>*</em><?php echo $lang['nc_sort'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="input-txt" name="t_sort" id="t_sort" value="<?php echo $output['type_info']['type_sort'];?>" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['type_add_sort_desc'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['type_add_related_spec'];?></label>
        </dt>
        <dd class="opt">
          <div id="speccategory">快捷定位
            <input type="hidden" name="spec_checkbox" id="spec_checkbox" value="" />
            <select class="class-select">
              <option value="0"><?php echo $lang['nc_please_choose'];?></option>
              <?php if(!empty($output['gc_list'])){ ?>
              <?php foreach($output['gc_list'] as $k => $v){ ?>
              <?php if ($v['gc_parent_id'] == 0) {?>
              <option value="<?php echo $v['gc_id'];?>"><?php echo $v['gc_name'];?></option>
              <?php } ?>
              <?php } ?>
              <?php } ?>
            </select>
            分类下对应的规格<a class="ncap-btn" nctype="spec_hide" href="javascript:void(0);"><?php echo $lang['type_common_checked_hide'];?></a></div>
          <div id="spec_div" class="scrollbar-box">
            <div class="ncap-type-spec-list">
              <input type="hidden" value="" name="spec[form_submit]" nc_type="submit_value" />
              <?php if(is_array($output['spec_list'])){?>
              <?php foreach($output['spec_list'] as $k=>$val){?>
              <dl>
                <dt id="spec_dt_<?php echo $k;?>"><?php echo $val['name'];?></dt>
                <?php if(is_array($val['spec'])){?>
                <dd>
                  <?php foreach($val['spec'] as $v){?>
                  <label>
                    <input class="checkitem" nc_type="change_default_spec_value" type="checkbox" <?php if(in_array($v['sp_id'], $output['spec_related'])){ echo 'checked="checked"';}?> value="<?php echo $v['sp_id'];?>" name="spec_id[]">
                    <?php echo $v['sp_name'] . $v['sp_value'];?></label>
                  <?php }?>
                </dd>
                <?php }?>
              </dl>
              <?php }?>
              <?php }else{?>
              <div><?php echo $lang['type_add_spec_null_one'];?><a href="JavaScript:void(0);" onclick="window.parent.openItem('shop|spec')"><?php echo $lang['nc_spec_manage'];?></a><?php echo $lang['type_add_spec_null_two']?></div>
              <?php }?>
            </div>
          </div>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="member_name"><?php echo $lang['type_add_related_brand'];?></label>
        </dt>
        <dd class="opt">
          <div id="brandcategory">快捷定位
            <select class="class-select">
              <option value="0"><?php echo $lang['nc_please_choose'];?></option>
              <?php if(!empty($output['gc_list'])){ ?>
              <?php foreach($output['gc_list'] as $k => $v){ ?>
              <?php if ($v['gc_parent_id'] == 0) {?>
              <option value="<?php echo $v['gc_id'];?>"><?php echo $v['gc_name'];?></option>
              <?php } ?>
              <?php } ?>
              <?php } ?>
            </select>
            分类下对应的品牌 <a class="ncap-btn" nctype="brand_hide" href="javascript:void(0);"><?php echo $lang['type_common_checked_hide'];?></a></div>
          <div id="brand_div" class="scrollbar-box">
            <div class="ncap-type-spec-list">
              <?php if(is_array($output['brand_list']) && !empty($output['brand_list'])) {?>
              <input type="hidden" value="" name="brand[form_submit]" nc_type="submit_value" />
              <?php foreach ($output['brand_list'] as $k=>$val){?>
              <dl>
                <dt id="brand_dt_<?php echo $k;?>"><?php echo $val['name'];?></dt>
                <?php if($val['brand']) {?>
                <dd>
                  <?php foreach ($val['brand'] as $bval){?>
                  <label for="brand_<?php echo $bval['brand_id'];?>">
                    <input type="checkbox" name="brand_id[]" class="brand_change_default_submit_value" value="<?php echo $bval['brand_id']?>" <?php if(in_array($bval['brand_id'], $output['brang_related'])){ echo 'checked="checked"';}?> id="brand_<?php echo $bval['brand_id'];?>" />
                    <?php echo $bval['brand_name']?></label>
                  <?php }?>
                </dd>
                <?php }?>
              </dl>
              <?php }?>
              <?php }else{?>
              <div><?php echo $lang['type_add_brand_null_one'];?><a href="JavaScript:void(0);" onclick="window.parent.openItem('shop|brand')"><?php echo $lang['nc_brand_manage'];?></a><?php echo $lang['type_add_brand_null_two']?></div>
              <?php }?>
            </div>
          </div>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['type_add_attr_add'].$lang['nc_colon'];?></dt>
        <dd class="opt">
          <ul class="ncap-ajax-add" id="ul_attr">
            <?php if (is_array($output['attr_list']) && !empty($output['attr_list'])){?>
            <?php foreach ($output['attr_list'] as $aval){?>
            <li>
              <input type="hidden" value="" name="at_value[<?php echo $aval['attr_id'];?>][form_submit]" nc_type="submit_value" />
              <input type="hidden" value="<?php echo $aval['attr_id'];?>" name="at_value[<?php echo $aval['attr_id'];?>][a_id]" nc_type='ajax_attr_id' />
              <label>删
                <input type="checkbox" name="a_del[<?php echo $aval['attr_id'];?>]" value="<?php echo $aval['attr_id'];?>" />
              </label>
              <label title="<?php echo $lang['nc_sort'];?>">
                <input type="text" class="change_default_submit_value w30" name="at_value[<?php echo $aval['attr_id'];?>][sort]" value="<?php echo $aval['attr_sort'];?>" />
              </label>
              <label>
                <input type="text" class="change_default_submit_value w150" name="at_value[<?php echo $aval['attr_id'];?>][name]" value="<?php echo $aval['attr_name'];?>" />
              </label>
              <label>
                <input type="text" class="change_default_submit_value w300" name="at_value[<?php echo $aval['attr_id'];?>][value]" value="<?php echo $aval['attr_value'];?>" />
              </label>
              <label> <?php echo $lang['nc_display'];?>
                <input type="checkbox" name="at_value[<?php echo $aval['attr_id'];?>][show]" class="change_default_submit_value" <?php if($aval['attr_show'] == '1'){echo 'checked="checked"';}?> value="1" />
              </label>
              <label><a href="javascript:void(0);" onclick="ajax_form('attr_deit', '编辑属性', '<?php echo urlAdminShop('type', 'attr_edit', array('attr_id' => $aval['attr_id']));?>', 640)" class="ncap-btn"><?php echo $lang['nc_edit'];?></a></label>
            </li>
            <?php }?>
            <?php }?>
            <a id="add_type" class="ncap-btn" href="JavaScript:void(0);"><i class="fa fa-plus"></i><?php echo $lang['type_add_attr_add_one'];?></a>
          </ul>
          <p class="notic">需要修改属性值，请点击属性后面的编辑按钮</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">自定义属性</dt>
        <dd class="opt">
          <ul class="ncap-ajax-add" id="ul_custom">
            <?php if (!empty($output['custom_list'])) {?>
            <?php foreach ($output['custom_list'] as $val) {?>
            <li>
              <label>删
                <input type="checkbox" name="custom[<?php echo $val['custom_id'];?>][del]" value="1" />
              </label>
              <label>
                <input type="text" class="w150" name="custom[<?php echo $val['custom_id'];?>][name]" value="<?php echo $val['custom_name']?>" />
              </label>
            </li>
            <?php }?>
            <?php }?>
            <a id="add_custom" class="ncap-btn" style=" display: block; clear: both; width: 100px;" href="JavaScript:void(0);"><i class="fa fa-plus"></i>添加自定义属性</a>
          </ul>
          <p class="notic">自定义属性用于为商家自行添加某些属性值的预留选项，平台只需建立并设定属性名称即可，属性值由商家自行添加。注意：自定义属性不能作为商品检索项使用。</p>
        </dd>
      </dl>
      <div class="bot"><a id="submitBtn" class="ncap-btn-big ncap-btn-green" href="JavaScript:void(0);"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script>
$(function(){
    // 编辑分类时清除分类信息
    $('.edit_gcategory').click(function(){
        $('input[name="class_id"]').val('');
        $('input[name="class_name"]').val('');
    });
    //自动加载滚动条
    $('#spec_div').perfectScrollbar();
    $('#brand_div').perfectScrollbar();

    var i = 0;
    var ul_attr = '<li>' +
        '<label style="width: 36px;"></label>' +
        '<label title="<?php echo $lang['nc_sort'];?>"><input type="text" class="w30" name="at_value[key][sort]" value="0" /></label>' +
        '<label><input type="text" class="w150" name="at_value[key][name]" value="" placeholder="<?php echo $lang['type_add_attr_name'];?>"/></label>' +
        '<label><input type="text" class="w300" name="at_value[key][value]" value="" placeholder="<?php echo $lang['type_add_attr_value'];?>"/></label>' +
        '<label><?php echo $lang['nc_display'];?>&nbsp;<input type="checkbox" checked="checked" name="at_value[key][show]" value="1" /></label>' +
        '<label><a onclick="remove_attr($(this));" class="ncap-btn ncap-btn-red" href="JavaScript:void(0);"><?php echo $lang['type_add_remove'];?></a></label>' +
        '</li>';
    $("#add_type").click(function(){
        $('#ul_attr > a:last').before(ul_attr.replace(/key/g, 's_' + i));
        i++;
    });

    // 添加自定义
    var j = 0;
    var ul_custom = '<li style="width: 47%;">' +
        '<label><input type="text" class="w150" name="custom_new[key]" value="" placeholder="<?php echo $lang['type_add_attr_name'];?>" /></label>' +
        '<label><a onclick="remove_custom($(this));" class="ncap-btn ncap-btn-red" href="JavaScript:void(0);"><?php echo $lang['type_add_remove'];?></a></label>' +
        '</li>';
    $("#add_custom").click(function(){
        $('#ul_custom > a:last').before(ul_custom.replace(/key/g, j));
        j++;
    });
    

    //表单验证
    $('#type_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            t_mane: {
                required : true,
                maxlength: 20,
                minlength: 1
            },
            t_sort: {
                required : true,
                digits   : true
            }
        },
        messages : {
            t_mane : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['type_add_name_no_null'];?>',
                maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['type_add_name_max'];?>',
                minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['type_add_name_max'];?>'
            },
            t_sort: {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['type_add_sort_no_null'];?>',
                digits : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['type_add_sort_no_digits'];?>'
            }
        }
    });

    //按钮先执行验证再提交表单
    $("#submitBtn").click(function(){
        spec_check();
        if($("#type_form").valid()){
            $("#type_form").submit();
        }
    });

    $('input[nc_type="change_default_spec_value"]').click(function(){
        $(this).parents('div:first').find("input[nc_type='submit_value']").val('ok');
    });

    $('.change_default_submit_value').change(function(){
        $(this).parents('li:first').find("input[nc_type='submit_value']").val('ok');
    });

    $('.brand_change_default_submit_value').change(function(){
        $(this).parents('div:first').find("input[nc_type='submit_value']").val('ok');
    });

    // 所属分类
    $("#gcategory > select").live('change',function(){
        spec_scroll($(this));
        brand_scroll($(this));
    });

    // 规格搜索
    $("#speccategory > select").live('change',function(){
        spec_scroll($(this));
    });
    // 品牌搜索
    $("#brandcategory > select").live('change',function(){
        brand_scroll($(this));
    });
    
    // 规格隐藏未选项
    $('a[nctype="spec_hide"]').live('click',function(){
        checked_hide('spec');
    });
    // 规格全部显示
    $('a[nctype="spec_show"]').live('click',function(){
        checked_show('spec');
    });
    // 品牌隐藏未选项
    $('a[nctype="brand_hide"]').live('click',function(){
        checked_hide('brand');
    });
    // 品牌全部显示
    $('a[nctype="brand_show"]').live('click',function(){
        checked_show('brand');
    });
});
var specScroll = 0;
function spec_scroll(o){
    var id = o.val();
    if(!$('#spec_dt_'+id).is('dt')){
        return false;
    }
    $('#spec_div').scrollTop(-specScroll);
    var sp_top = $('#spec_dt_'+id).offset().top;
    var div_top = $('#spec_div').offset().top;
    $('#spec_div').scrollTop(sp_top-div_top);
    specScroll = sp_top-div_top;
}

var brandScroll = 0;
function brand_scroll(o){
    var id = o.val();
    if(!$('#brand_dt_'+id).is('dt')){
        return false;
    }
    $('#brand_div').scrollTop(-brandScroll);
    var sp_top = $('#brand_dt_'+id).offset().top;
    var div_top = $('#brand_div').offset().top;
    $('#brand_div').scrollTop(sp_top-div_top);
    brandScroll = sp_top-div_top;
}

//隐藏未选项
function checked_show(str) {
    $('#'+str+'_div').find('dt').show().end().find('label').show();
    $('#'+str+'_div').find('dl').show();
    $('a[nctype="'+str+'_show"]').attr('nctype',str+'_hide').html('<?php echo $lang['type_common_checked_hide'];?>');
    $('#'+str+'_div').perfectScrollbar('destroy').perfectScrollbar();
}

// 显示全部选项
function checked_hide(str) {
    $('#'+str+'_div').find('dt').hide();
    $('#'+str+'_div').find('input[type="checkbox"]').parents('label').hide();
    $('#'+str+'_div').find('input[type="checkbox"]:checked').parents('label').show();
    $('#'+str+'_div').find('dl').each(function(){
        if ($(this).find('input[type="checkbox"]:checked').length == 0 ) $(this).hide();
    });
    $('a[nctype="'+str+'_hide"]').attr('nctype',str+'_show').html('<?php echo $lang['type_common_checked_show'];?>');
    $('#'+str+'_div').perfectScrollbar('destroy').perfectScrollbar();
}

function spec_check(){
    var id='';
    $('input[nc_type="change_default_spec_value"]:checked').each(function(){
        if(!isNaN($(this).val())){
            id += $(this).val();
        }
    });
    if(id != ''){
        $('#spec_checkbox').val('ok');
    }else{
        $('#spec_checkbox').val('');
    }
}

function remove_attr(o){
    o.parents('li:first').remove();
}

function remove_custom(o){
    o.parents('li:first').remove();
}
// 所属分类
gcategoryInit('gcategory');
// 规格搜索
gcategoryInit('speccategory');
// 品牌搜索
gcategoryInit('brandcategory');

</script> 
