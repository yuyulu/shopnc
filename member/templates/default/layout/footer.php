<?php defined('In33hao') or exit('Access Invalid!');?>
<?php echo getChat($layout);?>
<script language="javascript">
function fade() {
	$("img[rel='lazy']").each(function () {
		var $scroTop = $(this).offset();
		if ($scroTop.top <= $(window).scrollTop() + $(window).height()) {
			$(this).hide();
			$(this).attr("src", $(this).attr("data-url"));
			$(this).removeAttr("rel");
			$(this).removeAttr("name");
			$(this).fadeIn(500);
		}
	});
}
if($("img[rel='lazy']").length > 0) {
	$(window).scroll(function () {
		fade();
	});
};
fade();
</script>
<div id="cti">
  <div class="wrapper">
    <ul>
      <?php if ($output['contract_list']) {?>
        <?php foreach($output['contract_list'] as $k=>$v){?>
          <?php if($v['cti_descurl']){ ?>
            <li><span class="line"></span><a href="<?php echo $v['cti_descurl'];?>" target="_blank"><span class="icon"> <img style="width: 60px;" src="<?php echo $v['cti_icon_url_60']; ?>" /> </span> <span class="name"> <?php echo $v['cti_name']; ?> </span></a></li>
          <?php }else{ ?>
            <li><span class="line"></span> <span class="icon"> <img style="width: 60px;" src="<?php echo $v['cti_icon_url_60']; ?>" /> </span> <span class="name"> <?php echo $v['cti_name']; ?> </span> </li>
          <?php }?>
        <?php }?>
      <?php }?>
    </ul>
  </div>
</div>
<div id="faq">
  <div class="wrapper">
    <?php if(is_array($output['article_list']) && !empty($output['article_list'])){ ?>
    <ul>
      <?php foreach ($output['article_list'] as $k=> $article_class){ ?>
      <?php if(!empty($article_class)){ ?>
      <li>
        <dl class="s<?php echo ''.$k+1;?>">
          <dt>
            <?php if(is_array($article_class['class'])) echo $article_class['class']['ac_name'];?>
          </dt>
          <?php if(is_array($article_class['list']) && !empty($article_class['list'])){ ?>
          <?php foreach ($article_class['list'] as $article){ ?>
          <dd><a href="<?php if($article['article_url'] != '')echo $article['article_url'];else echo urlMember('article', 'show',array('article_id'=> $article['article_id']));?>" title="<?php echo $article['article_title']; ?>"> <?php echo $article['article_title'];?> </a></dd>
          <?php }?>
          <?php }?>
        </dl>
      </li>
      <?php }}?>
      <li class="kefu-con">
      <dl>
      	<dt>联系我们</dt>
        <dd>
        <i class="icon-tel"></i>
        <div><strong><?php echo $output['setting_config']['hao_phone']; ?></strong>
         <p><?php echo $output['setting_config']['hao_time']; ?></p>
         </div>
          </dd>
          <dd>
          <i class="icon-chat"></i>
          <div>
          <strong>在线客服</strong>
           <p>E-mail：<?php echo $output['setting_config']['hao_mail']; ?></p>
           </div>
        </dd>
       <dl>
      </li>
      <li class="box-qr">
        <dl>
          <dt>关注我们<a title="关注<?php echo $output['setting_config']['site_name']; ?>微信公众号" class="weixin">
                        <span class="weixin-qr"></span>
                    </a>
                    <a title="关注<?php echo $output['setting_config']['site_name']; ?>微博" class="weibo">
                        <span class="weibo-qr"></span>
                    </a>
          </dt>
          <?php if (C('mobile_isuse') && C('mobile_app')){?>
          <dd>
            <p><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('mobile_app');?>" ></p>
            <p>下载APP手机端</p>
          </dd>
          <?php } ?>
          <?php if (C('mobile_wx')) { ?>
          <dd>
            <p><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_MOBILE.DS.C('mobile_wx');?>" ></p>
            <p>关注微信公众号</p>
          </dd>
          <?php } ?>
        </dl>
      </li>
    </ul>
    <?php }?>
  </div>
</div>
<div id="footer">
  <p><a href="<?php echo SHOP_SITE_URL;?>"><?php echo $lang['nc_index'];?></a>
    <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
    <?php foreach($output['nav_list'] as $nav){?>
    <?php if($nav['nav_location'] == '2'){?>
    | <a  <?php if($nav['nav_new_open']){?>target="_blank" <?php }?>href="<?php switch($nav['nav_type']){
    	case '0':echo $nav['nav_url'];break;
    	case '1':echo urlShop('search', 'index', array('cate_id'=>$nav['item_id']));break;
    	case '2':echo urlMember('article', 'article',array('ac_id'=>$nav['item_id']));break;
    	case '3':echo urlShop('activity', 'index',array('activity_id'=>$nav['item_id']));break;
    }?>"><?php echo $nav['nav_title'];?></a>
    <?php }?>
    <?php }?>
    <?php }?>
  </p>
  <?php echo html_entity_decode($output['setting_config']['statistics_code'],ENT_QUOTES); ?>  <?php echo $output['setting_config']['icp_number']; ?></div>
<?php if (C('debug') == 1){?>
<div id="think_page_trace" class="trace">
  <fieldset id="querybox">
    <legend><?php echo $lang['nc_debug_trace_title'];?></legend>
    <div> <?php print_r(Tpl::showTrace());?> </div>
  </fieldset>
</div>
<?php }?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.cookie.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script>
