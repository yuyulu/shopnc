<div class="eject_con">
  <form id="post_form" method="post" action="index.php?act=member_favorite_goods&op=log_msg&log_id=<?php echo $output['favorites']['log_id']; ?>" onsubmit="ajaxpost('post_form','','','onerror')">
    <input type="hidden" name="form_submit" value="ok" />
      <dl>
        <dt><?php echo '备注信息'.$lang['nc_colon'];?></dt>
        <dd>
          <input type="text" class="text w250" maxlength="20" name="fav_msg" value="<?php echo $output['favorites']['log_msg']; ?>" />
          <p class="hint">
            最多可以输入20个字符，如商品价格、促销信息、个人评价等，为空时显示收藏时的商品价格。</p>
        </dd>
      </dl>
    <div class="bottom">
      <label class="submit-border">
        <input type="submit" class="submit" id="confirm_button" value="<?php echo $lang['nc_ok'];?>" />
      </label><a class="ncbtn ml5" href="javascript:DialogManager.close('log_msg');">取消</a>
    </div>
  </form>
</div>
