<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="<?php echo urlAdminShop('mall_consult', 'type_list');?>" title="返回平台咨询类型列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>平台客服 - 新增类型</h3>
        <h5>商城对用户咨询类型设定与处理</h5>
      </div>
    </div>
  </div>
  <form method="post" name="form_typeadd" id="form_typeadd">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>类型名称</label>
        </dt>
        <dd class="opt">
          <input class="input-txt" type="text" name="mct_name" value="" />
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>类型备注</label>
        </dt>
        <dd class="opt">
          <?php showEditor('mct_introduce');?>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>排序</label>
        </dt>
        <dd class="opt">
          <input class="input-txt" type="text" name="mct_sort" value="255" />
          <span class="err"></span>
          <p class="notic">类型按由小到大顺序排列</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $("#submitBtn").click(function(){
        $("#form_typeadd").submit();
    });
    $("#form_typeadd").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            mct_name : {
                required : true,
                maxlength : 10
            },
            mct_sort : {
                required : true,
                range : [0,255]
            }
        },
        messages : {
            mct_name : {
                required : '<i class="fa fa-exclamation-circle"></i>请填写咨询类型名称',
                maxlength: '<i class="fa fa-exclamation-circle"></i>咨询类型名称长度不能超过10个字符'
            },
            mct_sort : {
                required : '<i class="fa fa-exclamation-circle"></i>请填写0~255之间的数字',
                range : '<i class="fa fa-exclamation-circle"></i>请填写0~255之间的数字'
            }
        }
    });
});
</script> 
