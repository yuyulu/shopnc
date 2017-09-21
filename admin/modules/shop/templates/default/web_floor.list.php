<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>频道管理</h3>
        <h5>商城的频道及模块内容管理</h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>模块列表由程序按名称排序，即数字、字母、汉字顺序。</li>
      <li>模块名称只在后台中作为标识使用，在前台页面中不会出现，可在设置中修改。</li>
      <li>显示状态设置为否时，在所有频道中都不会显示该模块，如果只是想让单独一个频道页不显示请在频道编辑页面中修改。</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script>
function update_flex(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=web_channel&op=get_floor_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '模块名称', name : 'web_name', width : 250, sortable : false, align: 'center'},
            {display: '模块类型', name : 'web_page',  width : 100, sortable : false, align: 'center'},
            {display: '更新时间', name : 'update_time', width : 150, sortable : false, align: 'center'},
            {display: '显示状态', name : 'web_show',  width : 100, sortable : false, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增模块', name : 'add', bclass : 'add', title : '新增模块', onpress : fg_operation_add }
        ],
        searchitems : [
            {display: '模块名称', name : 'web_name'}
            ],
        usepager: true,
        rp: 15,
        title: '模块列表'
    });
}
function fg_operation_add(name, bDiv){
    var _url = 'index.php?act=web_channel&op=add_floor';
    window.location.href = _url;
}

$(function(){
	update_flex();
});

</script>