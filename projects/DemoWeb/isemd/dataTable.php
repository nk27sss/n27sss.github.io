<?php session_start();
header('Content-Type: text/html; charset=utf-8');
$isfileinput = true; // отображение загрузки файла / или текстбокса
?> 

<!DOCTYPE html>
<html>

<head>
    <title>Учебные планы</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <!--<script src="../js/jquery-3.0.0.min.js"></script>-->
</head>

<body style="overflow: auto;">
<?php
if (isset($_GET) || isset($_POST)){
    // присваивание массива
    if (empty($_GET)) {
        $Inq[f] = $_POST[f];
        $Inq[s] = $_POST[s];
        $Inq[data] = $_POST[data];
    }
    else {            
        $Inq[f] = $_GET[f];
        $Inq[s] = $_GET[s];
    }

// Описания
//  $_GET[f]: $_POST[f]: uplan, rplan, group
//  $_GET[s]: $_POST[s]: 5B070300, 5B090100
//
//  $_GET[typ]:  1-режим редактирования + 
//  $_POST[typ]: 2-отправка и сохранение данных + сами данные
//
// удаление строк `X` через js
// добавление строк `Добавить строку` через js

// пароль и логин
   
    // дополнительная проверка
    if (isset($_SESSION[login]) && isset($_SESSION[password])) {
        $_ses[login]  = $_SESSION[login];
        $_ses[password] = $_SESSION[password];
        /*
        if ($link=mysql_connect("localhost", "root", "")) {
            if(mysql_select_db("isemd", $link)) {
                $SQL = "SELECT * FROM `users` WHERE `login`='".$_ses[login]."' AND `password`='".$_ses[password]."'";
                if (!($result = mysql_query($SQL, $link)) || mysql_num_rows($result) == 0) {
                    unset($_ses[login]);    unset($_ses[password]);
                } else {
                    if ($_ses[login] != $_SESSION[login] && $_ses[password] != $_SESSION[password]) {
                        unset($_ses[login]);    unset($_ses[password]);
                    }
                    //else: всё норм, продолж без сброса _ses
                }
            } else {
                unset($_ses[login]);    unset($_ses[password]);
            }
            mysql_close($link);
        } else {
            unset($_ses[login]);    unset($_ses[password]);
        } */
    }
    

// Сохранение данных и файлов

if (isset($_ses[login]) && isset($_ses[password]) && $_POST[typ]==2 && isset($_POST[data])) {
    // запрос данных json
    $path = "res\\titles.json";
    $tit  = json_decode(file_get_contents($path),true);

    $path = "res\\".$Inq[f]."-".$Inq[s].".json";
    file_exists($path) or die("Error file!");
    $data = json_decode(file_get_contents($path),true);

    $cols=count($tit[$Inq[f]]);
    
    // список имен файлов
    foreach ($data[data] as $r => $row){
        $flist[$r] = $row[$cols-2];
    }

    $dir=dirname(__FILE__)."/res/".$Inq[f]."/";
    $diro=dirname($_SERVER[PHP_SELF])."/res/".$Inq[f]."/";
    
    if (!empty($_FILES[data])) {
        for ($i=0; $i<count($_FILES[data][name]); $i++) {
            if (move_uploaded_file($_FILES[data][tmp_name][$i], 
                $dir.( ($fl_=iconv('UTF-8','cp1251',$_FILES[data][name][$i])) ? $fl_ : $_FILES[data][name][$i] )
                ) && $_FILES[data][error][$i]==0 && !strpos($_FILES[userfile][name][$i],".htaccess")) {
                $flist[$i] = $_FILES[data][name][$i];
            }
        }
    }
    
    $listnamefiles=array();
    // обработчик
    foreach ($_POST[data] as $r => $row) {
        for ($i=0; $i < (($isfileinput)?($cols-2):($cols-1)); $i++) { 
            $fdata[data][$r][$i] = $row[$i];
        }
        if ($isfileinput) $fdata[data][$r][$cols-2] = $flist[$r];
        if ($flist[$r]!="") $listnamefiles[]=$flist[$r];
    }
    
    // декодирование и сохранение
    $json_save = json_encode($fdata);
    if ($json_save!=null) {
        $json_save = preg_replace("/\\\\u([a-f0-9]{4})/e","iconv('UCS-4LE','UTF-8',pack('V',hexdec('U$1')))",$json_save);

        $path = "res\\".$Inq[f]."-".$Inq[s].".json";

        echo '<div style="font-size:10pt;line-height:100%;width:90%; display:block;padding:5px;color:#888;"><b>File "'.$path.'":</b><br>'; print_r($json_save); echo "</div>";
        
        $fp = fopen($path, 'w');
        fwrite($fp, $json_save);
        fclose($fp);
        
        // удаление несвязанных с таблицей файлов
        if (true) {
            $files = scandir($dir);
            foreach ($files as $value) {
                if ($value !='.' and $value !='..' and !is_dir($dir."/".$value)) {
                    
                    $val=iconv('cp1251','UTF-8',$value);
                    //удаление если нет в $Fdata
                    if (!in_array($val,$listnamefiles)) {
                        if (file_exists($dir.$value)) unlink($dir.$value);
                    }
                }
            }
        }
        echo '<br><h2>Сохранение завершено</h2>';

        // перезагрузка
        echo '<script language="JavaScript">window.location.href=\'dataTable.php?f='.$Inq[f].'&s='.$Inq[s].'\';</script>';
    }
        
} else {

// запрос данных json
    $path = "res\\titles.json";
    $tit  = json_decode(file_get_contents($path),true);

    $path = "res\\".$Inq[f]."-".$Inq[s].".json";
    file_exists($path) or die("Error file!");
    $data = json_decode(file_get_contents($path),true);

    $cols=count($tit[$Inq[f]]);


// таблица

    echo'<div class="panel">';
    if ($_GET[typ]==1)     echo'<form name="form" enctype="multipart/form-data"  action="" method="post">';
    echo' <table id="dataT" class="table" >';
    echo "<thead><tr><th colspan=".($cols+1).">";
    // вывод заголовка
    switch ($Inq[f]) {
        case 'group': $tf="Группы"; break;
        case 'rplan': $tf="Рабочий учебный план"; break;
        case 'uplan': $tf="Учебные планы"; break;
        default: $tf="User"; break;
    }
    echo '<div style="width:90%; text-align:left; "><span><b>'.$tf.': </b> ';
    switch ($Inq[s]) {
        case '5B070300': $tf="Информационные системы 5B070300"; break;
        case '5B090100': $tf="Организация перевозок  5B090100"; break;
        default: $tf="n/a"; break;
    }
    echo '<i style="font-weight: normal;">'.$tf.'</i></span></div>';
    echo "</th></tr></thead>";
    if (!$_GET[typ]==1) { 

    //режим просмотра
    

        if (isset($_ses[login]) && isset($_ses[password])) { //при авторизации появление кнопки `Редактировать`
            echo '<tr> <th colspan='.($cols+1).'>
            <button class="button" style="float:left;" onclick="location.href=\'dataTable.php?f='.$Inq[f].'&s='.$Inq[s].'&typ=1\'" ><b>Редактировать</b></button> </tr>';
        }
        echo '<tr>';
        foreach ($tit[$Inq[f]] as $obj) {
            echo '<th>'.$obj.'</th>';
        }
        echo '</tr>';

        foreach ($data[data] as $k=>$row) {
            echo '<tr>';
            echo '<td>'.$k.'</td>';
            for ($i=0; $i < $cols-1; $i++) { 
                if (!($i==$cols-2)) {
                    echo '<td>'.$row[$i].'</td>';
                }
                else {
                    echo '<td class="tlin"><a href="res/'.$Inq[f].'/'.$row[$i].'" download>'.$row[$i].'</a></td>';
                }                
            }
            echo '</tr>';
        }
        echo '</table>';
    }
    else if (isset($_ses[login]) && isset($_ses[password])) {
    
    // режим редактирования

        echo '<tr><th colspan='.($cols+2).'>
            <input type="hidden" name="f" value="'.$Inq[f].'" />
            <input type="hidden" name="s" value="'.$Inq[s].'" />
            <input type="hidden" name="typ" value="2" />

            <button type="button" class="button" style="float:left;" onclick="location.href=\'dataTable.php?f='.$Inq[f].'&s='.$Inq[s].'\'" ><b>Отменить</b></button>
            <button type="submit" class="button" style="float:right;"><b>Сохранить</b></button>     
            </tr>';

        echo '<tr>';
        foreach ($tit[$Inq[f]] as $obj) {
            echo '<th>'.$obj.'</th>';
        }
        echo '<th width=30>
        <a onclick="insRow()" class="btn-img"><img src="img/add.png" alt="Добавить строку" title="Добавить строку"></a>
        </th></tr>';      
        
        foreach ($data[data] as $k=>$row) {
            echo '<tr> <td>'.$k.'</td>';
            for ($i=0; $i < $cols-1; $i++) { 
               
                if (($i==$cols-2) && $isfileinput) {
                    echo'<td><input type="file" style="width:100%" name="data['.$k.']" title="'.$row[$i].'"><br><label style="text-align:left; font-size:8px; position:absolute; margin-left:-50px;">'.$row[$i].'</label></td>';
                }
                else {
                    echo'<td><input type="text" style="width:100%" name="data['.$k.']['.$i.']" value="'.$row[$i].'"></td>'; 
                }
            }
            echo '<td><a onclick="deleteRow(this)" class="btn-img"><img src="img/del.png" alt="Удалить строку" title="Удалить строку"></a></td>';
            echo '</tr>';
        }
        echo '</table></form>';
    }
    echo '</div>';
    

// Редактирование (добавление и изменение) js

    if (isset($_ses[login]) && isset($_ses[password]) && $_GET[typ]==1) {
        ?>
        <script type="text/javascript">
            function insRow() { //добавление
                var tab=document.getElementById('dataT');
                var tr=tab.insertRow();
                <?
                    if(isset($cols)) echo 'var cols='.$cols.';'."\n";
                    if(isset($Inq[s])) echo 'var spec="'.$Inq[s].'";'."\n";
                    if(isset($isfileinput)) echo 'var isfileinput='.(($isfileinput)?'true':'false').';'."\n";
                ?>
                for (var i=0; i<(cols); i++) {
                    var td=tr.insertCell();

                    id = tab.rows[tab.rows.length-2].cells[0].innerHTML;
                    if (id==null || id<0 || isNaN(id) ) {
                        id=+(0);
                    }else{
                        id =+(id)+1;
                    }
                    
                    if (i==cols-1 && isfileinput)
                        td.innerHTML='<input type="file" style="width:100%" name="data['+id+']">';
                    else if (i==0)
                        td.innerHTML=id;
                    else if (i==2)
                        td.innerHTML='<input type="text" style="width:100%" name="data['+id+']['+(i-1)+']" value="'+spec+'">';
                    else 
                        td.innerHTML='<input type="text" style="width:100%" name="data['+id+']['+(i-1)+']" value="">';
                }
                var td=tr.insertCell();
                td.innerHTML='<a onclick="deleteRow(this)" class="btn-img"><img src="img/del.png" alt="Удалить строку" title="Удалить строку"></a>';
            }

            function deleteRow(r) {//удаление
                var i=r.parentNode.parentNode.rowIndex;
                document.getElementById('dataT').deleteRow(i);
            }

        </script>
        <?
    }
}
}
?>

</body>
</html>
