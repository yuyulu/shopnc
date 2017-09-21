<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['groupbuy_index_manage'];?></h3>
        <h5><?php echo $lang['groupbuy_index_manage_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <?php   foreach($output['menu'] as $menu) {  if($menu['menu_type'] == 'text') { ?>
        <li><a href="JavaScript:void(0);" class="current"><?php echo $menu['menu_name'];?></a></li>
        <?php }  else { ?>
        <li><a href="<?php echo $menu['menu_url'];?>" ><?php echo $menu['menu_name'];?></a></li>
        <?php  } }  ?>
      </ul>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['groupbuy_price_range_help1'];?></li>
    </ul>
  </div>

  <div id="flexigrid"></div>
</div>

<script>
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=groupbuy&op=price_list_xml',
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '价格区间名称', name: 'range_name', width: 200, sortable: false, align: 'left'},
            {display: '价格区间下限', name: 'range_start', width: 200, sortable: false, align: 'left'},
            {display: '价格区间上限', name: 'range_end', width: 200, sortable: false, align: 'left'}
        ],
        buttons: [
            {
                display: '<i class="fa fa-plus"></i>新增数据',
                name: 'add',
                bclass: 'add',
                title: '添加一条新数据到列表',
                onpress: function() {
                    location.href = 'index.php?act=groupbuy&op=price_add';
                }
            },
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

                    location.href = 'index.php?act=groupbuy&op=price_drop&range_id=' + ids.join(',');
                }
            }
        ],
        usepager: false,
        sortname: "range_id",
        sortorder: "desc",
        title: '抢购价格区间列表'
    });
});

$('a.confirm-del-on-click').live('click', function() {
    return confirm('确定删除?');
});

</script>
