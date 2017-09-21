<?php defined('In33hao') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.ajaxContent.pack.js"></script>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<?php
$info = $output['data']['info'];
?>
<div class="ncsc-form-default">
  <form id="add_form" action="index.php?act=store_promotion_cou&op=cou_edit_save" method="post">
    <input type="hidden" name="cou_id" value="<?php echo $info['id']; ?>" />
    <dl>
      <dt><i class="required">*</i>活动名称<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input id="cou_name" name="cou_name" type="text" class="text w400" value="<?php echo $info['name']; ?>" />
        <span></span>
        <p class="hint">活动名称将显示在加价购活动列表中，方便商家管理使用。</p>
      </dd>
    </dl>
    <dl>
      <dt>开始时间<?php echo $lang['nc_colon'];?></dt>
      <dd><?php echo date('Y-m-d H:i', $info['tstart']); ?></dd>
    </dl>
    <dl>
      <dt>结束时间<?php echo $lang['nc_colon'];?></dt>
      <dd><?php echo date('Y-m-d H:i', $info['tend']); ?></dd>
    </dl>

    <dl>
      <dt>活动商品：</dt>
      <dd>
        <p> <span></span> </p>
        <table class="ncsc-default-table mb15">
          <thead>
            <tr>
              <th class="tl" colspan="2">商品名称</th>
              <th class="w90">商品价格</th>
              <th class="w90">操作</th>
            </tr>
          </thead>
          <tbody class="bd-line" id="cou-sku-item-results">
<?php foreach ((array) $output['data']['skus'] as $k) {
$g = $output['data']['items'][$k];
if (empty($g)) continue; ?>
            <tr class="off-shelf" data-product-id="<?php echo $g['goods_id']; ?>">
              <td class="w50"><input type="hidden" name="cou_sku[]" value="<?php echo $g['goods_id']; ?>" />
                <div class="shelf-state">
                  <div class="pic-thumb"> <img src="<?php echo cthumb($g['goods_image'], 60, $_SESSION['store_id']); ?>" /> </div>
                </div></td>
              <td class="tl"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $g['goods_id'], )); ?>"> <?php echo $g['goods_name']; ?> </a></td>
              <td class="goods-price w90"><?php echo ncPriceFormat($g['goods_price']); ?></td>
              <td class="nscs-table-handle w50"><span> <a href="javascript:;" class="btn-bittersweet" data-cou-sku-remove-button="<?php echo $g['goods_id']; ?>"> <i class="icon-ban-circle"></i>
                <p>移除</p>
                </a> </span></td>
            </tr>
<?php } ?>
          </tbody>
          <tbody id="cou-sku-item-tpl" style="display:none;">
            <tr class="off-shelf" data-product-id="__id">
              <td class="w50"><input type="hidden" name="cou_sku[]" value="__id" />
                <div class="shelf-state">
                  <div class="pic-thumb"> <img alt="" data-src="__imgUrl" /> </div>
                </div></td>
              <td class="tl"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => '__id', )); ?>"> __name </a></td>
              <td class="goods-price w90"> __price </td>
              <td class="nscs-table-handle w50"><span> <a href="javascript:;" class="btn-bittersweet" data-cou-sku-remove-button="__id"> <i class="icon-ban-circle"></i>
                <p>移除</p>
                </a> </span></td>
            </tr>
          </tbody>
        </table>
        <a id="cou-sku-choose-btn" href="index.php?act=store_promotion_cou&op=cou_sku&cou_id=<?php echo $info['id']; ?>" class="ncbtn ncbtn-aqua">选择活动商品</a>
        <div class="div-goods-select-box">
          <div id="cou-sku-options"></div>
          <a id="cou-sku-close-btn" class="close" href="javascript:;" style="display:none;right:-10px;">&#215;</a> </div>
        <p class="hint">同一商品SKU不能参加多个加价购活动；同一个加价购活动可以选择多个商品SKU参与。<br/>
          同一订单中，参与同一活动的SKU共同累加的金额用于判断是否满足换购资格；同一订单中可以使用多组加价购活动。</p>
      </dd>
    </dl>

    <dl>
      <dt>活动规则：</dt>
      <dd>
      <div id="cou-level-container">
