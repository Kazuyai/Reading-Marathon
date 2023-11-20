<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading Marathon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Black+Ops+One&display=swap" rel="stylesheet">

    <link rel="icon" href="../images/アイコン_透過.png">
    <link rel="stylesheet" href="../common.css">
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="../index.php">
                <img src="../images/ロゴ_透過.png" alt="ロゴ">
                <p>Reading Marathon</p>
            </a>    
        </div>
        <div class="header-menu">
            <div class="header-button">
                <a href="../sign-up/index.php">新規登録</a>
            </div>
            <div class="header-button">
                <a href="./index.php">ログイン</a>
            </div>
        </div>
    </header>
    <script src="./script.js" type="text/javascript"></script>
    <?php
        // データベースからデータを取得する。
        $hostname = '127.0.0.1';
        $username = 'root';
        $password = 'dbpass';

        $dbname = 'reading_marathon';
        $tablename = 'PageData';

        $is_correct = false;
        $is_admin = false;

        mysqli_report(MYSQLI_REPORT_OFF);
        
        $link = mysqli_connect($hostname, $username,$password,$dbname);
        if (! $link){ exit("Connect error!"); }

        $id = "";
        $pass = "";

        // ID・パスワード入力時
        if(isset($_POST["UserID"], $_POST["pass"])) {
            // ID・パスワードに対応するユーザがいるかを確認
            $id = htmlspecialchars($_POST["UserID"]);
            $pass = ($_POST["pass"]);
            $result = mysqli_query($link, "SELECT COUNT(*) as EXIST FROM UserData WHERE UserID = '$id' AND PassWord = '$pass' LIMIT 1");
            $row = mysqli_fetch_assoc($result);
            if($row["EXIST"] == 1) {
                $result = mysqli_query($link, "SELECT COUNT(*) as ADMIN FROM UserData WHERE UserID = '$id' AND PassWord = '$pass' AND Admin = 1 LIMIT 1");
                $row = mysqli_fetch_assoc($result);
                if($row["ADMIN"] == 1) {
                    $is_admin = true;
                }else {
                    $is_correct = true;
                }
            } else {
                // ログイン失敗
                echo "<p class='alert'>IDかパスワードが間違っています</p>";
            }
            mysqli_free_result($result);
        }

        mysqli_close($link);
    ?>
    <section class="login">
        <div class="login-form">
            <h1>ログイン</h1>
            <form name="info" method="POST" action="./index.php">
                <div class="id">
                    <p>ID</p>
                    <input type="number" name="UserID" value="<?php echo $id ?>" required autocomplete="off">
                </div>
                <div class="pass">
                    <p>パスワード</p>
                    <input type="password" name="pass" value="<?php echo $pass ?>" required>                
                </div>
                <button type="submit">ログイン</button>
            </form>
        </div>
    </section>
    <?php
        if($is_correct) {
            echo "<script>login();</script>";
        } else if($is_admin) {
            echo "<script>login_admin();</script>";
        }
    ?>
</body>
</html>