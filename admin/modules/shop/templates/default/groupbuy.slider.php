<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['groupbuy_index_manage'];?></h3>
        <h5><?php echo $lang['groupbuy_index_manage_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <?php foreach($output['menu'] as $menu) { if($menu['menu_type'] == 'text') { ?>
        <li><a href="JavaScript:void(0);" class="current"><?php echo $menu['menu_name'];?></a></li>
        <?php }  else { ?>
        <li><a href="<?php echo $menu['menu_url'];?>"><?php echo $menu['menu_name'];?></a></li>
        <?php } }  ?>
      </ul>
    </div>
  </div>

  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>该组幻灯片滚动图片应用于抢购聚合页上部使用，最多可上传4张图片。</li>
      <li>图片要求使用宽度为970像素，高度为300像素jpg/gif/png格式的图片。</li>
      <li>上传图片后请添加格式为“http://网址...”链接地址，设定后将在显示页面中点击幻灯片将以另打开窗口的形式跳转到指定网址。</li>
      <li>清空操作将删除聚合页上的滚动图片，请注意操作</li>
    </ul>
  </div>
  <form id="live_form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label>滚动图片1</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_LIVE.DS.$output['list_setting']['live_pic1'];?>"/><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_LIVE.DS.$output['list_setting']['live_pic1'];?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input name="live_pic1" type="file" class="type-file-file" id="live_pic1" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            <input type='text' name='textfield1' id='textfield1' class='type-file-text' />
            <input type='button' name='button1' id='button1' value='选择上传...' class='type-file-button' />
            </span></div>
          <label title="请输入图片要跳转的链接地址" class="ml5"><i class="fa fa-link"></i>
            <input class="input-txt ml5" type="text" name="live_link1"  value="<?php echo $output['list_setting']['live_link1']?>" placeholder="请输入图片要跳转的链接地址" />
          </label><span class="err"></span> <label title="请输入图片要跳转的链接地址" class="ml5">
            <input class="input-txt m15" type="text" name="live_color1"  value="<?php echo $output['list_setting']['live_color1']?>" placeholder="请输入幻灯背景颜色" />
          </label>
          <p class="notic">请使用宽度970像素，高度300像素的jpg/gif/png格式图片作为幻灯片banner上传，<br/>
            如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>滚动图片2</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_LIVE.DS.$output['list_setting']['live_pic2'];?>"/><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_LIVE.DS.$output['list_setting']['live_pic2'];?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input name="live_pic2" type="file" class="type-file-file" id="live_pic2" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            <input type='text' name='textfield2' id='textfield2' class='type-file-text' />
            <input type='button' name='button2' id='button2' value='选择上传...' class='type-file-button' />
            </span></div>
          <label title="请输入图片要跳转的链接地址" class="ml5"><i class="fa fa-link"></i>
            <input class="input-txt ml5" type="text" name="live_link2"  value="<?php echo $output['list_setting']['live_link2']?>" placeholder="请输入图片要跳转的链接地址" />
          </label><span class="err"></span><input class="input-txt m15" type="text" name="live_color2"  value="<?php echo $output['list_setting']['live_color2']?>" placeholder="请输入幻灯背景颜色" />
          <p class="notic">请使用宽度970像素，高度300像素的jpg/gif/png格式图片作为幻灯片banner上传，<br/>
            如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>滚动图片3</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_LIVE.DS.$output['list_setting']['live_pic3'];?>"/><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_LIVE.DS.$output['list_setting']['live_pic3'];?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input name="live_pic3" type="file" class="type-file-file" id="live_pic3" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            <input type='text' name='textfield3' id='textfield3' class='type-file-text' />
            <input type='button' name='button3' id='button3' value='选择上传...' class='type-file-button' />
            </span></div>
          <label title="请输入图片要跳转的链接地址" class="ml5"><i class="fa fa-link"></i>
            <input class="input-txt ml5" type="text" name="live_link3"  value="<?php echo $output['list_setting']['live_link3']?>" placeholder="请输入图片要跳转的链接地址" />
          </label><span class="err"></span><input class="input-txt m15" type="text" name="live_color3"  value="<?php echo $output['list_setting']['live_color3']?>" placeholder="请输入幻灯背景颜色" />
          <p class="notic">请使用宽度970像素，高度300像素的jpg/gif/png格式图片作为幻灯片banner上传，<br/>
            如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>滚动图片4</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_LIVE.DS.$output['list_setting']['live_pic4'];?>"/><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_LIVE.DS.$output['list_setting']['live_pic4'];?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input name="live_pic4" type="file" class="type-file-file" id="live_pic4" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
            <input type='text' name='textfield4' id='textfield4' class='type-file-text' />
            <input type='button' name='button4' id='button4' value='选择上传...' class='type-file-button' />
            </span></div>
          <label title="请输入图片要跳转的链接地址" class="ml5"><i class="fa fa-link"></i>
            <input class="input-txt ml5" type="text" name="live_link4"  value="<?php echo $output['list_setting']['live_link4']?>" placeholder="请输入图片要跳转的链接地址" />
          </label><span class="err"></span><input class="input-txt m15" type="text" name="live_color4"  value="<?php echo $output['list_setting']['live_color4']?>" placeholder="请输入幻灯背景颜色" />
          <p class="notic">请使用宽度970像素，高度300像素的jpg/gif/png格式图片作为幻灯片banner上传，<br/>
            如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>

      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a> <a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-red ml10" id="clearBtn">清空数据</a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script>
//按钮先执行验证再提交表单
$(function(){
    // 图片js
    $("#live_pic1").change(function(){$("#textfield1").val($("#live_pic1").val());});
    $("#live_pic2").change(function(){$("#textfield2").val($("#live_pic2").val());});
    $("#live_pic3").change(function(){$("#textfield3").val($("#live_pic3").val());});
    $("#live_pic4").change(function(){$("#textfield4").val($("#live_pic4").val());});
	$('.nyroModal').nyroModal();
    $('#live_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parents('dd').children('span.err');
            error_td.append(error);
        },
        success: function(label){
            label.addClass('valid');
        },
        rules : {
            live_link1: {
                url : true
            },
            live_link2:{
                url : true
            },
            live_link3:{
                url : true
            },
            live_link4:{
                url : true
            }
        },
        messages : {
            live_link1: {
                url : '<i class="fa fa-exclamation-circle"></i>链接地址格式不正确'
            },
            live_link2:{
                url : '<i class="fa fa-exclamation-circle"></i>链接地址格式不正确'
            },
            live_link3:{
                url : '<i class="fa fa-exclamation-circle"></i>链接地址格式不正确'
            },
            live_link4:{
                url : '<i class="fa fa-exclamation-circle"></i>链接地址格式不正确'
            }
        }
    });

    $('#clearBtn').click(function(){
        if (!confirm('确认清空虚拟抢购幻灯片设置？')) {
            return false;
        }
        $.ajax({
            type:'get',
            url:'index.php?act=vr_groupbuy&op=slider_clear',
            dataType:'json',
            success:function(result){
                if(result.result){
                    alert('清空成功');
                    location.reload();
                }
            }
        });
    });

    $("#submitBtn").click(function(){
        $("#live_form").submit();
    });
});
</script>
