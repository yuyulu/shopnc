<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>虚拟订单结算</h3>
        <h5>虚拟商品订单结算索引及商家账单表</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a class="current" href="JavaScript:void(0);">结算管理</a></li>
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
      <li>账单计算公式：消费金额(已消费的虚拟码 + 已过期未消费但不退款的虚拟码) - 佣金</li>
      <li>账单处理流程为：系统出账 > 商家确认 > 平台审核 > 财务支付(完成结算) 4个环节，其中平台审核和财务支付需要平台介入，请予以关注</li>
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
              <dt>账单编号</dt>
              <dd>
                <input type="text" value="" name="ob_id" id="ob_id" class="s-input-txt">
              </dd>
            </dl>
            <dl>
              <dt>商家名称</dt>
              <dd>
              <label><input type="text" value="" name=ob_store_name id="ob_store_name" class="s-input-txt"></label>
              <label><input type="checkbox" value="1" name="jq_query">精确</label>
              </dd>
            </dl>
            <dl>
              <dt>结算状态</dt>
              <dd>
                    <select class="s-select" name="ob_state">
                    <option value="">-请选择-</option>
                    <option value="1">已经出账</option>
                    <option value="2">商家已确认</option>
                    <option value="3">平台已审核</option>
                    <option value="4">结算完成</option>
                    </select>
              </dd>
            </dl>
            <dl>
              <dt>结账月份</dt>
              <dd>
                <select name="query_year">
                <option value=""> 年份&nbsp;&nbsp;</option>
                <?php for($i=date('Y',time())-4;$i<=date('Y',time())+4;$i++) { ?>
                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php } ?>
                </select> - 
                <select name="query_month">
                <option value=""> 月份&nbsp;&nbsp;</option>
                <?php for($i=1;$i<=12;$i++) { ?>
                <option value="<?php echo str_pad($i,2,'0',STR_PAD_LEFT);?>"><?php echo $i;?></option>
                <?php } ?>
                </select>
              </dd>
              </dd>
            </dl>
          </div>
        </div>
        <div class="bottom">
          <a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green">提交查询</a>
          <a href="javascript:void(0);" id="ncreset" class="ncap-btn ncap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['nc_cancel_search'];?></a>
        </div>
      </form>
    </div>
</div>
<script type="text/javascript">
$(function(){
    // 高级搜索提交
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=vr_bill&op=get_bill_xml&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });

    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=vr_bill&op=get_bill_xml'}).flexReload();
        $("#formSearch")[0].reset();
    });
    $("#flexigrid").flexigrid({
        url: 'index.php?act=vr_bill&op=get_bill_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '账单编号', name : 'ob_id', width : 70, sortable : true, align: 'center'}, 
			{display: '开始日期', name : 'ob_start_date', width : 80, sortable : true, align : 'center'},           
			{display: '结束日期', name : 'ob_end_date', width : 80, sortable : true, align: 'center'},
			{display: '订单金额', name : 'ob_order_totals', width : 110, sortable : true, align: 'left'},
            {display: '收取佣金', name : 'ob_commis_totals', width : 70, sortable : true, align: 'left'},
            {display: '本期应结', name : 'ob_result_totals', width : 80, sortable : true, align: 'left'},
            {display: '出账日期', name : 'ob_create_date', width : 70, sortable : true, align: 'center'},
            {display: '账单状态', name : 'ob_state', width : 100, sortable : true, align: 'left'},
            {display: '商家名称', name : 'ob_store_name', width : 130, sortable : false, align: 'left'},
            {display: '商家ID', name : 'ob_store_id', width : 90, sortable : true, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出csv文件,如果不选中行，将导出列表所有数据', onpress : fg_operate}
        ],
        searchitems : [
       {display: '账单编号', name : 'ob_id', isdefault: true},
	   {display: '原账单编号', name : 'ob_no', isdefault: true},
       {display: '商家名称', name : 'ob_store_name'}
       ],
        sortname: "ob_id",
        sortorder: "desc",
        title: '账单列表'
    });
});
function fg_operate(name, grid) {
    if (name == 'csv') {
    	var itemlist = new Array();
        if($('.trSelected',grid).length>0){
            $('.trSelected',grid).each(function(){
            	itemlist.push($(this).attr('data-id'));
            });
        }
        fg_csv(itemlist);
    }
}
function fg_csv(ids) {
    id = ids.join(',');
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&op=export_bill&ob_id='+id;
}
</script> 
