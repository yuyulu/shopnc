<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['activity_index_manage'];?></h3>
        <h5><?php echo $lang['activity_index_manage_subhead']; ?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['activity_index_help1'];?></li>
      <li><?php echo $lang['activity_index_help2'];?></li>
      <li><?php echo $lang['activity_index_help3'];?></li>
      <li><?php echo $lang['activity_index_help4'];?></li>
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
            <dt>活动标题</dt>
            <dd>
              <label>
                <input type="text" name="activity_title" class="s-input-txt" placeholder="请输入活动标题关键字" />
              </label>
            </dd>
          </dl>
          <dl>
            <dt>活动状态</dt>
            <dd>
              <label>
                <select name="activity_state" class="s-select">
                  <option value="">-请选择-</option>
                  <option value="1">开启</option>
                  <option value="0">关闭</option>
                </select>
              </label>
            </dd>
          </dl>

            <dl>
              <dt>活动时期筛选</dt>
              <dd>
                <label>
                    <input type="text" name="pdate1" data-dp="1" class="s-input-txt" placeholder="结束时间不晚于" />
                </label>
                <label>
                    <input type="text" name="pdate2" data-dp="1" class="s-input-txt" placeholder="开始时间不早于" />
                </label>
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
    var flexUrl = 'index.php?act=activity&op=activity_xml';

    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '排序', name: 'activity_sort', width: 100, sortable: 1, align: 'left'},
            {display: '活动标题', name: 'activity_title', width: 350, sortable: false, align: 'left'},
            {display: '横幅图片', name: 'activity_banner', width: 80, sortable: false, align: 'left'},
            {display: '开始时间', name: 'activity_start_date', width: 120, sortable: 1, align: 'center'},
            {display: '结束时间', name: 'activity_end_date', width: 120, sortable: 1, align: 'center'},
            {display: '状态', name: 'activity_state', width: 80, sortable: false, align: 'center'}
        ],
        buttons: [
            {
                display: '<i class="fa fa-plus"></i>新增活动',
                name: 'add',
                bclass: 'add',
                title: '平台发起新活动',
                onpress: function() {
                    location.href = 'index.php?act=activity&op=new';
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
                    var href = 'index.php?act=activity&op=del&activity_id=__IDS__'.replace('__IDS__', ids.join(','));

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
            {display: '活动标题', name: 'activity_title', isdefault: true}
        ],
        sortname: "activity_id",
        sortorder: "desc",
        title: '活动列表'
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

    $("input[data-dp='1']").datepicker({dateFormat: 'yy-mm-dd'});

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

$("span[data-live-inline-edit='activity_sort']").live('click', function() {
    var $this = $(this);
    var $input = $('<input type="text" style="width:50px;">');
    $input.val(parseInt($this.html()) || 0);
    $this.after($input);
    $this.hide();
    $input.focus();
    $input.change(function() {
        var v2 = parseInt($input.val()) || 0;
        $.get('index.php?act=activity&op=ajax&branch=activity_sort', {
            id: $this.parents('tr').attr('data-id'),
            column: 'activity_sort',
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

$("span[data-live-inline-edit='activity_title']").live('click', function() {
    var $this = $(this);
    var $input = $('<input type="text" style="width:333px;">');
    $input.val($this.html());
    $this.after($input);
    $this.hide();
    $input.focus();
    $input.change(function() {
        var v2 = $.trim($input.val());
        if (!v2) {
            alert('请输入标题！');
            $input.focus();
            return false;
        }
        $.get('index.php?act=activity&op=ajax&branch=activity_title', {
            id: $this.parents('tr').attr('data-id'),
            column: 'activity_title',
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
