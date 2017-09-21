<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['g_album_manage'];?></h3>
        <h5><?php echo $lang['g_album_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['g_album_del_tips'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=goods_album&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '相册ID', name : 'aclass_id', width : 40, sortable : true, align: 'left'},
            {display: '相册名称', name : 'aclass_name', width : 120, sortable : true, align: 'left'},
            {display: '店铺ID', name : 'store_id', width : 40, sortable : true, align: 'center'},
            {display: '店铺名称', name : 'store_name', width : 150, sortable : false, align: 'left'},
            {display: '封面图片', name : 'aclass_cover', width : 150, sortable : false, align: 'center'},
            {display: '图片数量', name : 'pic_count', width : 150, sortable : false, align: 'center'},
            {display: '相册描述', name : 'aclass_des', width : 300, sortable : false, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-image-o"></i>全部图片', name : 'add', bclass : 'add', title : '全部图片', onpress : fg_operation }
        ],
        searchitems : [
            {display: '相册ID', name : 'aclass_id'},
            {display: '相册名称', name : 'aclass_name'},
            {display: '店铺ID', name : 'store_id'}
            ],
        sortname: "aclass_id",
        sortorder: "asc",
        title: '相册列表'
    });
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=goods_album&op=pic_list';
    }
}
function fg_del(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=goods_album&op=aclass_del', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>