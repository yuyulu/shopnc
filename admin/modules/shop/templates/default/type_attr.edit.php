<?php defined('In33hao') or exit('Access Invalid!');?>


  <form id="attr_form" method="post" class="ncap-form-dialog" action="<?php echo urlAdminShop('type', 'attr_edit');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="attr_id" value="<?php echo $output['attr_info']['attr_id']?>" />
    <input type="hidden" name="type_id" value="<?php echo $output['attr_info']['type_id']?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="attr_name"><em>*</em><?php echo $lang['type_add_attr_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="txt" name="attr_name" id="attr_name" value="<?php echo $output['attr_info']['attr_name'];?>" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['type_attr_edit_name_desc'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="attr_sort"><em>*</em><?php echo $lang['nc_sort'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="txt" name="attr_sort" id="attr_sort" value="<?php echo $output['attr_info']['attr_sort'];?>" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['type_attr_edit_sort_desc'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['type_edit_type_attr_is_show'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="attr_show1" class="cb-enable <?php if($output['attr_info']['attr_show'] == '1'){?>selected<?php }?>"><?php echo $lang['nc_yes'];?></label>
            <label for="attr_show0" class="cb-disable <?php if($output['attr_info']['attr_show'] == '0'){?>selected<?php }?>"><?php echo $lang['nc_no'];?></label>
            <input id="attr_show1" name="attr_show" <?php if($output['attr_info']['attr_show'] == '1'){?>checked="checked"<?php }?> value="1" type="radio" />
            <input id="attr_show0" name="attr_show" <?php if($output['attr_info']['attr_show'] == '0'){?>checked="checked"<?php }?> value="0" type="radio" />
          </div>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
    </div>
    <div class="ncap-form-all">
      <div class="title">
        <h3>编辑属性可选值</h3>
      </div>
      <dl class="row">
        <dd class="opt">
          <ul class="ncap-ajax-add" id="attr_model">
            <?php if(is_array($output['attr_value_list']) && !empty($output['attr_value_list'])) {?>
            <?php foreach($output['attr_value_list'] as $val) {?>
            <li>
              <input type="hidden" nc_type="submit_value" name='attr_value[<?php echo $val['attr_value_id'];?>][form_submit]' value='' />
              <label>删除：
                <input type="checkbox" name="attr_del[<?php echo $val['attr_value_id'];?>]" value="<?php echo $val['attr_value_id'];?>" />
              </label>
              <label class="ml20"><?php echo $lang['nc_sort'];?>：
                <input type="text" nc_type="change_default_submit_value" name="attr_value[<?php echo $val['attr_value_id'];?>][sort]" value="<?php echo $val['attr_value_sort'];?>" class="w30"/>
              </label>
              <label class="ml20">可选值：
                <input type="text" nc_type="change_default_submit_value" name="attr_value[<?php echo $val['attr_value_id'];?>][name]" value="<?php echo $val['attr_value_name'];?>"  class="w150"/>
              </label>
              <label></label>
            </li>
            <?php }?>
            <?php }else{?>
            <div class="no-data"><?php echo $lang['spec_edit_spec_value_null'];?></div>
            <?php }?>
            <a class="ncap-btn" id="add_attr" href="JavaScript:void(0);"><i class="fa fa-plus"></i>添加一个属性可选值</a>
          </ul>
        </dd>
      </dl>
      <div class="bot"><a id="attrSubmitBtn" class="ncap-btn-big ncap-btn-green" href="JavaScript:void(0);"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>

<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/admin.js"></script>
<script type="text/javascript">
$(function(){
//自动加载滚动条
    $('.ncap-form-dialog').perfectScrollbar();
	
    var attr_i=0;
	var attr_model = '<li>'+
		'<label style="width: 64px;"></label><label class="ml20"><?php echo $lang['nc_sort'];?>：<input type="text" name="attr_value[key][sort]" value="0" class="w30"/></label>'+
		'<label class="ml20">可选值：<input type="text" name="attr_value[key][name]" value="" class="w150"/></label>'+
		'<label class="ml10"><a onclick="remove_tr($(this));" href="JavaScript:void(0);" class="ncap-btn ncap-btn-red"><?php echo $lang['nc_del'];?></a></label>'+
	'</li>';
	$("#add_attr").click(function(){
		$('#attr_model > li:last').after(attr_model.replace(/key/g,'s_'+attr_i));
		<?php if(empty($output['attr_value_list'])) {?>
		$('.no_data').hide();
		<?php }?>
		attr_i++;
	});

	//表单验证
    $('#attr_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },

        rules : {
        	attr_name: {
        		required : true,
                maxlength: 10,
                minlength: 1
            },
            attr_sort: {
				required : true,
				digits	 : true
            }
        },
        messages : {
        	attr_name : {
            	required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['type_edit_type_attr_name_no_null'];?>',
                maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['type_edit_type_attr_name_max'];?>',
                minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['type_edit_type_attr_name_max'];?>'
            },
            attr_sort: {
				required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['type_edit_type_attr_sort_no_null'];?>',
				digits   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['type_edit_type_attr_sort_no_digits'];?>'
            }
        }
    });

    //按钮先执行验证再提交表单
    $("#attrSubmitBtn").click(function(){
        if($("#attr_form").valid()){
            ajaxpost('attr_form', '', '', 'onerror');
    	}
    });

    $("input[nc_type='change_default_submit_value']").change(function(){
    	$(this).parents('li:first').find("input[nc_type='submit_value']").val('ok');
    });
});

function remove_tr(o){
	o.parents('li:first').remove();
}
</script> 