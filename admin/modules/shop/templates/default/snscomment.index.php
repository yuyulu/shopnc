<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=snstrace&op=tracelist" title="返回动态列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['admin_snstrace_manage'];?></h3>
        <h5><?php echo $lang['admin_snstrace_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li><?php echo $lang['admin_snstrace_commentlisttip'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=snstrace&op=get_comment_xml&id=<?php echo $_GET['tid'];?>',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '评论内容', name : 'comment_content', width : 200, sortable : true, align: 'left'},
            {display: '会员名称', name : 'comment_membername', width : 120, sortable : true, align: 'left'},
            {display: '会员ID', name : 'comment_memberid', width : 40, sortable : true, align: 'left'},
            {display: '显示', name : 'comment_state', width : 60, sortable : true, align: 'center'},
            {display: '发表时间', name : 'comment_addtime', width : 120, sortable : true, align: 'center'}
            ],
        searchitems : [
            {display: '评论内容', name : 'comment_content_like'},
            {display: '会员名称', name : 'comment_membername_like'}
            ],
        sortname: "comment_id",
        sortorder: "desc",
        title: '<?php echo $output['title'];?>'
    });
});

function fg_del(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=snstrace&op=commentdel', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>