<?php foreach ((array) $output['data']['levels'] as $k => $v) { ?>
        <div data-cou-level-item="<?php echo (string) $k; ?>" class="ncsc-cou-rule">
          <div class="rule-note">
            <h5>规则<?php echo $k; ?>：<a href="javascript:;" class="ncbtn-mini ncbtn-grapefruit" data-cou-level-remove="<?php echo (string) $k; ?>"><i class="icon-trash"></i>删除</a></h5>
            <span>购买同一加价购活动商品消费满
            <input type="text" class="w50" name="cou_level[<?php echo (string) $k; ?>][mincost]" value="<?php echo $v['mincost']; ?>" />
            元，即可换购最多
            <input type="text" class="w30" name="cou_level[<?php echo (string) $k; ?>][maxcou]" value="<?php echo $v['maxcou']; ?>" />
            件（0为不限）优惠商品，换购商品如下：<a data-cou-level-sku-choose-button="<?php echo (string) $k; ?>" href="javascript:;" class="ncbtn"><i class="icon-gift"></i>添加换购商品</a></span></div>
          <div data-cou-level-item="<?php echo (string) $k; ?>">
              <div class="div-goods-select-box">
                <div data-cou-level-sku-choose-container="<?php echo (string) $k; ?>"></div>
                <a data-cou-level-sku-close-button="<?php echo (string) $k; ?>" class="close" href="javascript:;" style="display:none;right:-10px;">&#215;</a> </div></div>
          <table class="ncsc-default-table mb15">
            <thead>
              <tr>
                <th colspan="2">换购商品</th>
                <th class="w100"> 原价(元)</th>
                <th class="w100"> 换购价(元)</th>
                <th class="handle">操作</th>
              </tr>
            </thead>
            <tbody class="bd-line" id="cou-level-sku-container-<?php echo (string) $k; ?>">
<?php foreach ((array) $output['data']['levelSkus'][$k] as $k2 => $v2) {
$g = $output['data']['items'][$k2];
if (empty($g)) continue; ?>
              <tr class="off-shelf" data-cou-level-selected-sku="<?php echo (string) $k2; ?>" data-level="<?php echo (string) $k; ?>">
                <td class="w50"><div class="shelf-state">
                    <div class="pic-thumb"> <img src="<?php echo cthumb($g['goods_image'], 60, $_SESSION['store_id']); ?>" /> </div>
                  </div></td>
                <td class="tl"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $g['goods_id'], )); ?>"> <?php echo $g['goods_name']; ?> </a></td>
                <td class="goods-price w90" nctype="bundling_data_price"><s> <?php echo ncPriceFormat($g['goods_price']); ?> </s></td>
                <td class="w90"><input type="text" class="w50" name="cou_level[<?php echo (string) $k; ?>][skus][<?php echo (string) $k2; ?>]" value="<?php echo $v2['price']; ?>" data-max-price="<?php echo ncPriceFormat($g['goods_price']); ?>" /></td>
                <td class="nscs-table-handle w50"><span> <a href="javascript:;" class="btn-bittersweet" data-cou-level-sku-remove="<?php echo (string) $k2; ?>"> <i class="icon-ban-circle"></i>
                  <p>移除</p>
                  </a> </span></td>
              </tr>
<?php } ?>
            </tbody>
          </table>
        </div>
<?php } ?>
      </div>

        <a href="javascript:;" id="cou-level-add-button" class="ncbtn ncbtn-aqua mt10"> <i class="icon-plus-sign"></i> 添加规则 </a>

        <p class="hint">级别会依据购满金额由小到大自动排序；换购购满金额不能重复。</p>
        <p class="hint">换购限制数量为满足换购条件时，会员可以选择的换购商品种数，若设置为0则为不限制；会员下单时每种换购商品可以换购的数量默认为1。</p>
        <p class="hint">下一级换购规则会自动继承上一级规则中可换购的商品；下一级规则会覆盖上一级规则中的换购数限制。</p>
        <p class="hint">换购规则以满足换购条件的最下一级为准。</p>
        <p class="hint">可以设置换购商品的换购价格。</p>
      </dd>
    </dl>

    <div class="bottom">
      <label class="submit-border">
        <input id="submit_button" type="submit" class="submit" value="<?php echo $lang['nc_submit'];?>">
      </label>
    </div>
  </form>
