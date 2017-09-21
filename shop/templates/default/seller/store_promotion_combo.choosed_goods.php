<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<form method="post" id="goods_combo" action="<?php echo urlShop('store_promotion_combo', 'save_combo');?>">
  <input type="hidden" name="form_submit" value="ok">
  <input type="hidden" name="goods_id" value="<?php echo $output['goods_info']['goods_id'];?>">
  <div class="ncsc-form-goods-combo-default" data-gid="<?php echo $output['goods_info']['goods_id'];?>">
    <div class="goods-pic"> <span><img src="<?php echo thumb($output['goods_info'], 240);?>"/></span></div>
    <dl>
      <dt><?php echo $output['goods_info']['goods_name'];?></dt>
      <dd>商品SKU：<?php echo $output['goods_info']['goods_id'];?></dd>
      <dd>商品价格：<em class="goods-price">￥<?php echo ncPriceFormat($output['goods_info']['goods_price']);?></em></dd>
      <dd>库&nbsp;&nbsp;存&nbsp;&nbsp;量：<?php echo $output['goods_info']['goods_storage'];?></dd>
      <dd class="mt5">
        <?php if ($output['goods_info']['is_general']) {?>
        <a class="ncbtn ncbtn-aqua" nctype="select_goods" href="javascript:void(0);"><i class="icon-thumbs-up"></i>添加推荐组合</a>
        <?php } else {?>
        <a class="ncbtn" href="javascript:void(0);"><i class="icon-thumbs-up"></i>不能添加推荐组合</a>
        <?php }?>
      </dd>
    </dl>
  </div>
  <div nctype="ncsc-goods-combo" class="ncsc-goods-combo" id="ncscGoodsCombo">
    <?php if (!empty($output['combo_list'])) {$i = 0;?>
    <?php foreach ($output['combo_list'] as $key => $value) {?>
    <div class="ncsc-form-goods-combo">
      <div class="combo-title">分类名称
        <input type="text" name="class[s<?php echo $i;?>]" value="<?php echo $value['name'];?>" >
        <a href="javascript:void(0);" nctype="ncsc-goods-combo-del" class="ncbtn ncbtn-grapefruit"><i class="icon-trash"></i>删除</a>
      </div>
      <div class="combo-goods" nctype="choose_goods_list">
        <ul>
          <?php if (!empty($value['goods'])) {?>
          <?php foreach ($value['goods'] as $combo) {?>
          <li>
            <input type="hidden" value="<?php echo $combo['goods_id'];?>" name="combo[s<?php echo $i;?>][]">
            <div class="pic-thumb"><span><img src="<?php echo cthumb($combo['goods_image'], '240', $_SESSION['store_id']);?>"></span></div>
            <dl>
              <dt><?php echo $combo['goods_name'];?></dt>
              <dd>￥<?php echo ncPriceFormat($combo['goods_price']);?></dd>
            </dl>
            <a class="ncbtn-mini ncbtn-bittersweet" nctype="del_choosed" href="javascript:void(0);"><i class="icon-ban-circle"></i>取消推荐</a></li>
          <?php }?>
          <?php }?>
        </ul>
      </div>
    </div>
    <?php $i++;}?>
    <?php }?>
  </div>
  <div class="clear">&nbsp;</div>
  <div class="div-goods-select" style="display: none;">
    <table class="search-form">
      <thead>
        <tr>
          <td></td>
          <th>商品名称</th>
          <td class="w160"><input class="text" type="text" name="search_combo"></td>
          <td class="tc w70"><a class="ncbtn" href="javascript:void(0);" nctype="search_combo"><i class="icon-search"></i>搜索</a></td>
          <td class="w10"></td>
        </tr>
      </thead>
    </table>
    <div class="search-result" nctype="combo_goods_list"></div>
    <a class="close" href="javascript:void(0);" nctype="btn_hide_goods_select">X</a> </div>
  <div class="bottom tc">
    <label class="submit-border">
      <input type="submit" class="submit" value="提交" />
    </label>
  </div>
</form>
<script type="text/javascript">
$(function(){	
    // 选择推荐组合按钮
    var _i = 1;
    $('a[nctype="select_goods"]').click(function(){
        $('div[nctype="ncsc-goods-combo"]').append('<div class="ncsc-form-goods-combo"><div class="combo-title">分类名称<input type="text" name="class['+ _i +']" value="默认' + _i + '" ><a href="javascript:void(0);" nctype="ncsc-goods-combo-del" class="ncbtn ncbtn-grapefruit"><i class="icon-trash"></i>删除</a></div><div class="combo-goods" nctype="choose_goods_list" data-i="' + _i + '"><ul></ul></div></div>');
        selected_box($('.ncsc-form-goods-combo', '#goods_combo').last());
        _i++;
    });

    // 关闭按钮
    $('a[nctype="btn_hide_goods_select"]').click(function(){
        $(this).parent().hide();
    });

    // 删除按钮
    $('div[nctype="ncsc-goods-combo"]').on('click', 'a[nctype="ncsc-goods-combo-del"]', function(){
        $(this).parents('.ncsc-form-goods-combo:first').remove();
    });

    // 所搜商品
    $('a[nctype="search_combo"]').click(function(){
        _url = "<?php echo urlShop('store_goods_online', 'search_goods');?>";
        _name = $(this).parents('tr').find('input[name="search_combo"]').val();
        $(this).parents('table:first').next().load(_url + '&name=' + _name);
    });

    // 分页
    $('div[nctype="combo_goods_list"]').on('click', 'a[class="demo"]', function(){
        $(this).parents('div[nctype="combo_goods_list"]').load($(this).attr('href'));
        return false;
    });

    $('#goods_combo')
    // 选择分类组
    .on('click', '.ncsc-form-goods-combo', function(){
        selected_box($(this));
    })
    // 删除推荐商品
    .on('click', 'a[nctype="del_choosed"]', function(){
        $(this).parents('li:first').remove();
    });
    

    // 选择商品
    $('div[nctype="combo_goods_list"]').on('click', 'a[nctype="a_choose_goods"]', function(){
        var _select_group = $('.selected > [nctype="choose_goods_list"]').last();
        _owner_i = _select_group.attr('data-i');
        eval('var data_str = ' + $(this).attr('data-param'));
        _li = $('<li></li>')
            .append('<input type="hidden" value="' + data_str.gid + '" name="combo[' + _owner_i + '][]">')
            .append('<div class="pic-thumb"> <span> <img src="' + data_str.gimage240 + '"> </span> </div>')
            .append('<dl><dt>' + data_str.gname + '</dt><dd>￥' + data_str.gprice + '</dd></dl>')
            .append('<a class="ncbtn-mini ncbtn-bittersweet" nctype="del_choosed" href="javascript:void(0);"><i class="icon-ban-circle"></i>取消推荐</a>');
        _select_group.find('ul').append(_li);
    });

    $('#goods_combo').submit(function(){
        ajaxpost('goods_combo', '', '', 'onerror');
    });
	//品牌索引过长滚条
	$("#ncscGoodsCombo").perfectScrollbar({suppressScrollX:true});
});

function selected_box($this) {
	$('.ncsc-form-goods-combo', '#goods_combo').removeClass('selected');
	$this.addClass('selected');
    $('.div-goods-select').show().find('input[name="search_combo"]').val('').end().find('a[nctype="search_combo"]').click();
}
</script> 
