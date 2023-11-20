// 「変更」ボタンの処理
$(".change").click(function() {
    let quit = $(this).next();
    if(quit.attr("class") == "quit view") {
        // 型が正しいかの判定を行う
        if (!$(this).parent().parent()[0].reportValidity()) {
            return false;
        }
        // 変更を確定
        $(this).nextAll(".process").val("change");
        $(this).parent().parent().attr('action', './index.php').submit();
    } else {
        // 変更用入力欄の表示
        $(this).parent().prevAll("div").children(".toggle-box").children(".toggle").toggleClass("view");
        $(this).next().next().toggleClass("view");
        quit.toggleClass("view");    
    }    
});

// 「中止」ボタンの処理
$(".quit").click(function() {
    $(this).parent().prevAll("div").children(".toggle-box").children(".toggle").toggleClass("view");
    $(this).nextAll(".delete").toggleClass("view");
    $(this).toggleClass("view");
});

// 「削除」ボタンの処理
$(".delete").click(function() {
    $(this).parent().prev().toggleClass("view");
    $(this).prevAll(".change").toggleClass("view");
    $(this).toggleClass("view");
    $(this).next().toggleClass("view");
    $(this).next().next().toggleClass("view");
});

// 「はい」ボタンの処理
$(".yes").click(function() {
    $(this).nextAll(".process").val("delete");
    $(this).parent().parent().attr('action', './index.php').submit();
});

// 「いいえ」ボタンの処理
$(".no").click(function() {
    $(this).parent().prev().toggleClass("view");
    $(this).prevAll(".change").toggleClass("view");
    $(this).prevAll(".delete").toggleClass("view");
    $(this).prev().toggleClass("view");
    $(this).toggleClass("view");
});