</div>

<div id="cou-level-newly" style="display:none;">
        <div data-cou-level-item="__level" class="ncsc-cou-rule">
          <div class="rule-note">
            <h5>新增规则：<a href="javascript:;" class="ncbtn-mini ncbtn-grapefruit" data-cou-level-remove="__level"><i class="icon-trash"></i>删除</a></h5>
            <span>购买同一加价购活动商品消费满
            <input type="text" class="w50" name="cou_level[__level][mincost]" value="" />
            元，即可换购最多
            <input type="text" class="w30" name="cou_level[__level][maxcou]" value="0" />
            件（0为不限）优惠商品，换购商品如下：<a data-cou-level-sku-choose-button="__level" href="javascript:;" class="ncbtn"><i class="icon-gift"></i>添加换购商品</a></span></div>
          <div data-cou-level-item="__level">
              <div class="div-goods-select-box">
                <div data-cou-level-sku-choose-container="__level"></div>
                <a data-cou-level-sku-close-button="__level" class="close" href="javascript:;" style="display:none;right:-10px;">&#215;</a> </div></div>
          <table class="ncsc-default-table mb15">
            <thead>
              <tr>
                <th colspan="2">换购商品</th>
                <th class="w100"> 原价(元)</th>
                <th class="w100"> 换购价(元)</th>
                <th class="handle">操作</th>
              </tr>
            </thead>
            <tbody class="bd-line" id="cou-level-sku-container-__level">
            </tbody>
          </table>
        </div>
</div>

<table style="display:none;">
  <tbody id="cou-level-sku-newly">
    <tr class="off-shelf" data-cou-level-selected-sku="__id" data-level="__level">
      <td class="w50"><div class="shelf-state">
          <div class="pic-thumb"> <img alt="" data-src="__imgUrl" /> </div>
        </div></td>
      <td class="tl"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => '__id', )); ?>"> __name </a></td>
      <td class="goods-price w90" nctype="bundling_data_price"><s> __price </s></td>
      <td class="w90"><input type="text" class="w50" name="cou_level[__level][skus][__id]" value="__price" data-max-price="__price" /></td>
      <td class="nscs-table-handle w50"><span> <a href="javascript:;" class="btn-bittersweet" data-cou-level-sku-remove="__id"> <i class="icon-ban-circle"></i>
        <p>移除</p>
        </a> </span></td>
    </tr>
  </tbody>
</table>

