<?php
// 入力チェック(受信確認処理追加)
if(
    !isset($_POST["name"]) || $_POST["name"] =="" ||
    !isset($_POST["email"]) || $_POST["email"] =="" ||
    !isset($_POST["naiyou"]) || $_POST["naiyou"] ==""||
    !isset($_POST["feeling"]) || $_POST["feeling"] ==""
) {
    // 入力漏れ等あると'ParamError'と出る。
    exit('ParamError');
}

//1. POSTデータ取得

//まず前のphpからデーターを受け取る（この受け取ったデータをもとにbindValueと結びつけるため）
$name = $_POST["name"];
$email = $_POST["email"];
$naiyou = $_POST["naiyou"];
$feeling = $_POST["feeling"];
// radioの表示／非表示の設定
$_POST["feeling"]=="good"."bad"?"checked":"";

//2. DB接続します xxxにDB名を入力する
//ここから作成したDBに接続をしてデータを登録します xxxxに作成したデータベース名を書きます
// mamppの方は
// $pdo = new PDO('mysql:dbname=xxx;charset=utf8;host=localhost', 'root', 'root');
try {
    $pdo = new PDO('mysql:dbname=a_db;charset=utf8;host=localhost', 'root', 'root');
} catch (PDOException $e) {
    exit('DbConnectError:'.$e->getMessage());
}


//３．データ登録SQL作成 //ここにカラム名を入力する
//xxx_table(テーブル名)はテーブル名を入力します
$stmt = $pdo->prepare("INSERT INTO a_table(id, name, email, naiyou, feeling,
indate )VALUES(NULL, :name, :email, :naiyou, :feeling, sysdate())");

// バインド変数に変数を入れる（「:変数名」=bind変数）、次に文字列か数値か。
$stmt->bindValue(':name', $name, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':email', $email, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':naiyou', $naiyou, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':feeling', $feeling, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)

$status = $stmt->execute();

//４．データ登録処理後
if ($status==false) {
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit("QueryError:".$error[2]);
} else {
    //５．index.phpへリダイレクト 書くときにLocation: in この:　のあとは半角スペースがいるので注意！！
    header("Location: index.php");
    exit;
}
