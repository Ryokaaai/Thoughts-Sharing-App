<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_6_SignUp</title>
    <link rel="stylesheet" href="Style1.css">
    <link rel="stylesheet" href="Style5.css">
    <link rel="stylesheet" href="Style6.css">
    <link rel="stylesheet" href="Style3.css">
</head>
<body>
    <!--背景の設定-->
    <style type="text/css">
        body {
        background-color: #486d46;            /* 背景色 */
        background-image: url("BG1.png"); /* 画像 */
        background-size: cover;               /* 全画面 */
        background-attachment: fixed;         /* 固定 */
        background-position: center center;/* 縦横中央 */
        }
    </style>
    <!--ヘッダー-->
    <div class = "box17" align="center">
    <h2>🍌新規登録フォーム🐠</h2><br>
    <p>下記をご記入の上、「登録する」を押し、ログインフォームからログインしてください！</p>
    </div>
    
    <?php
        
        /// DB接続設定
        $dsn = 'mysql:dbname=********;host=localhost';
        $user = '********';
        $password = '********';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        // テーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS USER_INFO"
        ."("
        . "name TEXT,"
        . "pass TEXT,"
        . "mail TEXT"
        .");";
        $stmt = $pdo->query($sql);

        //新規登録
        //新規登録フォームで受け取りが空でなかったら以下を実行
        if(!empty($_POST["name_new"]) && !empty($_POST["pass_new"] && !empty($_POST["mail_new"]))){ 
            //データの受け取り
            $name = $_POST["name_new"]; 
            $pass = $_POST["pass_new"];
            $mail = $_POST["mail_new"];
            // 登録完了を表示
            echo "<center>$name さん、登録が完了しました！<center><br>";
            
            // レコードの挿入
            $sql = $pdo -> prepare("INSERT INTO USER_INFO (name, pass, mail) VALUES (:name, :pass, :mail)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $sql -> bindParam(':mail', $mail, PDO::PARAM_STR);
            $sql -> execute();
        }
        
    ?>
    <div align="center">
    <form action="" method="post">
        <table>
            <tr>
                <!--ユーザー名フォーム-->
                <th><label>ユーザー名</label></th>
                <td><input type="text" name="name_new" placeholder="例) Aslan Callenreese" size="50"><br></td>
            </tr>
            
            <tr>
                <!--パスワードフォーム-->
                <th><label>パスワード</label></th>
                <td><input type="text" name="pass_new" placeholder="●●●●●●●●●●" size="50"><br></td>
            </tr>
            
            <tr>
                <!--emailフォーム-->
                <th><label>メールアドレス</label></th>
                <td><input type="text" name="mail_new" placeholder="例) BananaFish@example.com" size="50"><br></td>
            </tr>
        </table>
            
            <br>
        　　<p　class="formbottom">
                <!--送信ボタン-->
                <input type="submit" name="submit" value="登録する" class="btns submit">
                <input type="button" name="Longin" id="LoginForm" value="ログインフォームへ" class="btns submit">
            </p>    
            
    </form>
    
    <!--ログインフォームへのボタン-->
        
        <script>
            document.getElementById("LoginForm").onclick = function () {
                location.href = 'M_6_Login.php';
            }
        </script>
    
</body>
</html>