<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['member_index_manage']?></h3>
        <h5><?php echo $lang['member_shop_manage_subhead']?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['member_index_help1'];?></li>
      <li><?php echo $lang['member_index_help2'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=member&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '会员ID', name : 'member_id', width : 40, sortable : true, align: 'center'},
            {display: '会员名称', name : 'member_name', width : 150, sortable : true, align: 'left'},
            {display: '会员邮箱', name : 'member_email', width : 150, sortable : true, align: 'left'},
            {display: '会员手机', name : 'member_mobile', width : 80, sortable : true, align: 'center'},
            {display: '会员性别', name : 'member_sex', width : 60, sortable : true, align: 'center'},
            {display: '真实姓名', name : 'member_truename', width : 100, sortable : true, align: 'left'},
            {display: '出生日期', name : 'member_birthday', width : 100, sortable : true, align: 'center'},
            {display: '注册时间', name : 'member_time', width : 100, sortable : true, align: 'center'},
            {display: '最后登录时间', name : 'member_login_time', width : 100, sortable : true, align: 'center'},
            {display: '最后登录IP', name : 'member_login_ip', width : 100, sortable : true, align: 'center'},
            {display: '会员积分', name : 'member_points', width : 60, sortable : true, align: 'center'},
            {display: '会员经验', name : 'member_exppoints', width : 60, sortable : true, align: 'center'},
            {display: '会员等级', name : 'member_grade', width : 60, sortable : false, align: 'center'},
            {display: '可用预存款(元)', name : 'available_predeposit', width : 100, sortable : true, align: 'center', className: 'normal'},
            {display: '冻结预存款(元)', name : 'freeze_predeposit', width : 100, sortable : true, align: 'center', className: 'abnormal'},
            {display: '可用充值卡(元)', name : 'available_rc_balance', width : 100, sortable : true, align: 'center', className: 'normal'},
            {display: '冻结充值卡(元)', name : 'freeze_rc_balance', width : 100, sortable : true, align: 'center', className: 'abnormal'},
            {display: '允许举报', name : 'inform_allow', width : 60, sortable : true, align: 'center'},
            {display: '允许购买', name : 'is_buy', width : 60, sortable : true, align: 'center'},
            {display: '允许咨询', name : 'is_allowtalk', width : 60, sortable : true, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation },
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出CVS文件', onpress : fg_operation }		
            ],
        searchitems : [
            {display: '会员ID', name : 'member_id'},
            {display: '会员名称', name : 'member_name'}
            ],
        sortname: "member_id",
        sortorder: "desc",
        title: '商城会员列表'
    });
	
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=member&op=member_add';
    }
    if (name == 'csv') {
        if ($('.trSelected', bDiv).length == 0) {
            if (!confirm('您确定要下载全部数据吗？')) {
                return false;
            }
        }
        var itemids = new Array();
        $('.trSelected', bDiv).each(function(i){
            itemids[i] = $(this).attr('data-id');
        });
        fg_csv(itemids);
    }
}

function fg_csv(ids) {
    id = ids.join(',');
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&op=export_csv&id=' + id;
}
</script> 

