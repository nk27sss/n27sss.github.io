<?header('Content-Type: text/html; charset=utf-8');?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Практика, таблица водительских прав</title>
	<link rel="stylesheet" href="css\style.css" type="text/css"  />
</head>
<body>
<? 
$ror = $_GET[r];
$find = $_GET[f]; 
?>
	<div id="head">
		<div class="topx">
			<div class="title">
				БД ВУ ОАП УВД г Семей
			</div>
			<a class="knopka" href="form_add.html?typ=0">Добавить данные</a> <!--тип(typ) 0-добавить, 1-изменить, 2-удалить-->
			<a class="knopka" onclick="infof();">Инфо</a>
			<div class="right">
				<form method="get">
				<select display="inline" name="r" class="sea">
					<option <? echo($ror=='all')?'selected':'' ?> value="all">Все</option>
					<option <? echo($ror=='id')?'selected':'' ?> value="id">№</option>
					<option <? echo($ror=='surname')?'selected':'' ?> value="surname">Фамилия</option>
					<option <? echo($ror=='name')?'selected':'' ?> value="name">Имя</option>
					<option <? echo($ror=='patr')?'selected':'' ?> value="patr">Отчество</option>
					<option <? echo($ror=='dat')?'selected':'' ?> value="dat">Дата выдачи</option>
					<option <? echo($ror=='driv_num')?'selected':'' ?> value="driv_num">№ ВУ</option>
					<option <? echo($ror=='iin')?'selected':'' ?> value="iin">ИИН</option>
					<option <? echo($ror=='cat')?'selected':'' ?> value="cat">Категории</option>
					<option <? echo($ror=='add_inf')?'selected':'' ?> value="add_inf">Доп. инф</option>
				</select>
				<input type="search" name="f" display="inline" <? echo 'value="'.$find.'"';?>  class="sea">
				<input type="submit" class="search" value="">
			</div>
				</form>
			</div>
		</div>
	</div>
