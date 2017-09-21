<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['complain_manage_title'];?></h3>
        <h5><?php echo $lang['complain_manage_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['complain_help1'];?></li>
      <li><?php echo $lang['complain_help2'];?></li>
      <li><?php echo $lang['complain_help3'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=complain&op=get_new_xml&state=<?php echo $_GET['state'];?>',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '投诉人', name : 'accuser_name', width : 70, sortable : true, align: 'left'},
            {display: '投诉内容', name : 'complain_content', width: 300, sortable : false, align : 'left'},
            {display: '投诉图片', name : 'complain_pic', width: 70, sortable : false, align : 'left'},
            {display: '投诉时间', name : 'complain_id', width : 120, sortable : true, align: 'center'},
            {display: '投诉主题', name : 'complain_subject_content', width: 100, sortable : false, align : 'left'},
            <?php if (in_array($_GET['state'],array(30,40))) { ?>
            {display: '申诉内容', name : 'appeal_message', width: 250, sortable : false, align : 'left'},
            {display: '申诉图片', name : 'appeal_pic', width: 70, sortable : false, align : 'left'},
            {display: '申诉时间', name : 'appeal_datetime', width : 120, sortable : true, align: 'center'},
            <?php } ?>
            <?php if (in_array($_GET['state'],array(99))) { ?>
            {display: '最终处理', name : 'final_handle_message', width: 200, sortable : false, align : 'left'},
            {display: '处理时间', name : 'final_handle_datetime', width : 120, sortable : true, align: 'center'},
            <?php } ?>
            {display: '被投商家', name : 'accused_name', width : 120, sortable : true, align: 'left'},
            {display: '投诉人ID', name : 'accuser_id', width : 60, sortable : true, align: 'center'},
            {display: '商家ID', name : 'accused_id', width : 40, sortable : true, align: 'center'}
            ],
        searchitems : [
            {display: '投诉人', name : 'complain_accuser'},
            {display: '被投商家', name : 'complain_accused'}
        ],
        sortname: "complain_id",
        sortorder: "desc",
        title: '商家投诉列表'
    });
});
function fg_operate(name, grid) {
    if (name == 'delete') {
        if($('.trSelected',grid).length>0){
            var itemlist = new Array();
            $('.trSelected',grid).each(function(){
            	itemlist.push($(this).attr('data-id'));
            });
            fg_delete(itemlist);
        } else {
            return false;
        }
    }
}

function fg_delete(id) {
	if (typeof id == 'number') {
    	var id = new Array(id.toString());
	};
	if(confirm('删除后将不能恢复，确认删除这 ' + id.length + ' 项吗？')){
		id = id.join(',');
	} else {
        return false;
    }
	$.ajax({
        type: "GET",
        dataType: "json",
        url: "index.php?act=evaluate&op=evalgoods_del",
        data: "geval_id="+id,
        success: function(data){
            if (data.state){
                $("#flexigrid").flexReload();
            } else {
            	alert(data.msg);
            }
        }
    });
}
$(document).ready(function(){
	//表格移动变色
	$("tbody .line").hover(
    function()
    {
        $(this).addClass("complain_highlight");
    },
    function()
    {
        $(this).removeClass("complain_highlight");
    });
    $('#time_from').datepicker({dateFormat: 'yy-mm-dd',onSelect:function(dateText,inst){
        var year2 = dateText.split('-') ;
        $('#time_to').datepicker( "option", "minDate", new Date(parseInt(year2[0]),parseInt(year2[1])-1,parseInt(year2[2])) );
    }});
    $('#time_to').datepicker({onSelect:function(dateText,inst){
        var year1 = dateText.split('-') ;
        $('#time_from').datepicker( "option", "maxDate", new Date(parseInt(year1[0]),parseInt(year1[1])-1,parseInt(year1[2])) );
    }});

});
</script> 
