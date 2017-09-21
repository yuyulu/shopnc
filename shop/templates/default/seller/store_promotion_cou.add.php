<?php defined('In33hao') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.ajaxContent.pack.js"></script>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>

<div class="ncsc-form-default">
    <form id="add_form" action="index.php?act=store_promotion_cou&op=cou_add_save" method="post">
    <dl>
      <dt><i class="required">*</i>活动名称<?php echo $lang['nc_colon'];?></dt>
      <dd>
          <input id="cou_name" name="cou_name" type="text" class="text w400" value="" />
          <span></span>
        <p class="hint">活动名称将显示在加价购活动列表中，方便商家管理使用。</p>
      </dd>
    </dl>
    <dl>

      <dt><i class="required">*</i>开始时间<?php echo $lang['nc_colon'];?></dt>
      <dd>
          <input id="tstart" name="tstart" type="text" class="text w130" value="" /><em class="add-on"><i class="icon-calendar"></i></em><span></span>
        <p class="hint">开始时间发布之后不能修改</p>
        <p class="hint">
<?php if (!$output['isOwnShop'] && $output['current_cou_quota']['tstart'] > 1) { ?>
        开始时间不能为空且不能早于<?php echo date('Y-m-d H:i', $output['current_cou_quota']['tstart']); ?>
<?php } ?>
        </p>
      </dd>

    </dl>
    <dl>

      <dt><i class="required">*</i>结束时间<?php echo $lang['nc_colon'];?></dt>
      <dd>
          <input id="tend" name="tend" type="text" class="text w130" value="" /><em class="add-on"><i class="icon-calendar"></i></em><span></span>
        <p class="hint">结束时间发布之后不能修改</p>
        <p class="hint">
<?php if (!$output['isOwnShop']) { ?>
        结束时间不能为空且不能晚于<?php echo date('Y-m-d H:i', $output['current_cou_quota']['tend']); ?>
<?php } ?>
        </p>
      </dd>

    </dl>

    <div class="bottom">
      <label class="submit-border"><input id="submit_button" type="submit" class="submit" value="<?php echo $lang['nc_submit'];?>"></label>
    </div>
  </form>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.min.css"  />
<script>
$(function() {

    $('#tstart').datetimepicker({
        controlType: 'select'
    });

    $('#tend').datetimepicker({
        controlType: 'select'
    });

    jQuery.validator.methods.greaterThanDate = function(value, element, param) {
        var date1 = new Date(Date.parse(param.replace(/-/g, "/")));
        var date2 = new Date(Date.parse(value.replace(/-/g, "/")));
        return date1 < date2;
    };
    jQuery.validator.methods.lessThanDate = function(value, element, param) {
        var date1 = new Date(Date.parse(param.replace(/-/g, "/")));
        var date2 = new Date(Date.parse(value.replace(/-/g, "/")));
        return date1 > date2;
    };
    jQuery.validator.methods.greaterThanStartDate = function(value, element) {
        var start_date = $("#tstart").val();
        var date1 = new Date(Date.parse(start_date.replace(/-/g, "/")));
        var date2 = new Date(Date.parse(value.replace(/-/g, "/")));
        return date1 < date2;
    };

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
            },
            tstart : {
                required : true,
                greaterThanDate : '<?php echo date('Y-m-d H:i', $output['current_cou_quota']['tstart']);?>'
            },
            tend : {
                required : true,
<?php if (!$output['isOwnShop']) { ?>
                lessThanDate : '<?php echo date('Y-m-d H:i', $output['current_cou_quota']['tend']);?>',
<?php } ?>
                greaterThanStartDate : true
            }
        },
        messages : {
            cou_name : {
                required : '<i class="icon-exclamation-sign"></i>活动名称不能为空'
            },
            tstart : {
                required : '<i class="icon-exclamation-sign"></i>开始时间不能为空',
                greaterThanDate : '<i class="icon-exclamation-sign"></i>开始时间不能早于<?php echo date('Y-m-d H:i', $output['current_cou_quota']['tstart']); ?>'
            },
            tend : {
                required : '<i class="icon-exclamation-sign"></i>结束时间不能为空',
<?php if (!$output['isOwnShop']) { ?>
                lessThanDate : '<i class="icon-exclamation-sign"></i>结束时间不能晚于<?php echo date('Y-m-d H:i', $output['current_cou_quota']['tend']); ?>',
<?php } ?>
                greaterThanStartDate : '<i class="icon-exclamation-sign"></i>结束时间必须晚于开始时间'
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
        $("tr[data-cou-level-item='"+id+"']").remove();
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

        $('#cou-level-sku-container-'+id).append(h);
        setCouLevelSkuAddButton(sku, 0);
    });

    // 移除已选换购商品按钮
    $("[data-cou-level-sku-remove]").live('click', function() {
        var sku = $(this).attr('data-cou-level-sku-remove');
        $("[data-cou-level-selected-sku='"+sku+"']").remove();
        setCouLevelSkuAddButton(sku, 1);
    });

});
</script>
