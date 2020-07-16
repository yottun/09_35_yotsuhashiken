<?php
session_start();
include('functions.php');
check_session_id();
// DB接続の設定
// include('functions.php');
$pdo = connect_to_db();


// データ取得SQL作成
$sql = 'SELECT * FROM kadai_recipe_table';

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

// データ登録処理後
$view = '';
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  exit('sqlError:' . $error[2]);
  // echo json_encode(["error_msg" => "{$error[2]}"]);
} else {
  // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
  // fetchAll()関数でSQLで取得したレコードを配列で取得できる
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  // データの出力用変数（初期値は空文字）を設定
  $output = "";
  // <tr><td>deadline</td><td>todo</td><tr>の形になるようにforeachで順番に$outputへデータを追加
  // `.=`は後ろに文字列を追加する，の意味
  foreach ($result as $record) {
    $output .= "<tr>";
    $output .= "<td>{$record["recipename"]}</td>";
    $output .= "<td>{$record["category"]}</td>";
    $output .= "<td>{$record["howto"]}</td>";
    $output .= "<td>{$record["recipe_image"]}</td>";

    // edit deleteリンクを追加
    $output .= "<td><a href='recipe_edit.php?id={$record["id"]} '>edit</a></td>";
    $output .= "<td><a href='recipe_delete.php?id={$record["id"]} '>delete</a></td>";

    $output .= "</tr>";
  }
  // $valueの参照を解除する．解除しないと，再度foreachした場合に最初からループしない
  // 今回は以降foreachしないので影響なし
  unset($record);
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DB連携型todoリスト（一覧画面）</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <!-- ヘッダー -->
  <header class="PC_header">
    <div class="logo"><img src="img/okomekun.png" alt="" width="100px" height=100px></div>
    <div class="headertitle">管理栄養士のレシピ</div>
    <ul>
      <li><a href="index.php">トップページ</a></li>
      <li><a href="page/todo.php">レシピ一覧</a></li>
      <li><a href="page/about.html">レシピ検索</a></li>
      <li><a href="page/company.html">レシピ詳細</a></li>
    </ul>
  </header>

  <fieldset>
    <legend>DB連携型todoリスト（一覧画面）</legend>
    <a href="index.php">入力画面</a>
    <table>
      <thead>
        <tr>
          <th>recipename</th>
          <th>category</th>
          <th>howto</th>
          <th>recipe_image</th>
        </tr>
      </thead>
      <tbody>
        <?= $output ?>
      </tbody>
    </table>
  </fieldset>
</body>

</html>