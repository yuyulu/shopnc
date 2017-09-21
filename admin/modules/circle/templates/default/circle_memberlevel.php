<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['circle_memberlevel'];?></h3>
        <h5><?php echo $lang['nc_circle_memberlevel_subhead'];?></h5>
      </div>
      <ul class="tab-base nc-row">
        <li><a href="JavaScript:void(0);" class="current"><?php echo $lang['circle_defaultlevel'];?></a></li>
        <li><a href="index.php?act=circle_memberlevel&op=ref"><?php echo $lang['circle_memberlevelref'];?></a></li>
      </ul>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['circle_memberlevelprompts'];?></li>
    </ul>
  </div>
  <form method="post" id="clmdForm" name="clmdForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-all">
      <dl class="row">
        <dt class="tit"> <span class="w60 tc"><?php echo $lang['circle_level'];?></span> <span class="w100 tc"><?php echo $lang['circle_medal'];?></span> <span class="w350"><?php echo $lang['circle_rank'];?></span> <span class="w350"><?php echo $lang['circle_experience_required'];?></span> </dt>
        <?php for ($i=1;$i<=16;$i++){?>
        <dd class="opt">
          <label class="w60 ml10 tc"><?php echo $i;?>
            <input type="hidden" name="cmld[<?php echo $i;?>][id]" value="<?php echo $i;?>" class="" />
          </label>
          <label class="circle-level w100 tc"><span class="circle-level-<?php echo $i;?>"><strong><?php echo $i;?></strong></span></label>
          <label class="w350">
            <input type="text" name="cmld[<?php echo $i;?>][name]" value="<?php echo $output['mld_list'][$i]['mld_name'];?>" class="input-txt"/>
          </label>
          <label class="w350">
            <input type="text" name="cmld[<?php echo $i;?>][exp]" value="<?php echo $output['mld_list'][$i]['mld_exp'];?>" class="input-txt"/>
          </label>
        </dd>
        <?php }?>
      </dl>
      <div class="fix-bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a>  <a href="index.php?act=circle_memberlevel&op=update_cache" class="ncap-btn-big ncap-btn-orange" ><?php echo $lang['nc_circle_cache'];?></a></div>
    </div>
  </form>
</div>
<script>
$(function(){
	$("#submitBtn").click(function(){
	    if($("#clmdForm").valid()){
	    	$("#clmdForm").submit();
		}
	});
	$("#clmdForm").validate({
        rules : {
            <?php for($i=1;$i<=16;$i++){?>
        	"cmld[<?php echo $i;?>][name]": {
        		required : true,
        		maxlength:4
        	},
        	"cmld[<?php echo $i;?>][exp]": {
        		required : true,
        		digits:4
        	}<?php if($i!=16){?>,<?php }?>
        	<?php }?>
        },
		messages : {
			<?php for($i=1;$i<=16;$i++){?>
        	"cmld[<?php echo $i;?>][name]": {
        		required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['circle_rank_not_null'];?>',
        		maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['circle_rank_maxlength'];?>'
        	},
        	"cmld[<?php echo $i;?>][exp]": {
        		required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['circle_experience_error'];?>',
        		digits: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['circle_experience_error'];?>'
        	}<?php if($i!=16){?>,<?php }?>
        	<?php }?>
		}
	});
});
</script>