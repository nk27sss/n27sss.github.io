<? session_start();
header('Content-Type: text/html; charset=utf-8');
$this_url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

if (isset($_SESSION[username]) && isset($_SESSION[admin])){

include('connect.php'); 

$link=Xconnect();

include('auth.php');
$auth = 1;
$admin = 1;
$userfio = $_SESSION[username];

?>
<!DOCTYPE html>
<html>
<head>
	<title><?=(!isset($_GET[id]) ) ? "Добавление товара" : "Изменение товара";?></title>
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
		<a class="navbar-brand" href="admin.php">&lt;E&gt; Market - <?=(!isset($_GET[id]) ) ? "Добавление товара" : "Изменение товара";?></a>
	</div>
	<ul class="nav navbar-nav navbar">
		<li>
			<a href="index.php" >На главную</a>
		</li>
		<li>
			<a href="admin.php" ><span class=" glyphicon glyphicon-plus"></span> Добавить товар</a>
		</li>
	</ul>

	<ul class="nav navbar-nav navbar-right">
		<li><a href="user.php" <?=($admin)?'style="color: #f00"':'' ?>><span class="glyphicon glyphicon-user"></span> <?=$userfio; ?></a></li>
		<li><a href="<?=$this_url.((strripos($this_url,'?') )?"&q":"?q");?>"><span class="glyphicon glyphicon-log-out"></span> Выйти</a></li>
	</ul>
</div>
</header>

<?
$id = "";
$title = "";
$fab = "";
$coun = "";
$price = "";
$description = "";

if (isset($_GET[id])) {
	$SQL = "SELECT * FROM `emark_products` WHERE id=$_GET[id]";
	$result = mysql_query($SQL, $link);
	$row = mysql_fetch_array($result);
	if ($result && mysql_num_rows($result)) {
		$id = $row[id];
		$title = $row[title];
		$fab = $row[fab];
		$coun = $row[coun];
		$price = $row[price];
		$description = $row[description];
		$img = $row[img];
	}
}
?>

<div class="container">
<div class="panel panel-info">
	<div class="panel-heading">
		<?=(!isset($_GET[id]) ) ? "Добавление товара" : "Изменение товара";?>
	</div>
	<div class="panel-body pull-center">
		<form id="fmessage-form" enctype="multipart/form-data" action="insert.php" method="post">
			<?if (isset($_GET[id])){ ?><input type="hidden" name="id" value="<?=$id;?>"> <? }; ?>
			<div class="row">
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-12">Название товара</div>
						<div class="col-md-12"><input type="text" id="ftitle" name="title" class="form-control" value="<?=$title;?>"></div>
					</div>
				</div>
				
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-12">Производитель</div>
						<div class="col-md-12"><input type="text" id="ffab" name="fab" class="form-control" value="<?=$fab;?>"></div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="row">
						<div class="col-md-12">Количество</div>
						<div class="col-md-12"><input type="text" id="fcoun" name="coun" class="form-control" value="<?=$coun;?>"></div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="row">
						<div class="col-md-12">Цена</div>
						<div class="col-md-12"><input type="text" id="fprice" name="price" class="form-control" value="<?=$price;?>"></div>
					</div>
				</div>
				<div class="col-md-8">
					<div class="row">
						<div class="col-md-12">Описание</div>
						<div class="col-md-12"><textarea id="fdescription" name="description" class="form-control" rows=4><?=$description;?></textarea></div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-12">Изображение</div>
						<div class="col-md-12"><input type="file" id="fimg" name="img" accept="image/JPG/GIF/PNG" class="form-control"></div>
					</div>
				</div>
			</div>
			<div class="row">
				<br>
				<div class="col-md-3"><input type="reset" value="Очистить" class="btn btn-default form-control" onclick="clr()"></div>
				
				<div class="col-md-3"><input type="submit" value="Отправить" class="btn btn-success form-control" id="pre-m" disabled="true"></div>
			</div>
		</form>
	</div>
</div>


<div class="panel panel-primary">
	<div class="panel-heading" id="head-m">
		<span id='title-m' style='padding-right:20px;'> ... </span> 
		<span class=' pull-right' style="color: #ff0;" ><span class="glyphicon glyphicon-shopping-cart"></span> <span id="price-m"></span> Тг. </span>  
	</div>
	<div class="panel-body">
		<div class='col-md-8 '>
			<b>В наличии:</b> <span id="coun-m"> </span><br>
			<b>Производитель:</b> <span id="fab-m"></span><hr class="hr">
			<p id="description-m"></p> 
		</div>
		<div id="img-m" class='col-md-4 pull-right'><img id="img-img" src='<?=($img<>"")?("data:image/png;base64,".base64_encode($img)) : "" ?>' class="img-thumbnail" style="max-width:320px;max-height:240px"></div>
	</div>
</div>
</div>

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
<script> function update_view() { var ititle = $("#ftitle").val(); var ifab = $("#ffab").val(); var icoun = $("#fcoun").val(); var iprice = $("#fprice").val(); var idescription = $("#fdescription").val(); $("#title-m").html(ititle); $("#fab-m").html(ifab); $("#coun-m").html(icoun); $("#price-m").html(iprice); $("#description-m").html(idescription); $('#pre-m').prop('disabled', (ititle && icoun && iprice) ? false : true); } function readURL(input) { if (input.files && input.files[0]) { var reader = new FileReader(); reader.onload = function(e) { $('#img-img').attr('src', e.target.result); }; reader.readAsDataURL(input.files[0]); } } $("#fimg").change(function() { readURL(this); }); $("#ftitle").on('input keyup', function(e) { update_view(); }); $("#ffab").on('input keyup', function(e) { update_view(); }); $("#fcoun").on('input keyup', function(e) { update_view(); }); $("#fprice").on('input keyup', function(e) { update_view(); }); $("#fdescription").on('input keyup', function(e) { update_view(); }); update_view(); function clr() { $("#ftitle").val(""); $("#ffab").val(""); $("#fcoun").val(""); $("#fprice").val(""); $("#fdescription").val(""); $("#img").val(""); $("#img-img").attr('src', ''); }
</script>
</html>
<? if ($link) mysql_close($link);  
} else echo '<script language="JavaScript">window.location.href=\'index.php\';</script>';?>