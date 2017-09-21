<?php defined('In33hao') or exit('Access Invalid!'); ?>
<link href="<?php echo SHOP_TEMPLATES_URL; ?>/css/hao-ht-brand.css" rel="stylesheet" type="text/css">
<div class="body" style="background:#0f0d1a url(<?php echo SHOP_TEMPLATES_URL; ?>/images/hao-ht-brand.jpg) no-repeat center top;">
    <header id="recomHeader" class="m-header m-recomHeader">
        <h3>今日主打</h3>
    </header>
    <?php if (!empty($output['brand_r'])) {
        $i = 0 ?>
        <article class="m-recomBrand">
            <section class="rowOfFour clearfix">
                <?php foreach ($output['brand_r'] as $key => $brand_r) {
                    $i++;
                    $i < 9 ?>
                    <?php if ($i < 9) { ?>
                        <div class="brandWrap clearfix"><a class="brandDesc f-fl" href="<?php echo urlShop('brand', 'list', array('brand' => $brand_r['brand_id'])); ?>" target="_blank" style="top: 0px;"><img class="brandLogo" data-url="<?php echo brandImage($brand_r['brand_pic']); ?>" rel='lazy' src="<?php echo SHOP_SITE_URL; ?>/img/loading.gif" title="<?php echo $brand_r['brand_name']; ?>" alt="<?php echo $brand_r['brand_name']; ?>">
                                <p class="brandName" title="<?php echo $brand_r['brand_name']; ?>"><?php echo $brand_r['brand_name']; ?></p>
                                <span class="brandBtn">进入品牌</span></a></div>
                    <?php }
                } ?>
            </section>
        </article>
    <?php } ?>
    <header id="streetHeader" class="m-header m-streetHeader">
        <h3>品牌逛不停</h3>
    </header>
    <div class="nch-brand-class">
        <div class="nch-brand-class-tab">
            <?php if (!empty($output['brand_class'])) { ?>
                <ul class="tabs-nav">
                    <?php $i = 0;
                    foreach ($output['brand_class'] as $key => $brand) {
                        $i++; ?>
                        <li class="<?php if ($i == 1) {
                            echo 'tabs-selected';
                        } ?>"><a href="javascript:void(0);"><?php echo $brand['brand_class']; ?></a><b class="line">/</b></li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </div>
        <?php if (!empty($output['brand_c'])) { ?>
            <?php $i = 0;
            foreach ($output['brand_c'] as $key => $brand_c) {
                $i++; ?>
                <div class="nch-barnd-list tabs-panel <?php if ($i != 1) {
                    echo 'tabs-hide';
                } ?>">
                    <ul>
                        <?php if ($brand_c['image']) { ?>
                            <?php foreach ($brand_c['image'] as $key => $brand) { ?>
                                <li>

                                    <dl>
                                        <dt><a href="<?php echo urlShop('brand', 'list', array('brand' => $brand['brand_id'])); ?>"><img src="<?php echo brandImage($brand['brand_pic']); ?>" alt="<?php echo $brand['brand_name']; ?>"/></a></dt>
                                        <dd><a href="<?php echo urlShop('brand', 'list', array('brand' => $brand['brand_id'])); ?>"><?php echo $brand['brand_name']; ?></a></dd>
                                    </dl>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                    <?php if ($brand_c['text']) { ?>
                        <div class="nch-barnd-list-text"><strong>更多品牌：</strong>
                            <?php foreach ($brand_c['text'] as $key => $brand) { ?>
                                <a href="<?php echo urlShop('brand', 'list', array('brand' => $brand['brand_id'])); ?>"><?php echo $brand['brand_name']; ?></a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>
<script>
    $(".brandWrap").hover(function () {
        $(this).find(".brandDesc").animate({top: "-20px"}, 400, "swing")
    }, function () {
        $(this).find(".brandDesc").stop(!0, !1).animate({top: "0px"}, 400, "swing")
    });

    $(".nch-barnd-list li").hover(function () {
        $(this).find(".nch-barnd-list  li a").animate({top: "-20px"}, 400, "swing")
    }, function () {
        $(this).find(".nch-barnd-list li").stop(!0, !1).animate({top: "0px"}, 400, "swing")
    });

    //首页Tab标签卡滑门切换
    $(".tabs-nav > li > a").live('mousedown', (function (e) {
        if (e.target == this) {
            var tabs = $(this).parents('ul:first').children("li");
            var panels = $(this).parents('.nch-brand-class:first').children(".tabs-panel");
            var index = $.inArray(this, $(this).parents('ul:first').find("a"));
            if (panels.eq(index)[0]) {
                tabs.removeClass("tabs-selected").eq(index).addClass("tabs-selected");
                panels.addClass("tabs-hide").eq(index).removeClass("tabs-hide");
            }
        }
    }));
</script>
</div>
