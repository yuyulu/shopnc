<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_microshop_store_manage'];?></h3>
        <h5><?php echo $lang['nc_microshop_store_manage_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <?php   foreach($output['menu'] as $menu) {  if($menu['menu_type'] == 'text') { ?>
        <li><a href="<?php echo $menu['menu_url'];?>" class="current"><?php echo $menu['menu_name'];?></a></li>
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
      <li><?php echo $lang['microshop_store_add_tip1'];?></li>
    </ul>
  </div>

  <div id="flexigrid"></div>
</div>

<script>
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=store&op=store_add_xml',
        colModel: [
            {display: '操作', name: 'operation', width: 60, sortable: false, align: 'center', className: 'handle-s'},
            {display: '店铺', name: 'store_name', width: 300, sortable: false, align: 'left'},
            {display: '店主账号', name: 'member_name', width: 150, sortable: false, align: 'left'},
            {display: '所在地', name: 'area_info', width: 200, sortable: false, align: 'left'},
            {display: '有效期至', name: 'store_end_time_text', width: 120, sortable: false, align: 'left'},
            {display: '已添加', name: 'added_state', width: 100, sortable: false, align: 'center'}
        ],
        searchitems: [
            {display: '店铺', name: 'store_name', isdefault: true},
            {display: '店主', name: 'member_name'}
        ],
        sortname: "store_id",
        sortorder: "desc",
        title: '商城店铺列表'
    });
});

</script>
