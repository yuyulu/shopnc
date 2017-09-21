$(function(){
    var key = getCookie('key');
	$.sValid.init({
		rules:{
			true_name:"required",
			mob_phone:"required",
			area_info:"required",
			address:"required"
		},
		messages:{
			true_name:"姓名必填！",
			mob_phone:"手机号必填！",
			area_info:"地区必填！",
			address:"街道必填！"
		},
		callback:function (eId,eMsg,eRules){
			if(eId.length >0){
				var errorHtml = "";
				$.map(eMsg,function (idx,item){
					errorHtml += "<p>"+idx+"</p>";
				});
				errorTipsShow(errorHtml);
			}else{
				errorTipsHide();
			}
		}  
	});
	$('#header-nav').click(function(){
		$('.btn').click();
	});
	$('.btn').click(function(){
		if($.sValid()){
			var true_name = $('#true_name').val();
			var mob_phone = $('#mob_phone').val();
			var address = $('#address').val();
			var city_id = $('#area_info').attr('data-areaid2');
			var area_id = $('#area_info').attr('data-areaid');
			var area_info = $('#area_info').val();
			var is_default = $('#is_default').attr("checked") ? 1 : 0;

			$.ajax({
				type:'post',
				url:ApiUrl+"/index.php?act=member_address&op=address_add",	
				data:{
				    key:key,
				    true_name:true_name,
				    mob_phone:mob_phone,
				    city_id:city_id,
				    area_id:area_id,
				    address:address,
				    area_info:area_info,
				    is_default:is_default
				},
				dataType:'json',
				success:function(result){
					if(result){
						location.href = WapSiteUrl+'/tmpl/member/address_list.html';
					}else{
						location.href = WapSiteUrl;
					}
				}
			});
		}
	});

    // 选择地区
    $('#area_info').on('click', function(){
        $.areaSelected({
            success : function(data){
                $('#area_info').val(data.area_info).attr({'data-areaid':data.area_id, 'data-areaid2':(data.area_id_2 == 0 ? data.area_id_1 : data.area_id_2)});
            }
        });
    });
});