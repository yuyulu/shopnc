<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>会员统计</h3>
        <h5>平台针对会员的各项数据统计</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/statistics.js"></script> 
<script>
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=stat_member&op=get_member_xml&t=<?php echo $_GET['t'];?>',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '会员名称', name : 'member_name', width : 120, sortable : false, align: 'left'},
            {display: '真实姓名', name : 'member_truename',  width : 100, sortable : false, align: 'left'},
            {display: '邮箱', name : 'member_email',  width : 120, sortable : false, align: 'left'},
            {display: '注册时间', name : 'member_time',  width : 120, sortable : false, align: 'center'},
            {display: '登录次数', name : 'member_login_num',  width : 80, sortable : false, align: 'center'},
            {display: '最后登录时间', name : 'member_login_time',  width : 100, sortable : false, align: 'center'},
            {display: '最后登录IP', name : 'member_login_ip',  width : 100, sortable : false, align: 'center'},
            {display: '旺旺', name : 'member_ww',  width : 50, sortable : false, align: 'center'},
            {display: 'QQ', name : 'member_qq',  width : 50, sortable : false, align: 'center'},
            {display: '积分', name : 'member_points',  width : 50, sortable : false, align: 'center'},
            {display: '可用预存款(元)', name : 'available_predeposit',  width : 100, sortable : false, align: 'center'},
            {display: '冻结预存款(元)', name : 'freeze_predeposit',  width : 100, sortable : false, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'excel', bclass : 'csv', title : '导出EXCEL文件', onpress : fg_operation }
        ],
        usepager: true,
        rp: 15,
        title: '会员详细'
    });
});
function fg_operation(name, bDiv){
    var stat_url = 'index.php?act=stat_member&op=showmember&exporttype=excel&t=<?php echo $_GET['t'];?>';
    get_excel(stat_url,bDiv);
}
</script> 
