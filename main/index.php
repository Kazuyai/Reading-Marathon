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
    <style>
        <?php
        if(isset($_POST['update'])) {
            echo "body {overflow: hidden}";
        }
        ?>
    </style>
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
            <form method="post" action="../mypage/index.php" class="header-button">
                <input type="hidden" name="UserID" value="<?php echo $_POST['UserID']?>">
                <button type="submit">マイページ</button>
            </form>
        </div>
        
    </header>
    <?php
        // ユーザ情報
        $user_id = $_POST['UserID'];

        // データベースからデータを取得する。
        $hostname = '127.0.0.1';
        $username = 'root';
        $password = 'dbpass';

        $dbname = 'reading_marathon';
        $tablename = 'PageData';

        $ranking_data = [];

        mysqli_report(MYSQLI_REPORT_OFF);
        
        $link = mysqli_connect($hostname, $username,$password,$dbname);
        if (! $link){ exit("Connect error!"); }

        // 読んだページ数が多い順に取得
        $result = mysqli_query($link, 
            "SELECT 
            UserData.UserID, 
            UserData.NAME, 
            COALESCE(pages.sum, 0) AS sum, 
            RANK() OVER(ORDER BY COALESCE(pages.sum, 0) DESC) AS `rank` 
        FROM 
            UserData 
        LEFT JOIN 
            (SELECT UserID, SUM(PAGE) as sum FROM PageData GROUP BY UserID) as pages 
        ON 
            UserData.UserID = pages.UserID
        WHERE 
            UserData.Admin != 1        
        ");
        if(!$result) { exit("query error"); }

        if($row = mysqli_fetch_assoc($result)) {
            $user_count = 0;
            do {
                $ranking_data[$user_count]["ID"] = $row['UserID'];
                $ranking_data[$user_count]["NAME"] = $row['NAME'];
                $ranking_data[$user_count]["PAGE"] = $row['sum'];
                $ranking_data[$user_count]["RANK"] = $row['rank'];
                $user_count++;
            } while($row = mysqli_fetch_assoc($result));
        } else {
            echo "<p>参加者はいません</p>";
        }

        // ユーザの配列番号を取得
        $user_index = array_search($user_id, array_column($ranking_data, "ID"));

        mysqli_free_result($result);
        mysqli_close($link);

        if(isset($_POST['update'])) {
            // 更新のアニメーション用要素を表示
            echo <<<EOT
            <section class="preview-zone night-bg" id="preview">
                <div class="marathon fadein">
                    <form method="post" action="./index.php" class="update">
                        <input type="text" name="UserID" value="$user_id">
                        <button type="submit" name="update">更新</button>
                    </form>
                    <div class="back-ground night-sky">
                        <img src="../images/sun.png" alt="" class="sun night-out">
                        <img src="../images/moon.png" alt="" class="moon night-in">
                        <img src="../images/back_wide.png" alt="" class="back night">
                        <img src="../images/front_wide.png" alt="" class="front night">
                    </div>
                    <div class="field night">
                        <img src="../images/track_wide.png" alt="" class="track">
EOT;
            for($i = 0; $i < 3; $i++) {
                if(isset($ranking_data[$i])) {
                    echo <<<EOT
                        <div class="player walk">
                            <img src="../images/runner.gif" alt="" class="human">
                            <div class="details">
                                <p class="name">{$ranking_data[$i]["NAME"]}</p>
                                <p>{$ranking_data[$i]["PAGE"]}ページ</p>
                            </div>
                        </div>
EOT;
                }
            }
            echo <<<EOT
                        <div class="player walk you">
                            <img src="../images/runner.gif" alt="" class="human">
                            <div class="details">
                                <p class="name">{$ranking_data[$user_index]["NAME"]}</p>
                                <p>{$ranking_data[$user_index]["PAGE"]}ページ</p>
                            </div>
                        </div>
EOT;
            echo <<<EOT
                    </div>
                </div>
            </section>
EOT;
        } else {
            echo <<<EOT
            <section class="preview-zone" id="preview">
                <div class="marathon fadein">
                    <form method="post" class="update">
                        <input type="text" name="UserID" value="$user_id">
                        <button type="submit" name="update">更新</button>
                    </form>
                    <div class="back-ground">
                        <img src="../images/sun.png" alt="" class="sun">
                        <img src="../images/moon.png" alt="" class="moon">
                        <img src="../images/back_wide.png" alt="" class="back">
                        <img src="../images/front_wide.png" alt="" class="front">
                    </div>
                    <div class="field">
                        <img src="../images/track_wide.png" alt="" class="track">
EOT;
            for($i = 0; $i < 3; $i++) {
                if(isset($ranking_data[$i])) {
                    echo <<<EOT
                        <div class="player">
                            <img src="../images/runner.gif" alt="" class="human">
                            <div class="details">
                                <p class="name">{$ranking_data[$i]["NAME"]}</p>
                                <p>{$ranking_data[$i]["PAGE"]}ページ</p>
                            </div>
                        </div>
EOT;
                }
            }
            echo <<<EOT
                        <div class="player you">
                            <img src="../images/runner.gif" alt="" class="human">
                            <div class="details">
                                <p class="name">{$ranking_data[$user_index]["NAME"]}</p>
                                <p>{$ranking_data[$user_index]["PAGE"]}ページ</p>
                            </div>
                        </div>
EOT;
            echo <<<EOT
                    </div>
                </div>
            </section>
EOT;
        }
    ?>
    <script>
        // ランキングデータをJSに渡す。
        const ranking_data = <?php echo json_encode($ranking_data); ?>;
        const user_id = <?php echo $user_id; ?>
    </script>
    <div class="connecting-part"></div>
    <section class="ranking">
        <h1>トップ10</h1>
        <div class="ranking-board">
            <?php
                for($i = 0; $i < min(10, sizeof($ranking_data)); $i++) {
                    echo '<div class="ranking-one">';
                    echo "<div class=\"number\">" . $i + 1 . "</div>";
                    echo "<div class=\"name\">{$ranking_data[$i]["NAME"]}</div>";
                    echo "<div class=\"page\">{$ranking_data[$i]["PAGE"]}</div>";
                    echo '</div>';
                }
                if($i == 0) {
                    echo "<p>参加者はいません</p>";
                }
            ?>
            <hr>
            <?php
                echo '<div class="ranking-one you">';
                echo "<div class=\"number\">{$ranking_data[$user_index]["RANK"]}</div>";
                echo "<div class=\"name\">{$ranking_data[$user_index]["NAME"]}</div>";
                echo "<div class=\"page\">{$ranking_data[$user_index]["PAGE"]}</div>";
                echo '</div>';
            ?>
        </div>
    </section>

    <script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"
    ></script>
    <script src="./script.js" type="text/javascript"></script>
</body>
</html>