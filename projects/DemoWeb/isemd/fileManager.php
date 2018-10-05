<?php session_start();
header('Content-Type: text/html; charset=utf-8');
?> 
<!DOCTYPE html>
<html>
<head>
    <title>Файловый менеджер</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <style type="text/css">
       a { 
        text-decoration: none;
        color:#00e;
        margin:0px;
        padding: 0px;
       }
       a:hover { 
        text-decoration: underline;
       }
       .table td,
       .table th {
        padding: 0px;
       }
    </style>
</head>
<body>
<?php
// присваивание массива
if (empty($_GET)) {
    $Inq[f] = $_POST[f];
}
else {            
    $Inq[f] = $_GET[f];
}
//  $_GET[f]: $_POST[f]: uplan, rplan, group

if (isset($_SESSION[login]) && isset($_SESSION[password]) && ($Inq[f]=='group' || $Inq[f]=='rplan' || $Inq[f]=='uplan' || $Inq[f]=='docs' || $Inq[f]=='user') ) {

// вывод заголовка
switch ($Inq[f]) {
    case 'group': $tf="Группы"; break;
    case 'rplan': $tf="Рабочий учебный план"; break;
    case 'uplan': $tf="Учебные планы"; break;
    case 'docs' : $tf="Справочники"; break;
    case 'user' : $tf="Файлы пользователя"; break;
    default: $tf="User"; break;
}
?>
<div class="panel">
<table id="dataT" class="table">
    <thead>
    <tr>
        <th colspan=2>
        <div style="width:90%; text-align:left;">
            <span><b>Файловый менеджер: </b> <i style="font-weight: normal;"><?echo($tf);?></i></span>
        </div>
        </th>
    </tr>
    </thead>
    <tr>
    <th colspan=2 style="text-align: left;">
        <button type="button" class="button" onclick="location.href='filemanager.php?f=docs'" >Справочники</button>
        <button type="button" class="button" onclick="location.href='filemanager.php?f=uplan'">Учебные планы</button>
        <button type="button" class="button" onclick="location.href='filemanager.php?f=rplan'">Рабочий учебный план</button>
        <button type="button" class="button" onclick="location.href='filemanager.php?f=group'">Группы</button>
        <button type="button" class="button" onclick="location.href='filemanager.php?f=user'" >Файлы пользователя</button> 
    </th>
    </tr>
    <tr>
    <th colspan=2 style="text-align: left;">
        <form enctype="multipart/form-data" action="filemanager.php" method="post" accept-charset="utf-8">
            <input type="hidden" name="f" value=<? echo('"'.$Inq[f].'"'); ?> >
            <b>Загрузить файл: </b><input name="userfile" type="file"><input type="submit">
        </form>            
    </th>
    </tr>

<?

// директории
$dir=dirname(__FILE__)."/res/".$Inq[f]."/";
$diro=dirname($_SERVER['PHP_SELF'])."/res/".$Inq[f]."/";

// загрузка файла
if (!empty($_POST)) {
    if ( move_uploaded_file($_FILES[userfile][tmp_name], 
        $dir.( ($fl_=iconv('UTF-8','cp1251',$_FILES[userfile][name]))? $fl_ : $_FILES[userfile][name] )
       ) && $_FILES[userfile][error]==0 && !strpos($_FILES[userfile][name],".htaccess")) {/* всё норм */};
}

// удаление файла (опасно)
if (!$val=iconv('UTF-8','cp1251',$_GET[del])) $val=$_GET[del];
if (!empty($_GET[del])) {
    if (file_exists($dir.$val) && !strpos($val,".htaccess"))
        unlink($dir.$val);
    /*echo "<b>файл удален:</b> ".$dir.$val;*/
    echo '<script language="JavaScript">window.location.href=\'filemanager.php?f='.$Inq[f].'\';</script>';
}

// список файлов
$files = scandir($dir);
foreach ($files as $value) {
    echo '<tr>';
    if ($value !='.' && $value !='..'  && !strpos($val,".htaccess") && !is_dir($dir."/".$value)) {
        if (!$val=iconv('cp1251','UTF-8',$value)) $val=$value;
        echo '<td style="min-width:400px; text-align:left;"><img src="img/unknown.gif" style="vertical-align: middle;margin: 0 5px 0 5px;">
            <a href="'.$diro.$val.'" target="_blank" download>'.$val.'</a>
            </td><td>';
        if ($Inq[f]!="docs" || ( $val!="IS.pdf" && $val!="OP.pdf" && $val!="qual.html" && $val!="tel.html") )
        echo '<a style="color:red;" href="filemanager.php?f='.$Inq[f].'&del='.$val.'" onclick=\'return confirm("Удалить файл `'.$val.'` ?")\'>Удалить</a>';
        echo '</td>';
    }
    echo '</tr>';
}


?>
</table>
</div>

<? } ?>
</body>
</html>
