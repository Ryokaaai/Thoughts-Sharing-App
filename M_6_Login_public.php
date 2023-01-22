<?php

session_start();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_6_Login</title>
    <link rel="stylesheet" href="Style2.css">
    <link rel="stylesheet" href="Style4.css">
</head>
<body>
    
    <div class="content3">このサイトの説明</div>
    <div class="body"></div>
       
        <div class="grad"></div>
        
        <div class="header">
        <div>BANANA<span>FISHERS</span></div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        
        
        
        </div>
    <br>
    
    <form action="" method="post">
    <div class="login">
        <font size = 2.5>
        2018年に原作30周年を記念して<br>
        アニメ放送された不朽の名作 ーBANANA FISHー<br>
        見始めてしまったそこのあなた、<br>
        誰かと語らずにはいられないですよね...?<br>
        以下からログインor新規登録して、語り合いましょう😏
        </font><br>
        <input type="text" placeholder="username" name="user"><br>
        <input type="password" placeholder="password" name="password"><br>
        <input type="submit" value="Login">
        <input type="button" value="Sign Up" name="new" id="SignUp">
    </div>
    </form>
    
    <!-- SignUpをクリックしたら新規登録フォームに画面遷移させる-->
    <script>
    document.getElementById("SignUp").onclick = function () {
        location.href = 'M_6_SignUp.php';
    }
    </script>

    <?php
        
        /// DB接続設定
        $dsn = 'mysql:dbname=********;host=localhost';
        $user = '********';
        $password = '********';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        //ユーザー名からパスワードを比較し、登録されたものと等しければ、ログイン可能にする
        if(!empty($_POST["user"]) && !empty($_POST["password"])){
            //データ受け取り
            $user = $_POST["user"];
            $password = $_POST["password"];
            
            //ユーザーネームからパスワードを取り出し、受け取りと一緒か確認
            $sql = "SELECT * FROM USER_INFO WHERE name=:name";
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':name', $user, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                //パスワードが一致したら、ログイン成功＝次の画面へ
                if($row['pass'] == $password){
                    session_start();
                    $_SESSION["name"]=$row["name"];
                    $_SESSION["pass"]=$row["pass"];
                    $_SESSION["ok"]="ok";
                    header("Location:M_6_Home.php");
                    exit();
                }else{
                    echo "usernameかpasswordが正しくありません";//ここ表示されない？？？/////////////////
                }
            }
        }
    ?>
</body>
</html>