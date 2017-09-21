<?php defined('In33hao') or exit('Access Invalid!');?>
<link href="<?php echo CMS_SITE_URL.DS;?>templates/cms_special.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.VMiddleImg.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jcarousel/jquery.jcarousel.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/template.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/kindeditor/kindeditor-min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/kindeditor/lang/zh_CN.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/cms/cms_index.js" charset="utf-8"></script>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=cms_index&op=cms_index" title="返回模板"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['nc_cms_index_manage'];?> - <?php echo $lang['cms_index_module_edit'];?></h3>
        <h5>资讯首页模板可视化编辑生成</h5>
      </div>
      
    </div>
  </div>
  <form id="add_form" method="post" action="index.php?act=cms_index&op=save_page_module">
    <input name="module_id" type="hidden" value="<?php echo $output['module_detail']['module_id'];?>" />
    <div class="ncap-form-all">
      <dl class="row">
        
        <dd class="opt nobs nobd" id="cmsIndexModuleEdit">
          <?php $module_content = unserialize(base64_decode($output['module_detail']['module_content']));?>
          <?php $value['module_style'] = $output['module_detail']['module_style'];?>
          <?php require($output['module_template']);?>
        </dd>
      </dl>
      <div class="bot"><a href="Javascript: void(0)" class="ncap-btn-big ncap-btn-green" id="btn_module_save"><?php echo $lang['cms_text_save'];?></a></div>
    </div>
  </form>
</div>
<!--编辑标题-->
<div id="dialog_module_title_edit" style="display:none;">
  <div class="ncap-form-default">
    <dl class="row">
      <dt class="tit"><?php echo $lang['cms_index_module_title'];?></dt>
      <dd class="opt">
        <input name="input_module_title" type="text" class="input-txt" id="input_module_title" maxlength="8" />
        <p class="notic"><?php echo $lang['cms_index_module_title_explain'];?></p>
      </dd>
    </dl>
    <div class="bot"><a class="ncap-btn-big ncap-btn-green" id="btn_module_title_save" href="JavaScript:void(0);"><?php echo $lang['cms_text_save'];?></a></div>
  </div>
</div>
<!-- 关键字标题 -->
<div id="dialog_module_tag_edit" style="display:none;">
  <div class="ncap-form-all">
    <dl class="row">
      <dt class="tit"><?php echo $lang['cms_index_module_tag_selected'];?></dt>
      <dd class="opt">
        <ul id="article_tag_selected_list" class="article-tag-selected-list">
        </ul>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit"><?php echo $lang['cms_index_module_tag_list'];?></dt>
      <dd class="opt">
        <ul class="article-tag-list">
          <?php if(!empty($output['tag_list']) && is_array($output['tag_list'])) {?>
          <?php foreach($output['tag_list'] as $value){ ?>
          <li nctype="btn_tag_select" data-tag-id="<?php echo $value['tag_id'];?>"><a href="<?php echo CMS_SITE_URL;?>/index.php?act=article&op=article_tag_search&tag_id=<?php echo $value['tag_id'];?>" ><?php echo $value['tag_name'];?></a><i></i></li>
          <?php } ?>
          <?php } ?>
        </ul>
      </dd>
    </dl>
    <div class="bot"><a class="ncap-btn-big ncap-btn-green" id="btn_module_tag_save" href="JavaScript:void(0);"><?php echo $lang['cms_text_save'];?></a></div>
  </div>
</div>
<!--编辑图片-->
<div id="dialog_module_image_edit" style="display:none;">
  <div id="module_image_edit_explain" class="s-tips"></div>
  <form action="index.php?act=cms_index&op=image_upload" method="post">
    <div class="ncap-form-default">
      <ul id="image_selected_list" class="image-selected-list clearfix">
      </ul>
      <dl class="row">
        <dt class="tit"><?php echo $lang['cms_index_module_image_select'];?></dt>
        <dd class="opt">
          <div class="input-file-show"><span class="type-file-box">
            <input type='text' name='textfield' id='textfield' class='type-file-text' />
            <input type='button' name='button' id='button' value='选择上传...' class='type-file-button' />
            <input id="btn_image_upload" name="image_upload" type="file" class="type-file-file" size="30" hidefocus="true" />
            </span></div>
        </dd>
      </dl>
      <div class="bot"><a class="ncap-btn-big ncap-btn-green" id="btn_module_image_save" href="JavaScript:void(0);"><?php echo $lang['cms_text_save'];?></a></div>
    </div>
  </form>
