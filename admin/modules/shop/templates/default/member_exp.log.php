<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>经验值管理</h3>
        <h5>商城会员经验值设定及获取日志</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="JavaScript:void(0);" class="current">经验值明细</a></li>
        <li><a href="index.php?act=member_exp&op=expsetting">规则设置</a></li>
        <li><a href="index.php?act=member_exp&op=member_grade">等级设定</a></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>经验值明细，展示了会员经验值增减情况的详细情况记录，经验值前有符号“-”表示减少，无符号表示增加</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=member_exp&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '日志ID', name : 'exp_id', width : 40, sortable : true, align: 'center'},
            {display: '会员ID', name : 'exp_memberid', width : 40, sortable : true, align: 'center'},
            {display: '会员名称', name : 'exp_membername', width : 150, sortable : true, align: 'left'},
            {display: '经验值', name : 'exp_points', width : 120, sortable : true, align: 'center'},
            {display: '添加时间', name : 'exp_addtime', width : 120, sortable : true, align: 'center'},
            {display: '操作阶段', name : 'exp_stage', width : 120, sortable : false, align: 'left'},
            {display: '操作描述', name : 'exp_desc', width : 120, sortable : false, align: 'left'}
            ],
        searchitems : [
            {display: '会员ID', name : 'exp_memberid'},
            {display: '会员名称', name : 'exp_membername'}
            ],
        sortname: "exp_id",
        sortorder: "desc",
        title: '经验值明细'
    });
});
</script>
