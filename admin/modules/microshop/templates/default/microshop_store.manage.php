<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_microshop_store_manage'];?></h3>
        <h5><?php echo $lang['nc_microshop_store_manage_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <?php   foreach($output['menu'] as $menu) {  if($menu['menu_type'] == 'text') { ?>
        <li><a href="<?php echo $menu['menu_url'];?>" class="current"><?php echo $menu['menu_name'];?></a></li>
        <?php }  else { ?>
        <li><a href="<?php echo $menu['menu_url'];?>" ><?php echo $menu['menu_name'];?></a></li>
        <?php  } }  ?>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['microshop_store_tip1'];?></li>
      <li><?php echo $lang['microshop_store_tip2'];?></li>
    </ul>
  </div>

  <div id="flexigrid"></div>
</div>

<script>
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=store&op=store_manage_xml',
        colModel: [
            {display: '操作', name: 'operation', width: 100, sortable: false, align: 'center', className: 'handle'},
            {display: '排序', name: 'microshop_sort', width: 80, sortable: false, align: 'left'},
            {display: '店铺', name: 'store_name', width: 280, sortable: false, align: 'left'},
            {display: '店主账号', name: 'member_name', width: 100, sortable: false, align: 'left'},
            {display: '所在地', name: 'area_info', width: 180, sortable: false, align: 'left'},
            {display: '有效期至', name: 'store_end_time_text', width: 120, sortable: false, align: 'left'},
            {display: '推荐', name: 'state', width: 100, sortable: false, align: 'center'}
        ],
        buttons: [
            {
                display: '<i class="fa fa-trash"></i>批量删除',
                name: 'del',
                bclass: 'del',
                title: '将选定行数据批量删除',
                onpress: function() {
                    var ids = [];
                    $('.trSelected[data-id]').each(function() {
                        ids.push($(this).attr('data-id'));
                    });
                    if (ids.length < 1 || !confirm('确定删除?')) {
                        return false;
                    }
                    location.href = 'index.php?act=store&op=store_drop_save&store_id=__IDS__'.replace('__IDS__', ids.join(','));
                }
            }
        ],
        searchitems: [
            {display: '店铺', name: 'store_name', isdefault: true},
            {display: '店主', name: 'member_name'}
        ],
        sortname: "store_id",
        sortorder: "desc",
        title: '商城店铺列表'
    });
});

$('a.confirm-del-on-click').live('click', function() {
    return confirm('确定删除?');
});

$('a[data-ie-column]').live('click', function() {
    $.get('index.php?act=store&op=ajax&branch=store_commend', {
        column: $(this).attr('data-ie-column'),
        value: $(this).attr('data-ie-value'),
        id: $(this).parents('tr').attr('data-id')
    }, function(d) {
        if (d != 'true') {
            alert('操作失败！');
            return false;
        }
        $("#flexigrid").flexReload();
    });
});

$("span[data-live-inline-edit='microshop_sort']").live('click', function() {
    var $this = $(this);
    var $input = $('<input type="text" style="width:50px;">');
    $input.val(parseInt($this.html()) || 0);
    $this.after($input);
    $this.hide();
    $input.focus();
    $input.change(function() {
        var v2 = parseInt($input.val()) || 0;
        $.getJSON('index.php?act=store&op=store_sort_update', {
            id: $this.parents('tr').attr('data-id'),
            value: v2
        }, function(d) {
            if (d.result) {
                $this.html(v2);
            } else {
                alert(d.message);
            }
            $input.remove();
            $this.show();
            // $("#flexigrid").flexReload();
        });
    });
});

</script>
