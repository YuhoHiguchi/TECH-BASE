<!DOCTYPE html>

<html lang="ja">

<head>

    <meta charset="UTF-8">

    <title>Mission5-1</title>

</head>

<body>
        <?php
        //データベースに接続
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
        //テーブルの作成
        $sql = "CREATE TABLE IF NOT EXISTS tbtest"
        ."("
        ."id INT AUTO_INCREMENT PRIMARY KEY,"
        ."name char(32),"
        ."comment TEXT,"
        ."date TEXT,"
        ."pass TEXT"
        .");";
        $stmt = $pdo -> query($sql);
	
	
	//Post受信
	$name = $_POST["name"];
	$comment = $_POST["comment"];
	$pass = $_POST["pass"];
	$editingID = $_POST["editingid"];
	
	$deleteid = $_POST["deleteid"];
	$deletepass = $_POST["deletepass"];
	
	$editid = $_POST["editid"];
	$editpass = $_POST["editpass"];
	
	//変数定義
	$date = date("Y/m/d H:i:s");
	
	//投稿フォーム
	if($name != "" && $comment != "" && $pass != "")
	{
	    //新規投稿
	    if($editingID == "")
	    {
	        $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
	        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
	        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	        $sql -> execute();
	    }
	    //編集実行
	    else
	    {
	        $sql = 'UPDATE tbtest SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
	        $stmt = $pdo -> prepare($sql);
	        $stmt -> bindParam(':id', $editingID, PDO::PARAM_INT);
	        $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
	        $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
	        $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
	        $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
	        $stmt -> execute();
	    }
	}
	
	//削除フォーム
	if($deleteid != "" && $deletepass != "")
	{
	    //パスワードの抽出・比較
	    $id = $deleteid;
	    $sql = 'SELECT * FROM tbtest WHERE id=:id ';
            $stmt = $pdo -> prepare($sql);                
            $stmt -> bindParam(':id', $id, PDO::PARAM_INT); 
            $stmt -> execute();     
            $results = $stmt -> fetchAll();
            foreach($results as $row)
            {
                if($row['pass'] == $deletepass)
                {
                    //削除実行
                    $sql = 'delete from tbtest where id=:id';
	            $stmt = $pdo -> prepare($sql);
	            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	            $stmt -> execute();
                }
            }
	}
	
	//編集フォーム
	if($editid != "" && $editpass != "")
	{
	    //パスワードの抽出・比較
	    $id = $editid;
	    $sql = 'SELECT * FROM tbtest WHERE id=:id ';
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
            $stmt -> execute();
            $results = $stmt -> fetchAll();
            foreach($results as $row)
            {
                if($row['pass'] == $editpass)
                {
                    //編集する名前・コメントの取得
                    $editname = $row['name'];
                    $editcomment = $row['comment'];
                    //編集中番号の定義
                    $editingid = $editid;
                }
            }
	}
	
	//掲示板の表示（全部）
	$sql = 'SELECT * FROM tbtest';
	$stmt = $pdo -> query($sql);
	$results = $stmt -> fetchAll();
	foreach ($results as $row)
	{
		echo $row['id'].' ';
		echo '名前：'.$row['name'].' ';
		echo '投稿日時：'.$row['date'].'<br>';
		echo $row['comment'].'<br>';
	}
	echo "<hr>";
	
        ?>
    
        <form action="" method="post">
        【投稿フォーム】<br>
        名前：　　　<input type="text" name="name" value="<?php echo $editname; ?>" placeholder=""> <br>
        コメント：　<input type="text" name="comment" value="<?php echo $editcomment; ?>" placeholder=""> <br>
        パスワード：<input type="text" name="pass" placeholder="">　<input type="submit" name="submit"> <br>
        <input type="hidden" name="editingid" value="<?php echo $editingid; ?>" placeholder="編集中番号">
        </form>
        <br>
        <form action="" method="post">
        【削除フォーム】<br>
        削除番号：　<input type="number" name="deleteid" placeholder=""> <br>
        パスワード：<input type="text" name="deletepass" placeholder="">　<input type="submit" value="削除"> <br>
        </form>
        <br>
        <form action="" method="post">
        【編集フォーム】<br>
        編集番号：　<input type="number" name="editid" placeholder=""> <br>
        バスワード：<input type="text" name="editpass" placeholder="">　<input type="submit" value="編集"> <br>
        </form>

</body>
	
</html>
