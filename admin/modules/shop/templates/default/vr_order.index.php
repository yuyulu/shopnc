<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['vr_order_manage'];?></h3>
        <h5><?php echo $lang['vr_order_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li>点击查看操作将显示订单（包括电子兑换码）的详细信息</li>
      <li>未付款的订单可以点击取消操作来取消订单</li>
      <li>如果平台已确认收到买家的付款，但系统支付状态并未变更，可以点击收到货款操作，并填写相关信息后更改订单支付状态</li>
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
              <dt>关键字搜索</dt>
              <dd><label><select class="s-select" name="keyword_type">
                <option selected="selected" value=""><?php echo $lang['nc_please_choose'];?></option>
                <option value="order_sn">订单编号</option>
                <option value="buyer_name">买家账号</option>
                <option value="store_name">店铺名称</option>
                </select></label>
                <label><input type="text" value="" name="keyword" class="s-input-txt" placeholder="请输入关键字"></label>
              <label>
                <input type="checkbox" value="1" name="jq_query">精确
              </label>
              </dd>
            </dl>
            <dl>
              <dt>日期筛选</dt>
              <dd><label><select class="s-select" name="qtype_time">
                <option selected="selected" value=""><?php echo $lang['nc_please_choose'];?></option>
                <option value="add_time">下单时间</option>
                <option value="payment_time">支付时间</option>
                <option value="finnshed_time">完成时间 </option>
                </select></label><label><input readonly id="query_start_date" placeholder="请选择起始时间" class="s-input-txt" name=query_start_date value="" type="text" /></label><label><input readonly id="query_end_date" placeholder="请选择结束时间" class="s-input-txt" name="query_end_date" value="" type="text" /></label></dd>
            </dl>
            <dl>
              <dt>金额筛选</dt>
              <dd><label><input placeholder="请输入起始金额" name=query_start_amount value="" type="text" class="s-input-txt" /></label><label><input placeholder="请输入结束金额" name="query_end_amount" value="" type="text" class="s-input-txt"/></label></dd>
            </dl>
            <dl>
              <dt>支付方式</dt>
              <dd>
              <label><select name="payment_code" class="s-select">
              <option value=""><?php echo $lang['nc_please_choose'];?></option>
              <?php foreach($output['payment_list'] as $val) { ?>
              <option <?php if($_GET['payment_code'] == $val['payment_code']){?>selected<?php }?> value="<?php echo $val['payment_code']; ?>"><?php echo $val['payment_name']; ?></option>
              <?php } ?>
              </select></label>
              </dd>
            </dl>
            <dl>
              <dt>订单状态</dt>
              <dd>
              <select name="order_state" class="s-select">
              <option value=""><?php echo $lang['nc_please_choose'];?></option>
              <option value="10">未支付</option>
              <option value="20">已支付</option>
              <option value="40">已完成</option>
              <option value="0"><?php echo $lang['order_state_cancel'];?></option>
            </select>
            </dd>
            </dl>
            <dl>
              <dt>订单来源</dt>
              <dd>
              <select name="order_from" class="s-select">
              <option value=""><?php echo $lang['nc_please_choose'];?></option>
              <option value="1">网站</option>
              <option value="2">移动端</option>
            </select>
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
	$('#query_start_date').datepicker();
    $('#query_end_date').datepicker();
    // 高级搜索提交
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=vr_order&op=get_xml&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });
    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=vr_order&op=get_xml'}).flexReload();
        $("#formSearch")[0].reset();
    });
    $("#flexigrid").flexigrid({
        url: 'index.php?act=vr_order&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '订单编号', name : 'order_sn', width : 130, sortable : false, align: 'center'},
            {display: '订单来源', name : 'order_from', width : 50, sortable : true, align : 'left'},
            {display: '下单时间', name : 'order_id', width : 120, sortable : true, align: 'center'},
            {display: '订单金额(元)', name : 'order_amount', width : 120, sortable : true, align: 'left'},
            {display: '订单状态', name : 'order_state', width: 60, sortable : true, align : 'center'},
            {display: '支付方式', name : 'payment_code', width: 60, sortable : true, align : 'left'},
            {display: '支付时间', name : 'payment_time', width: 120, sortable : true, align : 'center'},
            {display: '买家账号', name : 'buyer_name', width : 90, sortable : true, align: 'left'},
            {display: '接收手机', name : 'buyer_phone', width : 100, sortable : true, align: 'center'},
            {display: '买家ID', name : 'buyer_id', width : 120, sortable : true, align: 'center'},
            {display: '店铺名称', name : 'store_name', width : 100, sortable : true, align: 'left'},
            {display: '店铺ID', name : 'store_id', width : 150, sortable : true, align: 'center'},
            {display: '商品', name : 'goods_id', width : 120, sortable : true, align: 'left'},
            {display: '有效期', name : 'vr_indate', width : 120, sortable : true, align: 'center'},
            {display: '充值卡支付', name : 'rcb_amount', width : 60, sortable : true, align: 'left'},
            {display: '预存款支付', name : 'pd_amount', width : 60, sortable : true, align: 'left'},
            {display: '完成时间', name : 'finnshed_time', width: 120, sortable : true, align : 'center'},
            {display: '是否评价', name : 'evaluation_state', width : 80, sortable : true, align: 'center'},
            {display: '退款金额', name : 'refund_amount', width : 80, sortable : true, align: 'left'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出excel文件,如果不选中行，将导出列表所有数据', onpress : fg_operate }        ],
        searchitems : [
            {display: '订单编号', name : 'order_sn', isdefault: true},
            {display: '买家账号', name : 'buyer_name'},
            {display: '店铺名称', name : 'store_name'}
            ],
        sortname: "order_id",
        sortorder: "desc",
        title: '线上交易虚拟订单明细'
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
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&op=export_step1&order_id=' + id;
}
function fg_cancel(id) {
	if (typeof id == 'number') {
    	var id = new Array(id.toString());
	};
	if(confirm('取消后将不能恢复，确认取消这 ' + id.length + ' 项吗？')){
		id = id.join(',');
	} else {
        return false;
    }
	$.ajax({
        type: "GET",
        dataType: "json",
        url: "index.php?act=vr_order&op=change_state&state_type=cancel",
        data: "order_id="+id,
        success: function(data){
            if (data.state){
                $("#flexigrid").flexReload();
            } else {
            	alert(data.msg);
            }
        }
    });
}
</script> 