<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_pointprod'];?></h3>
        <h5><?php echo $lang['nc_pointprod_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="JavaScript:void(0);" class="current"><?php echo $lang['admin_pointprod_list_title'];?></a></li>
        <li><a href="index.php?act=pointprod&op=pointorder_list" ><?php echo $lang['admin_pointorder_list_title'];?></a></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['pointprod_help1'];?></li>
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
        <input type="hidden" name="advanced" value="1" />
        <div id="searchCon" class="content">
          <div class="layout-box">
            <dl>
              <dt>礼品名称</dt>
              <dd>
                <input type="text" name="pgoods_name" class="s-input-txt" placeholder="请输入礼品名称关键字" />
              </dd>
            </dl>
            <dl>
              <dt>上架</dt>
              <dd>
                <select name="pgoods_show" class="s-select">
                    <option value="">全部</option>
                    <option value="1">是</option>
                    <option value="0">否</option>
                </select>
              </dd>
            </dl>
            <dl>
              <dt>推荐</dt>
              <dd>
                <select name="pgoods_commend" class="s-select">
                    <option value="">全部</option>
                    <option value="1">是</option>
                    <option value="0">否</option>
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
<script>
$(function(){
    var flexUrl = 'index.php?act=pointprod&op=pointprod_xml';

    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '礼品名称', name: 'pgoods_name', width: 300, sortable: false, align: 'left'},
            {display: '礼品图片', name: 'pgoods_image_url', width: 80, sortable: false, align: 'left'},
            {display: '兑换积分', name: 'pgoods_points', width: 80, sortable: 1, align: 'left'},
            {display: '礼品原价', name: 'pgoods_price', width: 80, sortable: 1, align: 'center'},
            {display: '库存', name: 'pgoods_storage', width: 50, sortable: 1, align: 'center'},
            {display: '浏览', name: 'pgoods_view', width: 50, sortable: 1, align: 'center'},
            {display: '售出', name: 'pgoods_salenum', width: 50, sortable: 1, align: 'center'},
            {display: '上架', name: 'pgoods_show_onoff', width: 50, sortable: false, align: 'center'},
            {display: '推荐', name: 'pgoods_commend_onoff', width: 50, sortable: false, align: 'center'}
        ],
        buttons: [
            {
                display: '<i class="fa fa-plus"></i>新增礼品',
                name: 'add',
                bclass: 'add',
                title: '新增积分兑换礼品',
                onpress: function() {
                    location.href = 'index.php?act=pointprod&op=prod_add';
                }
            },
            {
                display: '<i class="fa fa-trash"></i>批量删除',
                name: 'del',
                bclass: 'del',
                title: '将选定行数据批量删除',
                onpress: function() {
                    var ids = [];
                    $('.trSelected[data-id]').each(function() {
                        ids.push($(this).attr('data-id'));
                    });
                    if (ids.length < 1 || !confirm('确定删除?')) {
                        return false;
                    }

                    var href = '<?php echo urlAdminShop('pointprod', 'prod_dropall', array(
                        'pg_id' => '__IDS__',
                    )); ?>'.replace('__IDS__', ids.join(','));

                    $.getJSON(href, function(d) {
                        if (d && d.result) {
                            $("#flexigrid").flexReload();
                        } else {
                            alert(d && d.message || '操作失败！');
                        }
                    });
                }
            }
        ],
        searchitems: [
            {display: '礼品名称', name: 'pgoods_name', isdefault: true}
        ],
        sortname: "pgoods_id",
        sortorder: "desc",
        title: '积分商品列表'
    });

    // 高级搜索提交
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: flexUrl + '&' + $("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });

    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions({url: flexUrl}).flexReload();
        $("#formSearch")[0].reset();
    });

});

$('a[data-href]').live('click', function() {
    if ($(this).hasClass('confirm-del-on-click') && !confirm('确定删除?')) {
        return false;
    }

    $.getJSON($(this).attr('data-href'), function(d) {
        if (d && d.result) {
            $("#flexigrid").flexReload();
        } else {
            alert(d && d.message || '操作失败！');
        }
    });
});

$('a[data-ie-column]').live('click', function() {
    $.get('<?php echo urlAdminShop('pointprod', 'ajax'); ?>', {
        column: $(this).attr('data-ie-column'),
        value: $(this).attr('data-ie-value'),
        id: $(this).parents('tr').attr('data-id')
    }, function(d) {
        if (d != 'true') {
            alert('操作失败！');
            return false;
        }
        $("#flexigrid").flexReload();
    });
});

</script>
