function getQueryStringByName(name) {
	var result = location.search.match(new RegExp("[\?\&]" + name + "=([^\&]+)", "i"));
	if (result == null || result.length < 1) {
		return "";
	}
	return result[1];
}
$(function () {
    changePic();
    $(".u_form_item .u_label_text").each(function () {
        $(this).on('click', function () {
            $(this).hide();
            $(this).siblings(".u_input").focus();
        });

    });
    $(".u_form_item .u_input").each(function () {
        var thisVal = $(this).val();
        //判断文本框的值是否为空，有值的情况就隐藏提示语，没有值就显示
        if (thisVal != "") {
            $(this).siblings(".u_label_text").hide();
        } else {
            $(this).siblings(".u_label_text").show();
        }
        //聚焦型输入框验证
        $(this).focus(function () {
            $(this).siblings(".u_label_text").hide();
            $(".f_clearfix label").attr("class", "u_label");
            $(this).parent("label").attr("class", "u_label u_label_err");
        }).blur(function () {
            var val = $(this).val();
            if (val != "") {
                $(this).siblings(".u_label_text").hide();
            } else {
                $(this).siblings(".u_label_text").show();
            }
        });
    })
});

function changePic() {
	var path = $("#hidValidCode").val() + "?time=" + (new Date()).toLocaleTimeString();
	$("#vpic").attr("src", path);

}

function reg() {
		if ($("#form_main").valid()) {
			$.post("/Customer/Register", $("#form_main").serialize(), function (data) {
                    
				if (data.indexOf("<") >= 0) {
                        
					$("body").html(data);
				} else {
					if (data.go)
						window.location.href = data.url;
					var returnurl = getQueryStringByName('returnurl');
					if (returnurl)
					{ window.location.href = returnurl; return }

					window.location.href = window.location.href.substr(0, window.location.href.indexOf("/register"));
				}
			})
		}
}

