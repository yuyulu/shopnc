<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>虚拟抢购</h3>
        <h5>虚拟商品抢购促销活动相关设定及管理</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=vr_groupbuy&op=class_list">分类管理</a></li>
        <li><a href="javascript:;" class="current">区域管理</a></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>商家发布虚拟商品的抢购时，需要选择虚拟抢购所属区域</li>
      <li>显示一级城市名称，可以编辑、删除一级城市，点击查看区域，可以查看该城市下区域列表</li>
      <li>可以按照区域名称、首字母进行查询</li>
    </ul>
  </div>

  <form id="list_form" method="post" action="index.php?act=vr_groupbuy&op=area_drop">
    <input id="area_id" name="area_id" type="hidden" />
  </form>

  <div id="flexigrid"></div>
</div>

<script>
function submit_delete(id){
    if (confirm('<?php echo $lang['nc_ensure_del']; ?>')) {
        $('#area_id').val(id);
        $('#list_form').submit();
    }
}

$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=vr_groupbuy&op=area_list_xml',
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '区域名称', name: 'area_name', width: 200, sortable: false, align: 'left'},
            {display: '首字母', name: 'first_letter', width: 50, sortable: false, align: 'left'},
            {display: '区号', name: 'area_number', width: 100, sortable: false, align: 'left'},
            {display: '邮编', name: 'post', width: 100, sortable: false, align: 'left'},
            {display: '显示', name: 'hot_city', width: 100, sortable: false, align: 'center'},
            {display: '添加时间', name: 'add_time', width: 150, sortable: false, align: 'center'}
        ],
        searchitems: [
            {display: '区域名称', name: 'area_name', isdefault: true},
            {display: '首字母', name: 'first_letter'}
        ],
        buttons: [
            {
                display: '<i class="fa fa-plus"></i>新增区域',
                name: 'add',
                bclass: 'add',
                title: '新增区域',
                onpress: function() {
                    location.href = '<?php echo urlAdminShop('vr_groupbuy', 'area_add'); ?>';
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
                    if (ids.length < 1) {
                        return false;
                    }
                    submit_delete(ids.join(','));
                }
            }
        ],
        sortname: "area_id",
        sortorder: "desc",
        title: '虚拟抢购区域列表'
    });
});

</script>
