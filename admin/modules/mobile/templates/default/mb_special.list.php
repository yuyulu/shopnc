<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page"> 
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>模板设置</h3>
        <h5>手机客户端首页/专题页模板设置</h5>
      </div>
      <ul class="tab-base nc-row">
        <?php foreach($output['menu'] as $menu) {  if($menu['menu_key'] == $output['menu_key']) { ?>
        <li><a href="JavaScript:void(0);" class="current"><?php echo $menu['menu_name'];?></a></li>
        <?php }  else { ?>
        <li><a href="<?php echo $menu['menu_url'];?>" ><?php echo $menu['menu_name'];?></a></li>
        <?php  } }  ?>
      </ul>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>点击添加专题按钮可以添加新的专题，专题描述可以点击后直接修改</li>
      <li>点击编辑按钮对专题内容进行修改</li>
      <li>点击删除按钮可以删除整个专题</li>
    </ul>
  </div>
  
  <div id="flexigrid"></div>
</div>
<form id="del_form" action="<?php echo urlAdminMobile('mb_special', 'special_del');?>" method="post">
  <input type="hidden" id="del_special_id" name="special_id">
</form>
<div id="dialog_add_mb_special" style="display:none;">
  <form id="add_form" method="post" action="<?php echo urlAdminMobile('mb_special', 'special_save');?>">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="special_desc"><em>*</em>专题描述</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="special_desc" class="input-txt">
          <span class="err"></span>
          <p class="notic">专题描述，最多20个字符</p>
        </dd>
      </dl>
      <div class="bot"><a id="submit" href="javascript:void(0)" class="ncap-btn-big ncap-btn-green"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js"></script> 
<script type="text/javascript">
function update_flex(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=mb_special&op=get_special_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '专题编号', name : 'special_id', width : 150, sortable : false, align: 'center'},
            {display: '专题描述', name : 'special_desc',  width : 360, sortable : false, align: 'left'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增专题', name : 'add', bclass : 'add', title : '新增专题', onpress : fg_operation_add }
        ],
        usepager: true,
        rp: 15,
        title: '专题列表'
    });
}

//编辑专题描述
$('span[nc_type="edit_special_desc"]').live('click', function() {
    if($(this).attr("edit")>0) return;
    $(this).inline_edit({act: 'mb_special',op: 'update_special_desc'});
    $(this).attr("edit",1);
});
//添加专题
function fg_operation_add() {
    $('#dialog_add_mb_special').nc_show_dialog({title: '新增专题'});
}
//删除专题
function fg_operation_del(special_id){
    if(confirm('确认删除?')) {
        $('#del_special_id').val(special_id);
        $('#del_form').submit();
    }
}
$(function(){
    update_flex();
        //提交
        $("#submit").click(function(){
            $("#add_form").submit();
        });

        $('#add_form').validate({
            errorPlacement: function(error, element){
                var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
            },
            rules : {
                special_desc : {
                    required : true,
                    maxlength : 20
                }
            },
            messages : {
                special_desc : {
                    required : "<i class='fa fa-exclamation-circle'></i>专题描述不能为空",
                    maxlength : "<i class='fa fa-exclamation-circle'></i>专题描述最多20个字"
                }
            }
        });
});
</script> 
