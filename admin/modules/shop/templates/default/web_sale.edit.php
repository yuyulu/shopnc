<?php defined('In33hao') or exit('Access Invalid!');?>
<script type="text/javascript">
var SHOP_SITE_URL = "<?php echo SHOP_SITE_URL; ?>";
var UPLOAD_SITE_URL = "<?php echo UPLOAD_SITE_URL; ?>";
</script>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_web_index'];?></h3>
        <h5><?php echo $lang['nc_web_index_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=web_config&op=web_config"><?php echo '板块区';?></a></li>
        <li><a href="index.php?act=web_config&op=focus_edit"><?php echo '焦点区';?></a></li>
        <li><a href="JavaScript:void(0);" class="current"><?php echo '促销区';?></a></li>
      </ul>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo '最多可以加五组，每组五个商品。';?></li>
      <li><?php echo '所有相关设置完成，使用底部的“更新板块内容”前台展示页面才会变化。';?></li>
    </ul>
  </div>
  <form id="sale_list_form" method="post" name="form1">
    <input type="hidden" name="web_id" value="<?php echo $output['code_sale_list']['web_id'];?>">
    <input type="hidden" name="code_id" value="<?php echo $output['code_sale_list']['code_id'];?>">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">促销区布局块预览</dt>
        <dd class="opt sale-layout">
          <?php if (is_array($output['code_sale_list']['code_info']) && !empty($output['code_sale_list']['code_info'])) { ?>
          <?php foreach ($output['code_sale_list']['code_info'] as $key => $val) { ?>
          <dl sale_id="<?php echo $key;?>">
            <dt class="title">
              <h4><?php echo $val['recommend']['name'];?></h4>
              <input name="sale_list[<?php echo $key;?>][recommend][name]" value="<?php echo $val['recommend']['name'];?>" type="hidden">
              <a href="JavaScript:del_sale_list(<?php echo $key;?>);" class="ncap-btn-mini del"><i class="fa fa-trash"></i>删除</a><a href="JavaScript:show_sale_dialog(<?php echo $key;?>);" class="ncap-btn-mini edit"><i class="fa fa-pencil-square-o"></i><?php echo $lang['nc_edit'];?></a> </dt>
            <dd>
              <ul>
                <?php if(!empty($val['goods_list']) && is_array($val['goods_list'])){ ?>
                <?php foreach($val['goods_list'] as $k => $v){ ?>
                <li>
                  <div class="goods-thumb"><img title="<?php echo $v['goods_name'];?>" src="<?php echo strpos($v['goods_pic'],'http')===0 ? $v['goods_pic']:UPLOAD_SITE_URL."/".$v['goods_pic'];?>"/></div>
                  <input name="sale_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_id]" value="<?php echo $v['goods_id'];?>" type="hidden">
                  <input name="sale_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][market_price]" value="<?php echo $v['market_price'];?>" type="hidden">
                  <input name="sale_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_name]" value="<?php echo $v['goods_name'];?>" type="hidden">
                  <input name="sale_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_price]" value="<?php echo $v['goods_price'];?>" type="hidden">
                  <input name="sale_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_pic]" value="<?php echo $v['goods_pic'];?>" type="hidden">
                </li>
                <?php } ?>
                <?php } ?>
              </ul>
            </dd>
          </dl>
          <?php } ?>
          <?php } ?>
          <div class="clear" id="add_list"></div>
          <div class="add-sale"><a class="ncap-btn" href="JavaScript:add_sale_list();"><?php echo $lang['web_config_add_recommend'];?></a><?php echo $lang['web_config_sale_max'];?></div>
        </dd>
      </dl>
      <div class="bot"><a href="index.php?act=web_config&op=html_update&web_id=<?php echo $output['code_sale_list']['web_id'];?>" class="ncap-btn-big ncap-btn-green"><?php echo $lang['web_config_web_html'];?></a></div>
    </div>
  </form>
</div>

<!-- 促销区商品推荐模块 -->

<div id="sale_list_dialog" style="display:none;">
  <div class="s-tips"><i></i><?php echo $lang['web_config_goods_list_tips'];?></div>
  <?php if (is_array($output['code_sale_list']['code_info']) && !empty($output['code_sale_list']['code_info'])) { ?>
  <?php foreach ($output['code_sale_list']['code_info'] as $key => $val) { ?>
  <div class="ncap-form-default" select_sale_id="<?php echo $key;?>">
    <dl class="row">
      <dt class="tit"><?php echo $lang['web_config_recommend_title'];?></dt>
      <dd class="opt">
        <input name="sale_list[<?php echo $key;?>][recommend][name]" value="<?php echo $val['recommend']['name'];?>" type="text" class="w200">
        <p class="notic"><?php echo $lang['web_config_recommend_tips'];?></p>
      </dd>
    </dl>
  </div>
  <div class="ncap-form-all" select_sale_id="<?php echo $key;?>">
    <dl class="row">
      <dt class="tit">已选商品</dt>
      <dd class="opt">
        <ul class="dialog-goodslist-s1 goods-list">
          <?php if(!empty($val['goods_list']) && is_array($val['goods_list'])){ ?>
          <?php foreach($val['goods_list'] as $k => $v){ ?>
          <li>
            <div ondblclick="del_sale_goods(<?php echo $v['goods_id'];?>);" class="goods-pic"> <span class="ac-ico" onclick="del_sale_goods(<?php echo $v['goods_id'];?>);"></span> <span class="thumb size-72x72"><i></i><img select_goods_id="<?php echo $v['goods_id'];?>"
                title="<?php echo $v['goods_name'];?>" goods_name="<?php echo $v['goods_name'];?>" src="<?php echo strpos($v['goods_pic'],'http')===0 ? $v['goods_pic']:UPLOAD_SITE_URL."/".$v['goods_pic'];?>"
                onload="javascript:DrawImage(this,72,72);" goods_price="<?php echo $v['goods_price'];?>" market_price="<?php echo $v['goods_marketprice'];?>" /></span></div>
            <div class="goods-name"><a href="<?php echo SHOP_SITE_URL."/index.php?act=goods&goods_id=".$v['goods_id'];?>" target="_blank"><?php echo $v['goods_name'];?></a></div>
          </li>
          <?php } ?>
          <?php } ?>
        </ul>
      </dd>
    </dl>
  </div>
  <?php } ?>
  <?php } ?>
  <div id="select_sale_list" style="display:none;"></div>
  <div class="ncap-form-all">
    <dl class="row">
      <dt class="tit"><?php echo $lang['web_config_recommend_add_goods'];?></dt>
      <dd class="opt">
        <div class="search-bar" id="gcategory">
          <label>排序
            <select name="goods_order" id="goods_order">
              <option value="goods_salenum" selected><?php echo $lang['web_config_goods_order_sale'];?></option>
              <option value="goods_click" ><?php echo $lang['web_config_goods_order_click'];?></option>
              <option value="evaluation_count" ><?php echo $lang['web_config_goods_order_comment'];?></option>
              <option value="goods_collect" ><?php echo $lang['web_config_goods_order_collect'];?></option>
            </select>
          </label>
          分类
          <input type="hidden" id="cate_id" name="cate_id" value="0" class="mls_id" />
          <input type="hidden" id="cate_name" name="cate_name" value="" class="mls_names" />
          <select>
            <option value="0"><?php echo $lang['nc_please_choose'];?></option>
            <?php if(!empty($output['goods_class']) && is_array($output['goods_class'])){ ?>
            <?php foreach($output['goods_class'] as $k => $v){ ?>
            <option value="<?php echo $v['gc_id'];?>"><?php echo $v['gc_name'];?></option>
            <?php } ?>
            <?php } ?>
          </select>
          <input type="text" value="" name="order_goods_name" id="order_goods_name" placeholder="输入商品名称或SKU编号" class="txt 150">
          <a href="JavaScript:void(0);" onclick="get_goods_list();" class="ncap-btn"><?php echo $lang['nc_query'];?></a></div>
      </dd>
    </dl>
    <div id="show_sale_goods_list"></div>
  </div>
  <div class="bot"><a href="JavaScript:void(0);" onclick="update_sale();" class="ncap-btn-big ncap-btn-green"><span><?php echo $lang['web_config_save'];?></span></a></div>
</div>
<script src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<script src="<?php echo ADMIN_RESOURCE_URL?>/js/web_index.js"></script>
