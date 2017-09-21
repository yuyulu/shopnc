<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>搜索设置</h3>
        <h5>热搜词与默认词设置</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <form id="form" method="post" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="hot_search">搜索默认词</label>
        </dt>
        <dd class="opt">
          <input id="hot_search" name="hot_search" value="<?php echo $output['list_setting']['hot_search'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">搜索默认词设置后，将显示在前台搜索框下面，前台点击时直接作为关键词进行搜索，多个关键词间请用半角逗号 "," 隔开</p>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.settingForm.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