</div>
<!--编辑文章-->
<div id="dialog_module_article_edit" style="display:none;">
  <div class="ncap-form-all">
    <dl class="row">
      <dt class="tit"><?php echo $lang['cms_index_module_article_seleceted'];?></dt>
      <dd class="opt">
        <ul id="article_selected_list" class="dialog-article-selected-list">
        </ul>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit"><?php echo $lang['cms_index_module_article_search'];?></dt>
      <dd class="opt">
        <div class="dialog-show-box">
          <div class="search-bar">
            <input id="radio_article_search_type_1" name="article_search_type" type="radio" value="article_id" checked />
            <label for="radio_article_search_type_1"><?php echo $lang['cms_text_id'];?></label>
            <input id="radio_article_search_type_2" name="article_search_type" type="radio" value="article_keyword"/>
            <label for="radio_article_search_type_2"><?php echo $lang['cms_text_keyword'];?></label>
            <input id="input_article_search_keyword" type="text" class="txt" />
            <a href="JavaScript:void(0);" id="btn_article_search" class="ncap-btn"><?php echo $lang['cms_text_search'];?></a></div>
          <div id="div_article_select_list" class="show-recommend-goods-list"> </div>
        </div>
      </dd>
    </dl>
    <div class="bot"><a class="ncap-btn-big ncap-btn-green" id="btn_module_article_save" href="JavaScript:void(0);"><?php echo $lang['cms_text_save'];?></a></div>
  </div>
</div>
<!--编辑商品-->
<div id="dialog_module_goods_edit" class="dialog-special-insert-goods upload_adv_dialog" style="display:none;">
  <div class="s-tips"><i class="fa fa-lightbulb-o"></i><?php echo $lang['cms_index_module_goods_explain'];?></div>
  <div id="upload_adv_type" class="ncap-form-default">
    <dl class="row">
      <dt class="tit"><?php echo $lang['cms_index_module_goods_input_url'];?></dt>
      <dd class="opt">
        <input id="input_goods_link" type="text" class="input-txt" />
        <a class="ncap-btn" id="btn_goods_search" href="javascript:void(0);"><?php echo $lang['cms_text_add'];?></a>
        <p class="notic"><?php echo $lang['cms_index_module_goods_explain'];?></p>
      </dd>
    </dl>
  </div>
  <div class="ncap-form-all">
    <dl class="row">
      <dd class="opt">
        <ul id="goods_selected_list" class="special-goods-list">
        </ul>
      </dd>
    </dl>
  </div>
  <div class="bot"><a class="ncap-btn-big ncap-btn-green" id="btn_module_goods_save" href="JavaScript:void(0);"><?php echo $lang['cms_text_save'];?></a></div>
</div>
<!-- 品牌选择弹出窗口 -->
<div id="dialog_module_brand_edit" class="brand_list_dialog" style="display:none;">
  <div class="s-tips"><i class="fa fa-lightbulb-o"></i><?php echo $lang['cms_index_module_brand_explain'];?> </div>
  <div class="ncap-form-all" id="upload_adv_type">
    <dl class="row">
      <dt class="tit"><?php echo $lang['cms_index_module_brand_selected'];?><?php echo $lang['nc_colon'];?></dt>
      <dd class="opt">
        <ul id="brand_selected_list" class="cms-brand-list">
        </ul>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit"><?php echo $lang['cms_index_module_brand_list'];?><?php echo $lang['nc_colon'];?></dt>
      <dd class="opt">
        <div id="div_brand_select_list" class="dialog-show-box"></div>
      </dd>
    </dl>
    <div class="bot"><a class="ncap-btn-big ncap-btn-green" id="btn_module_brand_save" href="JavaScript:void(0);"><?php echo $lang['cms_text_save'];?></a></div>
  </div>
</div>
<!-- 商品分类选择弹出窗口 -->
<div id="dialog_module_goods_class_edit" class="goods_class_list_dialog" style="display:none;">
  <div class="s-tips"><i></i><?php echo $lang['cms_index_module_goods_class_explain'];?></div>
  <div class="ncap-form-default">
    <dl class="row"><dt class="tit"><?php echo $lang['cms_index_module_goods_class'];?> </dt>
    <dd class="opt"><span class="handle">
      <select id="select_goods_class_list" class=" w200">
      </select>
      </span><p class="notic"><?php echo $lang['cms_index_module_goods_class_explain'];?></p></dd></dl>
  </div>
  <div id="goods_class_selected_list" class="category-list category-list-edit"></div>
  <div class="bot"><a class="ncap-btn-big ncap-btn-green" id="btn_module_goods_class_save" href="JavaScript:void(0);"><?php echo $lang['cms_text_save'];?></a></div>
