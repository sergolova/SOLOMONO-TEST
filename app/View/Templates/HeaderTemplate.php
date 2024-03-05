<?php
/** @var string $title */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel='icon' href='/View/Images/favicon.ico' type='image/x-icon'>
<!--    <link rel='preload' href='/View/Images/bg.webp' as="image">-->
<!--    <link rel='prerender' href='/View/Images/bg.webp'>-->
    <title><?= $title ?? 'TestStore' ?></title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'
          integrity='sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH' crossorigin='anonymous'>
    <script src='/View/Scripts/main.js' defer></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'
            integrity='sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz'
            crossorigin='anonymous'></script>


    <?php if (isset($styles)) : ?>
        <?php foreach ($styles as $style): ?>
            <link rel='stylesheet' href='<?= STYLES_URL . '/' . $style . '.css' ?>'>
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
<header>
    <div class='logo'>
        <a href='/'>
            <img src='/View/Images/logo-dan.webp' alt='Logo' class='logo-image'>
            TEST STORE
        </a>
    </div>
</header>
<main>