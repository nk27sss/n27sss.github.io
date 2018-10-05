<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Форма для печати и просмотра</title>
	<link rel="stylesheet" href="css\style.css" type="text/css"  />
</head>
<body style="padding:10px;">
<h2><b>Форма для печати и просмотра</b></h2>
<div style="padding:10px;">
<?php
// Если есть id в get запросе
if (isset($_GET['id'])) {

	include('connect.php');
	$link=Xconnect();

	// Выполняем SQL-запрос
	$query = "
	SELECT id, surname, name, patr, dat, driv_num, iin, photo, sign, A1, A, B1, B, C1, C, D1, D, BE, C1E, CE, D1E, DE, add_inf 
	FROM bdvu_prava 
	WHERE id = ".$_GET['id'];
	$result = mysql_query($query) or die('Запрос не удался: ' . mysql_error());
		
	if ( mysql_num_rows( $result ) == 1 ) {
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	$id = $row[id];
	$surname = $row[surname];
	$name = $row[name];
	$patr = $row[patr];
	$dat = $row[dat];
	$dat2 = date("Y-m-d",strtotime("+10 year", strtotime(preg_replace('~^(\d+)\.(\d+)\.(\d+)$~', '$3-$2-$1', $dat))));  
	$driv_num = $row[driv_num];
	$iin = $row[iin];

	$categories = 
	(($row[A1]==1) ? 'A1,':'').
	(($row[A]==1)  ? 'A,':'').
	(($row[B1]==1) ? 'B1,':'').
	(($row[B]==1)  ? 'B,':'').
	(($row[C1]==1) ? 'C1,':'').
	(($row[C]==1)  ? 'C,':'').
	(($row[D1]==1) ? 'D1,':'').
	(($row[D]==1)  ? 'D,':'').
	(($row[BE]==1) ? 'BE,':'').
	(($row[C1E]==1)? 'C1E,':'').
	(($row[CE]==1) ? 'CE,':'').
	(($row[D1E]==1)? 'D1E,':'').
	(($row[DE]==1) ? 'DE,':'');
	$categories[strlen($categories)-1]=null;

	$add_inf = $row[add_inf];
	
	
	$photo = $row[photo];
	$sign = $row[sign];
	echo "<table class='form_'>";
	echo "<tr><td><b>ID:</b></td><td>". $id  ."</td></tr>";
	echo "<tr><td><b>Фамилия:</b></td><td>". $surname  ."</td></tr>";
	echo "<tr><td><b>Имя:</b></td><td>". $name  ."</td></tr>";
	echo "<tr><td><b>Отчество:</b></td><td>". $patr  ."</td></tr>";
	echo "<tr><td><b>Дата выдачи:</b></td><td>". $dat  ."</td></tr>";
	echo "<tr><td><b>Действ до:</b></td><td>". $dat2  ."</td></tr>";
	echo "<tr><td><b>№ ВУ:</b></td><td>". $driv_num  ."</td></tr>";
	echo "<tr><td><b>ИИН:</b></td><td>". $iin  ."</td></tr>";
	echo "<tr><td><b>Категории:</b></td><td>". $categories  ."</td></tr>";
	echo "<tr><td><b>Доп. инф:</b></td><td>". $add_inf  ."</td></tr>";

	echo "<tr><td><b>Фото:</b></td><td>";
	if ($photo<>'') echo "<img src='data:image/png;base64,".base64_encode($photo)."'>  <br>";
	else echo "Фото отсутствует  <br>";

	echo "</tr><tr><td><b>Роспись:</b></td><td>";
	if ($sign<>'') echo "<img src='data:image/png;base64,".base64_encode($sign)."'>  <br>";
	else echo "Роспись отсутствует  <br>";

	echo "</tr><tr><td></td><td><input type='button' value='Печать' onclick=\"alert('Печать недоступна, тк это тестовая версия!');\"></td></tr>";
	echo "</table>";
	}
}else echo "<b>Не указан ID</b>";
?>
</div>
</body>
</html>