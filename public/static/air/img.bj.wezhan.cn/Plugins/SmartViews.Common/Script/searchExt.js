$(function () {
    $("#topContainer").on("pageframesearch_stopresize", function (event, smartview, dragSize) {
        //var img = $("#" + smartview.htmlId).find("img");
    }//设计器触发图片尺寸改变的事件
    ).on("pageframesearch_resize", function (event, smartview) {
        var view = $("#" + smartview.htmlId);

        var width = view.width();
        var div = view.children("div").children("div").eq(1);
        if(div.attr("id")=="Style2"){
            var widthbutton = div.find("button").width();
            div.find("input").css("width", width - 70);
            var height = view.height();
            div.children().css("height", height - 4).css("line-height", (height - 4) + "px");
            div.children("div").children().css("height", height - 4).css("line-height", (height - 4) + "px");
            div.find("input").css("height", div.find("button").height());
        } else {
            var widthdiv = div.children("div").width() + parseInt(div.children("div").css("margin-right")) + parseInt(div.children("div").css("border-right-width")) + 24;

            //div.children().css("line-height", height);
            var widthbutton = div.children("button").width();
            var inputmargin = parseInt(div.children("input").css("margin-right")) + parseInt(div.children("input").css("border-right-width")) + parseInt(div.children("input").css("margin-left")) + parseInt(div.children("input").css("border-left-width"));
            div.children("input").css("width", width - widthdiv - widthbutton - inputmargin);

            div.children("div").children("input").css("width", width - 70);
            var height = view.height();
            div.children().css("height", height - 4).css("line-height", (height - 4) + "px");
            div.children("div").children().css("height", height - 4).css("line-height", (height - 4) + "px");
        }
    });
});
