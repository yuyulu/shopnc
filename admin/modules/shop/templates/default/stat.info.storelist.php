<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>店铺统计</h3>
        <h5>平台针对店铺的各项数据统计</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/statistics.js"></script> 
<script>
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=stat_store&op=get_store_xml&provid=<?php echo $_GET['provid'];?>&scid=<?php echo $_GET['scid'];?>&t=<?php echo $_GET['t'];?>',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '店铺名称', name : 'store_name', width : 150, sortable : false, align: 'center'},
            {display: '店主账号', name : 'member_name',  width : 120, sortable : false, align: 'center'},
            {display: '店主商家账号', name : 'seller_name',  width : 120, sortable : false, align: 'center'},
            {display: '所属等级', name : 'grade_id',  width : 120, sortable : false, align: 'center'},
            {display: '有效期至', name : 'store_end_time',  width : 120, sortable : false, align: 'center'},
            {display: '开店时间', name : 'store_time',  width : 120, sortable : false, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'excel', bclass : 'csv', title : '导出EXCEL文件', onpress : fg_operation }
        ],
        usepager: true,
        rp: 15,
        title: '店铺详细'
    });
});
function fg_operation(name, bDiv){
    var stat_url = 'index.php?act=stat_store&op=showstore&exporttype=excel&provid=<?php echo $_GET['provid'];?>&scid=<?php echo $_GET['scid'];?>&t=<?php echo $_GET['t'];?>';
    get_excel(stat_url,bDiv);
}
</script> 
