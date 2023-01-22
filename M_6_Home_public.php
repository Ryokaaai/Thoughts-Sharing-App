<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf8mb4">
    <title>mission_6_Home</title>
    <link rel="stylesheet" href="Style3.css">
    <link rel="stylesheet" href="Style4.css">
    <link rel="stylesheet" href="Style6.css">
</head>
<body>
    <!-- 背景の設定-->
    <div class="bg"></div>
    <div class="bg bg2"></div>
    <div class="bg bg3"></div>
    
    <!--タイトル-->
    <div class="box17">
        <h1>全24話について語り合いましょう！</h1>
        <h3>フォームから各話ごとのコメントの投稿、削除、編集が可能です！</h3>
        <!--公式ページへのボタンの設定-->
        <input type=button id="Official" Value=公式ページへ>
        <!-- クリックしたら公式ページに画面遷移させる-->
        <script>
        document.getElementById("Official").onclick = function () {
            const url = 'https://bananafish.tv/';
            window.open(url, '_blank');
        }
        </script>
        <h3></h3>
    </div>
    
    <div class="box5" align="center">
    <div class="button04"><br>
        <h2>🍌タイトル一覧🐠</h2>
         <a href="#EP.1">#1 バナナ・フィッシュにうってつけの日</a><br>
         <a href="#EP.2">#2 異国にて</a><br>
         <a href="#EP.3">#3 河を渡って木立の中へ</a><br>
         <a href="#EP.4">#4 楽園のこちら側</a><br>
         <a href="#EP.5">#5 死より朝へ</a><br>
         <a href="#EP.6">#6 マイ・ロスト・シティー</a><br>
         <a href="#EP.7">#7 リッチ・ボーイ</a><br>
         <a href="#EP.8">#8 陳腐なストーリー</a><br>
         <a href="#EP.9">#9 ワルツは私と</a><br>
         <a href="#EP.10">#10 バビロンに帰る</a><br>
         <a href="#EP.11">#11 美しく呪われし者</a><br>
         <a href="#EP.12">#12 持つと持たぬと</a><br>
         <a href="#EP.13">#13 キリマンジャロの雪</a><br>
         <a href="#EP.14">#14 夜はやさし</a><br>
         <a href="#EP.15">#15 エデンの園</a><br>
         <a href="#EP.16">#16 悲しみの孔雀</a><br>
         <a href="#EP.17">#17 殺し屋</a><br>
         <a href="#EP.18">#18 海流の中の島々</a><br>
         <a href="#EP.19">#19 氷の宮殿</a><br>
         <a href="#EP.20">#20 征服されざる人々</a><br>
         <a href="#EP.21">#21 敗れざる者</a><br>
         <a href="#EP.22">#22 死の床に横たわりて</a><br>
         <a href="#EP.23">#23 誰がために鐘は鳴る</a><br>
         <a href="#EP.24">#24 ライ麦畑でつかまえて</a><br>
    </div>
    </div>
    
    <?php
    /// DB接続設定
        $dsn = 'mysql:dbname=********;host=localhost';
        $user = '********';
        $password = '********';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        // テーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS COMMENT"
        ." ("
        . "id INT,"
        . "epi INT,"
        . "name TEXT,"
        . "comment TEXT,"
        . "date TEXT,"
        . "password TEXT"
        .");";
        $stmt = $pdo->query($sql);
    
   
    ?>
    
    <div class="content2">
            <?php
                
                // sessionで名前とパスワードを受け取り、フォームに自動的に入力
                session_start();
                $L_name = $_SESSION["name"];
                $L_pass = $_SESSION["pass"];
                
                //投稿
                //投稿フォームで受け取りが空でなかったら以下を実行
                if(!empty($_POST["comment"]) && !empty($_POST["epi_number"]) && empty($_POST["num_edit"])){ //パスワードの入力がなくても一応投稿できるようにする
                //データの受け取り
                    $comment = $_POST["comment"]; 
                    $name = $_POST["name"];
                    $password = $_POST["password_new"];
                    $epi = $_POST["epi_number"];
                    // 受け取り完了を表示
                    echo $name."さん、メッセージを受け付けました！<br>";
                    //投稿日時の設定
                    $date = date("Y/m/d H:i:s");
                    //idの設定
                    //もしレコードがない時はid=1 それ以外は最後のid+1
                    $sql = "SELECT * FROM COMMENT";
                    $result = $pdo->query($sql);
                    $cnt = $result->rowCount();
                    if($cnt != 0){
                        $id = $cnt + 1;
                    }else{
                        $id = 1;
                    }
            
                    // レコードの挿入
                    $sql = $pdo -> prepare("INSERT INTO COMMENT (id, epi, name, comment, date, password) VALUES (:id, :epi, :name, :comment, :date, :password)");
                    $sql -> bindParam(':id', $id, PDO::PARAM_INT);
                    $sql -> bindParam(':epi', $epi, PDO::PARAM_INT);
                    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                    $sql -> bindParam(':password', $password, PDO::PARAM_STR);
                    $sql -> execute();
                
                // 削除
                // 削除フォームで受け取りが空でなかったら以下を実行       
                }elseif(!empty($_POST["number_delete"]) && !empty($_POST["password_delete"])){
                    //データ受け取り
                    $number_delete = $_POST["number_delete"];
                    $password_delete = $_POST["password_delete"];
                    //削除番号のidのレコードをDBから取り出し
                    $id = $number_delete ; // idがこの値のデータだけを抽出したい、とする
                    $sql = 'SELECT * FROM COMMENT WHERE id=:id ';
                    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                    $stmt->execute();                             // ←SQLを実行する。
                    $results = $stmt->fetchAll(); 
                    foreach ($results as $row){
                        //$rowの中にはテーブルのカラム名が入る
                        //パスワードが一致したら、削除する
                        if($row['password'] == $password_delete){
                            $sql = 'delete from COMMENT where id=:id';
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                    }  
                    //idが$number_deleteより大きいものは-1する
                    $sql = "UPDATE COMMENT SET id=id-1 WHERE id > $number_delete";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
            
                    echo "削除しました";
                
                //編集
                //編集フォームで受け取りが空でなかったら以下を実行    
                }elseif(!empty($_POST["number_edit"]) && !empty($_POST["password_edit"])){
                    //データ受け取り
                    $number_edit = $_POST["number_edit"];
                    $password_edit = $_POST["password_edit"];
                    //編集番号のidのレコードをDBから取り出し
                    $id = $number_edit ; // idがこの値のデータだけを抽出したい、とする
                    $sql = 'SELECT * FROM COMMENT WHERE id=:id ';
                    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                    $stmt->execute();                             // ←SQLを実行する。
                    $results = $stmt->fetchAll(); 
                    foreach ($results as $row){
                        //$rowの中にはテーブルのカラム名が入る
                        //パスワードが一致したら、中身を取り出してフォームに表示する
                        if($row['password'] == $password_edit){
                            //番号と名前とコメントとパスワードの取得
                            $e_num = $row['id'];
                            //$name_exist = $row['name'];
                            $comment_exist = $row['comment'];
                            //$password_exist = $row['password'];
                            //既存の投稿フォームに、取得した値を代入する
                            //formのvalue属性で対応
                        }
                    }
                
                //編集か新規登録か判断し、編集の場合以下を実行    
                }elseif((!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["num_edit"]))){
                    //データを受け取る
                    $name_edit = $_POST["name"];
                    $comment_edit = $_POST["comment"];
                    $num_edit = $_POST["num_edit"];
                    //投稿日時の再設定
                    $date = date("Y/m/d H:i:s"); 
                    //編集番号のidのレコードをDBから取り出し
                    // レコードの編集
                    //bindParamの引数（:nameなど）は4-2でどんな名前のカラムを設定したかで変える必要がある。
                    $id = $num_edit; //変更する投稿番号
                    $name = $name_edit;
                    $comment = $comment_edit; 
                    $sql = 'UPDATE COMMENT SET name=:name,comment=:comment,date=:date WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                    $stmt->execute();
            
                    echo "編集しました";
                }
            ?>
            
        <form action="" method="post">
            
            <!--名前フォーム-->
            <input type="hidden" name="name" placeholder="name"  value="<?php if(isset($L_name)) {echo $L_name;} ?>">
            <!--エピソード番号フォーム-->
            <input type="number" name="epi_number" placeholder="Episode Number"><br><br>
            <!--コメントフォーム-->
            <textarea name="comment" cols="50" rows="5" placeholder="Comment"><?php if(isset($comment_exist)) {echo $comment_exist;} ?></textarea>
            
            <!--編集したい投稿番号のテキストボックス-->
            <input type="hidden" name="num_edit" value="<?php if(isset($e_num)) {echo $e_num;} ?>">
            <!--パスワードフォーム-->
            <input type="hidden" name="password_new" placeholder="password" value="<?php if(isset($L_pass)) {echo $L_pass;} ?>">
            <!--送信ボタン-->
            <input type="submit" name="submit" value="SEND"><br><br>
            <!--削除番号フォーム-->
            <input type="number" name="number_delete" placeholder="Delete Number">
            <!--パスワードフォーム-->
            <input type="hidden" name="password_delete" placeholder="password" value="<?php if(isset($L_pass)) {echo $L_pass;} ?>">
            <!--削除ボタン-->
            <input type="submit" name="delete" value="DELETE"><br> 
            <!--編集番号フォーム-->
            <input type="number" name="number_edit" placeholder="Edit Number">
            <!--パスワードフォーム-->
            <input type="hidden" name="password_edit" placeholder="password" value="<?php if(isset($L_pass)) {echo $L_pass;} ?>">
            <!--編集ボタン-->
            <input type="submit" name="edit" value="EDIT"><br>
        </form>
    </div>
    
    
    
    <!--サインアウトボタンの設定-->
    <button class="fixed_btn" id="SignOut">Sign out</button>
    <!-- SignUpをクリックしたら新規登録フォームに画面遷移させる-->
    <script>
    document.getElementById("SignOut").onclick = function () {
        <?php
        //$_SESSION = array(); ボタンクリックされたらセッション終了がうまくいかない（ボタン押さなくても終了してしまう？）
        ?>
        location.href = 'M_6_Login.php';
    }
    </script>
    
    <!-- SignOutボタンが押されたらセッションの終了＝どうやるの？-->
    <br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.1">#1 バナナ・フィッシュにうってつけの日</h2>
        <?php
            $epi = 1;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.2">#2 異国にて</h2>
        <?php
            $epi = 2;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.3">#3 河を渡って木立の中へ</h2>
        <?php
            $epi = 3;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.4">#4 楽園のこちら側</h2>
        <?php
            $epi = 4;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.5">#5 死より朝へ</h2>
        <?php
            $epi = 5;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.6">#6 マイ・ロスト・シティー</h2>
        <?php
            $epi = 6;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.7">#7 リッチ・ボーイ</h2>
        <?php
            $epi = 7;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.8">#8 陳腐なストーリー</h2>
        <?php
            $epi = 8;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.9">#9 ワルツは私と</h2>
        <?php
            $epi = 9;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.10">#10 バビロンに帰る</h2>
        <?php
            $epi = 10;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.11">#11 美しく呪われし者</h2>
        <?php
            $epi = 11;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.12">#12 持つと持たぬと</h2>
        <?php
            $epi = 12;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.13">#13 キリマンジャロの雪</h2>
        <?php
            $epi = 13;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.14">#14 夜はやさし</h2>
        <?php
            $epi = 14;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.15">#15 エデンの園</h2>
        <?php
            $epi = 15;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.16">#16 悲しみの孔雀</h2>
        <?php
            $epi = 16;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.17">#17 殺し屋</h2>
        <?php
            $epi = 17;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.18">#18 海流の中の島々</h2>
        <?php
            $epi = 18;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    
    <section>
    <h2 id="EP.19">#19 氷の宮殿</h2>
        <?php
            $epi = 19;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

    <section>
    <h2 id="EP.20">#20 征服されざる人々</h2>
        <?php
            $epi = 20;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

    <section>
    <h2 id="EP.21">#21 敗れざる者</h2>
        <?php
            $epi = 21;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

    <section>
    <h2 id="EP.22">#22 死の床に横たわりて</h2>
        <?php
            $epi = 22;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

    <section>
    <h2 id="EP.23">#23 誰がために鐘は鳴る</h2>
        <?php
            $epi = 23;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

    <section>
    <h2 id="EP.24">#24 ライ麦畑でつかまえて</h2>
        <?php
            $epi = 24;
            $sql = 'SELECT * FROM COMMENT WHERE epi=:epi ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':epi', $epi, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'：';
                    echo $row['name'].' 🍌 ';
                    echo $row['comment'].' 🐠 ';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
        ?>
    </section>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>



</body>
</html>