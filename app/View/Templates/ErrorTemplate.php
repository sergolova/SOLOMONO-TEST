<?php
/** @var int|string $code */
/** @var string $message */
include 'HeaderTemplate.php';
?>
    <div class='notfound-container'>
        <div><span><?= $code ?? '404' ?></span> - <span><?= $message ?? 'Сторінка не знайдена :(' ?></span></div>
    </div>
<?php
include 'FooterTemplate.php';
?>