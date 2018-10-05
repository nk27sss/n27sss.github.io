<? session_start();
header('Content-Type: text/html; charset=utf-8');

if (isset($_SESSION[username])){

include('connect.php'); 

$link=Xconnect();

include('auth.php');
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
	<title>Купленные товары</title>
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
		<a class="navbar-brand" href="buyslog.php">&lt;E&gt; Market - купленные товары</a>
	</div>

	<ul class="nav navbar-nav navbar">
		<li>
			<a href="index.php" >На главную</a>
		</li>
	</ul>
<?
if (isset($_SESSION[username])) {
?>
<ul class="nav navbar-nav navbar-right">
	<li><a href="user.php" <?=($admin)?'style="color: #f00"':'' ?>><span class="glyphicon glyphicon-user"></span> <?=$userfio; ?></a></li>
	<li><a href="<?=$this_url.((strripos($this_url,'?') )?"&q":"?q");?>"><span class="glyphicon glyphicon-log-out"></span> Выйти</a></li>
</ul>
<? } else { ?>

<ul class="nav navbar-nav navbar-right">
	<li><a href="#login-modal" data-toggle="modal"><span class="glyphicon glyphicon-log-in"></span> Войти</a></li>
</ul>
<? } ?>
</div>
</header>
<?
if ($admin) {
$query = "SELECT emark_sales.id AS id, emark_products.id AS pid, emark_products.title AS title, emark_users.email AS email, emark_sales.coun AS coun, emark_sales.s_date AS dat, emark_sales.n_card AS card FROM emark_sales, emark_products, emark_users WHERE (emark_sales.id_prod = emark_products.id && emark_sales.id_user = emark_users.id)";
$result = mysql_query($query);
	echo "<table class='tablesorter'><thead><tr>
		<th>№</th>
		<th>Покупатель</th>
		<th>Товар</th>
		<th>Количество</th>
		<th>Дата</th>
		<th>№ карты</th>
		<th>Перейти к товару</th>
		</tr></thead><tbody>";
	while ($row = mysql_fetch_array($result)) {
		echo "<tr>
		<td>$row[id]</td>
		<td>$row[email]</td>
		<td title='$row[pid]'>$row[title]</td>
		<td>$row[coun]</td>
		<td>$row[dat]</td>
		<td>$row[card]</td>
		<td><a href='index.php?f=%20$row[pid]'>Перейти к товару  <span class='glyphicon glyphicon-share'></span></a></td>
		</tr>";
	}
	echo "</tbody></table>";

} else {
$query = "SELECT emark_sales.id AS id, emark_products.id AS pid, emark_products.title AS title, emark_sales.coun AS coun, emark_sales.s_date AS dat, emark_sales.n_card AS card FROM emark_sales, emark_products, emark_users WHERE (emark_sales.id_prod = emark_products.id && emark_sales.id_user = emark_users.id && emark_users.login = '".$_SESSION[username]."')";	
$result = mysql_query($query);
	echo "<table class='tablesorter'><thead><tr>
		<th>Товар</th>
		<th>Количество</th>
		<th>Дата</th>
		<th>№ карты</th>
		<th>Перейти к товару</th>
		</tr></thead><tbody>";
	while ($row = mysql_fetch_array($result)) {
		echo "<tr>
		<td title='$row[pid]'>$row[title]</td>
		<td>$row[coun]</td>
		<td>$row[dat]</td>
		<td>$row[card]</td>
		<td><a href='index.php?f=*$row[pid]'>Перейти к товару  <span class='glyphicon glyphicon-share'></span></a></td>
		</tr>";
	}
	echo "</tbody></table>";
}

?>
<? modals_auth($this_url); ?>
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
<? if ($link) mysql_close($link);  
} else echo '<script language="JavaScript">window.location.href=\'index.php\';</script>';?>
