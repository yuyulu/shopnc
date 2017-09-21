<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <a class="back" href="index.php?act=activity&op=activity" title="返回活动列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['activity_index_manage']; ?> - 处理“<?php echo $output['activity_detail']['activity_title']; ?>”活动的申请</h3>
        <h5><?php echo $lang['activity_index_manage_subhead']; ?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['activity_detail_index_tip1'];?></li>
      <li><?php echo $lang['activity_detail_index_tip2'];?></li>
      <li><?php echo $lang['activity_detail_index_tip3'];?></li>
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
            <dt>商品名称</dt>
            <dd>
              <label><input type="text" name="item_name" class="s-input-txt" placeholder="请输入商品名称关键字" /></label>
            </dd>
          </dl>
          <dl>
            <dt>所属店铺</dt>
            <dd>
              <label><input type="text" name="store_name" class="s-input-txt"  placeholder="请输入商品所属店铺名称"/></label>
            </dd>
          </dl>
          <dl>
            <dt>处理状态</dt>
            <dd>
              <select name="activity_detail_state" class="s-select">
                <option value="">-请选择-</option>
                <?php foreach ((array) $output['states'] as $k => $v) { ?>
                <option value="<?php echo (string) $k; ?>"><?php echo $v; ?></option>
                <?php } ?>
              </select>
            </dd>
          </dl>
        </div>
      </div>
      <div class="bottom"> <a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green">提交查询</a> <a href="javascript:void(0);" id="ncreset" class="ncap-btn ncap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['nc_cancel_search'];?></a> </div>
    </form>
  </div>
</div>
<script>
$(function(){
    var flexUrl = 'index.php?act=activity&op=detail_xml&id=<?php echo $output['activity_detail']['activity_id']; ?>';

    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '排序', name: 'activity_detail_sort', width: 100, sortable: 1, align: 'left'},
            {display: '商品名称', name: 'item_name', width: 420, sortable: false, align: 'left'},
            {display: '所属店铺', name: 'store_name', width: 150, sortable: false, align: 'center'},
            {display: '状态', name: 'activity_detail_state_text', width: 80, sortable: 1, align: 'center'}
        ],
        buttons: [
            {
                display: '<i class="fa fa-check"></i>批量通过',
                name: 'add',
                bclass: 'add',
                title: '将选定行数据批量通过',
                onpress: function() {
                    var ids = [];
                    $('.trSelected[data-id]').each(function() {
                        ids.push($(this).attr('data-id'));
                    });
                    if (ids.length < 1 || !confirm('确定批量通过?')) {
                        return false;
                    }
                    var href = 'index.php?act=activity&op=deal&state=1&activity_detail_id=__IDS__'.replace('__IDS__', ids.join(','));
                    performReq(href);
                }
            },
            {
                display: '<i class="fa fa-ban"></i>批量拒绝',
                name: 'csv',
                bclass: 'csv',
                title: '将选定行数据批量拒绝',
                onpress: function() {
                    var ids = [];
                    $('.trSelected[data-id]').each(function() {
                        ids.push($(this).attr('data-id'));
                    });
                    if (ids.length < 1 || !confirm('确定批量拒绝?')) {
                        return false;
                    }
                    var href = 'index.php?act=activity&op=deal&state=2&activity_detail_id=__IDS__'.replace('__IDS__', ids.join(','));
                    performReq(href);
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
                    var href = 'index.php?act=activity&op=del_detail&activity_detail_id=__IDS__'.replace('__IDS__', ids.join(','));
                    performReq(href);
                }
            }
        ],
        searchitems: [
            {display: '商品名称', name: 'item_name', isdefault: true},
            {display: '所属店铺', name: 'store_name'}
        ],
        sortname: "activity_detail_id",
        sortorder: "desc",
        title: '活动商品申请列表'
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

    var performReq = function(url) {
        $.getJSON(url, function(d) {
            if (d && d.result) {
                $("#flexigrid").flexReload();
            } else {
                alert(d && d.message || '操作失败！');
            }
        });
    };

    $('a[data-href]').live('click', function() {
        if ($(this).hasClass('confirm-on-click') && !confirm('确定"'+this.innerHTML+'"?')) {
            return false;
        }

        performReq($(this).attr('data-href'));
    });

});

$("span[data-live-inline-edit='activity_detail_sort']").live('click', function() {
    var $this = $(this);
    var $input = $('<input type="text" style="width:50px;">');
    $input.val(parseInt($this.html()) || 0);
    $this.after($input);
    $this.hide();
    $input.focus();
    $input.change(function() {
        var v2 = parseInt($input.val()) || 0;
        $.get('index.php?act=activity&op=ajax&branch=activity_detail_sort', {
            id: $this.parents('tr').attr('data-id'),
            column: 'activity_detail_sort',
            value: v2
        }, function(d) {
            if (d == 'true') {
                $this.html(v2);
            } else {
                alert('操作失败！');
            }
            $input.remove();
            $this.show();
        });
    });
});

</script>
