<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$TITLE?></title>
    <link rel="stylesheet" href="<?=Request::url()?>/public/css/grid.css"/>
    <link rel="stylesheet" href="<?=Request::url()?>/public/css/style-page.css"/>
</head>
<body>

<div class="page">

    <div class="header full clear">
        <div class="menu full clear">
            <a href="<?=Request::url()?>/index/index">Main page</a>
            <a href="<?=Request::url()?>/index/blog">Blog articles</a>
            <a href="<?=Request::url()?>/index/login">Login</a>
            <span class="logo"> DEMO XMLWrapper </span>
        </div>
    </div>

    <div class="content full clear">
        <div class="grid-8 first">
            <?=$CONTENT?> &nbsp;
        </div>

        <div class="grid-4">
            <div class="block">
                <?=$RIGHT?>
            </div>
        </div>
    </div>

    <div class="footer full clear">
        DEMO XMLWrapper © 2014 г.  Все права защищены. Время генерации <?php echo round(microtime(true)-TIMESTART,4).'сек.';?>
    </div>

</div>

</body>
</html>