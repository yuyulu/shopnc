<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=member&op=member" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['member_index_manage']?> - <?php echo $lang['nc_new']?>会员</h3>
        <h5><?php echo $lang['member_shop_manage_subhead']?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>可从管理平台手动添加一名新会员，并填写相关信息。</li>
      <li>标识“*”的选项为必填项，其余为选填项。</li>
      <li>新增会员后可从会员列表中找到该条数据，并再次进行编辑操作，但该会员名称不可变。</li>
    </ul>
  </div>
  <form id="user_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="member_name"><em>*</em><?php echo $lang['member_index_name']?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="member_name" id="member_name" class="input-txt">
          <span class="err"></span>
          <p class="notic">3-15位字符，可由中文、英文、数字及“_”、“-”组成。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="member_passwd"><em>*</em><?php echo $lang['member_edit_password']?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="member_passwd" name="member_passwd" class="input-txt">
          <span class="err"></span>
          <p class="notic">6-20位字符，可由英文、数字及标点符号组成。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="member_email"><em>*</em><?php echo $lang['member_index_email']?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" id="member_email" name="member_email" class="input-txt">
          <span class="err"></span>
          <p class="notic">请输入常用的邮箱，将用来找回密码、接受订单通知等。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="member_truename"><?php echo $lang['member_index_true_name']?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" id="member_truename" name="member_truename" class="input-txt">
          <span class="err"></span> </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label> <?php echo $lang['member_edit_sex']?></label>
        </dt>
        <dd class="opt">
          <label>
            <input type="radio" checked="checked" value="0" name="member_sex">
            <?php echo $lang['member_edit_secret']?></label>
          <label>
            <input type="radio" value="1" name="member_sex">
            <?php echo $lang['member_edit_male']?></label>
          <label>
            <input type="radio" value="2" name="member_sex">
            <?php echo $lang['member_edit_female']?> </label>
          <span class="err"></span></dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="member_qq">QQ</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" id="member_qq" name="member_qq" class="input-txt">
          <span class="err"></span></dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="member_ww"><?php echo $lang['member_edit_wangwang']?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" id="member_ww" name="member_ww" class="input-txt">
          <span class="err"></span></dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['member_edit_pic']?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="type-file-box">
            <input class="type-file-file" id="_pic" name="_pic" type="file" size="30" hidefocus="true" title="点击按钮选择文件并提交表单后上传生效">
            <input type="text" name="member_avatar" id="member_avatar" class="type-file-text" />
            <input type="button" name="button" id="button" value="选择上传..." class="type-file-button" />
            </span></div>
          <p class="notic">默认会员头像图片请使用100*100像素jpg/gif/png格式的图片。</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
//裁剪图片后返回接收函数
function call_back(picname){
	$('#member_avatar').val(picname);
	$('#view_img').attr('src','<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR;?>/'+picname)
	   .attr('onmouseover','toolTip("<img src=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR;?>/'+picname+'>")');
}
$(function(){
	$('input[class="type-file-file"]').change(uploadChange);
	function uploadChange(){
		var filepath=$(this).val();
		var extStart=filepath.lastIndexOf(".");
		var ext=filepath.substring(extStart,filepath.length).toUpperCase();
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			alert("file type error");
			$(this).attr('value','');
			return false;
		}
		if ($(this).val() == '') return false;
		ajaxFileUpload();
	}	
	function ajaxFileUpload()
	{
		$.ajaxFileUpload
		(
			{
				url : '<?php echo ADMIN_SITE_URL?>/index.php?act=common&op=pic_upload&form_submit=ok&uploadpath=<?php echo ATTACH_AVATAR;?>',
				secureuri:false,
				fileElementId:'_pic',
				dataType: 'json',
				success: function (data, status)
				{
					if (data.status == 1){
						ajax_form('cutpic','<?php echo $lang['nc_cut'];?>','<?php echo ADMIN_SITE_URL?>/index.php?act=common&op=pic_cut&type=member&x=120&y=120&resize=1&ratio=1&url='+data.url,690);
					}else{
						alert(data.msg);
					}
					$('input[class="type-file-file"]').bind('change',uploadChange);
				},
				error: function (data, status, e)
				{
					alert('上传失败');
					$('input[class="type-file-file"]').bind('change',uploadChange);
				}
			}
		)
	};
	//按钮先执行验证再提交表单
	$("#submitBtn").click(function(){
    if($("#user_form").valid()){
     $("#user_form").submit();
	}
	});
    $('#user_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
			member_name: {
				required : true,
				minlength: 3,
				maxlength: 20,
				remote   : {
                    url :'index.php?act=member&op=ajax&branch=check_user_name',
                    type:'get',
                    data:{
                        user_name : function(){
                            return $('#member_name').val();
                        },
                        member_id : ''
                    }
                }
			},
            member_passwd: {
				required : true,
                maxlength: 20,
                minlength: 6
            },
            member_email   : {
                required : true,
                email : true,
				remote   : {
                    url :'index.php?act=member&op=ajax&branch=check_email',
                    type:'get',
                    data:{
                        user_name : function(){
                            return $('#member_email').val();
                        },
                        member_id : '<?php echo $output['member_array']['member_id'];?>'
                    }
                }
            },
			member_qq : {
				digits: true,
				minlength: 5,
				maxlength: 11
			}
        },
        messages : {
			member_name: {
				required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_add_name_null']?>',
				maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_add_name_length']?>',
				minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_add_name_length']?>',
				remote   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_add_name_exists']?>'
			},
            member_passwd : {
				required : '<i class="fa fa-exclamation-circle"></i><?php echo '密码不能为空'; ?>',
                maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_password_tip']?>',
                minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_password_tip']?>'
            },
            member_email  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_email_null']?>',
                email   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_valid_email']?>',
				remote : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_email_exists']?>'
            },
			member_qq : {
				digits: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_qq_wrong']?>',
				minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_qq_wrong']?>',
				maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_qq_wrong']?>'
			}
        }
    });
});
</script> 
