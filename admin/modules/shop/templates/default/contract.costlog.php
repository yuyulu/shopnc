<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page">
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=contract&op=contractlist" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>消费者保障服务 - 保证金日志</h3>
        <h5>消费者保障服务查看与管理</h5>
      </div>
    </div>
  </div>
    <div class="ncap-form-default">
        <dl class="row">
            <dt class="tit">
                <label>店铺名称</label>
            </dt>
            <dd class="opt"><?php echo $output['c_info']['ct_storename'];?></dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label>项目名称</label>
            </dt>
            <dd class="opt"><?php echo $output['item_info']['cti_name'];?></dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label>保证金余额</label>
            </dt>
            <dd class="opt"><?php echo $output['c_info']['ct_cost'];?>&nbsp;<?php echo $lang['currency_zh']; ?></dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label>状态</label>
            </dt>
            <dd class="opt">
                <?php if ($output['c_info']['ct_state_sign'] == 'applying') { ?>
                    <?php echo $output['c_info']['ct_state_text']."（{$output['c_info']['ct_auditstate_text']}）";?>
                <?php }else{ ?>
                    <?php echo $output['c_info']['ct_state_text'];?>
                <?php } ?>
            </dd>
        </dl>
    </div>
  <div id="flexigrid"></div>
</div>

<script>
    $(function(){
        var flexUrl = 'index.php?act=contract&op=costlist_xml&item_id=<?php echo $output['c_info']['ct_itemid'];?>&store_id=<?php echo $output['c_info']['ct_storeid'];?>';
        $("#flexigrid").flexigrid({
            url: flexUrl,
            colModel: [
                {display: '店铺名称', name: 'clog_storename', width: 200, sortable: false, align: 'left'},
                {display: '保障服务', name: 'clog_itemname', width: 200, sortable: false, align: 'left'},
                {display: '金额(<?php echo $lang['currency_zh'];?>)', name: 'clog_price', width: 100, sortable: false, align: 'right'},
                {display: '添加时间', name: 'clog_addtime', width: 120, sortable: false, align: 'center'},
                {display: '操作人', name: 'clog_adminname', width: 120, sortable: false, align: 'center'},
                {display: '描述', name: 'clog_desc', width: 500, sortable: false, align: 'left'}
            ],
            sortname: "clog_id",
            sortorder: "desc",
            title: '保证金日志列表'
        });
    });
</script>