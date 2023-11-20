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
    <?php
        // データベースからデータを取得する。
        $hostname = '127.0.0.1';
        $username = 'root';
        $password = 'dbpass';

        $dbname = 'reading_marathon';
        $tablename = 'PageData';

        $success_register = false;
        $success_change = false;
        $success_delete = false;

        mysqli_report(MYSQLI_REPORT_OFF);
        
        $link = mysqli_connect($hostname, $username,$password,$dbname);
        if (! $link){ exit("Connect error!"); }

        if(isset($_POST["UserID"])) {$user_id = $_POST["UserID"];}

        if(isset($_POST["process"])) {
            switch ($_POST["process"]) {
                case "register":
                    $time = date('Y-m-d');
                    $page = $_POST["page"];
                    $name = htmlspecialchars($_POST["name"]);
                    $result = mysqli_query($link, "INSERT INTO PageData SET TIME = '$time', UserID = $user_id, PAGE = $page, NAME = '$name'");
                    if ($result) { 
                        $success_register = true;
                        // ページデータIDを取得
                        $new_id = mysqli_insert_id($link);
                    } else {
                        echo "<p class='alert red'>登録に失敗しました</p>";
                    }
                    break;
                case "change":
                    $id = $_POST["ID"];
                    $time = date('Y-m-d');
                    $page = $_POST["page"];
                    $name = htmlspecialchars($_POST["name"]);
                    $result = mysqli_query($link, "UPDATE PageData SET TIME = '$time', PAGE = '$page' WHERE ID = '$id'");
                    if($result) {
                        $success_change = true;
                    } else {
                        echo "<p class='alert red'>失敗しました</p>";
                    }
                    break;
                case "delete":
                    $id = $_POST["ID"];
                    $result = mysqli_query($link, "DELETE FROM PageData WHERE ID = '$id'");
                    if($result) {
                        $success_delete = true;
                    } else {
                        echo "<p class='alert red'>失敗しました</p>";
                    }
                    break;
            }
        }

        if(isset($_POST["UserID"])) {
            $result = mysqli_query($link, "SELECT UserData.NAME, UserData.PassWord, COALESCE(SUM(PageData.PAGE), 0) AS sum FROM UserData LEFT JOIN PageData ON UserData.UserID = PageData.UserID WHERE UserData.UserID = '$user_id' GROUP BY UserData.UserID, UserData.NAME, UserData.PassWord");
            if($result) {
                $row = mysqli_fetch_assoc($result);
                $user_name = $row["NAME"];
                $pass = $row["PassWord"];
                $page_sum = $row["sum"];
            }
        }
    ?>
    <header>
        <div class="logo">
            <a href="../index.php">
                <img src="../images/ロゴ_透過.png" alt="ロゴ">
                <p>Reading Marathon</p>
            </a>    
        </div>
        <div class="header-menu">
            <form method="post" action="../main/index.php" class="header-button">
                <input type="hidden" name="UserID" value="<?php echo $user_id?>">
                <button type="submit">メイン画面</button>
            </form>
        </div>
    </header>
    <section class="my-page">
        <h1>マイページ</h1>
        <hr>
        <h3>ユーザ情報</h3>
        <div class="user-info">
            <div class="info-row">
                <p>ユーザID</p>
                <?php echo "<p>$user_id</p>"?>
            </div>
            <div class="info-row">
                <p>ユーザネーム</p>
                <?php echo "<p>$user_name</p>"?>
            </div>
            <div class="info-row">
                <p>パスワード</p>
                <?php echo "<p>$pass</p>"?>
            </div>
            <div class="info-row">
                <p>合計ページ数</p>
                <?php echo "<p>$page_sum</p>"?>
            </div>
        </div>
        <hr>
        <h3>ページデータ登録</h3>
        <form method="post" action="./index.php">
            <p>ページ数</p>
            <input type="text" pattern="^[1-9][0-9]*$" name="page" autocomplete="off" required>
            <p>本の名前</p>
            <input type="text" name="name">
            <input type="hidden" name="UserID" value="<?php echo $user_id?>">
            <button type="submit" name="process" value="register">登録</button>
        </form>
        
        <?php
            if($success_register) {
                echo '<p class="alert">以下の内容を登録しました</p>';
                $result = mysqli_query($link, "SELECT * FROM PageData WHERE ID = $new_id");
                $row = mysqli_fetch_assoc($result);
                echo '<div class="new">';
                echo_data_card($new_id, $row["PAGE"], $name, $time, true);
                echo '</div>';
            }

            echo '<hr>';
            echo '<h3>ページデータ一覧</h3>';
            // ページデータ変更・削除メッセージ
            if($success_change) { echo '<p class="alert">データを変更しました</p>';}
            if($success_delete) { echo '<p class="alert">データを削除しました</p>';}
            echo '<div class="data-box">';

            // ユーザのページデータ一覧取得
            if(isset($_POST["UserID"])) {
                $id = $_POST["UserID"];
                $result = mysqli_query($link, "SELECT * FROM PageData WHERE UserID = $id ORDER BY ID DESC");
                if($row = mysqli_fetch_assoc($result)) {
                    do {
                        echo_data_card($row["ID"], $row["PAGE"], $row["NAME"], $row["TIME"], false);
                    } while($row = mysqli_fetch_assoc($result));
                } else {
                    echo "<p>データがありません</p>";
                }
            }

            echo '</div>';

            mysqli_close($link);

            // ページデータをカード表示させる関数
            function echo_data_card($id, $page, $name, $time, $no_btn) 
            {
                global $user_id;
                echo <<<EOT
                    <form method="post" class="data-card">
                        <p>ページデータID：$id</p>
                        <div class="page toggle-row">
                            <p>ページ数：</p>
                            <div class="toggle-box">
                                <p class="toggle view">$page</p>
                                <input type="text" name="page" class="toggle" value="$page"  pattern="^[1-9][0-9]*$" autocomplete="off">
                            </div>
                        </div>
                        <div class="name toggle-row">
                            <p>本の名前：</p>
                            <div class="toggle-box">
                                <p class="toggle view">$name</p>
                                <input type="text" name="name" class="toggle" value="$name">
                            </div>
                        </div>
                        <p>登録日：$time</p>
EOT;
                if(!$no_btn) {
                    echo <<<EOT
                        <p class="confirm">本当に削除しますか？</p>
                        <div class="btn-box">
                            <a class="change view">変更</a>
                            <a class="quit">中止</a>
                            <a class="delete view">削除</a>
                            <a class="yes">はい</a>
                            <a class="no">いいえ</a>
                            <input type="hidden" name="process" class="process">
                            <input type="hidden" name="UserID" value="$user_id">
                            <input type="hidden" name="ID" value="$id">
                        </div>
EOT;
                }
                echo "</form>";
            }
        ?>
    </section>
    <script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"
    ></script>
    <script src="./script.js"></script>
</body>
</html>