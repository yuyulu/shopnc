<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_microshop_personal_manage'];?></h3>
        <h5><?php echo $lang['nc_microshop_personal_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['microshop_personal_tip1'];?></li>
      <li><?php echo $lang['microshop_personal_tip2'];?></li>
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
              <dt>编号</dt>
              <dd>
                <input type="text" name="personal_id" class="s-input-txt" placeholder="请输入编号" />
              </dd>
            </dl>
            <dl>
              <dt>会员</dt>
              <dd>
                <input type="text" name="member_name" class="s-input-txt" placeholder="请输入会员" />
              </dd>
            </dl>
            <dl>
              <dt>推荐</dt>
              <dd>
                <select name="microshop_commend" class="s-select">
                    <option value="">-请选择-</option>
                    <option value="1">是</option>
                    <option value="0">否</option>
                </select>
              </dd>
            </dl>
            <dl>
              <dt>推荐时间</dt>
              <dd>
                  <label>
                    <input type="text" name="sdate" data-dp="1" class="s-input-txt" placeholder="请输入起始时间" />
                  </label>
                  <label>
                    <input type="text" name="edate" data-dp="1" class="s-input-txt" placeholder="请输入终止时间" />
                  </label>
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
$(function() {
    var flexUrl = 'index.php?act=personal&op=personal_manage_xml';

    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '排序', name: 'microshop_sort', width: 80, sortable: false, align: 'left'},
            {display: '编号', name: 'personal_id', width: 60, sortable: false, align: 'left'},
            {display: '商品图片', name: 'imgs', width: 150, sortable: false, align: 'left'},
            {display: '会员', name: 'member_name', width: 120, sortable: false, align: 'left'},
            {display: '推荐说明', name: 'commend_message', width: 200, sortable: false, align: 'left'},
            {display: '推荐时间', name: 'commend_time_text', width: 120, sortable: false, align: 'center'},
            {display: '推荐', name: 'microshop_commend', width: 60, sortable: false, align: 'center'}
        ],
        buttons: [
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
                    location.href = 'index.php?act=personal&op=personal_drop&personal_id=__IDS__'.replace('__IDS__', ids.join(','));
                }
            }
        ],
        searchitems: [
            {display: '编号 ', name: 'personal_id', isdefault: true},
            {display: '会员', name: 'member_name'}
        ],
        sortname: "personal_id",
        sortorder: "desc",
        title: '个人秀列表'
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

$('a.confirm-del-on-click').live('click', function() {
    return confirm('确定删除?');
});

$('a.confirm-on-click').live('click', function() {
    return confirm('确定"'+this.innerHTML+'"?');
});

$('a[data-ie-column]').live('click', function() {
    $.get('index.php?act=personal&op=ajax&branch=personal_commend', {
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

$("span[data-live-inline-edit='microshop_sort']").live('click', function() {
    var $this = $(this);
    var $input = $('<input type="text" style="width:50px;">');
    $input.val(parseInt($this.html()) || 0);
    $this.after($input);
    $this.hide();
    $input.focus();
    $input.change(function() {
        var v2 = parseInt($input.val()) || 0;
        $.getJSON('index.php?act=personal&op=personal_sort_update', {
            id: $this.parents('tr').attr('data-id'),
            value: v2
        }, function(d) {
            if (d.result) {
                $this.html(v2);
            } else {
                alert(d.message);
            }
            $input.remove();
            $this.show();
            // $("#flexigrid").flexReload();
        });
    });
});

</script>
