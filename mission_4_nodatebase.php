<?php
header('Content-Type: text/html; charset=UTF-8');
?>

<form method="post" action="mission_4.php">
<p>
<input type="text" name="namae" placeholder="名前"></br>
<input type="text" name="komennto" placeholder="コメント"></br>
<input type="text" name="pass1" placeholder="パスワード">
<input type="submit" name="submit"></br></br>


<?php
//sqlデータベースに接続
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);


//変数いろいろ宣言
$name=$_POST['namae'];
$comment=$_POST['komennto'];
$delete=$_POST['delete'];
$pass1=$_POST['pass1'];
$pass2=$_POST['pass2'];
$pass3=$_POST['pass3'];
$postedat=date("Y年m月d日H時i分s秒");


if(!strlen($comment and $name and $pass1)){
    echo"空欄があります。</br></br>";
}
else{
	//基本情報の入力
	$sql=$pdo->prepare("INSERT INTO keijitb(name,comment) VALUES(:name,:comment)");
	$sql->bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> execute();
	//パスワードの入力
	$sql=$pdo->prepare("INSERT INTO passtb(pass) VALUES(:pass)");
	$sql->bindParam(':pass', $pass1, PDO::PARAM_STR);
	$sql -> execute();
}
?>


<input type="text" name="delete" placeholder="削除対象番号"></br>
<input type="text" name="pass2" placeholder="パスワード">
<input type="submit" name="sakujyo"></br></br>


<?php
if (isset($_POST["delete"])) {
	//ぱすわーどをテーブルから取得
	$passkakunin=$pdo->prepare("SELECT pass FROM passtb where id=$delete");
	$passkakunin->execute();
	$result=$passkakunin->fetch();
	/*パスワードをデータ取得できているか確認用
	echo $result['pass'];
	*/
	
	//パスワードが正しい時
	if($pass2==$result['pass']){
		$sakukome="削除されました";
		$sql="update keijitb set name='$sakukome' , comment='$sakukome' where id = $delete";
		$result = $pdo->query($sql);
	}else{
		echo "パスワードが違います。</br></br>";
	}	
}
?>


<input type="text" name="hennsyuu" placeholder="編集対象番号"></br>
<input type="text" name="pass3" placeholder="パスワード">
<input type="submit" name="uwagaki" value"編集"></br></br>


<?php
//編集機能
//出力
if (isset($_POST["uwagaki"])){
	//ぱすわーどをテーブルから取得
	$hennsyuu=$_POST['hennsyuu'];
	$passkakunin=$pdo->prepare("SELECT pass FROM passtb where id=$hennsyuu");
	$passkakunin->execute();
	$result=$passkakunin->fetch();
	
	//パスワードが正しい時
	if($pass3==$result['pass']){
		//テーブルから初期の投稿を取得
		$henkakunin=$pdo->prepare("SELECT * FROM keijitb where id=$hennsyuu");
		$henkakunin->execute();
		$result=$henkakunin->fetch();
		
		//取得した初期投稿を元に編集用input作成
		echo$hennsyuu."の書き込みを編集できます。</font></br>";
		echo "<form method=POST action=mission_4.php>";
		echo"名前<input type='text' name='name2' value='".$result['name']."'></br>";
		echo"コメント<input type='text' name='comment2' value='".$result['comment']."'></br>";
		echo"編集番号<input type='text' name='banngou' value='".$hennsyuu."'></br>";
		echo"<input type='submit' name='hennkaku' value='上書き保存'></br>";
		echo "</form>";
		
		}else{
	echo "パスワードが違います。</br></br>";
}}

//書き換え
if (isset($_POST["hennkaku"])){
	$name2 = $_POST['name2'];
	$comment2 = $_POST['comment2'];
	$banngou = $_POST['banngou'];
	$sql="update keijitb set name='$name2' , comment='$comment2' where id = $banngou";
	$result = $pdo->query($sql);
	echo"書き換え完了！</br></br>";
	}else{
		echo "書き換え失敗</br></br>";
	}
?>


<?php
//入力したデータを確認する(基礎データのみ)
$sql = 'SELECT * FROM keijitb order by id';
$results = $pdo -> query($sql);
foreach ($results as $row){
 //$rowの中にはテーブルのカラム名が入る
 echo $row['id'].',';
 echo $row['name'].',';
 echo $row['comment'].'<br>';
 }
?>