<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$title?></title>
    <link rel="stylesheet" href="../../css/grid.css"/>
    <link rel="stylesheet" href="../../css/style-page.css"/>
</head>
<body>

<div class="page">

    <div class="header full clear">
        <div class="menu full clear">
            <a href="../../index.php">Main page</a>
            <a href="../../index.php?r=blog">Blog articles</a>
            <a href="../../index.php?r=auth">Login</a>
            <span class="logo"> DEMO XMLWrapper </span>
        </div>
    </div>

    <div class="content full clear">
        <div class="grid-8 first">
            <?=$content?> &nbsp;
        </div>

        <div class="grid-4">
            <?=$right?>
            <div class="block">

            </div>
        </div>
    </div>

    <div class="footer full clear">
        DEMO XMLWrapper © 2014 г.  Все права защищены. Время генерации <?php echo round(microtime(true)-TIMESTART,4).'сек.';?>
    </div>

</div>

</body>
</html>