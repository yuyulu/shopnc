$(function(){
    var key = getCookie('key');
    var refund_id = getQueryString("refund_id");
    template.helper('isEmpty', function(o) {
        for (var i in o) {
            return false;
        }
        return true;
    });
    $.getJSON(ApiUrl + '/index.php?act=member_refund&op=get_refund_info', {key:key,refund_id:refund_id}, function(result){
            $('#refund-info-div').html(template.render('refund-info-script', result.datas));
    });
});