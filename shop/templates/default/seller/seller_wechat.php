<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="tabmenu">
    <?php include template('layout/submenu');?>
</div>
<div class="ncsc-form-default">
	<form method="post" action="<?php echo urlShop('seller_wechat', 'index');?>">
		<input type="hidden" name="form_submit" value="ok" />
	<dl>
      <dt><i class="required">*</i>公众号原始ID：</dt>
	  <dd><input type="text" value="<?php echo $output['wechat']['wxname'];?>" class="f-input" id="wxname" name="wxname" size="30" tabindex="1"><font class="msg_box">公众号设置-原始ID  如：gh_75sd835d25f9</font>
		  <span></span>
          <p class="hint"></p>
      </dd>
	</dl>
	<!-- <dl>
      <dt><i class="required">*</i>微信号：</dt>
	  <dd><input type="text" value="<?php echo $output['wechat']['weixin'];?>" class="f-input" id="weixin" name="weixin" size="30" tabindex="1">
		  <span></span>
          <p class="hint"></p>
      </dd>
	</dl> -->
	<dl>
      <dt><i class="required">*</i>TOKEN：</dt>
	  <dd><input type="text" value="<?php echo $output['wechat']['token'];?>" class="f-input" id="token" name="token" size="30" tabindex="1"><font class="msg_box">请填写微信TOKEN</font>
		  <span></span>
          <p class="hint"></p>
      </dd>
	</dl>
<?php if (isset($output['wechat']['token'])) {?>
	<dl>
      <dt>接口URL：</dt>
	  <dd><?php echo MOBILE_SITE_URL;?>/index.php?act=weixin&token=<?php echo $output['wechat']['token'];?>&wx_store_id=<?php echo $output['account_id'];?></dd>
	</dl>
<?php }?>
	<dl>
      <dt><i class="required">*</i>微信号类型：</dt>
	  <dd>普通公众号<input <?php if ($output['wechat']['wx_type'] == '0') echo ' checked '?> type="radio"  name="wx_type" value="0" /> &nbsp;&nbsp;高级公众号<input type="radio"  <?php if ($output['wechat']['wx_type'] == '1') echo ' checked '?>  name="wx_type" value="1" /><font style="color:red;" class="msg_box2">请根据自己的微信号选择类型,否则造成部分功能不能使用。<br><font style="color:blue;">普通公众号</font>:订阅号以及服务号(未认证)。<font style="color:blue;"><br>高级公众号</font>:服务号并且通过微信认证(含有高级接口)</font>
		  <span></span>
          <p class="hint"></p>
      </dd>
	</dl>
    <div class="bottom">
      <label class="submit-border">
        <input type="submit" class="submit" value="保存修改">
      </label>
    </div>
     </form>
</div>
