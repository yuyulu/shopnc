<div class="eject_con">
  <div id="warning" class="alert alert-error"></div>
  <form method="post" target="_parent" action="index.php?act=store_supplier&op=sup_save" id="apply_form">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="sup_id" value="<?php echo $output['sup_info']['sup_id']; ?>" />
    <dl>
      <dt><i class="required">*</i>供货商名称：</dt>
      <dd>
        <input type="text" class="text w150" name="sup_name" maxlength="50" value="<?php echo $output['sup_info']['sup_name']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>联系人：</dt>
      <dd>
        <input type="text" class="text w150" maxlength="30" name="sup_man" maxlength="50" value="<?php echo $output['sup_info']['sup_man']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>联系电话：</dt>
      <dd>
        <input type="text" class="text w150" maxlength="30" name="sup_phone" maxlength="50" value="<?php echo $output['sup_info']['sup_phone']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>备注信息：</dt>
      <dd>
        <textarea name="sup_desc" class="w250" maxlength="200"><?php echo $output['sup_info']['sup_desc'];?></textarea>
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_submit'];?>"/></label>
    </div>
  </form>
</div>
<script>
$(function(){
    $('#apply_form').validate({
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
               $('#warning').show();
        },
    	submitHandler:function(form){
    		ajaxpost('apply_form') 
    	},
        rules : {
            sup_name : {
                required : true,
                rangelength: [0,100]
            }	
        },
        messages : {
            sup_name : {
                required : '<i class="icon-exclamation-sign"></i>供货商名称不能为空'
            }
        }
    });
});

</script> 
