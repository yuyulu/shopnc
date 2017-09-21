<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_store_manage'];?></h3>
        <h5><?php echo $lang['nc_store_manage_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['store_help1'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
    <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
    <div class="ncap-search-bar">
      <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
      <div class="title">
        <h3>高级搜索</h3>
      </div>
      <form method="get" name="formSearch" id="formSearch">
        <div id="searchCon" class="content">
          <div class="layout-box">
            <dl>
              <dt>店铺名称</dt>
              <dd>
                <input type="text" value="" name="store_name" id="store_name" class="s-input-txt">
              </dd>
            </dl>
            <dl>
              <dt>店主账号</dt>
              <dd>
                <input type="text" value="" name="member_name" id="member_name" class="s-input-txt">
              </dd>
            </dl>
            <dl>
              <dt>商家账号</dt>
              <dd>
                <input type="text" value="" name="seller_name" id="seller_name" class="s-input-txt">
              </dd>
            </dl>
            <dl>
              <dt><?php echo $lang['belongs_level'];?></dt>
              <dd>
                <select name="grade_id" class="s-select">
                  <option value=""><?php echo $lang['nc_please_choose'];?></option>
                  <?php if(!empty($output['grade_list'])){ ?>
                  <?php foreach($output['grade_list'] as $k => $v){ ?>
                  <option value="<?php echo $v['sg_id'];?>"><?php echo $v['sg_name'];?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </dd>
            </dl>
            <dl>
              <dt>店铺状态</dt>
              <dd>
                <select name="store_state" class="s-select">
                  <option value=""><?php echo $lang['nc_please_choose'];?></option>
                  <option value="1">开启</option>
                  <option value="0">关闭</option>
                </select>
              </dd>
            </dl>
          </div>
        </div>
        <div class="bottom">
          <a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green">提交查询</a>
          <a href="javascript:void(0);" id="ncreset" class="ncap-btn ncap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['nc_cancel_search'];?></a>
        </div>
      </form>
    </div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=store&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '店铺ID', name : 'store_id', width : 40, sortable : true, align: 'center'},
            {display: '店铺名称', name : 'store_name', width : 150, sortable : false, align: 'left'},
            {display: '店主账号', name : 'member_id', width : 120, sortable : true, align: 'left'},
            {display: '商家账号', name : 'seller_name', width : 120, sortable : false, align: 'left'},            
            {display: '店铺头像', name : 'store_avatar', width: 60, sortable : false, align : 'center'},
            {display: '店铺logo', name : 'store_label', width: 60, sortable : false, align : 'center'},                        
            {display: '店铺等级', name : 'grade_id', width : 80, sortable : true, align: 'center'},
            {display: '开店时间', name : 'store_time', width : 100, sortable : true, align: 'center'},
            {display: '到期时间', name : 'store_end_time', width : 100, sortable : true, align: 'center'},
            {display: '当前状态', name : 'store_state', width : 80, sortable : true, align: 'center'},
            {display: '店铺分类', name : 'sc_id', width : 80, sortable : true, align: 'left'},
            {display: '所在地区', name : 'area_info', width : 150, sortable : false, align : 'left'},
            {display: '详细地址', name : 'store_address', width : 200, sortable : false, align : 'left'},
            {display: 'QQ', name : 'store_qq', width : 80, sortable : false, align : 'left'},
            {display: '旺旺', name : 'store_ww', width : 80, sortable : false, align : 'left'},
            {display: '商家电话', name : 'store_phone', width : 120, sortable : false, align : 'left'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'csv', bclass : 'csv', title : '将选定行数据导出CVS文件', onpress : fg_operation }	,
			{display: '<i class="fa fa-plus"></i>新增数据', name : 'haoshop_add', bclass : 'add', title : '添加一条新数据到列表', onpress : fg_operations }					
        ],
        searchitems : [
            {display: '店铺名称', name : 'store_name', isdefault: true},
            {display: '店主账号', name : 'member_name'},
            {display: '商家账号', name : 'seller_name'}
            ],
        sortname: "store_id",
        sortorder: "asc",
        title: '店铺列表'
    });

    // 高级搜索提交
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=store&op=get_xml&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });

    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=store&op=get_xml'}).flexReload();
        $("#formSearch")[0].reset();
    });
});

function fg_operation(name, bDiv) {
    if (name == 'csv') {
        if ($('.trSelected', bDiv).length == 0) {
            if (!confirm('您确定要下载全部数据吗？')) {
                return false;
            }
        }
        var itemids = new Array();
        $('.trSelected', bDiv).each(function(i){
            itemids[i] = $(this).attr('data-id');
        });
        fg_csv(itemids);
    }
}
function fg_operations(name, bDiv) {
    if (name == 'haoshop_add') {
        window.location.href = 'index.php?act=store&op=haoshop_add';
    }
}

function fg_csv(ids) {
    id = ids.join(',');
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&op=export_csv&id=' + id;
}
</script>