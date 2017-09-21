<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_member_album_manage'];?></h3>
        <h5><?php echo $lang['nc_member_album_manage_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="JavaScript:void(0);" class="current"><?php echo $lang['snsalbum_class_list'];?></a></li>
        <li><a href="index.php?act=sns_malbum&op=setting"><?php echo $lang['snsalbum_album_setting'];?></a></li>
      </ul>
    </div>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=sns_malbum&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '相册ID', name : 'ac_id', width : 40, sortable : true, align: 'left'},
            {display: '相册名称', name : 'ac_name', width : 120, sortable : true, align: 'left'},
            {display: '会员ID', name : 'member_id', width : 40, sortable : true, align: 'center'},
            {display: '会员名称', name : 'member_name', width : 60, sortable : false, align: 'center'},
            {display: '封面图片', name : 'ac_cover', width : 60, sortable : false, align: 'center'},
            {display: '图片数量', name : 'pic_count', width : 40, sortable : false, align: 'center'},
            {display: '相册描述', name : 'ac_des', width : 200, sortable : false, align: 'center'}
            ],
        buttons : [
                   {display: '<i class="fa fa-file-image-o"></i>全部图片', name : 'add', bclass : 'add', title : '全部图片', onpress : fg_operation }
               ],
        searchitems : [
            {display: '相册ID', name : 'ac_id'},
            {display: '相册名称', name : 'ac_name'},
            {display: '会员ID', name : 'member_id'}
            ],
        sortname: "ac_id",
        sortorder: "asc",
        title: '相册列表'
    });
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=sns_malbum&op=pic_list';
    }
}
</script>