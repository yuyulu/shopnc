<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_statgeneral'];?></h3>
        <h5>商城统计最新情报及相关设置</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>设置订单金额区间，当对订单金额进行相关统计时按照以下设置的价格区间进行统计和显示</li>
      <li>设置价格区间的几点建议：一、建议设置的第一个价格区间起始额为0；二、价格区间应该设置完整，不要缺少任何一个起始额和结束额；三、价格区间数值应该连贯例如0~100,101~200</li>
    </ul>
  </div>
  <form method="post" action="index.php" name="pricerangeform" id="pricerangeform">
    <input type="hidden" value="ok" name="form_submit">
    <input type="hidden" name="act" value="stat_general" />
    <input type="hidden" name="op" value="orderprange" />
    <div class="ncap-form-default" id="pricerang_table">
      <dl class="row">
        <dt class="tit">订单金额区间段设定</dt>
        <dd class="opt">
          <ul class="ncap-ajax-add">
            <?php if (!empty($output['list_setting']['stat_orderpricerange']) && is_array($output['list_setting']['stat_orderpricerange'])){?>
            <?php foreach ($output['list_setting']['stat_orderpricerange'] as $k=>$v){ ?>
            <li id="row_<?php echo $k; ?>">
              <label>起始额：
                <input type="text" class="txt w100 mr5" value="<?php echo $v['s'];?>" name="pricerange[<?php echo $k;?>][s]">元
              </label>
              <label class="ml20 mr10">结束额：
                <input type="text" class="txt w100 mr5" value="<?php echo $v['e'];?>" name="pricerange[<?php echo $k;?>][e]">元
              </label>
              <label><a href="JavaScript:void(0);" onclick="delrow(<?php echo $k;?>);" class="ncap-btn ncap-btn-red"><?php echo $lang['nc_del']; ?></a></label>
            </li>
            <?php } ?>
            <?php }?>
          </ul>
          <a id="addrow" href="javascript:void(0);" class="ncap-btn"><i class="fa fa-plus"></i>增加一行</a> </dd>
      </dl>
      <div class="bot"><a id="ncsubmit" class="ncap-btn-big ncap-btn-green" href="JavaScript:void(0);"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
function delrow(i){
	$("#row_"+i).remove();
}
$(function(){
	var i = <?php echo count($output['list_setting']['stat_orderpricerange']); ?>;
	i += 1;
	var html = '';
	/*新增一行*/
	$('#addrow').click(function(){
		html = '<li id="row_'+i+'">';
		html += '<label>起始额：<input type="text" class="txt w100 mr5" name="pricerange['+i+'][s]" value="0"/>元</label>';
		html += '<label class="ml20 mr10">结束额：<input type="text" class="txt w100 mr5" name="pricerange['+i+'][e]" value="0"/>元</label>';
		html += '<label><a href="JavaScript:void(0);" onclick="delrow('+i+');" class="ncap-btn ncap-btn-red"><?php echo $lang['nc_del']; ?></a></label></li>';
		$('#pricerang_table').find('ul').append(html);
		i += 1;
	});
	
	$('#ncsubmit').click(function(){
		var result = true;
		$("#pricerang_table").find("[name^='pricerange']").each(function(){
			if(!$(this).val()){
				result = false;
			}
		});
		if(result){
			$('#pricerangeform').submit();
		} else {
			showDialog('请将价格区间填写完整');
		}
    });
})
</script>