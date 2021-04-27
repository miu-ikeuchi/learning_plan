<!DOCTYPE html>
<html lang="ja">

<?php include_once __DIR__ . '/_head.html' ?>

<body>
    <h1 class="title">学習管理アプリ</h1>
    <div class="edit-area">
        <h2 class="sub-title">編集</h2>
        <form action="" method="post">
            <label for="title">学習内容</label>
            <input type="text" name="title">
            <label for="due_date">期限日</label>
            <input type="date" name="due_date">
            <input type="submit" value="更新" class="btn submit-btn">
        </form>
        <a href="index.php" class="btn return-btn">戻る</a>
    </div>
</body>

</html>