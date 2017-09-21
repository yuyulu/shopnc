<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=navigation&op=navigation" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['navigation_index_nav'];?> - <?php echo $lang['nc_new'];?></h3>
        <h5><?php echo $lang['navigation_index_nav_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="navigation_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['navigation_add_type'];?></label>
        </dt>
        <dd class="opt">
          <ul class="nofloat">
            <li class="left "><span class="radio">
              <input type="radio" <?php if($output['navigation_array']['nav_type'] == '0'){ ?>checked="checked"<?php } ?> value="0" name="nav_type" id="diy" onclick="showType('diy');">
              <label for="diy"><?php echo $lang['navigation_add_custom'];?></label>
              </span></li>
            <li class="left "><span class="radio">
              <input type="radio" <?php if($output['navigation_array']['nav_type'] == '1'){ ?>checked="checked"<?php } ?> value="1" name="nav_type" id="goods_class" onclick="showType('goods_class');">
              <label for="goods_class"><?php echo $lang['navigation_add_goods_class'];?></label>
              </span>
              <select name="goods_class_id" id="goods_class_id" style="display: none;">
                <?php if(is_array($output['goods_class_list'])){ ?>
                <?php foreach($output['goods_class_list'] as $k => $v){ ?>
                <option <?php if($output['navigation_array']['item_id'] == $v['gc_id']){ ?>selected="selected"<?php } ?> value="<?php echo $v['gc_id'];?>"><?php echo $v['gc_name'];?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </li>
            <li class="left "><span class="radio">
              <input type="radio" <?php if($output['navigation_array']['nav_type'] == '2'){ ?>checked="checked"<?php } ?> value="2" name="nav_type" id="article_class" onclick="showType('article_class');">
              <label for="article_class"><?php echo $lang['navigation_add_article_class'];?></label>
              </span>
              <select name="article_class_id" id="article_class_id" style="display: none;">
                <?php if(is_array($output['article_class_list'])){ ?>
                <?php foreach($output['article_class_list'] as $k => $v){ ?>
                <option <?php if($output['navigation_array']['item_id'] == $v['ac_id']){ ?>selected="selected"<?php } ?> value="<?php echo $v['ac_id'];?>"><?php echo $v['ac_name'];?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </li>
            <li class="left "><span class="radio">
              <input type="radio" <?php if($output['navigation_array']['nav_type'] == '3'){ ?>checked="checked"<?php } ?> value="3" name="nav_type" id="activity" onclick="showType('activity');">
              <label for="activity"><?php echo $lang['navigation_add_activity'];?></label>
              </span>
              <select name="activity_id" id="activity_id" style="display: none;">
                <?php if(is_array($output['activity_list'])){ ?>
                <?php foreach($output['activity_list'] as $k => $v){ ?>
                <option <?php if($output['navigation_array']['item_id'] == $v['activity_id']){ ?>selected="selected"<?php } ?> value="<?php echo $v['activity_id'];?>"><?php echo $v['activity_title'];?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </li>
          </ul>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="nav_title"><em>*</em><?php echo $lang['navigation_index_title'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="nav_title" id="" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="nav_url"> <?php echo $lang['navigation_index_url'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="http://" name="nav_url" id="" class="input-txt">
          <span class="err"></span>
          <p class="notic"> 
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="type"><?php echo $lang['navigation_index_location'];?></label>
        </dt>
        <dd class="opt">
          <input type="radio" value="0" name="nav_location">
          <label><?php echo $lang['navigation_index_top'];?></label>
          <input type="radio" checked="checked" value="1" name="nav_location">
          <label><?php echo $lang['navigation_index_center'];?></label>
          <input type="radio" value="2" name="nav_location">
          <label><?php echo $lang['navigation_index_bottom'];?></label>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['navigation_index_open_new'];?></label>
        <dd class="opt">
          <div class="onoff">
            <label for="nav_new_open1" class="cb-enable selected" ><?php echo $lang['nc_yes'];?></label>
            <label for="nav_new_open0" class="cb-disable" ><?php echo $lang['nc_no'];?></label>
            <input id="nav_new_open1" name="nav_new_open" checked="checked" value="1" type="radio">
            <input id="nav_new_open0" name="nav_new_open" value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="nav_sort"> <?php echo $lang['nc_sort'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="255" name="nav_sort"  id="nav_sort" class="input-txt">
          <span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#navigation_form").valid()){
     $("#navigation_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$('#navigation_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            nav_title : {
                required : true
            },
            nav_sort:{
               number   : true
            }
        },
        messages : {
            nav_title : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['navigation_add_partner_null'];?>'
            },
            nav_sort  : {
                number   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['navigation_add_sort_int'];?>'
            }
        }
    });
});

function showType(type){
	$('#goods_class_id').css('display','none');
	$('#article_class_id').css('display','none');
	$('#activity_id').css('display','none');
	if(type == 'diy'){
		$('#nav_url').attr('disabled',false);
	}else{
		$('#nav_url').attr('disabled',true);
		$('#'+type+'_id').show();	
	}
}

</script>