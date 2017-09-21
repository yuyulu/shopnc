<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_circle_thememanage'];?></h3>
        <h5><?php echo $lang['nc_circle_thememanage_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="index.php?act=circle_theme&op=theme_list"><?php echo $lang['circle_theme_list'];?></a></li>
        <li><a href="index.php?act=circle_theme&op=theme_info&t_id=<?php echo $output['t_id'];?>"><?php echo $lang['circle_theme_info'];?></a></li>
        <li><a href="JavaScript:void(0);" class="current"><?php echo $lang['circle_reply_list'];?></a></li>
      </ul>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=circle_theme&op=get_reply_xml&t_id=<?php echo $_GET['t_id'];?>',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '回复内容', name : 'reply_content', width : 200, sortable :false, align: 'left'},
            {display: '成员名称', name : 'member_name', width : 60, sortable : false, align: 'left'},
            {display: '回复时间', name : 'reply_addtime', width : 80, sortable : true, align: 'center'}
            ],
        searchitems : [
            {display: '成员名称', name : 'member_name'}
            ],
        sortname: "reply_addtime",
        sortorder: "desc",
        title: '圈子话题回复列表'
    });
});

function fg_del(t_id, r_id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=circle_theme&op=theme_replydel', {t_id:t_id, r_id:r_id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}

function fg_recommend(id, value) {
    if (value == 1 && !confirm('把有附件的话题推荐圈子首页？')) {
        return false;
    }
    $.getJSON('index.php?act=circle_theme&op=theme_recommend', {id:id, value:value}, function(data){
        if (data.state) {
            $("#flexigrid").flexReload();
        } else {
            showError(data.msg)
        }
    });
}
</script>