<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>地区设置</h3>
        <h5>可对系统内置的地区进行编辑</h5>
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
      <li>全站所有涉及的地区均来源于此处，强烈建议对此处谨慎操作。</li>
      <li>编辑地区信息后，需手动更新地区缓存(平台  > 设置 > 清理缓存 > 地区)，前台才会生效。</li>
      <li>所属大区为默认的全国性的几大区域，只有省级地区才需要填写大区域，目前全国几大区域有：华北、东北、华东、华南、华中、西南、西北、港澳台、海外</li>
      <li>所在层级为该地区的所在的层级深度，如北京>北京市>朝阳区,其中北京层级为1，北京市层级为2，朝阳区层级为3</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
//定义变量，点击返回上一级、新增分类自动获取当前父类时用到
var his_parent_ids = [0],cur_parent_id = 0;

$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=area&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '地区', name : 'area_name', width : 200, sortable : false, align: 'left'},
            {display: '所属大区', name : 'area_region', width : 120, sortable : false, align: 'left'},
			{display: '所在层级', name : 'area_deep', width : 100, sortable : false, align : 'left'},
			{display: '上级地区ID', name : 'area_parent_id', width : 140, sortable : false, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增数据', name : 'add', bclass : 'add', title : '新增数据', onpress : fg_operate },
            {display: '<i class="fa fa-trash"></i>批量删除', name : 'delete', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operate },
            {display: '<i class="fa fa-level-up"></i>返回上级地区', name : 'up', bclass : 'up', title : '返回上级地区', onpress : fg_operate }
            ],
        searchitems : [
            {display: '地区', name : 'area_name'}
            ],
        sortname: "",
        sortorder: "",
        rp: 40,
        title: '地区列表'
    });
});
function fg_operate(name, grid) {
    if (name == 'add') {
        window.location.href = 'index.php?act=area&op=add&parent_id='+cur_parent_id;
    }else if (name == 'delete') {
        if($('.trSelected',grid).length>0){
            var itemlist = new Array();
            $('.trSelected',grid).each(function(){
            	itemlist.push($(this).attr('data-id'));
            });
            fg_delete(itemlist);
        } else {
            return false;
        }
    }else if (name == 'up') {
    	fg_up();
    }
}

function fg_delete(id) {
	if (typeof id == 'number') {
    	var id = new Array(id.toString());
	};
	if(confirm('系统将会把选中地区及所有子地区删除，确认操作吗？')){
		id = id.join(',');
	} else {
        return false;
    }
	$.ajax({
        type: "GET",
        dataType: "json",
        url: "index.php?act=area&op=del",
        data: "area_id="+id,
        success: function(data){
            if (data.state){
                $("#flexigrid").flexReload();
            } else {
            	alert(data.msg);
            }
        }
    });
}

function fg_show_children(area_id,parent_id) {
	$("#flexigrid").flexOptions({url: 'index.php?act=area&op=get_xml&parent_id='+area_id}).flexReload();
	his_parent_ids.push(parent_id);
	cur_parent_id = area_id;
}

function fg_up() {
	if (his_parent_ids.length == 0) {
		his_parent_ids.push(0);
	}
	$("#flexigrid").flexOptions({url: 'index.php?act=area&op=get_xml&parent_id='+his_parent_ids[his_parent_ids.length-1]}).flexReload();
	cur_parent_id = his_parent_ids[his_parent_ids.length-1];
	his_parent_ids.pop();
}
</script> 
