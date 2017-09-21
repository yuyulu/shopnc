<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="<?php echo urlAdminShop('delivery', 'index');?>" title="返回<?php echo $lang['nc_manage'];?>列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>物流自提服务站 - “<?php echo $output['dlyp_info']['dlyp_address_name'];?>”订单列表</h3>
        <h5>商城对线下物流自提点的设定集管理</h5>
      </div>

    </div>
  </div>

  <div id="flexigrid"></div>
</div>

<script>
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=delivery&op=order_list_xml&dlyp_id=<?php echo $output['dlyp_id']; ?>',
        colModel: [
            {display: '操作', name: 'operation', width: 60, sortable: false, align: 'center', className: 'handle-s'},
            {display: '订单号', name: 'order_sn', width: 150, sortable: false, align: 'left'},
            {display: '运单号', name: 'shipping_code', width: 150, sortable: false, align: 'left'},
            {display: '收货人', name: 'reciver_name', width: 150, sortable: false, align: 'left'},
            {display: '手机号', name: 'reciver_mobphone', width: 150, sortable: false, align: 'left'},
            {display: '座机号', name: 'reciver_telphone', width: 150, sortable: false, align: 'left'},
            {display: '状态', name: 'dlyo_state', width: 80, sortable: false, align: 'center'}
        ],
        searchitems: [
            {display: '订单号', name: 'order_sn', isdefault: true},
            {display: '运单号', name: 'shipping_code'},
            {display: '收货人', name: 'reciver_name'},
            {display: '手机号', name: 'reciver_mobphone'},
            {display: '座机号', name: 'reciver_telphone'}
        ],
        sortname: "order_id",
        sortorder: "desc",
        title: '自提订单列表'
    });
});

</script>
