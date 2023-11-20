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
                <a href="./index.php">新規登録</a>
            </div>
            <div class="header-button">
                <a href="../login/index.php">ログイン</a>
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

        mysqli_report(MYSQLI_REPORT_OFF);
        
        $link = mysqli_connect($hostname, $username,$password,$dbname);
        if (! $link){ exit("Connect error!"); }

        $id = "";
        $name = "";
        $pass = "";

        // 名前・パスワード入力時
        if(isset($_POST["name"], $_POST["pass"])) {
            $name = htmlspecialchars($_POST["name"]);
            $pass = htmlspecialchars($_POST["pass"]);
            $result = mysqli_query($link, "INSERT INTO UserData SET NAME = '$name', PassWord = '$pass'");
            if (!$result) { 
                echo "<p class='alert'>登録に失敗しました</p>"; 
            } else {
                $is_correct = true;
                // IDを検索する
                $id = mysqli_insert_id($link);
            }
        }

        mysqli_close($link);
    ?>
    <section class="sign-up">
        <div class="sign-up-form">
            <h1>新規登録</h1>
            <form name="info" method="POST" action="./index.php">
                <input type="hidden" name="UserID" value="<?php echo $id?>">
                <div class="name">
                    <p>ユーザ名</p>
                    <input type="text" name="name" value="" required autocomplete="off" maxlength="8">
                </div>
                <div class="pass">
                    <p>パスワード</p>
                    <input type="password" name="pass" value="" required>                
                </div>
                <button type="submit">登録</button>
            </form>
        </div>
    </section>
    <?php
        if($is_correct) {
            echo "<script>login();</script>";
        }
    ?>
</body>
</html>