<?php session_start();
header('Content-Type: text/html; charset=utf-8');
if (isset($_SESSION[username]) && isset($_SESSION[admin])) {

// присваивание массива
if (empty($_GET)) {
	$Inq[f] = $_POST[f];
} else {            
	$Inq[f] = $_GET[f];
}
//  $_GET[f]: $_POST[f]: about, news, user

if ($Inq[f]!='about' && $Inq[f]!='news' && $Inq[f]!='user') $Inq[f]='user';

// вывод заголовка
switch ($Inq[f]) {
	case 'about': $tf="О компании"; break;
	case 'news': $tf="Новости"; break;
	default: $tf="User"; break;
}


?> 
<!DOCTYPE html>
<html>
<head>
	<title>Kaz Internet - Добавление тарифа</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	
	<script src="js/jquery-3.0.0.min.js"></script>
	<script src="js/bootstrap.js"></script>

	<script src="js/jquery.tablesorter.js"></script>
	<script src="js/widgets/widget-storage.js"></script>
	<script src="js/widgets/widget-filter.js"></script>
	
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/bootstrap-theme.css">

	<link rel="stylesheet" href="css/swbutton.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="css/theme.tablesorter.blue.css" type="text/css">

	<link rel="shortcut icon" href="img/favicon.png" type="image/png">
	<style type="text/css">	.cbalink {display: none;}


	</style>
</head>
<body>



<header id="wrap">
	<div class="navbar navbar-inverse navbar-static-top hr">
	<div class="container">

		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<div class="navbar-brand logo"></div>
		</div>
		
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav" style="">
				<li><a href="index.php">На главную</a></li>
				<li class="divider-vertical"></li>
				<li><a href="news.php">Новости</a></li>
				<li class="divider-vertical"></li>

				<li><a href="about.php">О компании</a></li>
				<li class="divider-vertical"></li>
				<li><a href="perarea.php#tab-pay"><span class="glyphicon glyphicon-usd"></span> Оплата онлайн</a></li>
				<li class="divider-vertical"></li>
			</ul>
			<ul class="nav navbar-nav pull-right">
			<?if (isset($_SESSION[username])) { ?>
				<li class="dropdown">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle" style="background: #1F84D4;<?=($admin)?'color: #f22;':'' ?>" ><span class="glyphicon glyphicon-user"></span> <?=$userfio; ?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
					<?if (!$admin):?>
						<li><a href="perarea.php#tab-about"><span style='color: #aa0; font-weight: bold;'><span><?=$moneyuser;?></span> Тг. </span></a></li>
					<?endif;?>
						<li><a href="perarea.php#tab-user" onclick="toggle('tab-user',true);">Личный кабинет</a></li>
						<li><a href="auth.php?q&this_url=<?=$this_url;?>"><span class="glyphicon glyphicon-log-out"></span> Выйти</a></li>
					</ul>
				</li>
			<? } else { ?>
				<li style="background: #1F84D4"><a href="#login-modal" data-toggle="modal"> Войти</a></li>
			<? } ?>
				<li class="dropdown pull-right">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle">Рус <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="#eng">English</a></li>
						<li><a href="#kaz">Қазақша</a></li>
					</ul>
				</li>
			</ul>


	</div></div>
	</div>
	<div class="navbar navbar-default  hr" style="background:#FEFEFE; border-top:none; ">
		<div class="container"><div class="navbar">
		
		<ul class="nav navbar-nav" style="font-size:14px; width: 100%">
		<li><a class="navbar" href="perarea.php"><table><tr>
				<td><img style="width: 80px; margin-right: 10px;" src="img/logo.png"></td>
				<td style="color:#344;"><h2 style="margin-top:0px; font-weight:bold;"> Kaz Internet</h2><b>Файловый менеджер</b></td>
			</tr></table></a></li>
		<li>
			<div class='panel-body'>
				<span>Замена, редакрирование и удаление файлов ресурса</span>
			</div>
		</li>
		<li class="divider-vertical" style="margin-right: 600px"></li>
		<li style="bottom: -5px; font-weight: bolder;">
			<ul class="nav nav-tabs sub-menu content-fade-menu">
				<li id="tab-newsli" role="presentation" <?=($Inq[f]=="news")?"class='active'":"";?>>
					<a href="fileManager.php?f=news">Новости</a>
				</li>
				<li id="tab-aboutli" role="presentation" <?=($Inq[f]=="about")?"class='active'":"";?>>
					<a href="fileManager.php?f=about">О компании</a>
				</li>
				<li id="tab-userli" role="presentation" <?=($Inq[f]=="user")?"class='active'":"";?>>
					<a href="fileManager.php?f=user">Файлы</a>
				</li>
			</ul>
		</li>
		</ul>
	</div></div>
</header>
<br>







<div class="container" style="width: 100%">
<div class="panel panel-primary">

	<div class='panel-heading' style="padding:15px">
		<span class="pull-right"><b>Файловый менеджер: </b> <i style="font-weight: normal;"><?echo($tf);?></i></span>
		<form enctype="multipart/form-data" action="fileManager.php" method="post" accept-charset="utf-8">
			<input type="hidden" name="f" value=<? echo('"'.$Inq[f].'"'); ?> >
			<span style="display: inline-block;">
				<b>Загрузить файл: </b>
			</span>
			<span style="display: inline-block;">
				<input name="userfile" type="file"  class="btn btn-primary">
			</span>
			<span style="display: inline-block;">
				<input type="submit" class="btn btn-primary">
			</span>
		</form>
	</div>
	<div class='panel-body'>
	<table class='tablesorter' width="100%">
		<thead><tr><th>Список файлов в директории "<i><?=$Inq[f];?></i>"</th></tr></thead>
		<tbody>
<?
// директории
$dir=dirname(__FILE__)."/".$Inq[f]."/";
$diro=dirname($_SERVER[PHP_SELF])."/".$Inq[f]."/";

// загрузка файла
if (!empty($_POST)) {
	if ( move_uploaded_file($_FILES[userfile][tmp_name], 
		$dir.( ($fl_=iconv('UTF-8','cp1251',$_FILES[userfile][name]))? $fl_ : $_FILES[userfile][name] )
	   ) && $_FILES[userfile][error]==0 && !strpos($_FILES[userfile][name],".htaccess")) {/* всё норм */};
}

// удаление файла (опасно)
if (!$val=iconv('UTF-8','cp1251',$_GET[del])) $val=$_GET[del];
if (!empty($_GET[del]) && file_exists($dir.$val) && !strpos($val,".htaccess")) {
	unlink($dir.$val);
    echo '<script language="JavaScript">window.location.href=\'fileManager.php?f='.$Inq[f].'\';</script>';
}

// список файлов
$files = scandir($dir);
foreach ($files as $value) {
	echo '<tr>';
	if ($value !='.' && $value !='..'  && !strpos($val,".htaccess") && !is_dir($dir."/".$value)) {
		if (!$val=iconv('cp1251','UTF-8',$value)) $val=$value;
		echo '<td style="min-width:400px; text-align:left;"><img src="img/unknown.gif" style="vertical-align: middle;margin: 0 5px 0 5px;">
			<a href="'.$diro.$val.'" target="_blank" download>'.$val.'</a>
			<span class="pull-right" style="margin-right:10px;">';
		if ( $val!="index.html" )
		echo '<a style="color:red;" href="fileManager.php?f='.$Inq[f].'&del='.$val.'" onclick=\'return confirm("Удалить файл `'.$val.'` ?")\'>Удалить</a>';
		else 
		echo '<s>Удалить</s>';
		echo '</span></td>';
	}
	echo '</tr>';
}



?>
	</tbody>
	</table>
	</div>
</div>
</div>






<br>
<footer class="footer navbar navbar-default">
	<div class="container">
		<div class="row">
		<div class="col-sm-12">
			<p class="navbar-text">Copyright &copy; N27sss, 2018</p>
			<p class="navbar-text navbar-right">Данный сайт является демонстрацией.</p>
		</div>
		</div>
	</div>
</footer>

</body>
<script type="text/javascript">


$(function() {
	var $table = $('table').tablesorter({
	theme: 'blue',
	widgets: ["zebra", "filter"],
	widgetOptions : {
		filter_external : '.search',
		filter_defaultFilter: { 1 : '~{query}' },
		filter_columnFilters: true,
		filter_placeholder: { search : 'Search...' },
		filter_saveFilters : true,
		filter_reset: '.reset'
	}
	});
});
</script>
</html>
<? if ($link) mysql_close($link);  
} else {header("Location: index.php");exit;}
?>
