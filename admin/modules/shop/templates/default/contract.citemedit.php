<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=contract&op=citemlist" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>消费者保障服务 - 编辑服务项目</h3>
        <h5>消费者保障服务查看与管理</h5>
      </div>
    </div>
  </div>
  <form id="item_form" method="post" name="item_form" enctype="multipart/form-data">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="item_name"><em>*</em>项目名称</label>
        </dt>
        <dd class="opt">
            <input type="text" value="<?php echo $output['item_info']['cti_name'];?>" name="item_name" id="item_name" class="input-txt" />
            <span class="err"></span>
            <p class="notic">项目名称不能为空且不能大于50个字符</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
            <label for="item_cost"><em>*</em>保证金</label>
        </dt>
        <dd class="opt">
            <input type="text" value="<?php echo $output['item_info']['cti_cost'];?>" name="item_cost" id="item_cost" class="input-txt" />
            <span class="err"></span>
            <p class="notic">保证金不能为空且必须为数字</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
            <label><em>*</em>项目图标</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show">
            <span class="show">
                <a class="nyroModal" rel="gal" href="<?php echo $output['item_info']['cti_icon_url'];?>">
                    <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo $output['item_info']['cti_icon_url'];?>>')" onMouseOut="toolTip()"/></i>
                </a>
            </span>
            <span class="type-file-box">
                <input type="text" name="textfield" id="textfield1" class="type-file-text"/>
                <input type="button" name="button" id="button1" value="选择上传..." class="type-file-button" />
                <input class="type-file-file" id="item_icon" name="item_icon" type="file" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效" />
            </span>
          </div>
          <span class="err"></span>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
            <label for="item_desc"><em>*</em>项目描述</label>
        </dt>
        <dd class="opt">
            <textarea id="item_desc" name="item_desc" class="w300"><?php echo $output['item_info']['cti_describe'];?></textarea>
            <span class="err"></span>
            <p class="notic">项目描述不能为空且小于200个字符</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
            <label for="item_descurl">说明文章链接地址</label>
        </dt>
        <dd class="opt">
            <input type="text" value="<?php echo $output['item_info']['cti_descurl'];?>" name="item_descurl" id="item_descurl" class="input-txt" />
            <span class="err"></span>
            <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
            <label for="item_sort"><em>*</em>排序</label>
        </dt>
        <dd class="opt">
            <input type="text" value="<?php echo $output['item_info']['cti_sort'];?>" name="item_sort" id="item_sort" class="input-txt" />
            <span class="err"></span>
            <p class="notic">排序应为大于1的正整数</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>状态</label>
        </dt>
        <dd class="opt">
          <?php foreach ($output['itemstate_arr'] as $k=>$v){ ?>
            <label for="item_state<?php echo $v['sign'];?>"><input type="radio" value="<?php echo $k;?>" id="item_state<?php echo $v['sign'];?>" name="item_state" <?php echo $v['sign'] == $output['item_info']['cti_state']?'checked="checked"':'';?>><?php echo $v['name'];?></label>
          <?php } ?>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script> 
<script>
//按钮先执行验证再提交表单
$(function(){
	$("#submitBtn").click(function(){
        if($("#item_form").valid()){
            $("#item_form").submit();
    	}
	});

	// 模拟默认用户图片上传input type='file'样式
    $("#item_icon").change(function(){
    	   $("#textfield1").val($("#item_icon").val());
    });
    // 上传图片类型
	$('input[class="type-file-file"]').change(function(){
		var filepath=$(this).val();
		var extStart=filepath.lastIndexOf(".");
		var ext=filepath.substring(extStart,filepath.length).toUpperCase();
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
            $("#textfield1").attr('value','');
            $(this).attr('value','');
			showDialog("图片限于png,gif,jpeg,jpg格式");
			return false;
		}
	});

    // 点击查看图片
	$('.nyroModal').nyroModal();
});
$(document).ready(function(){
	$('#item_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.closest('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            item_name : {
        		required : true,
                rangelength : [1,50]
            },
            item_cost : {
            	required : true,
                number : true,
                min:1
            },
            <?php if(!$output['item_info']['cti_icon']){?>
            textfield : {
                required : true
            },
            <?php }?>
            item_desc : {
            	required : true,
                rangelength : [1,200]
            },
            item_sort : {
            	required : true,
                digits : true,
                min:1
            }
        },
        messages : {
            item_name : {
                required : '<i class="fa fa-exclamation-circle"></i>项目名称不能为空且不能大于50个字符',
                rangelength : '<i class="fa fa-exclamation-circle"></i>项目名称不能为空且不能大于50个字符'
            },
            item_cost : {
                required : '<i class="fa fa-exclamation-circle"></i>保证金应为大于1的数字',
                number : '<i class="fa fa-exclamation-circle"></i>保证金应为大于1的数字',
                min : '<i class="fa fa-exclamation-circle"></i>保证金应为大于1的数字'
            },
            <?php if(!$output['item_info']['cti_icon']){?>
            textfield : {
                required : '<i class="fa fa-exclamation-circle"></i>请添加项目图标'
            },
            <?php }?>
            item_desc : {
                required : '<i class="fa fa-exclamation-circle"></i>项目描述不能为空且小于200个字符',
                rangelength : '<i class="fa fa-exclamation-circle"></i>项目描述不能为空且小于200个字符'
            },
            item_sort : {
            	required : '<i class="fa fa-exclamation-circle"></i>排序应为大于1的正整数',
                digits : '<i class="fa fa-exclamation-circle"></i>排序应为大于1的正整数',
                min : '<i class="fa fa-exclamation-circle"></i>排序应为大于1的正整数'
            }
        }
    });
});
</script>