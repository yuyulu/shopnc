<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="ncc-form-default">
  <form method="POST" id="addr_form" action="index.php">
    <input type="hidden" value="buy" name="act">
    <input type="hidden" value="add_addr" name="op">
    <input type="hidden" name="form_submit" value="ok"/>
    <dl>
      <dt><i class="required">*</i>门店<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <div><input name="region" type="hidden" id="region" value="">
        <input type="hidden" name="city_id" id="_area_2" />
        <input type="hidden" name="area_id" id="_area" />
        </div>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>收货人<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input type="text" class="text w100" name="true_name" maxlength="20" id="true_name" value=""/>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i><?php echo $lang['cart_step1_mobile_num'].$lang['nc_colon'];?></dt>
      <dd>
        <input type="text" class="text w200" name="mob_phone" id="mob_phone" maxlength="15" value=""/>
        &nbsp;&nbsp;(或)&nbsp;<?php echo $lang['cart_step1_phone_num'].$lang['nc_colon'];?>
        <input type="text" class="text w200" id="tel_phone" name="tel_phone" maxlength="20" value=""/>
      </dd>
    </dl>
  </form>
</div>

<script type="text/javascript">
$(document).ready(function(){
	var chain = function(area_id){
	    $.getJSON(SITEURL + '/index.php?act=buy&op=load_chain&area_id=' + area_id + '&store_id=' + chain_store_id + '&callback=?',function(data){
	    	$('#chain').remove();
	    	$('#region').next('label').remove();
	        if (data.length > 0) {
				var $newArea = $("<select name='chain' id='chain'><option value=''>请选择门店</option></select>");
				$("#region").before($newArea);
			    for (var i in data) {
			    	$newArea.append("<option value='"+data[i]['chain_id']+'|||'+data[i]['chain_name']+'（' + data[i]['chain_address'] + "）'>" + data[i]['chain_name'] + '（' + data[i]['chain_address'] + '）' + "</option>");
				}
		    } else {
		    	$('#region').after('<label class="error" for="region"><i class="icon-exclamation-sign"></i>该地区没有门店</label>');
		    }
		});
	};
	if (typeof ref_area_name != 'undefined' && ref_area_name != null) {
		$('#region').val(ref_area_name);
		$('#_area').val(ref_area_id);
		$('#_area_2').val(ref_area_id_2);
		var $newArea = $("<select name='chain' id='chain' style='display:none'></select>");
		$("#region").before($newArea);
	    $newArea.append("<option value='"+ref_chain_id+'|||'+ref_area_name+"'></option>");
	}
	$("#region").nc_region({last_click:chain});
	jQuery.validator.addMethod("checkchain", function(value, element) {
		if ($('#chain').length > 0 ) {
			jQuery.validator.messages['checkchain'] = '<i class="icon-exclamation-sign"></i>请选择门店';
			return $('#chain').val() != '';
		} else {
			jQuery.validator.messages['checkchain'] = '<i class="icon-exclamation-sign"></i>该地区没有门店';
			return false;
		}
	});
    $('#addr_form').validate({
        rules : {
            true_name : {
                required : true
            },
            region : {
            	checklast: true,
            	checkchain: true
            },
            mob_phone : {
                required : checkPhone,
                minlength : 11,
				maxlength : 11,
                digits : true
            },
            tel_phone : {
                required : checkPhone,
                minlength : 6,
				maxlength : 20
            }
        },
        messages : {
            true_name : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_input_receiver'];?>'
            },
            region : {
            	checklast: '<i class="icon-exclamation-sign"></i>请将地区选择完整'
            },
            mob_phone : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_telphoneormobile'];?>',
                minlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_mobile_num_error'];?>',
				maxlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_mobile_num_error'];?>',
                digits : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_mobile_num_error'];?>'
            },
            tel_phone : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_telphoneormobile'];?>',
                minlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['member_address_phone_rule'];?>',
				maxlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['member_address_phone_rule'];?>'
            }
        },
        groups : {
            phone:'mob_phone tel_phone'
        }
    });
});
function checkPhone(){
    return ($('input[name="mob_phone"]').val() == '' && $('input[name="tel_phone"]').val() == '');
}
function submitAddAddr(){
    if ($('#addr_form').valid()){
    	ref_area_name = null;
        $('#buy_city_id').val($('#region').fetch('area_id_2'));
        var true_name = $.trim($("#true_name").val());
        var tel_phone = $.trim($("#tel_phone").val());
        var mob_phone = $.trim($("#mob_phone").val());
    	var area_info = $.trim($("#region").val());
    	var address = $.trim($("#chain").val());
    	address = address.split('|||');
    	chain_id = address[0];
    	$('#input_chain_id').val(chain_id);
    	$('#input_chain_buyer_name').val(true_name);
    	$('#input_chain_mob_phone').val(mob_phone);
    	$('#input_chain_tel_phone').val(tel_phone);
        showProductChain($('#region').fetch('area_id_2') ? $('#region').fetch('area_id_2') : $('#region').fetch('area_id'));
    	hideAddrList(0,true_name,'[门店]'+'&nbsp;&nbsp;'+address[1],(mob_phone != '' ? mob_phone : tel_phone));
    }else{
        return false;
    }
}
</script>