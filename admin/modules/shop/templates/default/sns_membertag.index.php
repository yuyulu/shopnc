<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['sns_member_tag'];?></h3>
        <h5><?php echo $lang['sns_member_tag_subhead'];?></h5>
      </div>
    </div>
  </div>
  
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li><?php echo $lang['sns_member_index_tips_1'];?></li>
            <li><?php echo $lang['sns_member_index_tips_2'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=sns_member&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '标签ID', name : 'mtag_id', width : 80, sortable : true, align: 'center'},
            {display: '标签名称', name : 'mtag_name', width : 80, sortable : true, align: 'center'},
            {display: '标签排序', name : 'mtag_sort', width : 80, sortable : true, align: 'center'},            
            {display: '标签图片', name : 'mtag_img', width : 80, sortable : false, align: 'center'},
            {display: '标签描述', name : 'mtag_desc', width : 300, sortable : false, align: 'left'},
			{display: '标签推荐', name : 'mtag_recommend', width : 80, sortable : false, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : test }
            ],
        searchitems : [
            {display: '标签ID', name : 'mtag_id'},
            {display: '标签名称', name : 'mtag_name'}
            ],
        sortname: "mtag_id",
        sortorder: "asc",
        title: '商城会员标签列表'
    });
});

function test(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=sns_member&op=tag_add';
    }
}


</script> 