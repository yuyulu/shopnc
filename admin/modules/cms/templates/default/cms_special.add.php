<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=cms_special&op=cms_special_list" title="返回专题列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_cms_special_manage'];?> -  新增专题页</h3>
        <h5><?php echo $lang['nc_cms_special_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="add_form" method="post" enctype="multipart/form-data" action="index.php?act=cms_special&op=cms_special_save">
    <input name="special_id" type="hidden" value="<?php if(!empty($output['special_detail'])) echo $output['special_detail']['special_id'];?>" />
    <input id="special_state" name="special_state" type="hidden" value="" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="special_title"><em>*</em><?php echo $lang['cms_text_title'];?></label>
        </dt>
        <dd class="opt">
          <input id="special_title" name="special_title" class="input-txt" type="text" value="<?php if(!empty($output['special_detail'])) echo $output['special_detail']['special_title'];?>"/>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['cms_special_title_explain'];?></p>
        </dd>
      </dl>
       <dl class="row">
        <dt class="tit">
          <label for="special_title"><em>*</em>专题副标题</label>
        </dt>
        <dd class="opt">
          <input id="special_stitle" name="special_stitle" class="input-txt" type="text" value="<?php if(!empty($output['special_detail'])) echo $output['special_detail']['special_stitle'];?>"/>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['cms_special_title_explain'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="special_title"><em>*</em>专题类型</label>
        </dt>
        <dd class="opt">
          <select name="special_type">
            <?php if(!empty($output['special_type_array']) && is_array($output['special_type_array'])) {?>
            <?php foreach($output['special_type_array'] as $special_type => $special_type_text) {?>
            <option value="<?php echo $special_type;?>" <?php echo $special_type == $output['special_detail']['special_type']?'selected':'';?>><?php echo $special_type_text;?></option>
            <?php } ?>
            <?php } ?>
          </select>
          <span class="err"></span>
          <p class="notic">资讯类型将出现在资讯频道内，商城类型将出现在商城内</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['cms_special_image'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"><a class="nyroModal" rel="gal" href="<?php if(!empty($output['special_detail']['special_image'])){ echo getCMSSpecialImageUrl($output['special_detail']['special_image']);} else {echo ADMIN_TEMPLATES_URL . '/images/preview.png';}?>"><i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php if(!empty($output['special_detail']['special_image'])){ echo getCMSSpecialImageUrl($output['special_detail']['special_image']);} else {echo ADMIN_TEMPLATES_URL . '/images/preview.png';}?>>')" onMouseOut="toolTip()"></i></a></span><span class="type-file-box">
            <input class="type-file-file" id="special_image" name="special_image" type="file" size="30" hidefocus="true" nctype="cms_image" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            <input name="old_special_image" type="hidden" value="<?php echo $output['special_detail']['special_image'];?>" />
            </span></div>
          <span class="err"></span>
          <p class="notic"><span class="vatop rowform"><?php echo $lang['cms_special_image_explain'];?></span></p>
        </dd>
      </dl>
      <div class="title">
        <h3><?php echo $lang['cms_special_background'];?></h3>
      </div>
      <dl class="row">
        <dt class="tit">背景颜色</dt>
        <dd class="opt">
          <input class="txt" name="special_background_color" type="color" value="<?php if(!empty($output['special_detail'])) echo $output['special_detail']['special_background_color'];?>" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['cms_special_background_color_explain'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['cms_special_background_image'];?></dt>
        <dd class="opt">
          <div class="input-file-show"><span class="show"> <a href="<?php if(!empty($output['special_detail']['special_background'])){ echo getCMSSpecialImageUrl($output['special_detail']['special_background']);} else {echo ADMIN_TEMPLATES_URL . '/images/preview.png';}?>" nctype="nyroModal"> <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php if(!empty($output['special_detail']['special_background'])){ echo getCMSSpecialImageUrl($output['special_detail']['special_background']);} else {echo ADMIN_TEMPLATES_URL . '/images/preview.png';}?>>')" onMouseOut="toolTip()"></i> </a> </span> <span class="type-file-box">
            <input name="special_background" type="file" class="type-file-file" id="special_background" size="30" hidefocus="true" nctype="cms_image">
            <input name="old_special_background" type="hidden" value="<?php echo $output['special_detail']['special_background'];?>" />
            </span></div>
          <span class="err"></span>
          <p class="notic"><span class="vatop rowform"><?php echo $lang['cms_special_background_image_explain'];?></span></p>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['cms_special_background_type'];?></dt>
        <dd class="opt">
          <label class="mr10">
            <input name="special_repeat" type="radio" value="no-repeat" <?php if($output['special_detail']['special_repeat'] == 'no-repeat') echo 'checked';?> />
            <?php echo $lang['cms_special_background_type_norepeat'];?></label>
          <label class="mr10">
            <input name="special_repeat" type="radio" value="repeat" <?php if($output['special_detail']['special_repeat'] == 'repeat') echo 'checked';?>/>
            <?php echo $lang['cms_special_background_type_repeat'];?></label>
          <label class="mr10">
            <input name="special_repeat" type="radio" value="repeat-x" <?php if($output['special_detail']['special_repeat'] == 'repeat-x') echo 'checked';?>/>
            <?php echo $lang['cms_special_background_type_xrepeat'];?></label>
          <label class="mr10">
            <input name="special_repeat" type="radio" value="repeat-y" <?php if($output['special_detail']['special_repeat'] == 'repeat-y') echo 'checked';?>/>
            <?php echo $lang['cms_special_background_type_yrepeat'];?></label>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['cms_special_background_type_explain'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['cms_special_content_top_margin'];?></dt>
        <dd class="opt">&nbsp;
          <input class="txt" style=" width: 50px;" name="special_margin_top" type="text" value="<?php echo empty($output['special_detail']['special_margin_top'])?'0':$output['special_detail']['special_margin_top'];?>" />
          像素<span class="err"></span>
          <p class="notic"><?php echo $lang['cms_special_content_explain'];?></p>
        </dd>
      </dl>
    </div>
    <div class="ncap-form-all">
      <div class="title">
        <h3><?php echo $lang['cms_special_content'];?></h3>
        <ul class="tab-base nc-row">
          <li> <a id="btn_content_view" class="current" href="javascript:void(0);"><?php echo $lang['cms_text_view'];?></a> </li>
          <li> <a id="btn_content_edit" href="javascript:void(0);"><?php echo $lang['nc_edit'];?></a> </li>
        </ul>
      </div>
      <dl class="row">
        <dd class="opt nopd nobd nobs">
          <div class="tab-content" style="background-color: <?php echo $output['special_detail']['special_background_color'];?>; background-image: url(<?php if(!empty($output['special_detail']['special_background'])){echo getCMSSpecialImageUrl($output['special_detail']['special_background']);}?>); background-repeat: <?php echo $output['special_detail']['special_repeat'];?>; background-position: top center; width: 100%; padding: 0; margin: 0; overflow: hidden;">
            <div id="div_content_view" style=" background-color: transparent; background-image: none; width: 1000px; margin-top: <?php echo $output['special_detail']['special_margin_top']?>px; margin-right: auto; margin-bottom: 0; margin-left: auto; border: 0; overflow: hidden;"></div>
          </div>
          <div id="div_content_edit" class="tab-content" style="display:none;">
            <textarea id="special_content" name="special_content" rows="50" cols="80"><?php echo $output['special_detail']['special_content'];?></textarea>
          </div>
        </dd>
      </dl>
    </div>
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit"><?php echo $lang['cms_special_image_and_goods'];?></dt>
        <dd class="opt">
          <div class="ncap-upload-btn">
            <input class="input-file" type="file" name="special_image_upload" id="picture_image_upload" multiple  file_id="0" size="1" hidefocus="true" />
            <input id="submit_button" class="input-button" type="button" value="&nbsp;" onClick="submit_form($(this))" />
            <a href="javascript:void(0);" class="ncap-btn"><i class="fa fa-upload"></i><?php echo $lang['cms_text_image_upload'];?></a> </div>
          <div class="ncap-upload-btn">
            <input class="input-button" id="btn_show_special_insert_goods" type="button" value="" />
            <a href="javascript:void(0);" class="ncap-btn"><i class="fa fa-cubes"></i><?php echo $lang['cms_text_goods_add'];?></a></div>
          <p class="notic"><?php echo $lang['cms_special_image_explain1'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['cms_special_image_list'];?></dt>
        <dd class="opt">
          <div class="cms-special-uploadpic">
            <ul id="special_image_list" class="ncap-thumb-list">
              <?php if(!empty($output['special_detail']['special_image_all'])) { ?>
              <?php $special_image_all = unserialize($output['special_detail']['special_image_all']);?>
              <?php if(!empty($special_image_all) && is_array($special_image_all)) { ?>
              <?php foreach ($special_image_all as $value) {?>
              <?php $image_url = getCMSSpecialImageUrl($value['image_name']);?>
              <li class="picture">
                <div class="size-64x64"> <span class="thumb size-64x64"><i></i> <img alt="" src="<?php echo $image_url;?>"> </span></div>
                <p class="handle "><a image_url="<?php echo $image_url;?>" nctype="btn_show_image_insert_link" class="insert-link " title="<?php echo $lang['cms_special_image_tips1'];?>">&nbsp;</a><a image_name="<?php echo $value['image_name'];?>" image_url="<?php echo $image_url;?>" nctype="btn_show_image_insert_hot_point" class="insert-hotpoint  " title="<?php echo $lang['cms_special_image_tips2'];?>">&nbsp;</a><a image_name="<?php echo $value['image_name'];?>" nctype="btn_drop_special_image" class="delete  " title="<?php echo $lang['cms_special_image_tips3'];?>">&nbsp;</a></span> </p>
                <input type="hidden" value="<?php echo $value['image_name'];?>" name="special_image_all[]">
              </li>
              <?php } ?>
              <?php } ?>
              <?php } ?>
            </ul>
          </div>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-blue" id="btn_draft"><?php echo $lang['cms_special_draft'];?></a> <a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="btn_publish"><?php echo $lang['cms_special_publish'];?></a></div>
    </div>
  </form>
  <!-- 插入图片链接对话框 -->
  <div id="_dialog_image_insert_link" style="display:none;">
    <div class="upload_adv_dialog dialog-image-insert-link">
      <div class="s-tips"><i class="fa fa-lightbulb-o"></i><?php echo $lang['cms_special_image_link_explain1'];?></div>
      <div class="ncap-form-default" id="upload_adv_type">
        <dl class="row">
          <dt class="tit">插入图片预览</dt>
          <dd class="opt">
            <div class="dialog-pic-thumb"><a><img alt="" src=""></a></div>
          </dd>
        </dl>
        <dl class="row" id="upload_adv_type">
          <dt class="tit"><?php echo $lang['cms_special_image_link_url'];?></dt>
          <dd class="opt">
            <input nctype="_image_insert_link" type="text" class="input-txt" placeholder="http://"/>
            <p class="notic"><?php echo $lang['cms_special_image_link_url_explain'];?>如不填加任何链接请保持默认。</p>
          </dd>
        </dl>
        <div class="bot"><a nctype="btn_image_insert_link" href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" ><?php echo $lang['cms_text_save'];?></a></div>
      </div>
    </div>
  </div>
  <!-- 插入图片热点对话框 -->
  <div id="_dialog_image_insert_hot_point" style="display:none;">
    <div class="dialog-image-insert-hot-point">
      <div class="s-tips"><i class="fa fa-lightbulb-o"></i><?php echo $lang['cms_special_image_link_hot_explain1'];?></div>
      <div class="ncap-form-default" id="upload_adv_type">
        <div ncytpe="div_image_insert_hot_point" class="special-hot-point"><img nctype="img_hot_point" alt="" src="<?php echo $image_url;?>"> </div>
        <dl class="row">
          <dt class="tit"><?php echo $lang['cms_special_image_link_hot_url'];?></dt>
          <dd class="opt">
            <input nctype="x1" type="hidden" />
            <input nctype="y1" type="hidden" />
            <input nctype="x2" type="hidden" />
            <input nctype="y2" type="hidden" />
            <input nctype="w" type="hidden" />
            <input nctype="h" type="hidden" />
            <input nctype="url" type="text" class="input-txt" placeholder="http://" />
            <a class="ncap-btn" nctype="btn_hot_point_commit" href="javascript:void(0);"><i class="fa fa-plus"></i>添加热点</a>
            <p class="notic"><?php echo $lang['cms_special_image_link_url_explain'];?></p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">已添加的热点区域</dt>
          <dd class="opt">
            <ul nctype="list" class="hot-point-list">
            </ul>
          </dd>
        </dl>
        <div class="bot"><a nctype="btn_image_insert_hot_point" href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" ><?php echo $lang['cms_special_insert_editor'];?></a></div>
      </div>
    </div>
  </div>
  <!-- 插入商品对话框 -->
  <div id="_dialog_special_insert_goods" style="display:none;">
    <div class="upload_adv_dialog dialog-special-insert-goods">
      <div class="s-tips"><i class="fa fa-lightbulb-o"></i><?php echo $lang['cms_special_goods_explain1'];?></div>
      <div class="ncap-form-default" id="upload_adv_type">
        <dl class="row">
          <dt class="tit"> <?php echo $lang['cms_special_goods_url'];?></dt>
          <dd class="opt">
            <input nctype="_input_goods_link" type="text" class="input-txt"/>
            <a class="ncap-btn" nctype="btn_special_goods" href="javascript:void(0);"><?php echo $lang['cms_text_save'];?></a>
            <p class="notic"><?php echo $lang['cms_special_goods_explain3'];?></p>
          </dd>
        </dl>
        <div class="dialog-goods">
            <ul nctype="_special_goods_list" class="special-goods-list">
        </ul>
        </div>  
        <div class="bot"><a nctype="btn_special_insert_goods" href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green"><?php echo $lang['cms_special_insert_editor'];?></a></div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<link media="all" rel="stylesheet" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.imgareaselect/imgareaselect-animated.css" type="text/css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.imgareaselect/jquery.imgareaselect.min.js"></script> 
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script> 
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/cms/cms_special.js" charset="utf-8"></script> 
<script type="text/javascript">
    $(document).ready(function(){
    $("#btn_draft").click(function() {
        $("#special_state").val("draft");
        $("#add_form").submit();
    });
    $("#btn_publish").click(function() {
        $("#special_state").val("publish");
        $("#add_form").submit();
    });
    $('#add_form').validate({
        errorPlacement: function(error, element){
            error.appendTo(element.parents("tr").prev().find('td:first'));
        },
        rules : {
            <?php if(empty($output['special_detail'])) {?>
            special_image: {
                required : true
            },
            <?php } ?>
            special_title: {
                required : true,
                maxlength : 24,
                minlength : 4
            }
        },
        messages : {
            <?php if(empty($output['special_detail'])) {?>
            special_image: {
                required : "<?php echo $lang['cms_special_image_error'];?>"
            },
            <?php } ?>
            special_title: {
                required : "<?php echo $lang['cms_title_not_null'];?>",
                maxlength : "<?php echo $lang['cms_title_max'];?>", 
                minlength : "<?php echo $lang['cms_title_min'];?>" 
            }
        }
    });


    });
</script>