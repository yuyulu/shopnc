<?php defined('In33hao') or exit('Access Invalid!');?>
<style type="text/css">
.waybill-img-thumb { background-color: #FFF; vertical-align: top; display: inline-block; *display: inline; width: 70px; height: 45px; padding: 1px; border: solid 1px #E6E6E6; *zoom: 1;}
.waybill-img-thumb a { line-height: 0; text-align: center; vertical-align: middle; display: table-cell; *display: block; width: 70px; height: 45px; overflow: hidden;}
.waybill-img-thumb a img { max-width: 70px; max-height: 45px; margin-top:expression(45-this.height/2); *margin-top:expression(22-this.height/2)/*IE6,7*/;}
.waybill-img-size { color: #777; line-height: 20px; vertical-align: top; display: inline-block; *display: inline; margin-left: 10px; *zoom: 1;}
</style>
<div class="page"> 
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>运单模板</h3>
        <h5>预设供商家选择的运单快递模板</h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li>平台现有运单模板列表</li>
      <li>点击设计按钮可以对运单模板布局进行设计，点击测试按钮可以对模板进行打印测试，点击编辑按钮可以对模板参数进行调整</li>
      <li>设计完成后在编辑中修改模板状态为启用后，商家就可以绑定该模板进行运单打印</li>
      <li>点击删除按钮可以删除现有模板，删除后所有使用该模板的商家将自动解除绑定，请慎重操作</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>

<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=waybill&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '模板名称', name : 'waybill_name', width : 80, sortable : true, align: 'left'},
            {display: '快递公司', name : 'express_name', width : 80, sortable : true, align: 'left'},
            {display: '运单图例', name : 'waybill_image', width : 60, sortable : true, align: 'center'},
            {display: '宽度(mm)', name : 'waybill_width', width : 60, sortable : true, align: 'center'},
            {display: '高度(mm)', name : 'waybill_height', width : 60, sortable : true, align: 'center'},
            {display: '启用', name : 'waybill_usable', width : 60, sortable : true, align: 'center'},
            {display: '上偏移', name : 'waybill_top', width : 60, sortable : true, align: 'center'},
            {display: '左偏移', name : 'waybill_left', width : 60, sortable : true, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operation }
        ],
        searchitems : [
            {display: '模板名称', name : 'waybill_name'},
            {display: '快递公司', name : 'express_name'}
            ],
        sortname: "waybill_id",
        sortorder: "asc",
        title: '运单模板列表'
    });
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=waybill&op=waybill_add';
    }
}
function fg_del(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=waybill&op=waybill_del', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>