<center id="main">


	<?php

	include('connect.php');
	$link=Xconnect();
	

	$rw = '';
	if ($ror=='cat')
	{
		$s = '';
		$find = strtoupper($find);
		$arr = explode(",",$find);	//массив категорий
		foreach($arr as $key => $value) {
             $s .= $value.' = 1 && ';
        }
        $s = substr($s,0,-3);
        $rw = $s;
	}
	else
	if ($ror=='dat')
	{
		$rw = $ror." LIKE '".$find."' "; //~
	}
	else
	if (($ror=='id') || ($ror=='iin'))
	{
		$rw = $ror." = ".$find." ";
	}
	else
	if (($ror=='surname') || ($ror=='name') || ($ror=='patr') || ($ror=='driv_num') || ($ror=='add_inf'))
	{
		$rw = $ror." LIKE '".$find."' ";
	}
	else
	if ($ror=='all')
	{
		$rw = "
		( surname LIKE '".$find."' )
		|| ( name LIKE '".$find."' )
		|| ( patr LIKE '".$find."' )
		|| ( driv_num LIKE '".$find."' ) 
		|| ( add_inf LIKE '".$find."' )";
	}
	else
	{
		$rw = '1';
	};

	if ($find=='') $rw = '1';	// Если строка пуста, то выводить всё

	// Выполняем SQL-запрос
	$query = "
	SELECT id, surname, name, patr, dat, driv_num, iin, photo, sign, A1, A, B1, B, C1, C, D1, D, BE, C1E, CE, D1E, DE, add_inf 
	FROM bdvu_prava 
	WHERE ".$rw;

	// Тест запоса поиска
	//echo $query;
	
	$result = mysql_query($query) or die('Запрос не удался: ' . mysql_error());
	
	//html
	echo "<table class='simple-little-table' cellspacing='0'>\n";
	
	
	echo "
		<tr class='s-up'>
			<th>*</th>
			<th width=5>№</th>
			<th width=80>Фамилия</th>
			<th width=70>Имя</th>
			<th width=70>Отчество</th>
			<th width=50>Дата выдачи</th>
			<th width=50>Действ до</th>
			<th width=55>№ ВУ</th>
			<th width=55>ИИН</th>
			<th width=200>Категории</th>
			<th width=60>Доп. инф</th>
			
		</tr>
		";

	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		
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

		$ps = (($row[photo]=='')?'p':'').(($row[sign]=='')?'s':'');

		echo "\t<tr>\n"; 
		echo "<td><a onclick='prt_link(".$id.");'><img src='images/p.png' alt='Изменить' width=16 height=16></a>$ps</td>";
		echo "\t<td> $id </td>\n";
		echo "\t<td> $surname </td>\n";
		echo "\t<td> $name </td>\n";
		echo "\t<td> $patr </td>\n";
		echo "\t<td> $dat </td>\n";
		echo "\t<td> $dat2 </td>\n";
		echo "\t<td> $driv_num </td>\n";
		echo "\t<td> $iin </td>\n";
		echo "\t<td> $categories </td>\n";
		echo "\t<td> $add_inf </td>\n";

		//изменение
		$cat = 
		(($row[A1]==1) ? '1':'0').
		(($row[A]==1)  ? '1':'0').
		(($row[B1]==1) ? '1':'0').
		(($row[B]==1)  ? '1':'0').
		(($row[C1]==1) ? '1':'0').
		(($row[C]==1)  ? '1':'0').
		(($row[D1]==1) ? '1':'0').
		(($row[D]==1)  ? '1':'0').
		(($row[BE]==1) ? '1':'0').
		(($row[C1E]==1)? '1':'0').
		(($row[CE]==1) ? '1':'0').
		(($row[D1E]==1)? '1':'0').
		(($row[DE]==1) ? '1':'0');

		$get_edit='typ=1'.		//тип 0-доб, 1-изменить, 2-удалить
		'&id='.$id.
		'&surname='.$surname.
		'&name='.$name.
		'&patr='.$patr.
		'&dat='.$dat.
		'&driv_num='.$driv_num.
		'&iin='.$iin.
		'&cat='.$cat.
		'&add_inf='.$add_inf;

		echo "<td><a  href='form_add.html?".$get_edit."'><img src='images/ink.png' alt='Изменить' width=16 height=16></a></td>";
		echo "\t</tr>\n";
	}
	
	echo "</table>\n";
	
	$endid=$id;

	// Освобождаем память от результата
	mysql_free_result($result);
	
	//загрузка данных в бд
	if (  
		isset($_POST['id']) && 
		(
			(
				isset($_POST['name'])
				&& isset($_POST['patr'])
				&& isset($_POST['dat'])
				&& isset($_POST['driv_num'])
				&& isset($_POST['iin']) 
			) 
			|| $_POST['typ']==2
		)  
		)
	{
		$typ = $_POST['typ']; 		//тип 0-добавить, 1-изменить, 2-удалить

		$id = $_POST['id'];
		$surname = $_POST['surname'];
		$name = $_POST['name'];
		$patr = $_POST['patr'];
		$dat = $_POST['dat'];
		$driv_num = $_POST['driv_num'];
		$iin = $_POST['iin'];
		
		$A1 = $_POST['A1'];
		$A = $_POST['A'];
		$B1 = $_POST['B1'];
		$B = $_POST['B'];
		$C1 = $_POST['C1'];
		$C = $_POST['C'];
		$D1 = $_POST['D1'];
		$D = $_POST['D'];
		$BE = $_POST['BE'];
		$C1E = $_POST['C1E'];
		$CE = $_POST['CE'];
		$D1E = $_POST['D1E'];
		$DE = $_POST['DE'];

		$cat =
		(($A1==on) ? 'A1,':'').
		(($A==on)  ? 'A,':'').
		(($B1==on) ? 'B1,':'').
		(($B==on)  ? 'B,':'').
		(($C1==on) ? 'C1,':'').
		(($C==on)  ? 'C,':'').
		(($D1==on) ? 'D1,':'').
		(($D==on)  ? 'D,':'').
		(($BE==on) ? 'BE,':'').
		(($C1E==on)? 'C1E,':'').
		(($CE==on) ? 'CE,':'').
		(($D1E==on)? 'D1E,':'').
		(($DE==on) ? 'DE,':'');
		$cat[strlen($cat)-1]=null;
		
		$A1  = ($A1==on)?1:0;
		$A   = ($A==on)?1:0;
		$B1  = ($B1==on)?1:0;
		$B   = ($B==on)?1:0;
		$C1  = ($C1==on)?1:0;
		$C   = ($C==on)?1:0;
		$D1  = ($D1==on)?1:0;
		$D   = ($D==on)?1:0;
		$BE  = ($BE==on)?1:0;
		$C1E = ($C1E==on)?1:0;
		$CE  = ($CE==on)?1:0;
		$D1E = ($D1E==on)?1:0;
		$DE  = ($DE==on)?1:0;

		//фото
		$photo = '';
		$sign = '';
		$si_tit ='';
		$ph_tit ='';

		// Проверяем, что при загрузке не произошло ошибок
		if ( $_FILES['photo']['error'] == 0 ) {
		//Если файл загружен успешно, то проверяем - графический ли он
		if( substr($_FILES['photo']['type'], 0, 5)=='image' ) {
		// Читаем содержимое файла
		$photo = file_get_contents( $_FILES['photo']['tmp_name'] );
		// Экранируем специальные символы в содержимом файла
		$photo = mysql_escape_string( $photo );
		if ($typ==1) {
			$photo = "photo = '".$photo."', ";
		} else {
			$ph_tit = "photo, ";
			$photo =  "'".$photo."', ";
		};
		}};
		// Проверяем, что при загрузке не произошло ошибок
		if ( $_FILES['sign']['error'] == 0 ) {
		//Если файл загружен успешно, то проверяем - графический ли он
		if( substr($_FILES['sign']['type'], 0, 5)=='image' ) {
		// Читаем содержимое файла
		$sign = file_get_contents( $_FILES['sign']['tmp_name'] );
		// Экранируем специальные символы в содержимом файла
		$sign = mysql_escape_string( $sign );
		
		if ($typ==1) {
			$sign = "sign = '".$sign."', ";
		} else {
			$si_tit = "sign, ";
			$sign =  "'".$sign."', ";
		};
		}};

		$nam_org = 1;

		$add_inf = $_POST['add_inf'];


		
		$t=0; // перезагрузка
		if ($typ==1) {		//изменить /

			mysql_query("
				UPDATE bdvu_prava SET
				surname = '$surname', name = '$name', patr = '$patr', dat = '$dat', driv_num = '$driv_num', nam_org = $nam_org, iin = $iin,   $photo$sign  A1 = $A1, A = $A, B1 = $B1, B = $B, C1 = $C1, C = $C, D1 = $D1, D = $D, BE = $BE, C1E = $C1E, CE = $CE, D1E = $D1E, DE = $DE, add_inf = '$add_inf' 
				WHERE id = $id
			");
		} else {
			if ($typ==2) {		//удалить X
				mysql_query("
					DELETE 
					FROM bdvu_prava WHERE id=$id
				");
			} else {		//добавить O
				mysql_query("
					INSERT INTO bdvu_prava (surname, name, patr, dat, driv_num, nam_org, iin,  $ph_tit$si_tit  A1, A, B1, B, C1, C, D1, D, BE, C1E, CE, D1E, DE, add_inf) 
					VALUES ('$surname', '$name', '$patr', '$dat', '$driv_num', $nam_org, $iin, $photo$sign  $A1, $A, $B1, $B, $C1, $C, $D1, $D, $BE, $C1E, $CE, $D1E, $DE, '$add_inf')
				");
			};
		};
		mysql_close($link);
		echo '<script language="JavaScript"> 
		  window.location.href = "index.php"
		</script>';
	}
	else
	// Закрываем соединение
	mysql_close($link);
	?>


</center>
<div id="footer">
	<div class="tfootx">
		<div class="tfoot">
			Copyright © 2016 БД ВУ ОАП УВД г Семей  <b> (Демо)</b>
		</div><hr color="#000">
	</div>
</div>
</body>
<script language="JavaScript"> 
	function prt_link(a) {
		window.location.href = "print.php?id="+a;
	}
	function infof() {
		alert("Веб приложение \"БД ВУ ОАП УВД г Семей\"\n разработал студент 2 курса, специальности ИС-404 Красиков Н. А. (N27sss)\n  ");
	}
</script>
</html>