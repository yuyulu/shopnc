<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>聊天记录</h3>
        <h5>商城会员聊天工具内容记录管理</h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="JavaScript:void(0);" class="current">聊天记录</a></li>
        <li><a href="index.php?act=chat_log&op=msg_log">聊天内容</a></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span>
    </div>
    <ul>
      <li>发送人即发出消息的会员，接收人为收到消息的会员，如果是店铺的客服或管理员可显示所属店铺名称。</li>
            <li>为使查询信息更准确，请输入聊天双方的完整会员名——登录账号。</li>
            <li>可查询过去90天内聊天记录。</li>
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
            <dt>发送人</dt>
            <dd>
              <label>
                <input type="text" value="<?php echo $_GET['f_name'];?>" name="f_name" id="f_name" class="s-input-txt" placeholder="输入发送会员全称">
              </label>
            </dd>
          </dl>
          <dl>
            <dt>接收人</dt>
            <dd>
              <label>
                <input type="text" value="<?php echo $_GET['t_name'];?>" name="t_name" id="t_name" class="s-input-txt" placeholder="输入接收会员全称">
              </label>
            </dd>
          </dl>
          <dl>
            <dt>时期筛选</dt>
            <dd>
              <label>
                <input type="text" name="add_time_from" id="add_time_from" class="s-input-txt" placeholder="请选择开始时间" value="<?php echo $_GET['add_time_from'];?>" />
              </label>
              <label>
                <input type="text" name="add_time_to" id="add_time_to" class="s-input-txt" placeholder="请选择结束时间" value="<?php echo $_GET['add_time_to'];?>" />
              </label>
            </dd>
          </dl>
        </div>
      </div>
      <div class="bottom"><a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green mr5">提交查询</a><a href="javascript:void(0);" id="ncreset" class="ncap-btn ncap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['nc_cancel_search'];?></a></div>
    </form>
  </div>
</div>
<script type="text/javascript">
$(function(){
    $('#searchBarOpen').click();
    $('')
    $('#add_time_from').datepicker({minDate: '<?php echo $output['minDate']; ?>'});
    $('#add_time_to').datepicker({maxDate: '<?php echo $output['maxDate']; ?>'});
    // 高级搜索提交
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=chat_log&op=get_xml&'+$("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });

    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions({url: 'index.php?act=chat_log&op=get_xml'}).flexReload();
        $("#formSearch")[0].reset();
    });
    $("#flexigrid").flexigrid({
        url: 'index.php?act=chat_log&op=get_xml&'+$("#formSearch").serialize(),
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '消息内容', name : 't_msg', width : 120, sortable : false, align: 'left'},
            {display: '发送时间', name : 'add_time', width : 150, sortable : true, align: 'left'},
            {display: '发送人', name : 'f_name', width : 60, sortable : false, align: 'left'},
            {display: '发送人ID', name : 'f_id', width : 60, sortable : true, align: 'left'},
            {display: '发送店铺', name : 'f_store_name', width : 60, sortable : false, align: 'left'},
            {display: '发送店铺ID', name : 'f_store_id', width : 80, sortable : false, align: 'left'},
            {display: '客服名称', name : 'f_seller_name', width : 60, sortable : false, align: 'left'},
            {display: '发送IP', name : 'f_ip', width : 120, sortable : false, align: 'left'},
            {display: '接收人', name : 't_name', width : 60, sortable : false, align: 'left'},
            {display: '接收人ID', name : 't_id', width : 60, sortable : true, align: 'left'},
            {display: '接收店铺', name : 't_store_name', width : 60, sortable : false, align: 'left'},
            {display: '接收店铺ID', name : 't_store_id', width : 80, sortable : false, align: 'left'},
            {display: '客服名称', name : 't_seller_name', width : 60, sortable : false, align: 'left'}
            ],
        sortname: "add_time",
        sortorder: "desc",
        title: '聊天记录列表'
    });
});
</script>
