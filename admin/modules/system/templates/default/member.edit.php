<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=member&op=member" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['member_index_manage']?> - <?php echo $lang['nc_edit']?>会员“<?php echo $output['member_array']['member_name'];?>”</h3>
        <h5><?php echo $lang['member_system_manage_subhead']?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>编辑会员选项，会员名不可变，其余内容可重新填写，忽略或留空则保持原有信息数据。</li>
    </ul>
  </div>
  <form id="user_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="member_id" value="<?php echo $output['member_array']['member_id'];?>" />
    <input type="hidden" name="old_member_avatar" value="<?php echo $output['member_array']['member_avatar'];?>" />
    <input type="hidden" name="member_name" value="<?php echo $output['member_array']['member_name'];?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['member_index_name']?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="input-txt" value="<?php echo $output['member_array']['member_name'];?>" readonly />
          <p class="notic">会员用户名不可修改。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="member_passwd"><?php echo $lang['member_edit_password']?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="member_passwd" name="member_passwd" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['member_edit_password_keep']?>。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="member_email"><em>*</em><?php echo $lang['member_index_email']?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['member_array']['member_email'];?>" id="member_email" name="member_email" class="input-txt">
          <span class="err"></span>
          <p class="notic">请输入常用的邮箱，将用来找回密码、接受订单通知等。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="member_truename"><?php echo $lang['member_index_true_name']?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['member_array']['member_truename'];?>" id="member_truename" name="member_truename" class="input-txt">
          <span class="err"></span> </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['member_edit_sex']?></label>
        </dt>
        <dd class="opt">
          <input type="radio" <?php if($output['member_array']['member_sex'] == 0){ ?>checked="checked"<?php } ?> value="0" name="member_sex" id="member_sex0">
          <label for="member_sex0"><?php echo $lang['member_edit_secret']?></label>
          <input type="radio" <?php if($output['member_array']['member_sex'] == 1){ ?>checked="checked"<?php } ?> value="1" name="member_sex" id="member_sex1">
          <label for="member_sex1"><?php echo $lang['member_edit_male']?></label>
          <input type="radio" <?php if($output['member_array']['member_sex'] == 2){ ?>checked="checked"<?php } ?> value="2" name="member_sex" id="member_sex2">
          <label for="member_sex2"><?php echo $lang['member_edit_female']?></label>
          <span class="err"></span> </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label class="member_qq">QQ</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['member_array']['member_qq'];?>" id="member_qq" name="member_qq" class="input-txt">
          <span class="err"></span></dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label class="member_ww"><?php echo $lang['member_edit_wangwang']?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['member_array']['member_ww'];?>" id="member_ww" name="member_ww" class="input-txt">
          <span class="err"></span> </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['member_edit_pic']?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR; ?>/<?php echo $output['member_array']['member_avatar'];?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR; ?>/<?php echo $output['member_array']['member_avatar'];?>>')" id="view_img" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input class="type-file-file" id="_pic" name="_pic" type="file" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            <input type="text" name="member_avatar" id="member_avatar" class="type-file-text" />
            <input type="button" name="button" id="button" value="选择上传..." class="type-file-button" />
            </span></div>
          <span class="err"></span>
          <p class="notic">默认会员头像图片请使用100*100像素jpg/gif/png格式的图片。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['member_edit_allowlogin']; ?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="memberstate_1" class="cb-enable <?php if($output['member_array']['member_state'] == '1'){ ?>selected<?php } ?>" ><span><?php echo $lang['member_edit_allow'];?></span></label>
            <label for="memberstate_2" class="cb-disable <?php if($output['member_array']['member_state'] == '0'){ ?>selected<?php } ?>" ><span><?php echo $lang['member_edit_deny'];?></span></label>
            <input id="memberstate_1" name="memberstate" <?php if($output['member_array']['member_state'] == '1'){ ?>checked="checked"<?php } ?>  value="1" type="radio">
            <input id="memberstate_2" name="memberstate" <?php if($output['member_array']['member_state'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic">如果禁止该项则会员不能登录站点。</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
//裁剪图片后返回接收函数
function call_back(picname){
	$('#member_avatar').val(picname);
	$('#view_img').attr('src','<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR;?>/'+picname+'?'+Math.random())
	   .attr('onmouseover','toolTip("<img src=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR;?>/'+picname+'?'+Math.random()+'>")');
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
						ajax_form('cutpic','<?php echo $lang['nc_cut'];?>','<?php echo ADMIN_SITE_URL?>/index.php?act=common&op=pic_cut&type=member&x=120&y=120&resize=1&ratio=1&filename=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_AVATAR;?>/avatar_<?php echo $_GET['member_id'];?>.jpg&url='+data.url,690);
					}else{
						alert(data.msg);
					}
					$('input[class="type-file-file"]').bind('change',uploadChange);
				},
				error: function (data, status, e)
				{
					alert('上传失败');$('input[class="type-file-file"]').bind('change',uploadChange);
				}
			}
		)
	};
// 点击查看图片
	$('.nyroModal').nyroModal();
	
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
            member_passwd: {
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
            }
        },
        messages : {
            member_passwd : {
                maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_password_tip']?>',
                minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_password_tip']?>'
            },
            member_email  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_email_null']?>',
                email   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_valid_email']?>',
				remote   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_email_exists']?>'
            }
        }
    });
});
</script> 
