<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="ncap-form-default">
  <dl class="row">
    <dt class="tit">
      <label>发送人</label>
    </dt>
    <dd class="opt">
      <?php echo $output['chat_info']['f_name'];?>
    </dd>
  </dl>
  <dl class="row">
    <dt class="tit">
      <label>接受人</label>
    </dt>
    <dd class="opt">
      <?php echo $output['chat_info']['t_name'];?>
    </dd>
  </dl>
  <dl class="row">
    <dt class="tit">
      <label>消息内容</label>
    </dt>
    <dd class="opt">
      <?php echo parsesmiles($output['chat_info']['t_msg']);?>
    </dd>
  </dl>
  <dl class="row">
    <dt class="tit">
      <label>发送时间</label>
    </dt>
    <dd class="opt">
      <?php echo date('Y-m-d', $output['chat_info']['add_time']);?>
    </dd>
  </dl>
</div>
