<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=circle_memberlevel&op=ref" title="返回<?php echo $lang['circle_memberlevelref'];?>列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['circle_memberlevel'];?> - <?php echo $lang['circle_memberleveladd'];?></h3>
        <h5><?php echo $lang['nc_circle_memberlevel_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form method="post" id="clmdAddForm" name="clmdAddForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="mlref_name"><?php echo $lang['circle_memberlevelgroup'];?></label>
        </dt>
        <dd class="opt">
          <input id="mlref_name" name="mlref_name" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['circle_is_use'];?></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="mlref_status1" class="cb-enable selected" ><span><?php echo $lang['open'];?></span></label>
            <label for="mlref_status0" class="cb-disable" ><span><?php echo $lang['close'];?></span></label>
            <input id="mlref_status1" name="mlref_status" checked="checked" value="1" type="radio">
            <input id="mlref_status0" name="mlref_status" value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
    </div>
    <div class="ncap-form-all nopd">
      <div class="title">
        <h3>成员头衔名称设定</h3>
      </div>
      <dl class="row">
        <dt class="tit"> <span class="w100 tc"><?php echo $lang['circle_level'];?></span> <span class="w350"><?php echo $lang['circle_rankname'];?></span> <span class="w100 tc"><?php echo $lang['circle_level'];?></span> <span><?php echo $lang['circle_rankname'];?></span> </dt>
        <?php for($i=1;$i<=8;$i++){?>
        <dd class="opt">
          <label class="w100 tc ml10"><?php echo $i;?></label>
          <label class="w350">
            <input type="text" class="input-txt" name="mlref_<?php echo $i;?>" />
            <span class="err"></span> </label>
          <label class="w100 tc"><?php echo $i+8;?></label>
          <label class="w350">
            <input type="text" class="input-txt" name="mlref_<?php echo $i+8;?>" />
            <span class="err"></span> </label>
        </dd>
        <?php }?>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(function(){
	$("#submitBtn").click(function(){
	    if($("#clmdAddForm").valid()){
	    	$("#clmdAddForm").submit();
		}
	});
	$("#clmdAddForm").validate({
		errorPlacement: function(error, element){
			if(element.attr('id') == 'mlref_name'){
				var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
			}else{
				error.appendTo(element.parent().children('span.err'));
			}
        },
        rules : {
        	mlref_name : {
        		required : true
        	}
        	<?php for($i=1;$i<=16;$i++){?>
        	,mlref_<?php echo $i;?> : {
            	required : true,
            	maxlength : 4
        	}
        	<?php }?>
        },
		messages : {
			mlref_name : {
				required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['circle_memberlevelgroupname_not_null'];?>'
			}
			<?php for($i=1;$i<=16;$i++){?>
			,mlref_<?php echo $i;?> : {
				required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['circle_rank_not_null'];?>',
				maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['circle_rank_maxlength'];?>'
			}
			<?php }?>
		}
	});
});
</script>