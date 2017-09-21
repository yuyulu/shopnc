<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_domain_manage'];?></h3>
        <h5><?php echo $lang['nc_domain_manage_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=domain&op=store_domain_setting"><?php echo $lang['nc_config'];?></a></li>
        <li><a href="JavaScript:void(0);" class="current"><?php echo $lang['nc_domain_shop'];?></a></li>
      </ul>
    </div>
  </div>
  <div id="flexigrid"></div>
</div>

<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=domain&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '二级域名', name : 'store_domain', width : 150, sortable : true, align: 'left'},
            {display: '编辑次数', name : 'store_domain_times', width : 80, sortable : true, align: 'center'},
            {display: '店铺ID', name : 'store_id', width : 40, sortable : true, align: 'center'},
            {display: '店铺名称', name : 'store_name', width : 150, sortable : true, align: 'left'},
            {display: '店主名称', name : 'seller_name', width : 150, sortable : true, align: 'left'}
            ],
        searchitems : [
            {display: '二级域名', name : 'store_domain'},
            {display: '店铺ID', name : 'store_id'},
            {display: '店铺名称', name : 'store_name'},
            {display: '店主名称', name : 'seller_name'}
            ],
        sortname: "store_id",
        sortorder: "desc",
        title: '店铺二级域名列表'
    });
	
});
</script> 