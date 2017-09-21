<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=contract&op=quitlist" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>消费者保障服务 - 退出服务申请详情</h3>
        <h5>消费者保障服务查看与管理</h5>
      </div>
    </div>
  </div>
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label>项目名称</label>
        </dt>
        <dd class="opt"><?php echo $output['quit_info']['ctq_itemname'];?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">
            <label>店铺名称</label>
        </dt>
        <dd class="opt"><?php echo $output['quit_info']['ctq_storename'];?></dd>
      </dl>
      <dl class="row">
          <dt class="tit">
            <label>申请时间</label>
          </dt>
          <dd class="opt"><?php echo @date('Y-m-d H:i:s',$output['quit_info']['ctq_addtime']);?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>状态</label>
        </dt>
        <dd class="opt"><?php echo $output['quit_info']['ctq_auditstate_text']; ?></dd>
      </dl>
        <div class="bot"><a class="ncap-btn-big ncap-btn-green" href="index.php?act=contract&op=quitlist">返回列表</a></div>
    </div>
</div>