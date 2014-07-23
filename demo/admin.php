<?php
$form = '
    <div class="box-login">
        <form action="" method="post">
            <label>Login <br/><input type="text" value=""/></label>
            <label>Password <br/><input type="text" value=""/></label>
        </form>
    </div>';




?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AdminPanel</title>
    <link rel="stylesheet" href="css/grid.css"/>
    <link rel="stylesheet" href="css/style-admin.css"/>
</head>
<body>

<div class="page">

    <div class="panel-top full clear">
        <a href="#">Main page</a>
        <a href="#">Blog articles</a>
        <a href="#">Users</a>
        <span class="logo"> DEMO XMLWrapper </span>
    </div>
    <div class="full clear">
        <div class="left grid-3 first list-article">
            <ul>
                <li><a href="#">Create New Article</a></li>
                <li><a href="#">link</a></li>
                <li><a href="#">link</a></li>
                <li><a href="#">link</a></li>
                <li><a href="#">link</a></li>
                <li><a href="#">link</a></li>
                <li><a href="#">link</a></li>
            </ul>
        </div>

        <div class="right grid-9">

                <form action="" method="post">
                    <p>Page title <br/>
                        <input name="title" type="text" value=""/></p>
                    <p>Page url <br/>
                        <input name="url" type="text" value=""/></p>

                    <p>Page group <br/>
                        <select name="group" id="">
                            <option value="php">php</option>
                            <option value="xml">xml</option>
                            <option value="css">css</option>
                            <option value="css">main page</option>
                        </select></p>

                    <p>Page order <br/>
                        <select name="order" id="">
                            <option value="4" selected>4</option>
                            <?php for($i=1; $i<10;$i++): if($i==4) continue; ?>
                                <option value="<?=$i?>"><?=$i?></option>
                            <?php endfor;?>
                        </select></p>

                    <p>Page content <br/>
                        <textarea name="content" id=""></textarea></p>
                    <br/>
                    <input type="submit" value="Save" />&NonBreakingSpace; &NonBreakingSpace;
                    <input type="submit" value="Remove" />
                </form>
        </div>

    </div>


</div>

</body>
</html>