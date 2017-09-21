var invite_id = getQueryString("rec");
$(function() {
	var e = getCookie("key");
	if (e) {
		window.location.href = WapSiteUrl + "/tmpl/member/member.html";
		return
	}
	$.getJSON(ApiUrl + "/index.php?act=connect&op=get_state&t=connect_sms_reg", function(e) {
		if (e.datas != "0") {
			$(".register-tab").show()
		}
	});
	$.sValid.init({
		rules: {
			username: "required",
			userpwd: "required",
			password_confirm: "required",
			email: {
				required: true,
				email: true
			}
		},
		messages: {
			username: "用户名必须填写！",
			userpwd: "密码必填!",
			password_confirm: "确认密码必填!",
			email: {
				required: "邮件必填!",
				email: "邮件格式不正确"
			}
		},
		callback: function(e, r, a) {
			if (e.length > 0) {
				var i = "";
				$.map(r, function(e, r) {
					i += "<p>" + e + "</p>"
				});

				errorTipsShow(i)
			} else {
				errorTipsHide()
			}
		}
	});
	$("#registerbtn").click(function() {
		if (!$(this).parent().hasClass("ok")) {
			return false
		}

		var e = $("input[name=username]").val();
		var r = $("input[name=pwd]").val();
		var a = $("input[name=password_confirm]").val();
		var i = $("input[name=email]").val();
	    n = (invite_id);
		var t = "wap";
		var j="wq";
		if ($.sValid()) {
			$.ajax({
				type: "post",
				url: ApiUrl + "/index.php?act=login&op=register_invite",
				data: {
					username: e,
					password: r,
					password_confirm: a,
					email: i,
					invite_id: n,
					client: t
				},
				dataType: "json",
				success: function(e) {
					if (!e.datas.error) {
						if (typeof e.datas.key == "undefined") {
							return false
						} else {
							updateCookieCart(e.datas.key);
							addCookie("username", e.datas.username);
							addCookie("key", e.datas.key);
							location.href = WapSiteUrl + "/tmpl/member/member.html"
						}

						errorTipsHide()
					} else {
						errorTipsShow("<p>" + e.datas.error + "</p>")
					}
				}
			})
		}
	})
});