<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_member_predepositmanage'];?></h3>
        <h5><?php echo $lang['nc_member_predepositmanage_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=predeposit&op=predeposit"><?php echo $lang['admin_predeposit_rechargelist']?></a></li>
        <li><a href="JavaScript:void(0);" class="current"><?php echo $lang['admin_predeposit_cashmanage']; ?></a></li>
        <li><a href="index.php?act=predeposit&op=pd_log_list"><?php echo $lang['nc_member_predepositlog'];?></a></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li><?php echo $lang['admin_predeposit_cash_help1'];?></li>
      <li><?php echo $lang['admin_predeposit_cash_help2'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
  <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="ncap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" name="formSearch" id="formSearch">
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>会员名称</dt>
            <dd>
              <label>
                <input type="text" value="" name="member_name" id="goods_name" class="s-input-txt" placeholder="输入会员全称或关键字">
              </label>
            </dd>
          </dl>
          <dl>
            <dt>会员ID</dt>
            <dd>
              <label>
                <input type="text" value="" name="member_id" id="member_id" class="s-input-txt" placeholder="输入会员ID">
              </label>
            </dd>
          </dl>
          <dl>
            <dt>收款人姓名</dt>
            <dd>
              <label>
                <input type="text" value="" name="user_name" id="user_name" class="s-input-txt" placeholder="输入姓名全称或关键字">
              </label>
            </dd>
          </dl>
          <dl>
            <dt>时期筛选</dt>
            <dd>
              <label>
                <input type="text" name="stime" data-dp="1" class="s-input-txt" placeholder="请选择开始时间" />
              </label>
              <label>
                <input type="text" name="etime" data-dp="1" class="s-input-txt" placeholder="请选择结束时间"  />
              </label>
            </dd>
          </dl>
          <dl>
            <dt>支付状态</dt>
            <dd>
              <label>
                <select name="pdc_payment_state" class="s-select">
                  <option value=""><?php echo $lang['nc_please_choose'];?></option>
                  <option value="0">未支付</option>
                  <option value="1">已支付</option>
                </select>
              </label>
            </dd>
          </dl>
        </div>
      </div>
      <div class="bottom"><a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green mr5">提交查询</a><a href="javascript:void(0);" id="ncreset" class="ncap-btn ncap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['nc_cancel_search'];?></a></div>
    </form>
  </div>
</div>
<script type="text/javascript">
$(function(){
    $("input[data-dp='1']").datepicker();
    // 高级搜索提交
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=predeposit&op=get_cash_xml&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });
    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=predeposit&op=get_cash_xml'}).flexReload();
        $("#formSearch")[0].reset();
    });
    
    $("#flexigrid").flexigrid({
        url: 'index.php?act=predeposit&op=get_cash_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle'},
            {display: '提现ID', name : 'pdc_id', width : 40, sortable : true, align: 'center'},
            {display: '提现编号', name : 'pdc_sn', width : 150, sortable : true, align: 'left'},
            {display: '会员ID', name : 'pdc_member_id', width : 40, sortable : true, align: 'center'},
            {display: '会员名称', name : 'pdc_member_name', width : 80, sortable : true, align: 'center'},
            {display: '提现金额（元）', name : 'pdc_amount', width : 100, sortable : true, align: 'left'},
            {display: '申请时间', name : 'pdc_add_time', width : 100, sortable : true, align: 'left'},
            {display: '收款银行', name : 'pdc_bank_name', width : 100, sortable : true, align: 'center'},
            {display: '收款账号', name : 'pdc_bank_no', width : 100, sortable : true, align: 'center'},
            {display: '开户姓名', name : 'pdc_bank_user', width : 100, sortable : true, align: 'center'},
            {display: '支付状态', name : 'pdc_payment_state', width : 100, sortable : true, align: 'center'},
            {display: '支付时间', name : 'pdc_payment_time', width : 100, sortable : true, align: 'center'},
            {display: '管理员', name : 'pdc_payment_admin', width : 60, sortable : true, align: 'center'}
            ],
        buttons : [
                   {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '导出Excel文件', onpress : fg_operation }
               ],
        searchitems : [
            {display: '提现ID', name : 'pdc_id'},
            {display: '提现编号', name : 'pdc_sn'},
            {display: '会员ID', name : 'pdc_member_id'},
            {display: '会员名称', name : 'pdc_member_name'},
            {display: '管理员', name : 'pdc_payment_admin'}
            ],
        sortname: "pdc_id",
        sortorder: "desc",
        title: '预存款充值列表'
    });
});

function fg_operation(name, bDiv) {
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
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&op=export_cash_step1&id=' + id;
}
function fg_delete(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=predeposit&op=pd_cash_del', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg);
            }
        });
    }
}
</script> 