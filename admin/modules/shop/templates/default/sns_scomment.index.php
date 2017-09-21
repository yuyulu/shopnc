<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=sns_strace" title="返回动态列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['admin_snsstore_manage'];?></h3>
        <h5><?php echo $lang['admin_snsstore_manage_subhead'];?></h5>
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
        url: 'index.php?act=sns_strace&op=get_scomm_xml&id=<?php echo $_GET['st_id'];?>',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '评论内容', name : 'scomm_content', width : 200, sortable : true, align: 'left'},
            {display: '会员名称', name : 'scomm_membername', width : 120, sortable : true, align: 'left'},
            {display: '会员ID', name : 'scomm_memberid', width : 40, sortable : true, align: 'left'},
            {display: '显示', name : 'scomm_state', width : 60, sortable : true, align: 'center'},
            {display: '发表时间', name : 'scomm_time', width : 120, sortable : true, align: 'center'}
            ],
        searchitems : [
            {display: '评论内容', name : 'scomm_content'},
            {display: '会员名称', name : 'scomm_membername'},
            {display: '会员ID', name : 'scomm_memberid'}
            ],
        sortname: "scomm_id",
        sortorder: "desc",
        title: '<?php echo $output['title'];?>'
    });
});

function fg_del(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=sns_strace&op=scomm_del', {id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>