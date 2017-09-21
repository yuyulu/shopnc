<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
   <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['member_index_manage']?></h3>
        <h5><?php echo $lang['member_system_manage_subhead']?></h5>
      </div> <?php echo $output['top_link'];?>
    </div>
  </div>
  <form id="points_form" method="post" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>会员名称</label>
        </dt>
        <dd class="opt">
          <input type="text" name="member_name" id="member_name" class="input-txt" onchange="javascript:checkmember();">
          <input type="hidden" name="member_id" id="member_id" value='0'/>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['member_index_name']?></p>
        </dd>
      </dl>
      <dl class="row" id="tr_memberinfo">
        <dt class="tit">符合条件的会员</dt>
        <dd class="opt" id="td_memberinfo"></dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>增减类型</label>
        </dt>
        <dd class="opt">
        <select id="operatetype" name="operatetype">
              <option value="1">增加</option>
              <option value="2">减少</option>
               <option value="3">冻结</option>
               <option value="4">解冻</option> 
            </select>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>金额</label>
        </dt>
        <dd class="opt">
          <input type="text" id="pointsnum" name="pointsnum" class="input-txt">
          <span class="err"></span>
          <p class="notic">对应金额填写</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>描述</label>
        </dt>
        <dd class="opt">
          <textarea name="pointsdesc" rows="6" class="tarea"></textarea>
          <span class="err"></span>
          <p class="notic">描述信息将显示在预存款明细相关页，会员和管理员都可见</p>
        </dd>
      </dl>
      <div class="bot" ><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.form1.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
function checkmember(){
	var membername = $.trim($("#member_name").val());
	if(membername == ''){
		$("#member_id").val('0');
		alert(<?php echo $lang['admin_points_addmembername_error']; ?>);
		return false;
	}
	$.getJSON("index.php?act=member&op=checkmember", {'name':membername}, function(data){
	        if (data)
	        {
		        $("#tr_memberinfo").show();
				var msg= "会员"+ data.name + "当前预存款为" + data.available_predeposit + "，当前冻结预存款为" + data.freeze_predeposit;
				$("#member_name").val(data.name);
				$("#member_id").val(data.id);
		        $("#td_memberinfo").text(msg);
	        }
	        else
	        {
	        	$("#member_name").val('');
	        	$("#member_id").val('0');
		        alert("会员信息错误");
	        }
	});
}
$(function(){
	$("#tr_memberinfo").hide();
	
    $('#points_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
        	member_name: {
				required : true
			},
			member_id: {
				required : true
            },
            pointsnum   : {
                required : true,
                min : 1
            }
        },
        messages : {
			member_name: {
				required : '<i class="fa fa-exclamation-circle"></i>请输入会员名'
			},
			member_id : {
				required : '<i class="fa fa-exclamation-circle"></i>会员信息错误，请重新填写会员名'
            },
            pointsnum  : {
                required : '<i class="fa fa-exclamation-circle"></i>请添加预存款',
                min : '<i class="fa fa-exclamation-circle"></i>预存款必须大于0'
            }
        }
    });
});
</script>