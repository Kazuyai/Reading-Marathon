// POSTでメイン画面に値を送る処理
function login() {
    frm = document.info;
    frm.action="../main/index.php";
    frm.submit();
}

// 管理者でログインする処理
function login_admin() {
    frm = document.info;
    frm.action="../user-list/index.php";
    frm.submit();
}