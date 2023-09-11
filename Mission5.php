<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission5</title>
</head>
<body>
    <?php
    // 変数宣言or初期化
    $name = "";
    $comment = "";
    $deletenumber = "";
    $currentpassword = 76;
    ?>

    <p>・編集→名前,コメント,編集対象番号の3点を記入<br>
    ・削除、編集を行う場合はパスワード(76)も入力して下さい</p>

    <form action="" method="post">
        <input type="text" name="name" placeholder="名前"><br>
        <input type="text" name="comment" placeholder="コメント">
        <input type="submit" name="submit"><br><br>
        <input type="text" name="deletenumber" placeholder="削除対象番号">
        <input type="submit" name="submit" value="削除"><br>
        <input type="password" name="Dpassword" placeholder="削除パスワード"><br><br>
        <input type="text" name="editnumber" placeholder="編集対象番号">
        <input type="submit" name="submit" value="編集"><br>
        <input type="password" name="Epassword" placeholder="編集パスワード"><br><br>
    </form>

    <?php
    // SQL接続設定
    $dsn = 'mysql:dbname=データベース名;host=localhost';
    $user = 'ユーザ名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    . " ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name TEXT(12),"
    . "comment TEXT"
    . ");";
    $stmt = $pdo->query($sql);

    // 投稿ボタン
    if (!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["edit"])) {
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $sql = "INSERT INTO tbtest (name, comment) VALUES (:name, :comment)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->execute();
    }

    // 削除ボタン
    if (!empty($_POST["deletenumber"]) && !empty($_POST["Dpassword"])) {
        $Dpassword = $_POST["Dpassword"];
        if ($Dpassword == $currentpassword) {
            $id = $_POST["deletenumber"];
            $sql = 'DELETE FROM tbtest WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    // 編集ボタン
    if (!empty($_POST["name"]) && !empty($_POST["comment"])&&!empty($_POST["editnumber"]) && !empty($_POST["Epassword"])) {
        $Epassword = $_POST["Epassword"];
        if ($Epassword == $currentpassword) {
            $id = $_POST["editnumber"]; // 変更する投稿番号
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $sql = 'UPDATE tbtest SET name=:name,comment=:comment WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    // データ表示
    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row) {
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'] . ',';
        echo $row['name'] . ',';
        echo $row['comment'] . '<br>';
        echo "<hr>";
    }
    
    ?>
</body>
</html>