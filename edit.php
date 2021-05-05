<?php
require_once __DIR__ . '/functions.php';

$id = filter_input(INPUT_GET, 'id');

$plan = findById($id);

$title = '';
$due_date = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = filter_input(INPUT_POST, 'title');
    $due_date =filter_input(INPUT_POST, 'due_date');

    $errors = updateValidate($title, $due_date, $plan);

    if (empty($errors)) {
        updatePlan($id, $title, $due_date);
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<?php include_once __DIR__ . '/_head.html' ?>

<body>
    <h1 class="title">学習管理アプリ</h1>
    <div class="edit-area">
        <?php if ($errors) echo (createErrMsg($errors)) ?>
        <h2 class="sub-title">編集</h2>
        <form action="" method="post">
            <label for="title">学習内容</label>
            <input type="text" name="title" value="<?= h($plan['title']) ?>">
            <label for="due_date">期限日</label>
            <input type="date" name="due_date" value="<?= h($plan['due_date']) ?>">
            <input type="submit" value="更新" class="btn submit-btn">
        </form>
        <a href="index.php" class="btn return-btn">戻る</a>
    </div>
</body>

</html>