<? session_start();
header('Content-Type: text/html; charset=utf-8');
$this_url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

include('connect.php'); 

function deleteGET($url, $name, $amp = false) {
	$url = str_replace("&amp;", "&", $url);
	list($url_part, $qs_part) = array_pad(explode("?", $url), 2, "");
	parse_str($qs_part, $qs_vars);
	unset($qs_vars[$name]);
	if (count($qs_vars) > 0) {
		$url = $url_part."?".http_build_query($qs_vars);
		if ($amp) $url = str_replace("&", "&amp;", $url);
	}
	else $url = $url_part;
	return $url;
}
function isValidLogin($v) { return preg_match("#^[aA-zZ0-9\-_]+$#",$v);  }

if (isset($_GET[q])) {
	echo "<script>window.location.href=\"".deleteGET($this_url,"q")."\";</script>";
	unset($_SESSION[username]);
}
if (isset($_SESSION[username]) || $_POST) {
	$link=Xconnect();
}
if ($_POST && empty($_SESSION[username]) ) {
	if ( empty($_POST[login]) || empty($_POST[password]) || empty($_POST[email]) || empty($_POST[name])
	 || !isValidLogin($_POST[login]) || !isValidLogin($_POST[password]) ) {
		echo '<script language="JavaScript">window.location.href=\'index.php\';</script>';
		die("Ошибка!");
	}
	if ($link){
		$SQL = "SELECT * FROM `emark_users`   WHERE `login` = '".$_POST[login]."' ";
		$result = mysql_query($SQL);
		$row = mysql_fetch_array($result);
		if (mysql_num_rows($result) == 0) {
			$SQL = "INSERT INTO `emark_users` (`login`, `password`, `email`, `name` ) VALUES ('$_POST[login]', '$_POST[password]', '$_POST[email]', '$_POST[name]' ) ";

			mysql_query($SQL);
			$_SESSION[username]=$_POST[login];
		} else { echo "такой логин уже есть";}
	}
}	
if (isset($_SESSION[username])) {
	$auth = 1;
	$admin = isset($_SESSION[admin]);

	if (isset($_SESSION[username])) {
		if (!isset($_SESSION[admin])) {
			$result = mysql_query("SELECT * FROM `emark_users`  WHERE `login` = '".$_SESSION[username]."'", $link);
			$row = mysql_fetch_array($result);
			$userfio = $row[name];
		} else $userfio = $_SESSION[username];
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Страница пользователя</title>
	<script src="js/jquery-3.0.0.min.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>
	<script src="js/jquery.tablesorter.js"></script>
	<script src="js/widgets/widget-storage.js"></script>
	<script src="js/widgets/widget-filter.js"></script>
	
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="css/theme.tablesorter.blue.css" type="text/css">

	<link rel="shortcut icon" href="/img/favicon.png" type="image/png">
	<style type="text/css">	.cbalink {display: none;}
	html {
		position: relative;
		min-height: 100%;
	}
	body {
		margin-bottom: 200px;
	}
	.footer {
		position: absolute;
		bottom: 0;
		width: 100%;
		margin-bottom: 0px;
	}
</style>
</head>
<body>
<header class="navbar navbar-inverse">
<div class="container-fluid">
	<div class="navbar-header">
		<a class="navbar-brand" href="user.php">&lt;E&gt; Market - страница пользователя</a>
	</div>

	<ul class="nav navbar-nav navbar">
		<li>
			<a href="index.php" >На главную</a>
		</li>
	</ul>
<ul class="nav navbar-nav navbar-right">
	<li><a href="user.php" <?=($admin)?'style="color: #f00"':'' ?>><span class="glyphicon glyphicon-user"></span> <?=$userfio; ?></a></li>
	<li><a href="<?=$this_url.((strripos($this_url,'?') )?"&q":"?q");?>"><span class="glyphicon glyphicon-log-out"></span> Выйти</a></li>
</ul>

</div>
</header>

<div class="container">
<div class="panel panel-primary">
<?
if (!$admin) {
	$SQL = "SELECT * FROM `emark_users`  WHERE `login` = '".$_SESSION[username]."'";
	$result = mysql_query($SQL, $link);
	$row = mysql_fetch_array($result);
	
?>
<div class='panel-heading' style="padding:15px">
	<span style='padding-right:20px;'> <b>Ваши данные</b> </span>	
</div>
<div class='panel-body'>
	<b>Имя:</b> <label><?=$row[name]?></label><br>
	<b>Логин:</b> <label><?=$row[login]?></label><br>
	<b>E-mail:</b> <label><?=$row[email]?></label><br>
</div>
<?
} else {
?>
<div class='panel-heading' style="padding:15px">
	<span style='padding-right:20px;'> <b>Пользователи</b> </span>	
</div>
<div class='panel-body'>
<?
	$query = "SELECT * FROM emark_users ";	
	$result = mysql_query($query);
	echo "<table class='tablesorter'><thead><tr>
		<th>№</th>
		<th>ФИО</th>
		<th>Логин</th>
		<th>E-mail</th>
		</tr></thead><tbody>";
	while ($row = mysql_fetch_array($result)) {
		echo "<tr>
		<td>$row[id]</td>
		<td>$row[name]</td>
		<td>$row[login]</td>
		<td>$row[email]</td>
		</tr>";
	}
	echo "</tbody></table>";

?> </div> <?

} ?>
</div>
</div>

<footer class="footer navbar navbar-inverse">
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<p class="navbar-text">Copyright &copy; N27sss, 2017</p>
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
<?
} else {echo '<script language="JavaScript">window.location.href=\'index.php\';</script>';};

if ($link) mysql_close($link); 
?>

