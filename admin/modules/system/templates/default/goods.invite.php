<?php defined('In33hao') or exit('Access Invalid!');?>


  <form method="post" name="form1" id="form1" action="<?php echo urlAdmin('goods', 'goods_money');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" value="<?php echo $output["commonids"];?>" name="commonids">
     <div class="ncap-form-default">
    <dl class="row">
      <dt class="tit">
        <label for="invite">一级推荐佣金:</label
      </dt>
      <dd class="opt">
        <input type="text" value="<?php if($output['goodscommon_info']['invite1']){ echo $output['goodscommon_info']['invite1']; }else{ echo '0.00';}?>" name="invite1" id="invite1" class="input-txt">
      </dd>
    </dl>
        <dl class="row">
      <dt class="tit">
        <label for="invite">二级推荐佣金:</label>
      </dt>
      <dd class="opt">
        <input type="text" value="<?php if($output['goodscommon_info']['invite2']){ echo $output['goodscommon_info']['invite2']; }else{ echo '0.00';}?>" name="invite2" id="invite2" class="input-txt">
      </dd>
    </dl>
        <dl class="row">
      <dt class="tit">
        <label for="invite">三级推荐佣金:</label
      </dt>
      <dd class="opt">
        <input type="text" value="<?php if($output['goodscommon_info']['invite3']){ echo $output['goodscommon_info']['invite3']; }else{ echo '0.00';}?>" name="invite3" id="invite3" class="input-txt">
      </dd>
    </dl>
    <div class="bot"><a href="javascript:void(0);" class="ncap-btn-big ncap-btn-green" nctype="btn_submit"><?php echo $lang['nc_submit'];?></a></div>
  </div>
  
  </form>

<script>
$(function(){
    $('a[nctype="btn_submit"]').click(function(){
        ajaxpost('form1', '', '', 'onerror');
    });
});
</script>