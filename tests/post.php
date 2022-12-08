<?php

    require dirname(__DIR__) . '/tests/config.php';

    if($_POST){

        $firstname = $_POST['firstname'];
        $_csrf = $_POST['_csrf'];

        if($csrf->Check($_csrf)){

            $result = "Token is correct";
            $csrf->Reset();

        }else{

            $result = "Token is not correct";
            $csrf->Reset();

        }

    }

?>
<b>Form Data:</b><br>
<pre>
    <?php @print_r($_POST); ?>
</pre><br>
<b>Session Data:</b><br>
<pre>
    <?php print_r($_SESSION); ?>
</pre><br>
<b>Token Verification Result:</b> <?= $result; ?>