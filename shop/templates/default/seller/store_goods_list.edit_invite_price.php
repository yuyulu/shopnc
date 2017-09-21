<div class="eject_con">
  <div id="warning" class="alert alert-error"></div>
  <form id="category_form" method="post" target="_parent" action="index.php?act=store_goods_online&op=edit_invite_price"><!--album_add_save-->
    <input type="hidden" name="form_submit" value="ok" />
	<input type="hidden" name="goods_id" value="<?php echo $_GET['goods_id']; ?>" />
    <dl>
      <dt>一级佣金<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input class="w200 text" type="text" name="invite_rate" id="invite_rate" value="<?php echo $output['goods']['invite_rate']?>" />
      </dd>
    </dl>
    <dl>
      <dt>二级佣金<?php echo $lang['nc_colon'];?></dt>
      <dd>
        为当前设置的一级佣金的<em style="color:#F00"><?php echo $output['setting_config']['hao_invite2']; ?></em>%
      </dd>
    </dl>
    <dl>
      <dt>三级佣金<?php echo $lang['nc_colon'];?></dt>
      <dd>
        为当前设置的一级佣金的<em style="color:#F00"><?php echo $output['setting_config']['hao_invite3']; ?></em>%
      </dd>
    </dl>
        <dl>
      <dt>每单支出的金额<?php echo $lang['nc_colon'];?></dt>
      <dd>
        一级佣金+二级佣金+三级佣金=<em style="color:#F00">支出金额</em>
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-border">
        <input type="submit" class="submit" value="提交" />
      </label>
    </div>
  </form>
</div>

 
