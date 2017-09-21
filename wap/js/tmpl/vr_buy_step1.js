var key = getCookie('key');
var goods_id = getQueryString("goods_id");
var quantity = getQueryString("quantity");
var data = {};

data.key = key;
data.goods_id = goods_id;
data.quantity = quantity;

Number.prototype.toFixed = function(d)
{
    var s=this+"";if(!d)d=0;
    if(s.indexOf(".")==-1)s+=".";s+=new Array(d+1).join("0");
    if (new RegExp("^(-|\\+)?(\\d+(\\.\\d{0,"+ (d+1) +"})?)\\d*$").test(s))
    {
        var s="0"+ RegExp.$2, pm=RegExp.$1, a=RegExp.$3.length, b=true;
        if (a==d+2){a=s.match(/\d/g); if (parseInt(a[a.length-1])>4)
        {
            for(var i=a.length-2; i>=0; i--) {a[i] = parseInt(a[i])+1;
            if(a[i]==10){a[i]=0; b=i!=1;} else break;}
        }
        s=a.join("").replace(new RegExp("(\\d+)(\\d{"+d+"})\\d$"),"$1.$2");
    }if(b)s=s.substr(1);return (pm+s).replace(/\.$/, "");} return this+"";
};

var p2f = function(f) {
    return (parseFloat(f) || 0).toFixed(2);
};

$(function() {
    $.ajax({
        type:'post',
        url:ApiUrl+'/index.php?act=member_vr_buy&op=buy_step2',
        dataType:'json',
        data:data,
        success:function(result){
            var data = result.datas;
            if (typeof(data.error) != 'undefined') {
                location.href = WapSiteUrl;
                return;
            }
            data.WapSiteUrl = WapSiteUrl;
            var html = template.render('goods_list', data);
            $("#deposit").html(html);
            $('#buyerPhone').val(data.member_info.member_mobile);
            $('#totalPrice').html(data.goods_info.goods_total);
        }
    });

    $('#ToBuyStep2').click(function() {
        var data = {};
        data.key = key;
        data.goods_id = goods_id;
        data.quantity = quantity;

        var buyer_phone = $('#buyerPhone').val();
        if (! /^\d{7,11}$/.test(buyer_phone)) {
            $.sDialog({
                skin:"red",
                content:'请正确输入接收手机号码！',
                okBtn:false,
                cancelBtn:false
            });
            return false;
        }
        data.buyer_phone = buyer_phone;
        data.buyer_msg = $('#storeMessage').val();
        $.ajax({
            type:'post',
            url:ApiUrl+'/index.php?act=member_vr_buy&op=buy_step3',
            data:data,
            dataType:'json',
            success:function(result) {
                checkLogin(result.login);

                if (result.datas.error) {
                    $.sDialog({
                        skin:"red",
                        content:result.datas.error,
                        okBtn:false,
                        cancelBtn:false
                    });
                    return false;
                }

                if (result.datas.order_id) {
                	toPay(result.datas.order_sn,'member_vr_buy','pay');
                }

                return false;
            }
        });
    });
});