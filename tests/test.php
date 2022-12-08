<?php require dirname(__DIR__) . '/tests/config.php'; ?>
<form method="POST" action="post.php">
    <b>Firstname:</b><br>
    <input type="text" name="firstname"><br>
    <b>_CSRF Token:</b><br>
    <input type="text" name="_csrf" value="<?= $csrf->Get(); ?>"><br>
    <button type="submit">Submit</button>
</form>
