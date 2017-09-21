<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="<?php echo $output['murl'];?>" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo L('download_lang');?></h3>
        <h5>导出数据列表到本地时选择分页操作</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>该栏目下要导出的数据内容较多，系统自动设定了数据表格分页以确保导出成功。</li>
      <li>选择对应的分页序号并点击按钮开始下载数据表格。</li>
    </ul>
  </div>
  <div class="ncap-form-default">
    <dl class="row">
      <dt class="tit">选择数据分页</dt>
      <dd class="opt">
        <?php foreach($output['list'] as $k=>$v){?>
        <a href="index.php?<?php echo $_SERVER['QUERY_STRING'].'&curpage='.$k;?>" class="ncap-btn mr10 mb10">下载数据分页<?php echo $k;?> (<?php echo $v;?>条)</a>
        <?php }?>
      </dd>
    </dl>
  </div>
</div>
