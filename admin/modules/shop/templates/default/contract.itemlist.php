<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page">
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>消费者保障服务</h3>
        <h5>消费者保障服务查看与管理</h5>
      </div>
        <ul class="tab-base nc-row">
            <?php   foreach($output['menu'] as $menu) {  if($menu['menu_key'] == $output['menu_key']) { ?>
                <li><a href="JavaScript:void(0);" class="current"><?php echo $menu['menu_name'];?></a></li>
            <?php }  else { ?>
                <li><a href="<?php echo $menu['menu_url'];?>" ><?php echo $menu['menu_name'];?></a></li>
            <?php  } }  ?>
        </ul>
    </div>
  </div>

  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>列表为平台消费者保障服务项目</li>
      <li>当保障项目状态为“开启”时，店铺可以申请加入该服务；状态为“关闭”时，平台将会禁用该保障服务。</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>

<script>
$(function(){
    var flexUrl = 'index.php?act=contract&op=citemlist_xml';
    $("#flexigrid").flexigrid({
        height:'auto',// 高度自动
        usepager: false,// 不翻页
        striped:false,// 不使用斑马线
        resizable: false,// 不调节大小
        reload: false,// 不使用刷新
        columnControl: false,// 不使用列控制
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 120, sortable: false, align: 'right', className: 'handle'},
            {display: '排序', name: 'cti_sort', width: 120, sortable: false, align: 'center'},
            {display: '项目名称', name: 'cti_name', width: 300, sortable: false, align: 'left'},
            {display: '保证金(<?php echo $lang['currency_zh'];?>)', name: 'cti_cost', width: 120, sortable: false, align: 'left'},
            {display: '状态', name: 'cti_state_text', width: 120, sortable: false, align: 'center'}
        ],
        sortname: "cti_sort",
        sortorder: "asc",
        title: '保障服务列表'
    });
});
</script>