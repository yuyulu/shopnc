<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=sns_member" title="返回会员标签列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['sns_member_tag'];?> - 标签“<?php echo $output['mt_info']['mtag_name'];?>”下属会员</h3>
        <h5><?php echo $lang['sns_member_tag_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['sns_member_member_list_tips'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=sns_member&op=get_tm_xml&id=<?php echo $output['mt_info']['mtag_id'];?>',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '会员ID', name : 'member_id', width : 80, sortable : true, align: 'center'},
            {display: '会员名称', name : 'member_name', width : 80, sortable : true, align: 'center'},
			{display: '推荐', name : 'mtag_recommend', width : 80, sortable : false, align: 'center'}
            ],
        searchitems : [
            {display: '会员ID', name : 'member_id'}
            ],
        sortname: "member_id",
        sortorder: "asc",
        title: '标签“<?php echo $output['mt_info']['mtag_name'];?>” 下属会员列表'
    });
});
</script> 