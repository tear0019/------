<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
＜掲示板＞<br><br>
ルール<br>
1.「名前」フォームに名前を記入してください。ニックネームでも大丈夫です<br>
2.「コメント」フォームにコメントを記入してください。内容は何を書いても大丈夫です！<br>
（*人を傷つける内容や個人情報などは書かないでください。）<br>
3.設定したいパスワードを「パスワード」フォームに記入してください。<br>
（*名前、コメントの編集や削除の時に、使用します。）<br>
4.名前、コメント、パスワードを記入し、送信ボタンをクリックでデータを送信！<br>
（*すべて記入していないとデータは送信されません。）<br><br>
＜機能の説明＞<br>
・削除機能<br>
1.「削除対象番号」フォームに削除したい投稿の投稿番号を記入<br>
2.選択した投稿のパスワードを「パスワード」フォームに記入<br>
3.「削除」ボタンをクリックで選択した投稿が削除されます。<br><br>
・編集機能<br>
1.「編集対象番号」フォームに編集したい投稿の投稿番号を記入<br>
2.選択した投稿のパスワードを「パスワード」フォームに記入<br>
3.「編集」ボタンをクリックすると投稿の内容が「名前」と「コメント」フォームに表示されます。<br>
4.「名前」と「コメント」フォーム内で編集を行う。<br>
（*編集のとき、パスワードの入力の必要はありません。<br>パスワードを変更する場合はパスワードフォームに設定したいパスワードを記入してください。）<br>
5.「送信」ボタンをクリックで投稿が編集されます。<br>



<?php
    //データベース接続（4-1）
    $dsn = 'mysql:dbname=データベース名;host=localhost';
    $user = 'ユーザ名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //テーブルの作成（4-2）
    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name CHAR(32),"
    . "comment TEXT,"
    ."password TEXT"
    .");";
    $stmt = $pdo->query($sql);
    
    //投稿内容の編集(4-7)
    if(!empty($_POST["edit"])){
        $id=$_POST["edit"];
        $name=$_POST["name"];
         $comment = $_POST["comment"];
           $sql = 'UPDATE tbtest SET name=:name,comment=:comment WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
    }
    
    //投稿内容を記入(4-5)
    elseif(!empty($_POST["name"])&&!empty($_POST["comment"] &&!empty($_POST["password"]))){
    $name=$_POST["name"];
    $comment=$_POST["comment"];
    $password=$_POST["password"];
    $sql = "INSERT INTO tbtest (name, comment,password) VALUES (:name, :comment,:password)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();
    }
    
    //投稿内容の削除(4-8)
    if(!empty($_POST["delete"]) && !empty($_POST["deletepas"])){
            $id = $_POST["delete"];
            $sql = 'SELECT * FROM tbtest WHERE id=:id ';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
            $stmt->execute();                             
            $results = $stmt->fetchAll(); 
            foreach ($results as $row){
                if($_POST["deletepas"] == $row['password']){
                    $id = $_POST["delete"];
                    $sql = 'delete from tbtest where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
            }}}
?>

<html>
	<body>
	    <br>・投稿フォーム
	    <!--氏名の入力欄-->
	    <br>氏名<br>
		<form action = "" method = "post">
		<input type="text" name="name"
            value=<?php
            //編集の指示があった場合にフォームに指定された番号の名前を表示
            if(!empty($_POST["editnum"]) && !empty($_POST["editpass"])){
                $id = $_POST["editnum"];
                $sql = 'SELECT * FROM tbtest WHERE id=:id ';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
                $stmt->execute();                             
                $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    if($_POST["editpass"] == $row['password']){
                        echo $row["name"];
                    }
                }
            }
            ?>>
            
            <!--コメント入力欄-->
            <br>コメント<br>
            <input type="text" name="comment"
            value="<?php
            //編集の指示があった場合にフォームに指定された番号のコメントを表示
            if(!empty($_POST["editnum"]) && !empty($_POST["editpass"])){
                $id = $_POST["editnum"];
                $sql = 'SELECT * FROM tbtest WHERE id=:id ';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
                $stmt->execute();                             
                $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    if($_POST["editpass"] == $row['password']){
                        echo $row["comment"];
                    }
                }
            }
            ?>">
            
            <!--type=hiddenは隠しデータを設定-->
            <input type="hidden" name="edit" 
            value="<?php 
            if(!empty($_POST["editnum"]) && !empty($_POST["editpass"])){
                echo $_POST["editnum"];
            }
            ?>">
            
        <!--パスワード入力欄-->    
		<br>パスワード<br>
		<input type = "text" name = "password">
		<br>
		<input type = "submit" value = "送信" /><br>
		</form>
		<br>
		
		
		・削除用フォーム<br>
		<form action = "" method = "post">
		<br>削除対象番号<br>
		<input type = "number" name = "delete">
		<br>パスワード<br>
		<input type = "text" name = "deletepas">
		<br>
		<input type = "submit" value = "削除" >
		</form>
		<br>
		・編集用フォーム<br>
		<form action = "" method = "post">
		<br>編集対象番号<br>
		<input type = "number" name = "editnum" >
		<br>パスワード<br>
		<input type = "text" name = "editpass">
		<br>
		<input type = "submit" value = "編集" >
		</form>
		<br>【みんなのコメント】<br>
	</body>
	
	<!--データレコードの表示(4-6)-->
	<?php
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].'<br>';
                echo "<hr>";
            }
        ?>
</html>
