<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_circle_thememanage'];?></h3>
        <h5><?php echo $lang['nc_circle_thememanage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li><?php echo $lang['circle_theme_prompts_one'];?></li>
      <li><?php echo $lang['circle_theme_prompts_two'];?></li>
      <li><?php echo $lang['circle_theme_prompts_three'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=circle_theme&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '话题ID', name : 'theme_id', width : 40, sortable : true, align: 'left'},
            {display: '话题名称', name : 'theme_name', width : 200, sortable : true, align: 'left'},
            {display: '是否推荐', name : 'is_recommend', width : 80, sortable : true, align: 'center'},
            {display: '附件', name : 'affix', width : 80, sortable : false, align: 'center'},
            {display: '所属圈子', name : 'circle_name', width : 150, sortable : false, align: 'left'},
            {display: '圈子ID', name : 'circle_id', width : 40, sortable : true, align: 'left'},
            {display: '成员ID', name : 'member_id', width : 40, sortable : true, align: 'left'},
            {display: '成员名称', name : 'member_name', width : 120, sortable : false, align: 'left'},
            {display: '成员身份', name : 'is_identity', width : 60, sortable : true, align: 'center'},
            {display: '发表时间', name : 'theme_addtime', width : 120, sortable : true, align: 'left'},
            {display: '喜欢量', name : 'theme_likecount', width : 40, sortable : true, align: 'left'},
            {display: '评论量', name : 'theme_commentcount', width : 40, sortable : true, align: 'left'},
            {display: '浏览量', name : 'theme_browsecount', width : 40, sortable : true, align: 'left'},
            {display: '分享量', name : 'theme_sharecount', width : 40, sortable : true, align: 'left'},
            {display: '置顶', name : 'is_stick', width : 40, sortable : true, align: 'center'},
            {display: '加精', name : 'is_digest', width : 40, sortable : true, align: 'center'},
            {display: '话题类型', name : 'theme_special', width : 60, sortable : true, align: 'center'}
            ],
        searchitems : [
            {display: '话题ID', name : 'theme_id'},
            {display: '话题名称', name : 'theme_name'},
            {display: '圈子ID', name : 'circle_id'},
            {display: '圈子名称', name : 'circle_name'},
            {display: '成员ID', name : 'member_id'},
            {display: '成员名称', name : 'member_name'}
            ],
        sortname: "theme_id",
        sortorder: "desc",
        title: '圈子话题列表'
    });
});

function fg_del(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=circle_theme&op=theme_del', {id:id}, function(data){
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