<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.min.css"  />
<script>
$(function() {

    //页面输入内容验证
    $("#add_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span');
            error_td.append(error);
        },
        onfocusout: false,
        submitHandler:function(form){
            ajaxpost('add_form', '', '', 'onerror');
        },
        rules : {
            cou_name : {
                required : true
            }
        },
        messages : {
            cou_name : {
                required : '<i class="icon-exclamation-sign"></i>活动名称不能为空'
            }
        }
    });

    // ajax添加商品
    $('#cou-sku-choose-btn').ajaxContent({
        event: 'click',
        loaderType: "img",
        loadingMsg: SHOP_TEMPLATES_URL + "/images/loading.gif",
        target: '#cou-sku-options'
    }).click(function() {
        $(this).hide();
        $('#cou-sku-close-btn').show();
    });
    $('#cou-sku-close-btn').click(function() {
        $(this).hide();
        $('#cou-sku-options').html('');
        $('#cou-sku-choose-btn').show();
    });

    $('#cou-sku-item-results [data-cou-sku-remove-button]').live('click', function() {
        $(this).parents('tr').remove();

        // 未参加活动的商品显示“设置为活动商品”按钮
        var id = $(this).attr('data-cou-sku-remove-button');
        $("div[data-cou-sku-switch-disabled='"+id+"']").hide();
        $("div[data-cou-sku-switch-enabled='"+id+"']").show();
    });

    var nextId = (function() {
        var i = 10000;
        return function() {
            return ++i;
        };
    })();

    // ajax添加规则
    $('#cou-level-add-button').click(function() {
        var id = nextId();
        var h = $('#cou-level-newly').html();
        h = h.replace(/__level/g, id);
        $('#cou-level-container').append(h);
    });

    // 规则移除按钮
    $('[data-cou-level-remove]').live('click', function() {
        var id = $(this).attr('data-cou-level-remove');
        $("[data-cou-level-item='"+id+"']").remove();
    });

    var couLevelSkuChooseTriggered = function(id, url) {
        $("[data-cou-level-sku-choose-container='"+id+"']").load(
            url || 'index.php?act=store_promotion_cou&op=cou_level_sku&level='+id,
            function() {
                $("[data-cou-level-selected-sku]").each(function() {
                    var sku = $(this).attr('data-cou-level-selected-sku');
                    setCouLevelSkuAddButton(sku, 0);
                });
            }
        );
    };

    // 选择换购商品按钮
    $('[data-cou-level-sku-choose-button]').live('click', function() {
        var id = $(this).attr('data-cou-level-sku-choose-button');
        $("[data-cou-level-sku-choose-button='"+id+"']").hide();
        $("[data-cou-level-sku-close-button='"+id+"']").show();
        couLevelSkuChooseTriggered(id);
    });

    $('[data-cou-level-sku-choose-container] a.demo').live('click', function() {
        var id = $(this).parents('[data-cou-level-sku-choose-container]').attr('data-cou-level-sku-choose-container');
        var url = this.href;
        couLevelSkuChooseTriggered(id, url);
        return false;
    });

    $('[data-cou-level-sku-choose-container] a[nctype="search_a"]').live('click', function() {
        var id = $(this).parents('[data-cou-level-sku-choose-container]').attr('data-cou-level-sku-choose-container');
        var url = this.href;
        url += '&stc_id=' + $('#cou_level_sku_stc_id_'+id).val();
        url += '&keyword=' + encodeURIComponent($('#cou_level_sku_keyword_'+id).val());
        couLevelSkuChooseTriggered(id, url);
        return false;
    });

    // 关闭选择换购商品选择框
    $('[data-cou-level-sku-close-button]').live('click', function() {
        var id = $(this).attr('data-cou-level-sku-close-button');
        $(this).hide();
        $("[data-cou-level-sku-choose-button='"+id+"']").show();
        $("[data-cou-level-sku-choose-container='"+id+"']").html('');
    });

    var setCouLevelSkuAddButton = function(sku, b) {
        if (b) {
            $("[data-cou-level-sku-switch-enabled='"+sku+"']").show();
            $("[data-cou-level-sku-switch-disabled='"+sku+"']").hide();
        } else {
            $("[data-cou-level-sku-switch-enabled='"+sku+"']").hide();
            $("[data-cou-level-sku-switch-disabled='"+sku+"']").show();
        }
    };

    window.couLevelSkuInSearch = {};

    // 设置为换购商品
    $("[data-cou-level-sku-add-button]").live('click', function() {
        var sku = $(this).attr('data-cou-level-sku-add-button');
        var id = $(this).attr('data-level');

        var h = $('#cou-level-sku-newly').html();
        h = h.replace(/__level/g, id);
        h = h.replace(/__(\w+)/g, function($m, $1) {
            return window.couLevelSkuInSearch[sku][$1];
        });

        var $h = $(h);
        $h.find('img[data-src]').each(function() {
            this.src = $(this).attr('data-src');
        });

        $('#cou-level-sku-container-'+id).append($h);
        setCouLevelSkuAddButton(sku, 0);
    });

    // 移除已选换购商品按钮
    $("[data-cou-level-sku-remove]").live('click', function() {
        var sku = $(this).attr('data-cou-level-sku-remove');
        $("[data-cou-level-selected-sku='"+sku+"']").remove();
        setCouLevelSkuAddButton(sku, 1);
    });

    // 换购商品换购价不能高于原价

    $('input[data-max-price]').live('keyup', function() {
        var p = parseFloat(this.value) || 0;
        var mp = parseFloat($(this).attr('data-max-price')) || 0;
        if (p > mp) {
            alert('换购商品换购价不能高于原价，请重新填写！');
            this.value = '';
            this.focus();
            return false;
        }
    });

});
</script>
