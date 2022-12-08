<?php

    session_start();
    require dirname(__DIR__) . '/src/csrf.php';

    // Konfigüre ediyoruz
    $csrf = new Csrf([
        'key' => 'SuperKey',
        'secret' => 'SuperSecret'
    ]);

?>