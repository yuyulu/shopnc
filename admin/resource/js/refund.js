function show_msg(ajaxurl) {//提示信息
	$.ajax({
		type: "GET",
		dataType:"json",
		url: ajaxurl,
		async: false,
		success: function(rs){
            if(rs['state'] == 'true') {
            	showSucc(rs['msg']);
            } else {
                showError(rs['msg']);
            }
	    }
	});
}