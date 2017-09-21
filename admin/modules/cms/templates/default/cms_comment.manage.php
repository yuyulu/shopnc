<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_cms_comment_manage'];?></h3>
        <h5><?php echo $lang['nc_cms_comment_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['cms_comment_tip1'];?></li>
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
                <input type="text" name="comment_id" class="s-input-txt" placeholder="请输入编号" />
              </dd>
            </dl>
            <dl>
              <dt>作者</dt>
              <dd>
                <input type="text" name="member_name" class="s-input-txt" placeholder="请输入作者" />
              </dd>
            </dl>
            <dl>
              <dt>类型</dt>
              <dd>
                <select name="comment_type" class="s-select">
                    <option value="">全部</option>
                    <?php foreach ((array) $output['type_array'] as $k => $v) { ?>
                    <option value="<?php echo $k; ?>"><?php echo $v['name']; ?></option>
                    <?php } ?>
                </select>
              </dd>
            </dl>
            <dl>
              <dt>对象编号</dt>
              <dd>
                <input type="text" name="comment_object_id" class="s-input-txt" placeholder="请输入对象编号" />
              </dd>
            </dl>
            <dl>
              <dt>内容</dt>
              <dd>
                <input type="text" name="comment_message" class="s-input-txt" placeholder="请输入内容关键字" />
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
    var flexUrl = 'index.php?act=cms_comment&op=comment_manage_xml';

    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
            {display: '编号', name: 'comment_id', width: 60, sortable: false, align: 'left'},
            {display: '作者', name: 'member_name', width: 100, sortable: false, align: 'left'},
            {display: '类型', name: 'comment_type', width: 60, sortable: false, align: 'left'},
            {display: '对象编号', name: 'comment_object_id', width: 60, sortable: false, align: 'left'},
            {display: '内容', name: 'comment_message', width: 600, sortable: false, align: 'left'}
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
                    location.href = 'index.php?act=cms_comment&op=comment_drop&comment_id=__IDS__'.replace('__IDS__', ids.join(','));
                }
            }
        ],
        searchitems: [
            {display: '编号', name: 'comment_id', isdefault: true},
            {display: '作者', name: 'member_name'},
            {display: '对象编号', name: 'comment_object_id'},
            {display: '内容', name: 'comment_message'}
        ],
        sortname: "comment_id",
        sortorder: "desc",
        title: '评论列表'
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

$('a.confirm-del-on-click').live('click', function() {
    return confirm('确定删除?');
});

</script>
