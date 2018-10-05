<?php session_start();
header('Content-Type: text/html; charset=utf-8');
?> 
<!DOCTYPE html>
<html id="html">
<head>
	<title>АРМ сотрудника кафедры</title>
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<?
if ($_POST)
{
		//Проверим логин и пароль
	if (empty($_POST["login"]) || empty($_POST["password"])){
		?>
		<script type="text/javascript">
		window.onload = function () {
			history.back();
			alert("Поля логин и пароль обязательные!");
			document.getElementsByTagName("form").reset();
		}			
		</script>
		<?
	}else{
	
	include('connect.php');
	$link=Xconnect();
	}
			
	//Ищем в базе нашего пользователя
	$SQL = "SELECT * FROM `isemd_users` WHERE `login` =  '".$_POST["login"]."' AND `password` = '".$_POST["password"]."'";
	$result = mysql_query($SQL, $link);
	if (!$result || mysql_num_rows($result) == 0) {
		mysql_close($link);
		?>
		<script type="text/javascript">
		window.onload = function () {
			//history.back();
			alert("Пользователь не найден!");
			document.getElementsByTagName("form").reset();
		}
		</script>
		<?
	} else {
		$_SESSION['login']=$_POST['login'];
		$_SESSION['password']=$_POST['password'];
	}
		
	


}
else {
	unset($_SESSION['login']);
	unset($_SESSION['password']);
}


?>
<header>
	<nav>
	<a href="index.php"><div class="tit1"><span class="cen">АРМ AIS </span></div></a>
	<div class="title"><span class="cen">АРМ сотрудника кафедры</span></div>
	<div class="login">
<? if ($_POST):// показ содержимого только авторизировннным пользователям ?>
	<div class="namelog">
		<a><img src="img/blank.jpg" class="imglog">
		<label><? echo $_POST[login];?></label>
		</a>
		</div>
 	<a class="buttonlog" href="">Выйти</a>
<? else : ?>
	<a class="buttonlog" href="auto.html" target="cc">Войти</a>
<? endif; ?>

	</div>
	</nav>
</header>


	

<body class="body">

	<div class="content">
		<iframe src="res/docs/qual.html" class="frame" name="cc" scrolling="auto"></iframe>
	</div>
	<div class="sidebar">
		<div class="barlist">

			<div class="mnav"><div class="mnavp">Справочники</div></div>
				<ul class="nav">			
					<li><a href="res/docs/qual.html" target="cc">Квалификация</a></li>
					<li><a href="res/docs/IS.pdf" target="cc">Информационные системы 5B070300</a></li>
					<li><a href="res/docs/OP.pdf" target="cc">Организация перевозок  5B090100</a></li>
					<li><a href="res/docs/tel.html" target="cc">Тел.Справочники</a></li>
				</ul>
			
			<div class="mnav"><div class="mnavp">Учебные планы</div></div>
				<ul class="nav">			
					<li><a href="dataTable.php?f=uplan&s=5B070300" target="cc">Информационные системы 5B070300</a></li>
					<li><a href="dataTable.php?f=uplan&s=5B090100" target="cc">Организация перевозок  5B090100</a></li>
				</ul>
			
			<div class="mnav"><div class="mnavp">Рабочий учебный план</div></div>
				<ul class="nav">			
					<li><a href="dataTable.php?f=rplan&s=5B070300" target="cc">Информационные системы 5B070300</a></li>
					<li><a href="dataTable.php?f=rplan&s=5B090100" target="cc">Организация перевозок  5B090100</a></li>
				</ul>
			
			<div class="mnav"><div class="mnavp">Группы</div></div>
				<ul class="nav">			
					<li><a href="dataTable.php?f=group&s=5B070300" target="cc">Информационные системы 5B070300</a></li>
					<li><a href="dataTable.php?f=group&s=5B090100" target="cc">Организация перевозок  5B090100</a></li>
				</ul>
			

			<?if(isset($_SESSION['login']) && isset($_SESSION['password']) && $_SESSION['login']==$_POST['login'] && $_SESSION['password']==$_POST['password']):?>
			<div class="mnav"><div class="mnavp">Файлы</div></div>
				<ul class="nav">			
					<li><a href="filemanager.php?f=user" target="cc">Пользовательские файлы</a></li>
				</ul>
			
			<?endif;?>

		</div>
	</div>

<script>
  var elems = document.body.children;

  for (var i = 0; i < elems.length; i++) {
    if (elems[i].matches('a[href$="zip"]')) {
      alert( "Ссылка на архив: " + elems[i].href );
    }
  }
</script>

</body>
</html>

