<?php defined('In33hao') or exit('Access Invalid!'); ?>
<link href="<?php echo SHOP_TEMPLATES_URL; ?>/css/index.css" rel="stylesheet" type="text/css">
<script src="<?php echo RESOURCE_SITE_URL; ?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL; ?>/js/home_index.js" charset="utf-8"></script>
<style type="text/css">
    .category {
        display: block !important;
    }
</style>
<!--[if IE 6]>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ie6.js" charset="utf-8"></script>
<![endif]-->
<div class="clear"></div>
<div class="home-focus-layout"> <?php echo $output['web_html']['index_pic']; ?>
    <div class="right-sidebar">
        <div class="right-bannder"></div>
        <div class="right-bannder-content"> <?php echo loadadv(1049); ?><?php echo loadadv(1050); ?></div>
    </div>
</div>
<div class="home-sale-layout wrapper">
    <div class="left-layout">
        <div class="index-sale"><?php echo $output['web_html']['index_sale']; ?></div>
    </div>
    <div class="right-sidebar">
        <div class="title">
            <h3>商城快报</h3>
        </div>
        <div class="news">
            <ul>
                <?php if (!empty($output['show_article']['notice']['list']) && is_array($output['show_article']['notice']['list'])) { ?>
                    <?php foreach ($output['show_article']['notice']['list'] as $val) { ?>
                        <li><a target="_blank" href="<?php echo empty($val['article_url']) ? urlMember('article', 'show', array('article_id' => $val['article_id'])) : $val['article_url']; ?>" title="<?php echo $val['article_title']; ?>"><?php echo str_cut($val['article_title'], 24); ?> </a>
                            <time>(<?php echo date('m-d', $val['article_time']); ?>)</time>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
        <div class="ntrance">
            <ul>
                <li><a rel="nofollow" href="<?php echo urlShop('invite', 'index'); ?>" target="_self"><i class="i_ico01"></i>推广返利</a></li>
                <li><a rel="nofollow" href="<?php echo BASE_SITE_URL; ?>/other/service/index.html" target="_blank"><i class="i_ico02"></i>7大服务</a></li>
                <li><a rel="nofollow" href="<?php echo BASE_SITE_URL; ?>/other/guide/index.html" target="_blank"><i class="i_ico03"></i>导购流程</a></li>
                <li><a rel="nofollow" href="<?php echo DELIVERY_SITE_URL; ?>" target="_self"><i class="i_ico04"></i>物流自提</a></li>
                <li><a rel="nofollow" href="<?php echo urlShop('show_joinin', 'index'); ?>" target="_self"><i class="i_ico05"></i>招商入驻</a></li>
                <li><a rel="nofollow" href="<?php echo urlShop('seller_login', 'show_login'); ?>" target="_self"><i class="i_ico06"></i>商家管理</a></li>

            </ul>
        </div>
    </div>
</div>
<div class="wrapper">
    <div class="mt10">
        <div class="mt10"><?php echo loadadv(11); ?></div>
    </div>
</div>
<?php echo $output['web_html']['index']; ?>
<div class="wrapper">
    <div class="sale_lum clearfix">
        <div class="m" id="sale_cx">
            <div class="mt">
                <h2>今日抄底</h2>
            </div>
            <div class="sale_cx">
                <?php if (!empty($output['group_list']) && is_array($output['group_list'])) { ?>
                    <div class="groupbuy">
                        <ul>
                            <?php foreach ($output['group_list'] as $val) { ?>
                                <li>
                                    <dl style=" background-image:url(<?php echo gthumb($val['groupbuy_image1'], 'small'); ?>)">
                                        <dt><?php echo $val['groupbuy_name']; ?></dt>
                                        <dd class="price"><span class="groupbuy-price"><?php echo ncPriceFormatForList($val['groupbuy_price']); ?></span><span class="buy-button"><a href="<?php echo urlShop('show_groupbuy', 'groupbuy_detail', array('group_id' => $val['groupbuy_id'])); ?>">立即抢</a></span></dd>
                                        <dd class="time"><span class="sell">已售<em><?php echo $val['buy_quantity'] + $val['virtual_quantity']; ?></em></span> <span class="time-remain" count_down="<?php echo $val['end_time'] - TIMESTAMP; ?>"> <em time_id="d">0</em><?php echo $lang['text_tian']; ?><em time_id="h">0</em><?php echo $lang['text_hour']; ?> <em time_id="m">0</em><?php echo $lang['text_minute']; ?><em time_id="s">0</em><?php echo $lang['text_second']; ?> </span></dd>
                                    </dl>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
                <?php if (!empty($output['xianshi_item']) && is_array($output['xianshi_item'])) { ?>
                    <div class="right-sidebar">
                        <div class="sale-discount">
                            <ul>
                                <?php foreach ($output['xianshi_item'] as $val) { ?>
                                    <li>
                                        <div class="p-img"><a title="<?php echo $val['goods_name']; ?>" href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id'])); ?>" target="_blank"><img title="<?php echo $val['goods_name']; ?>" src="<?php echo thumb($val, 240); ?>" width="100" height="100" alt="<?php echo $val['goods_name']; ?>"></a></div>
                                        <div class="p-info">
                                            <div class="p-name"><a title="<?php echo $val['goods_name']; ?>" href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id'])); ?>" target="_blank"><?php echo $val['goods_name']; ?></a></div>
                                            <div class="p-price"><span><?php echo ncPriceFormatForList($val['xianshi_price']); ?></span></div>
                                            <div class="time-remain" count_down="<?php echo $val['end_time'] - TIMESTAMP; ?>"><i></i><em time_id="d">0</em><?php echo $lang['text_tian']; ?><em time_id="h">0</em><?php echo $lang['text_hour']; ?> <em time_id="m">0</em><?php echo $lang['text_minute']; ?><em time_id="s">0</em><?php echo $lang['text_second']; ?> </div>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="m" id="share">
            <div class="mt">
                <h2>热门晒单</h2>
            </div>
            <div class="share" id="sl">
                <ul class="show_share">
                    <?php if (!empty($output['goods_evaluate_info']) && is_array($output['goods_evaluate_info'])) { ?>
                        <?php foreach ($output['goods_evaluate_info'] as $k => $v) { ?>
                            <li>
                                <div class="p-img"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['geval_goodsid'])); ?>" target="_blank"><img src="<?php echo strpos($v['goods_pic'], 'http') === 0 ? $v['goods_pic'] : UPLOAD_SITE_URL . "/" . ATTACH_GOODS . "/" . $v['geval_storeid'] . "/" . $v['geval_goodsimage']; ?>" alt="<?php echo $v['geval_goodsname']; ?>" width="80" height="80"></a></div>
                                <div class="p-info">
                                    <div class="author-info"><img title="<?php echo str_cut($v['geval_frommembername'], 2) . '***'; ?>" src="<?php echo getMemberAvatarForID($v['geval_frommemberid']); ?>" alt="<?php echo str_cut($v['geval_frommembername'], 2) . '***'; ?>" width="28" height="28"> <span><?php echo str_cut($v['geval_frommembername'], 2) . '***'; ?></span></div>
                                    <div class="p-detail"><a target="_blank" title="<?php echo $v['geval_content']; ?>" href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['geval_goodsid'])); ?>"><?php echo $v['geval_content']; ?> <span class="icon-r">”</span></a> <span class="detail-arrow">◆</span> <span class="icon-l">“</span></div>
                                </div>
                            </li>
                        <?php }
                    } ?>
                </ul>
                <script type="text/javascript">$(document).ready(function () {
                        function statusRunner() {
                            setTimeout(function () {
                                var sl = $('#sl li'), f = $('#sl li:last');
                                f.hide().insertBefore(sl.eq(0)).css('opacity', '0.1');
                                f.slideDown(500, function () {
                                    f.animate({opacity: 1});
                                });
                                statusRunner();
                            }, 7000);
                        }

                        statusRunner();
                    });</script>
            </div>
        </div>
    </div>
</div>
</div>
<div class="wrapper index-brand">
    <div class="brand-title">
        <a href="<?php echo SHOP_SITE_URL; ?>/index.php?act=brand&op=index">更多品牌&nbsp;&nbsp;&gt;</a>
        <h3>推荐品牌<span>品牌汇集，一站购齐</span></h3>
    </div>
    <ul class="logo-list">
        <?php if (!empty($output['brand_r'])) { ?>
            <?php foreach ($output['brand_r'] as $key => $brand_r) { ?>
                <li><a target="_blank" href="<?php echo urlShop('brand', 'list', array('brand' => $brand_r['brand_id'])); ?>" alt="<?php echo $brand_r['brand_name']; ?>" title="<?php echo $brand_r['brand_name']; ?>"><img width="120" height="40" src="<?php echo brandImage($brand_r['brand_pic']); ?>"><span><?php echo $brand_r['brand_name']; ?></span></a></li>
            <?php }
        } ?>
    </ul>
</div>
<div class="clear"></div>
<div class="wrapper">
    <div class="mt20"><?php echo loadadv(9, 'html'); ?></div>
</div>
<div class="index-link wrapper">
    <dl class="website">
        <dt>合作伙伴 | 友情链接<b></b></dt>
        <dd>
            <?php
            if (is_array($output['$link_list']) && !empty($output['$link_list'])) {
                foreach ($output['$link_list'] as $val) {
                    if ($val['link_pic'] == '') {
                        ?>
                        <a href="<?php echo $val['link_url']; ?>" target="_blank" title="<?php echo $val['link_title']; ?>"><?php echo str_cut($val['link_title'], 15); ?></a>
                        <?php
                    }
                }
            }
            ?>
        </dd>
    </dl>
</div>
<div id="nav_box">
    <ul>
        <?php if (is_array($output['lc_list']) && !empty($output['lc_list'])) {
            $i = 0 ?>
            <?php foreach ($output['lc_list'] as $v) {
                $i++ ?>
                <li class="nav_h_<?php echo $i; ?> <?php if ($i == 1) echo 'hover' ?>"><a href="javascript:;" class="num"><?php echo $v['value'] ?></a> <a href="javascript:;" class="word"><?php echo $v['name'] ?></a></li>
            <?php }
        } ?>
    </ul>
</div>


