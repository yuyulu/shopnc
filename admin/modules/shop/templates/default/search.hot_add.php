<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=search&op=hot" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>热门搜索词 - 设置</h3>
        <h5>新增热门搜索词用于前台搜索框显示</h5>
      </div>
    </div>
  </div>

  <form id="search_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="id" value='<?php echo $_GET['id']?>'>
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="s_name"><em>*</em>搜索词</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['current_info']['value'];?>" name="s_value" id="s_value" class="input-txt">
          <span class="err"></span>
          <p class="notic">搜索词参于搜索，例：童装</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="ac_name"><em>*</em>显示词</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['current_info']['name'];?>" name="s_name" id="s_name" class="input-txt">
          <span class="err"></span>
          <p class="notic">显示词不参于搜索，只起显示作用，例：61儿童节，童装5折狂甩</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#search_form").valid()){
        $("#search_form").submit();
    }
	});
});

$(document).ready(function(){
	$('#search_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            s_name : {
                required : true
            },
            s_value : {
                required : true
            }
        },
        messages : {
            s_name : {
                required : '<i class="fa fa-exclamation-circle"></i>请填写显示司'
            },
            s_value : {
            	required : '<i class="fa fa-exclamation-circle"></i>请填写搜索词'
            }
        }
    });
});
</script>