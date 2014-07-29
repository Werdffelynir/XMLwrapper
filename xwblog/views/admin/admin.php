<?php

/** AUTH */

if( isset($_POST['login']) && isset($_POST['password']) && $_POST['login'] == 'admin' && $_POST['password'] == 'admin' ){
    setcookie('auth','admin');
    header('Location: admin.php');
}

$login = false;
if(!isset($_COOKIE['auth'])){
    $login = true;
}


/** COMMON */

include_once '../source/XMLWrapper.php';
$xml = new XMLWrapper(__DIR__.'/database/');



/** MENU */
$menu = null;
$xml->select('blog')->items();
$menuItems = $xml->result();
foreach($menuItems as $mi)
    $menu .= '<li><a href="admin.php?edit='.$mi['attr']['id'].'">[<i>'.$mi['attr']['group'].'</i>] '.$mi['title'].' </a></li>'."\n";




$id = null;
$title = null;
$content = null;
$titleForm = 'Create new item';
$type = 'insert';



/** FORM DELETE ITEM */

if(!empty($_POST['remove']))
{
    $xml->delete('blog');
    $xml->deleteItem($_POST['id']);
    $xml->save();

    header('Location: admin.php');
}



/** FORM SELECT ITEM */

if(!empty($_GET['edit']))
{
    $titleForm = 'Update item';

    $xml->items($_GET['edit']);
    $items = $xml->result();

    $type = 'update';
    $id = $items[0]['attr']['id'];
    $title = $items[0]['title'];
    $content = $items[0]['content'];
}




/** FORM UPDATE ITEM */
if(!empty($_POST['type']) && $_POST['type']=='update')
{
    $xml->update('blog', $_POST['id']);
    $xml->updateItem('title',$_POST['title']);
    $xml->updateItem('content',$_POST['content']);
    $xml->save();

    header('Location: admin.php?edit='.$_POST['id']);
}




/** FORM INSERT ITEM */
if(!empty($_POST['type']) && $_POST['type']=='insert')
{
    $xml->insert('blog');

    $xml->insertItem(
        array(
            'title'=>$_POST['title'],
            'content'=>$_POST['content'],
        ));
    $xml->insertAttr('group','blog');
    $xml->insertAttr('date',time());
    $xml->save();

    header('Location: admin.php');
}


?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AdminPanel</title>

    <link rel="stylesheet" href="../../css/grid.css"/>
    <link rel="stylesheet" href="../../css/style-admin.css"/>

    <script src="../../js/nicEdit.js" ></script>

    <script type="text/javascript">
        bkLib.onDomLoaded(function() {
            var editor = new nicEditor({
                //fullPanel : true,
                iconsPath: 'js/nicEditorIcons.gif',
                buttonList : ['bold','italic','underline','left','center','right','justify','ol','ul','removeformat',
                    'indent','outdent','hr','image','upload','forecolor','bgcolor','link','unlink','fontSize','fontFamily',
                    'fontFormat','xhtml'],

            }).panelInstance('editor');
        });
    </script>

</head>
<body>

<?php if($login):?>
    <div class="box-login">
        <form action="" method="post">
            <label>Login <br/><input name="login" type="text" value=""/></label>
            <label>Password <br/><input name="password" type="text" value=""/></label>
            <input type="submit" value="Авторизация" />
        </form>
    </div>
<?php else: ?>

<div class="page">



    <div class="panel-top full clear">
        <a href="admin.php">Main page</a>
        <a href="../../index.php">Go to Blog</a>
        <span class="logo"> DEMO XMLWrapper </span>
    </div>

    <div class="full clear">

        <div class="left grid-3 first list-article">
            <ul>
                <li><a href="admin.php?create=new">Create New Article</a></li>
                <?php echo $menu ?>
            </ul>
        </div>

        <div class="right grid-9">
            <h3><?=$titleForm?></h3><br/>
            <form action="" method="post">
                <div> Page title <br/>
                    <input name="title" type="text" value="<?=$title?>"/></div>

                <div> Page content <br/>
                    <textarea name="content" id="editor" ><?=$content?></textarea></div>
                <br/>

                <input name="type" type="text" value="<?=$type?>" hidden="hidden"/>
                <input name="id" type="text" value="<?=$id?>" hidden="hidden"/>
                <input type="submit" value="Save" />&NonBreakingSpace; &NonBreakingSpace;
                <input type="submit" value="Remove" name="remove" />
            </form>
        </div>

    </div>



</div>

<?php endif; ?>

</body>
</html>