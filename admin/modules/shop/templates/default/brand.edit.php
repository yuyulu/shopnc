<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=brand&op=brand" title="返回品牌列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['brand_index_brand'];?> - <?php echo $lang['nc_edit'];?>品牌“<?php echo $output['brand_array']['brand_name']?>”</h3>
        <h5><?php echo $lang['brand_index_brand_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="brand_form" method="post" name="form1" enctype="multipart/form-data">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="brand_id" value="<?php echo $output['brand_array']['brand_id']?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['brand_index_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['brand_array']['brand_name']?>" name="brand_name" id="brand_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>名称首字母</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['brand_array']['brand_initial'];?>" name="brand_initial" id="brand_initial" class="input-txt">
          <span class="err"></span>
          <p class="notic">商家发布商品快捷搜索品牌使用</p>
        </dd>
      </dl>
      <!--<dl class="row">
        <dt class="tit">
          <label>品牌副标题</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['brand_array']['brand_tjstore'];?>" name="brand_tjstore" id="brand_tjstore" class="input-txt">
          <span class="err"></span>
          <p class="notic">品牌的副标题15字内使用</p>
        </dd>
      </dl>-->
      <dl class="row">
        <dt class="tit">
          <label>品牌大图背景URL</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['brand_array']['brand_bgpic'];?>" name="brand_bgpic" id="brand_bgpic" class="input-txt">
          <span class="err"></span>
          <p class="notic">请填写背景图片完整URL大小为1920px*480px</p>
        </dd>
      </dl>
            <!--<dl class="row">
        <dt class="tit">
          <label>品牌小图背景URL</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['brand_array']['brand_xbgpic'];?>" name="brand_xbgpic" id="brand_xbgpic" class="input-txt">
          <span class="err"></span>
          <p class="notic">请填写背景图片完整URL大小为260px*260px</p>
        </dd>
      </dl>-->
      <dl class="row">
        <dt class="tit"><?php echo $lang['brand_index_class'];?></dt>
        <dd class="opt">
          <div id="gcategory">
            <input type="hidden" value="<?php echo $output['brand_array']['class_id']?>" name="class_id" class="mls_id">
            <input type="hidden" value="<?php echo $output['brand_array']['brand_class']?>" name="brand_class" class="mls_name">
            <?php echo $output['brand_array']['brand_class']?>
            <?php if (!empty($output['brand_array']['class_id'])) {?>
            <input class="edit_gcategory" type="button" value="<?php echo $lang['nc_edit'];?>">
            <?php }?>
            <select <?php if (!empty($output['brand_array']['class_id'])) {?>style="display:none;"<?php }?> class="class-select">
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
          <p class="notic"><?php echo $lang['brand_index_class_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['brand_index_pic_sign'];?></dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_BRAND.'/'.$output['brand_array']['brand_pic']; ?>"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_BRAND.'/'.$output['brand_array']['brand_pic']; ?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input class="type-file-file" id="_pic" name="_pic" type="file" size="30" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            <input type="text" name="brand_pic" id="brand_pic" class="type-file-text" />
            <input type="button" name="button" id="button" value="选择上传..." class="type-file-button" />
            </span></div>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['brand_index_upload_tips'].$lang['brand_add_support_type'];?>gif,jpg,png</p>
        </dd>
      </dl>
            <dl class="row">
        <dt class="tit">
          <label><em>*</em>品牌简介</label>
        </dt>
        <dd class="opt">
          <?php showEditor('brand_introduction',$output['brand_array']['brand_introduction']);?>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">展示方式</dt>
        <dd class="opt">
          <input id="show_type_0" type="radio" <?php echo !$output['brand_array']['show_type']?'checked':'';?> value="0" style="margin-bottom:6px;" name="show_type" />
          <label for="show_type_0">图片</label>
          <input id="show_type_1" type="radio" <?php echo $output['brand_array']['show_type']==1?'checked':'';?> value="1" style="margin-bottom:6px;" name="show_type" />
          <label for="show_type_1">文字</label>
          <span class="err"></span>
          <p class="notic">在“全部品牌”页面的展示方式，如果设置为“图片”则显示该品牌的“品牌图片标识”，如果设置为“文字”则显示该品牌的“品牌名”</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['brand_add_if_recommend'];?></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="brand_recommend1" class="cb-enable <?php if($output['brand_array']['brand_recommend'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_yes'];?>"><?php echo $lang['nc_yes'];?></label>
            <label for="brand_recommend0" class="cb-disable <?php if($output['brand_array']['brand_recommend'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['nc_no'];?>"><?php echo $lang['nc_no'];?></label>
            <input id="brand_recommend1" name="brand_recommend" <?php if($output['brand_array']['brand_recommend'] == '1'){ ?>checked="checked"<?php } ?>  value="1" type="radio">
            <input id="brand_recommend0" name="brand_recommend" <?php if($output['brand_array']['brand_recommend'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"><?php echo $lang['brand_index_recommend_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['nc_sort'];?></dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['brand_array']['brand_sort']?>" name="brand_sort" id="brand_sort" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['brand_add_update_sort'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script>
function call_back(picname){
  $('#brand_pic').val(picname);
  $('#view_img').attr('src','<?php echo UPLOAD_SITE_URL.'/'.ATTACH_BRAND;?>/'+picname);
}

//按钮先执行验证再提交表单
$(function(){
// 点击查看图片
  $('.nyroModal').nyroModal();
    // 编辑分类时清除分类信息
    $('.edit_gcategory').click(function(){
        $('input[name="class_id"]').val('');
        $('input[name="brand_class"]').val('');
    });
    
  $("#submitBtn").click(function(){
    if($("#brand_form").valid()){
     $("#brand_form").submit();
    }
  });
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
        url : '<?php echo ADMIN_SITE_URL?>/index.php?act=common&op=pic_upload&form_submit=ok&uploadpath=<?php echo ATTACH_BRAND;?>',
        secureuri:false,
        fileElementId:'_pic',
        dataType: 'json',
        success: function (data, status)
        {
          if (data.status == 1){
            ajax_form('cutpic','<?php echo $lang['nc_cut'];?>','<?php echo ADMIN_SITE_URL?>/index.php?act=common&op=pic_cut&type=brand&x=150&y=50&resize=1&ratio=3&url='+data.url,690);
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
  jQuery.validator.addMethod("initial", function(value, element) {
    return /^[A-Za-z0-9]$/i.test(value);
  }, "");
  $("#brand_form").validate({
    errorPlacement: function(error, element){
      var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            brand_name : {
                required : true,
                remote   : {
                    url :'index.php?act=brand&op=ajax&branch=check_brand_name',
                    type:'get',
                    data:{
                        brand_name : function(){
                            return $('#brand_name').val();
                        },
                        id  : '<?php echo $output['brand_array']['brand_id']?>'
                    }
                }
            },
            brand_initial : {
                initial  : true
            },
            brand_sort : {
                number   : true
            }
        },
        messages : {
            brand_name : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['brand_add_name_null'];?>',
                remote   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['brand_add_name_exists'];?>'
            },
            brand_initial : {
                initial : '<i class="fa fa-exclamation-circle"></i>请填写正确首字母'
            },
            brand_sort  : {
                number   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['brand_add_sort_int'];?>'
            }
        }
  });
});

gcategoryInit('gcategory');
</script> 