</div>
<!--编辑店铺-->
<div id="dialog_module_store_edit" style="display:none;">
  <div class="ncap-form-all">
    <dl class="row">
      <dt class="tit">已选店铺：</dt>
      <dd class="opt">
        <ul id="store_selected_list" class="dialog-store-selected-list">
        </ul>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">搜索店铺：</dt>
      <dd class="opt">
        <div class="search-bar">
          <input id="input_store_search_keyword" type="text" class="input-txt" placeholder="请输入要搜索的店铺名称" />
          <a href="JavaScript:void(0);" id="btn_store_search" class="ncap-btn"><?php echo $lang['cms_text_search'];?></a></div>
        <div class="dialog-show-box">
          <div id="div_store_select_list" class="show-store-list"> </div>
        </div>
      </dd>
    </dl>
    <div class="bot"><a class="ncap-btn-big ncap-btn-green" id="btn_module_store_save" href="JavaScript:void(0);"><?php echo $lang['cms_text_save'];?></a></div>
  </div>
</div>
<!--编辑会员-->
<div id="dialog_module_member_edit" style="display:none;">
  <div class="ncap-form-all">
    <dl class="row">
      <dt class="tit">已选会员：</dt>
      <dd class="opt">
        <ul id="member_selected_list" class="dialog-member-selected-list">
        </ul>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">搜索会员：</dt>
      <dd class="opt">
        <div class="search-bar">
          <input id="input_member_search_keyword" type="text" class="input-txt" placeholder="请输入要搜索的会员名称" />
          <a href="JavaScript:void(0);" id="btn_member_search" class="ncap-btn"><?php echo $lang['cms_text_search'];?></a></div>
        <div class="dialog-show-box">
          <div id="div_member_select_list" class="show-member-list"> </div>
        </div>
      </dd>
    </dl>
    <div class="bot"><a class="ncap-btn-big ncap-btn-green" id="btn_module_member_save" href="JavaScript:void(0);"><?php echo $lang['cms_text_save'];?></a></div>
  </div>
</div>
<!--编辑FLASH-->
<div id="dialog_module_flash_edit" style="display:none;">
  <div id="module_image_edit_explain" class="s-tips"><i class="fa fa-lightbulb-o"></i>输入扩展名为".swf"的flash文件地址，并根据模块区域大小设定宽高。 </div>
  <div class="ncap-form-default">
    <dl class="row">
      <dt class="tit">FLASH地址</dt>
      <dd class="opt">
        <div class="dialog-handle-box">
          <input id="input_flash_address" type="text" class="input-txt" />
        </div>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">FLASH尺寸</dt>
      <dd class="opt"><span style="margin-right: 30px;">宽:
        <input id="input_flash_width" type="text" class="w40" />px</span><span>高:
        <input id="input_flash_height" type="text" class="w40" />px</span></dd>
    </dl>
    <div class="bot"><a id="btn_module_flash_save" href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" ><?php echo $lang['cms_text_save'];?></a></div>
  </div>
</div>
<!-- FLASH组件模板 --> 
<script id="module_assembly_flash_template" type="text/html">
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="<%=width%>" height="<%=height%>" id="FlashID" >
    <param name="movie" value="<%=address%>"/>
    <param name="quality" value="high" />
    <param name="wmode" value="opaque" />
    <param name="swfversion" value="6.0.65.0" />
    <!--[if !IE]>-->
    <object type="application/x-shockwave-flash" data="<%=address%>" width="<%=width%>" height="<%=height%>" id="FlashID">
        <param name="movie" value="<%=address%>"/>
    </object>
    <!--<![endif]-->
</object>
</script> 
<!--编辑自定义html -->
<div id="dialog_module_html_edit" style="display:none;">
  <div class="ncap-form-all">
    <dl class="row">
      <dt class="tit">自定义块内容</dt>
      <dd class="opt">
        <textarea id="textarea_module_html" name="textarea_module_html" style="width:600px;height:300px;"></textarea>
      </dd>
    </dl>
    <div class="bot"><a class="ncap-btn-big ncap-btn-green"id="btn_module_html_save" href="JavaScript:void(0);"><?php echo $lang['cms_text_save'];?></a></div>
  </div>
</div>
