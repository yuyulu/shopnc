<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_store_manage'];?></h3>
        <h5><?php echo $lang['nc_store_manage_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li>此处可以对商家续签申请进行查看/审核/删除 操作。</li>
      <li>审核后，系统会自动将店铺的到期时间向后延续，店铺等级不会自动变更，如果新签约的店铺等级发生变更，请手动更改店铺等级。</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=store&op=get_reopen_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '续签ID', name : 're_id', width : 40, sortable : true, align: 'center'},
            {display: '店铺等级', name : 're_grade_id', width : 80, sortable : true, align: 'left'},
            {display: '等级收费(元/年)', name : 're_grade_price', width : 120, sortable : true, align: 'left'},
            {display: '续签时长(年)', name : 're_year', width : 80, sortable : false, align: 'left'},            
            {display: '应付金额(元)', name : 're_pay_amount', width: 120, sortable : false, align : 'center'},
            {display: '店铺ID', name : 're_store_id', width: 60, sortable : true, align : 'center'},                        
            {display: '店铺名称', name : 're_store_name', width : 120, sortable : false, align: 'center'},
            {display: '续签状态', name : 're_state', width : 100, sortable : true, align: 'center'},
            {display: '申请时间', name : 're_create_time', width : 100, sortable : true, align: 'center'},
            {display: '付款凭证', name : 'area_info', width : 80, sortable : false, align : 'center'},
            {display: '付款说明', name : 're_pay_cert_explain', width : 200, sortable : false, align : 'left'},
            {display: '有效期开始时间', name : 're_start_time', width : 100, sortable : false, align: 'center'},
            {display: '有效期结束时间', name : 're_end_time', width : 100, sortable : false, align: 'left'}
            ],
        searchitems : [
            {display: '店铺ID', name : 're_store_id', isdefault: true},
            {display: '店铺名称', name : 're_store_name', isdefault: true}
            ],
        sortname: "re_id",
        sortorder: "desc",
        title: '店铺续签申请列表'
    });
});

function reopen_check(id) {
    if(confirm('审核后，系统会自动将店铺的到期时间向后延续\n店铺等级不会自动变更，如果新签约的店铺等级发生变更，请手动更改店铺等级\n确认审核吗？')){
        $.getJSON('index.php?act=store&op=reopen_check', {id:id}, function(data){
            if (data.state) {
                showSucc(data.msg)
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
function reopen_del(id, store_id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=store&op=reopen_del', {id:id, store_id:store_id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script> 