// 「削除」ボタンの処理
$(".delete").click(function() {
    $(this).parent().prev().toggleClass("view");
    $(this).toggleClass("view");
    $(this).next().toggleClass("view");
    $(this).next().next().toggleClass("view");
});

// 「はい」ボタンの処理
$(".yes").click(function() {
    $(this).parent().attr('action', './index.php').submit();
});

// 「いいえ」ボタンの処理
$(".no").click(function() {
    $(this).parent().prev().toggleClass("view");
    $(this).prev().prev().toggleClass("view");
    $(this).prev().toggleClass("view");
    $(this).toggleClass("view");
});