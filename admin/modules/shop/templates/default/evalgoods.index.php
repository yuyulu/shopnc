<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_goods_evaluate']; ?></h3>
        <h5><?php echo $lang['nc_goods_evaluate_subhead']; ?></h5>
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
      <li><?php echo $lang['admin_evaluate_help1'].'，'.$lang['admin_evaluate_help2'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script> 
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>

<script type="text/javascript">
    $(function(){
        $("#flexigrid").flexigrid({
            url: 'index.php?act=evaluate&op=get_goods_xml',
            colModel : [
                {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
                {display: '评价人', name : 'geval_frommembername', width : 70, sortable : true, align: 'left'},
                {display: '评分', name : 'geval_scores', width : 90, sortable : false, align: 'center'},
                {display: '评价内容', name : 'geval_content', width: 250, sortable : false, align : 'left'},
                {display: '晒单图片', name : 'geval_image', width : 190, sortable : false, align : 'left'},
                {display: '评价时间', name : 'geval_addtime', width : 80, sortable : true, align: 'center'},
                {display: '被评商品', name : 'geval_goodsid', width : 150, sortable : true, align : 'left'},
                {display: '所属商家', name : 'geval_storename', width : 120, sortable : true, align: 'left'},
                {display: '订单编号', name : 'geval_orderno', width : 120, sortable : true, align: 'center'},
                {display: '评价人ID', name : 'geval_frommemberid', width : 60, sortable : true, align: 'center'},
                {display: '商家ID', name : 'geval_storeid', width : 40, sortable : true, align: 'center'},
                {display: '追评内容', name : 'geval_content_again', width: 250, sortable : false, align : 'left'},
                {display: '追评晒单', name : 'geval_image_again', width : 190, sortable : false, align : 'left'}
                ],
            buttons : [
                {display: '<i class="fa fa-trash"></i>批量删除', name : 'delete', bclass : 'del', title : '将选定行数据批量删除', onpress : fg_operate }
            ],
            searchitems : [
                {display: '评价人', name : 'geval_frommembername'},
                {display: '被评商品', name : 'geval_goodsname'},
                {display: '所属商家', name : 'geval_storename'}
            ],
            sortname: "geval_addtime",
            sortorder: "desc",
            title: '商品评价列表',
            onSuccess : function(){
                $('.raty').raty({
                    path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
                    readOnly: true,
                    score: function() {
                      return $(this).attr('data-score');
                    }
                });
            	$('a[nctype="nyroModal"]').nyroModal();
            }
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
</script> 
