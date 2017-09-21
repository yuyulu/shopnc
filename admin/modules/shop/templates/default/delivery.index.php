<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>物流自提服务站</h3>
        <h5>商城对线下物流自提点的设定集管理</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="<?php echo urlAdminShop('delivery', 'index');?>" <?php if ($output['sign'] != 'verify') {?>class="current"<?php }?>><?php echo $lang['nc_manage'];?></a></li>
        <li><a href="<?php echo urlAdminShop('delivery', 'index', array('sign' => 'verify'));?>" <?php if ($output['sign'] == 'verify') {?>class="current"<?php }?>>等待审核</a></li>
        <!-- <li><a href="<?php echo urlAdminShop('delivery', 'setting');?>">设置</a></li> -->
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
      <li>物流自提服务站关闭后，被用户选择设置成收货地址的记录会被删除，请谨慎操作。</li>
    </ul>
  </div>

  <div id="flexigrid"></div>
</div>

<script>
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=delivery&op=index_xml&sign=<?php echo $output['sign']; ?>',
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '用户名', name: 'dlyp_name', width: 100, sortable: false, align: 'left'},
            {display: '真实姓名', name: 'dlyp_truename', width: 100, sortable: false, align: 'left'},
            {display: '服务站名称', name: 'dlyp_address_name', width: 150, sortable: false, align: 'left'},
            {display: '所在地区', name: 'dlyp_area_info', width: 150, sortable: false, align: 'left'},
            {display: '详细地址', name: 'dlyp_address', width: 150, sortable: false, align: 'left'},
            {display: '状态', name: 'dlyp_state', width: 80, sortable: false, align: 'center'},
            {display: '申请时间', name: 'dlyp_addtime', width: 120, sortable: true, align: 'center'}
        ],
        searchitems: [
            {display: '用户名', name: 'dlyp_name', isdefault: true},
            {display: '真实姓名', name: 'dlyp_truename'},
            {display: '服务站名称', name: 'dlyp_address_name'},
            {display: '所在地区', name: 'dlyp_area_info'},
            {display: '详细地址', name: 'dlyp_address'}
        ],
        sortname: "dlyp_id",
        sortorder: "desc",
        title: '自提服务站列表'
    });
});

$('a.confirm-on-click').live('click', function() {
    return confirm('确定"'+this.innerHTML+'"?');
});
</script>
