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
    ?>
    <header>
        <div class="logo">
            <a href="../index.php">
                <img src="../images/ロゴ_透過.png" alt="ロゴ">
                <p>Reading Marathon</p>
            </a>    
        </div>
        <div class="header-menu">
        </div>
    </header>
    <section class="user-list">
        <h1>ユーザ一覧</h1>
        
        <?php
            echo '<hr>';
            // ページデータ削除
            if(isset($_POST["delete"])) {
                $delete_id = $_POST["UserID"];
                $result = mysqli_query($link, "DELETE FROM UserData WHERE UserID = '$delete_id'");
                if ($result){ 
                    echo "<p class='alert'>ユーザを削除しました</p>";
                } else {
                    echo "<p class='alert'>失敗しました</p>";
                }
            }

            // ユーザ一覧取得
            $result = mysqli_query($link, "SELECT * FROM UserData");
            if($row = mysqli_fetch_assoc($result)) {
                echo '<table class="user-list-box">';
                echo '<th>ユーザID</th><th>ユーザネーム</th><th></th>';
                do {
                    echo <<<EOT
                        <tr>
                            <td>{$row['UserID']}</td>
                            <td>{$row['NAME']}</td>
                            <td>
                                <p class="confirm">本当に削除しますか？</p>
                                <form method="post">
                                    <a class="delete view">削除</a>
                                    <a class="yes">はい</a>
                                    <a class="no">いいえ</a>
                                    <input type="hidden" name="delete">
                                    <input type="hidden" name="UserID" value="{$row['UserID']}">
                                </form>
                            </td>
                        </tr>
EOT;
                } while($row = mysqli_fetch_assoc($result));
                echo '</table>';
            } else {
                echo "<p>データがありません</p>";
            }

            mysqli_close($link);
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