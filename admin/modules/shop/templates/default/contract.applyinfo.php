<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=contract&op=applylist" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>消费者保障服务 - 服务申请详情</h3>
        <h5>消费者保障服务查看与管理</h5>
      </div>
    </div>
  </div>
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label>项目名称</label>
        </dt>
        <dd class="opt"><?php echo $output['item_info']['cti_name'];?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">
            <label>店铺名称</label>
        </dt>
        <dd class="opt"><?php echo $output['apply_info']['cta_storename'];?></dd>
      </dl>
      <dl class="row">
          <dt class="tit">
            <label>申请时间</label>
          </dt>
          <dd class="opt"><?php echo @date('Y-m-d H:i:s',$output['apply_info']['cta_addtime']);?></dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>状态</label>
        </dt>
        <dd class="opt"><?php echo $output['apply_info']['cta_auditstate_text']; ?></dd>
      </dl>
      <div class="bot"><a id="submitBtn" class="ncap-btn-big ncap-btn-green" href="index.php?act=contract&op=applylist">返回列表</a></div>
    </div>
</div>