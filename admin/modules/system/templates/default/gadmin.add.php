<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=admin&op=gadmin" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_limit_manage'];?> - <?php echo $lang['nc_add'];?>权限组</h3>
        <h5><?php echo $lang['nc_limit_manage_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <li></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>可添加一个权限组，并为其命名，方便添加管理员时使用。</li>
      <li>可在标题处全选所有功能或根据功能模块逐一选择操作权限，提交保存后生效。</li>
    </ul>
  </div>
  <form id="add_form" method="post" style="margin-bottom: 50px;">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="admin_name"><em>*</em><?php echo $lang['gadmin_name'];?></label></dt>
        <dd class="opt">
          <input type="text" id="gname" maxlength="40" name="gname" class="input-txt">
          <span class="err"></span>
          <p class="notic">为权限组设置特定名称，便于添加管理员时选择使用。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['admin_set_limt'];?></dt>
        <dd class="opt">
          <input id="limitAll" value="1" type="checkbox" class="checkbox">
          全部操作
          <p class="notic">勾选后选中全部操作功能，可根据需要从设置详情中进行分组设置。</p>
        </dd>
      </dl>
    </div>
    <div class="ncap-form-all">
      <div class="title">
        <h3>权限操作设置详情</h3>
      </div>
      <?php foreach((array)$output['limit'] as $key => $value) { ?>
      <dl class="row">
        <dt class="tit">
          <span><input class="checkbox" type="checkbox" nctype="modulesAll">
          <?php echo $value['name'];?>模块功能</span></dt>
        <dd class="opt nobg nopd nobd nobs">
          <?php foreach ($value['child'] as $ke => $val) {?>
          <div class="ncap-account-container">
            <h4>
              <input class="checkbox" type="checkbox" nctype="groupAll">
              <?php echo $val['name'];?>操作</h4>
            <ul class="ncap-account-container-list">
              <?php foreach ($val['child'] as $k => $v) {?>
              <?php if ($key == 'system' && $k == 'admin') {continue;}?>
              <li>
                <input class="checkbox" type="checkbox" value="<?php echo $k;?>" name="permission[<?php echo $key?>][]">
                <?php echo $v;?></li>
              <?php }?>
            </ul>
          </div>
          <?php } ?>
        </dd>
      </dl>
      <?php } ?>
      <div class="fix-bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
function selectLimit(name){
    if($('#'+name).attr('checked')) {
        $('.'+name).attr('checked',true);
    }else {
        $('.'+name).attr('checked',false);
    }
}
$(document).ready(function(){
	//按钮先执行验证再提交表单
	$("#submitBtn").click(function(){
	    if($("#add_form").valid()){
	     $("#add_form").submit();
		}
	});

    // 全选
    $('#limitAll').click(function(){
    	$('input[type="checkbox"]').attr('checked',$(this).attr('checked') == 'checked');
    });
    // 功能模块
    $('input[nctype="modulesAll"]').click(function(){
        $(this).parents('dt:first').next().find('input[type="checkbox"]').attr('checked',$(this).attr('checked') == 'checked');
    });
    // 功能组
    $('input[nctype="groupAll"]').click(function(){
        $(this).parents('h4:first').next().find('input[type="checkbox"]').attr('checked',$(this).attr('checked') == 'checked');
    });
	
	$("#add_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            gname : {
                required : true,
				remote	: {
                    url :'index.php?act=admin&op=ajax&branch=check_gadmin_name',
                    type:'get',
                    data:{
                    	gname : function(){
                            return $('#gname').val();
                        }
                    }
                }
            }
        },
        messages : {
            gname : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['nc_none_input'];?>',
                remote   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_add_admin_not_exists'];?>'
            }
        }
	});
});
</script>