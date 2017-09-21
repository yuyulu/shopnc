<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=goods" title="返回商品列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>快速添加评论 - 好商城原创插件</a></h3>
        <h5>我比别人更懂你的需求，你需要快速创建评论</h5>
      </div>
    </div>
  </div>

    <form id="pingjia_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" value="<?php echo $output["goods_list"]["goods_id"];?>" name="goodsid">
    <input type="hidden" value="<?php echo $output["goods_list"]["goods_name"];?>" name="gname">
   <input type="hidden" value="<?php echo $output["goods_list"]["store_id"];?>" name="storeid">
   <input type="hidden" value="<?php echo $output["goods_list"]["store_name"];?>" name="storename">
    <input type="hidden" value="<?php echo $output["goods_list"]["goods_promotion_price"];?>" name="goodsprice">
   <input type="hidden" value="<?php echo $output["goods_list"]["goods_image"];?>" name="goodsimage">
    <div class="ncap-form-default">
	<dl class="row">
        <dt class="tit">
          <label><em>*</em>会员名称</label>
        </dt>
        <dd class="opt">
		<input type="text" value="" name="member_name" id="member_name" class="input-txt">
          <span class="err"></span>
          <p class="notic">评价会员的名称</p>
        </dd>
      </dl>
      	<dl class="row">
        <dt class="tit">
          <label><em>*</em>会员ID</label>
        </dt>
        <dd class="opt">
		<input type="text" value="0" name="member_id" id="member_id" class="input-txt">
          <span class="err"></span>
          <p class="notic">不做真实的会员ID不需要修改</p>
        </dd>
      </dl>
	  
	  <dl class="row">
        <dt class="tit">
          <label><em>*</em>购买数量</label>
        </dt>
        <dd class="opt">
          <input type="text" value="1" name="goods_num" id="goods_num" class="input-txt">
          <span class="err"></span>
          <p class="notic">购买数量为整数 可输入任意数字 默认为1</p>
        </dd>
         <dl class="row">
        <dt class="tit">
          <label><em>*</em>评分等级</label>
        </dt>
        <dd class="opt">
          <input type="text" value="5" name="geval_scores" id="geval_scores" class="input-txt">
          <span class="err"></span>
          <p class="notic">评分等级1-5分</p>
        </dd>


	  	  <dl class="row">
        <dt class="tit">
          <label><em>*</em>评价内容</label>
        </dt>
        <dd class="opt">
          <textarea rows="6" class="tarea" cols="60" name="geval_content" id="geval_content"></textarea>
          <span class="err"></span>
          <p class="notic">评价内容越精确才会越吸引顾客购买哦</p>
        </dd></dl>
<div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
<script>
//裁剪图片后返回接收函数

$(function(){
	$("#submitBtn").click(function(){
	    if($("#pingjia_form").valid()){
	     $("#pingjia_form").submit();
		}
	});
	$("#pingjia_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
           member_name : {
                required : true
                
            },
            geval_content : {
               required : true
            }
        },
        messages : {
            member_name : {
                required : '<i class="fa fa-exclamation-circle"></i>请填写会员名称',
               
            },
            geval_content  : {
                required   : '<i class="fa fa-exclamation-circle"></i>请填写评价内容'
            }
        }
	});	
});

</script> 
