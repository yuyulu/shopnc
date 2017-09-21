$(function(){
    var key = getCookie('key');
    var return_id = getQueryString("refund_id");
    template.helper('isEmpty', function(o) {
        for (var i in o) {
            return false;
        }
        return true;
    });
    $.getJSON(ApiUrl + '/index.php?act=member_return&op=get_return_info', {key:key,return_id:return_id}, function(result){
        $('#return-info-div').html(template.render('return-info-script', result.datas));
    });
});