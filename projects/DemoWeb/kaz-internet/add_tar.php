<? session_start();
header('Content-Type: text/html; charset=utf-8');
$this_url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

function get_ip() {
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}
if (isset($_SESSION[username]) && isset($_SESSION[admin]) && $_SESSION[ip] == get_ip()) {

include('connect.php'); 
$link=Xconnect();

$auth = isset($_SESSION[username]) && $_SESSION[ip] == get_ip();
$admin = isset($_SESSION[admin]);

$userfio = $_SESSION[username];
if (empty($_SESSION[idd])) $_SESSION[idd] = -1;


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
				<li><a href="perarea.php#tab-pay" onclick="toggle('tab-pay',true);"><span class="glyphicon glyphicon-usd"></span> Оплата онлайн</a></li>
				<li class="divider-vertical"></li>
			</ul>
			<ul class="nav navbar-nav pull-right">
			<?if (isset($_SESSION[username])) { ?>
				<li class="dropdown">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle" style="background: #1F84D4;<?=($admin)?'color: #f22;':'' ?>" ><span class="glyphicon glyphicon-user"></span> <?=$userfio; ?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
					<?if (!$admin):?>
						<li><a href="perarea.php#tab-pay"><span style='color: #aa0; font-weight: bold;'><span><?=$moneyuser;?></span> Тг. </span></a></li>
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
				<td style="color:#344;"><h2 style="margin-top:0px; font-weight:bold;"> Kaz Internet</h2><b>Добавление тарифа</b></td>
			</tr></table></a></li>
		</ul>
	</div></div>
</header>
<br>

<?
$id="";
$title = "";
$dev = 1;
$coun = 30;
$price = "1000.00";
$description = "";
$act = true;

if (isset($_GET[id])) {
	$SQL = "SELECT * FROM `kaz_internet_tariffs` WHERE id=$_GET[id]";
	$result = mysql_query($SQL, $link);
	$row = mysql_fetch_array($result);
	if ($result && mysql_num_rows($result)) {
		$id = $row[id];
		$title = $row[title];
		$dev = $row[dev];
		$coun = $row[coun];
		$price = $row[price];
		$description = $row[description];
		$img = $row[img];
		$act = $row[act];
	}
}

?>

<div class="container">
<div class="panel panel-info">
	<div class="panel-heading">
		<?=(!isset($_GET[id]) ) ? "Добавление тарифа" : "Изменение тарифа";?>
	</div>
	<div class="panel-body pull-center">
		<form id="fmessage-form" enctype="multipart/form-data" action="insert.php" method="post">
			<?if (isset($_GET[id])){ ?><input type="hidden" name="id" value="<?=$id;?>"> <? } ?>
			<div class="row">
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-12">Название тарифа</div>
						<div class="col-md-12"><input type="text" id="ftitle" name="title" class="form-control" value="<?=$title;?>"></div>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="row">
						<div class="col-md-12">Девайс тарифа</div>
						<div class="col-md-12"  style="font-family: glyphicons-halflings-regular;">
						<select class="form-control" id="fdev" name="dev" class="form-control" value="1">
							<option <?=($dev==1)?"selected ":"";?> value="1">
							Все платформы</option>
							<option <?=($dev==2)?"selected ":"";?> value="2">
							Интернет</option>
							<option <?=($dev==3)?"selected ":"";?> value="3">
							Мобильная связь</option>
							<option <?=($dev==4)?"selected ":"";?> value="4">
							Телевидение</option>
							<option <?=($dev==5)?"selected ":"";?> value="5">
							Другой</option>
						</select>
						</div>
					</div>
				</div>
				<div class="col-md-3">
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
						<div class="col-md-12">Длительность (дней)</div>
						<div class="col-md-12"><input type="text" id="fcoun" name="coun" class="form-control" value="<?=$coun;?>"></div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-12">Активность</div>
						<div class="col-md-12">
						<input type="checkbox" id="fact" name="act" class="btn form-control icon-checkbox">
						<label for="fact" class="btn " style="font-weight: normal;">
							<span class='glyphicon glyphicon-eye-open checked' style="font-size: 20px;"></span>
							<span class='glyphicon glyphicon-eye-close unchecked' style="font-size: 20px;"></span>
							<span>Активность тарифа</span>
						</label>


						</div>
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
		<div id="head-m" class='panel-heading' style="padding:15px">
		<div class="row">	<div class="col-md-7">
			<span style='font-size: 16px; margin-top: 5px;'>
				<span id='title-m'> ... </span>
			</span>
		</div>
		<div class="col-md-5">
		<span class='' style='margin-top:-5px;'>
			
			<span class='pull-right' style='margin-left: 5px; margin-right: 5px;'>
				<div class='swbutton' id='swbutton-m'>
					<input type='checkbox'>
					<label><i></i></label>
				</div>
			</span>
			<span class="pull-right" style='margin-left: 10px; margin-right: 10px; margin-top: 5px; color: #ff0; font-weight: bold;font-size: 16px;'> <span id='price-m'></span> Тг. </span>
		</div></div>

		</div>
		<div class='panel-body' id='body-m'>
			<div class='row'></div>
			<div class='col-md-7 mycontent-left'>
					<p id="description-m"></p> 
			</div>
			<div class='col-md-5'>
				<b>Время действия:</b> <span id="coun-m"></span> дн.<br>
				<b>Платформа:</b> <span id="dev-m"></span>
			</div>
		</div>
	</div>
</div>





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
<script>
 function update_view() {
	var ititle = $("#ftitle").val();
	var idev = $("#fdev").val();
	var icoun = $("#fcoun").val();
	var iprice = $("#fprice").val();
	var idescription = $("#fdescription").val();
	$("#title-m").html(ititle);
	$("#dev-m").html(devtext(idev));
	$("#coun-m").html(icoun);
	$("#price-m").html(iprice);
	$("#description-m").html(idescription);
	$('#pre-m').prop('disabled', (ititle && icoun && iprice) ? false : true);
}


function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e) {
			$('#img-img').attr('src', e.target.result);
		};
		reader.readAsDataURL(input.files[0]);
	}
}
$("#fimg").change(function() {
	readURL(this);
});
$("#ftitle").on('input keyup', function(e) {
	update_view();
});
$("#fdev").on('input keyup', function(e) {
	update_view();
});
$("#fcoun").on('input keyup', function(e) {
	update_view();
});
$("#fprice").on('input keyup', function(e) {
	update_view();
});
$("#fdescription").on('input keyup', function(e) {
	update_view();
});
update_view();

function clr() {
	$("#ftitle").val("");
	$("#fdev").val("");
	$("#fcoun").val("");
	$("#fprice").val("");
	$("#fdescription").val("");
	$("#img").val("");
	$("#img-img").attr('src', '');
}

function devtext(n) {
	switch (n) {
		case "1":	return '<span class="glyphicon glyphicon-th-large"></span> Все платформы';break;
		case "2":	return '<span class="glyphicon glyphicon-globe"></span> Интернет';break;
		case "3":	return '<span class="glyphicon glyphicon-phone"></span> Мобильная связь';break;
		case "4":	return '<span class="glyphicon glyphicon-film"></span> Телевидение';break;
		case "5":	return '<span class=""></span> Другой';break;
		default: return 'none';break;
	}
}


</script>
</html>
<? if ($link) mysql_close($link);  
} else {header("Location: index.php");exit;}
?>