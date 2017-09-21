<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=spec&op=spec" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_spec_manage'];?> - <?php echo $lang['nc_new'];?></h3>
        <h5><?php echo $lang['nc_spec_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="spec_form" method="post" enctype="multipart/form-data">
    <input type="hidden" value="ok" name="form_submit" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="s_name"><em>*</em><?php echo $lang['spec_index_spec_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="input-txt" name="s_name" id="s_name" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['spec_index_spec_name_desc'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label class="" for="s_sort"><?php echo $lang['spec_common_belong_class'];?></label>
        </dt>
        <dd class="opt">
          <div id="gcategory">
            <input type="hidden" value="" class="mls_id" name="class_id" />
            <input type="hidden" value="" class="mls_name" name="class_name" />
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
          </div>
          <p class="notic"><?php echo $lang['spec_common_belong_class_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="s_sort"><em>*</em><?php echo $lang['nc_sort'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="input-txt" name="s_sort" id="s_sort" value="0" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['spec_index_spec_sort_desc'];?></p>
        </dd>
      </dl>
      <div class="bot"><a id="submitBtn" class="ncap-btn-big ncap-btn-green" href="JavaScript:void(0);"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript">
$(function(){
	//表单验证
    $('#spec_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },

        rules : {
        	s_name: {
        		required : true,
                maxlength: 10,
                minlength: 1
            },
            s_sort: {
				required : true,
				digits	 : true
            }
        },
        messages : {
        	s_name : {
            	required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['spec_add_name_no_null'];?>',
                maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['spec_add_name_max'];?>',
                minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['spec_add_name_max'];?>'
            },
            s_sort: {
				required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['spec_add_sort_no_null'];?>',
				digits   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['spec_add_sort_no_digits'];?>'
            }
        }
    });

    //按钮先执行验证再提交表单
    $("#submitBtn").click(function(){
        if($("#spec_form").valid()){
        	$("#spec_form").submit();
    	}
    });
});

gcategoryInit('gcategory');
</script> 
