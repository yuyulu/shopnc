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
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>平台可为店铺设置不同的结算周期（单位：天），默认各店铺的结算周期为一个自然月</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=store&op=get_bill_cycle_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 80, sortable : false, align: 'center', className: 'handle-s'},
            {display: '店铺ID', name : 'store_id', width : 60, sortable : true, align: 'center'},
            {display: '店铺名称', name : 'store_name', width : 200, sortable : false, align: 'left'},
            {display: '商家账号', name : 'seller_name', width : 150, sortable : false, align: 'left'},
            {display: '结算周期(天)', name : 'bill_cycle', width : 100, sortable : true, align: 'center'},
            {display: '店铺分类', name : 'sc_id', width : 100, sortable : true, align: 'left'},
            {display: '商家电话', name : 'store_phone', width : 150, sortable : false, align : 'left'}
            ],
        searchitems : [
            {display: '店铺名称', name : 'store_name', isdefault: true},
            {display: '商家账号', name : 'seller_name'}
            ],
        sortname: "store_id",
        sortorder: "asc",
        title: '店铺结算周期列表'
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
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&op=export_csv&id=' + id;
}
</script>