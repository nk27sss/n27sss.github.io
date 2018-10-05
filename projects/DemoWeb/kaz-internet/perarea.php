<? session_start();
header('Content-Type: text/html; charset=utf-8');
$this_url='http://'.$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];
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
$auth = isset($_SESSION[username]) && $_SESSION[ip] == get_ip();
$admin = isset($_SESSION[admin]);

if ($auth) {

include('connect.php');
$link=Xconnect();


if (!$admin) {
	$result = mysql_query("SELECT * FROM `kaz_internet_users`  WHERE `login` = '".$_SESSION[username]."'", $link);
	$user_row = mysql_fetch_array($result);
	$userfio = $user_row[name];
	$moneyuser = $user_row[moneyuser];
	if (empty($_SESSION[idd])) $_SESSION[idd] = $user_row[id];
} else {
	$userfio = $_SESSION[username];
	if (empty($_SESSION[idd])) $_SESSION[idd] = -1;
}





?>

<!DOCTYPE html>
<html>
<head>
	<title>Kaz Internet - Подключенные тарифы</title>
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
				<td style="color:#344;"><h2 style="margin-top:0px; font-weight:bold;"> Kaz Internet</h2><b>Личный кабинет</b></td>
			</tr></table></a></li>
		<li>
			<div class='panel-body'>
			<?if (!$admin):?>
				<b>Имя:</b> <span><?=$user_row[name];?></span><span style="margin-right: 20px"></span>
				<b>Логин:</b> <span><?=$user_row[login];?></span><span style="margin-right: 20px"></span>
				<b>E-mail:</b> <span><?=$user_row[email];?></span><span style="margin-right: 20px"></span>
				<b>IP:</b> <span><?=$_SESSION[ip];?></span><span style="margin-right: 20px"></span>
			<?else:?>
				<span>Просмотр подключенных тарифов и информации о пользователях</span><span style="margin-right: 20px"></span>
				<b>IP:</b> <span><?=$_SESSION[ip];?></span><span style="margin-right: 20px"></span>
			<?endif;?>
			</div>

		</li>
		<li class="divider-vertical" style="margin-right: 600px"></li>
		<li style="bottom: -5px; font-weight: bolder;">
			<ul class="nav nav-tabs sub-menu content-fade-menu">
				<li id="tab-buyslogli" role="presentation">
					<a href="#tab-buyslog" onclick="toggle('tab-buyslog');" role="tab" data-toggle="tab">Подключенные тарифы</a>
				</li>
				<li id="tab-payli" role="presentation">
					<a href="#tab-pay" onclick="toggle('tab-pay');" role="tab" data-toggle="tab">Баланс</a>
				</li>
				<li id="tab-userli" role="presentation">
					<a href="#tab-user" onclick="toggle('tab-user');" role="tab" data-toggle="tab"><?if(!$admin){?>Ваши данные<?}else{?>Данные пользователей<?}?></a>
				</li>
			</ul>
		</li>
		</ul>
	</div></div>
</header>
<br>


<div class="content-fade">
	<div class="content-fade-panel" id="tab-buyslog" style="display: none;">
		<?
		include("buyslog.php");
		?>
	</div>
	<div class="content-fade-panel" id="tab-pay" style="display: none;">
		<?
		include("pay.php");
		?>
	</div>
	<div class="content-fade-panel" id="tab-user" style="display: none;">
		<?
		include("user.php");
		?>
	</div>
</div>


<div style="padding-bottom: 30vh"></div>







<? include("modals_auth.php"); ?>

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


function toggle(id, tli=false, reload=false) {  
	$('#tab-buyslog').hide();
	$('#tab-pay').hide();
	$('#tab-user').hide();
	$('#'+id).fadeIn('fast');
	if (tli) {
		$('#'+id+'li ').addClass('active');
		$('a[href="#'+id+'"]').attr('aria-expanded','true');
	}
	if (reload)	location.reload();
	
	document.location.hash='#'+id;
	return false;
} 

var hash=document.location.hash;
if (hash!="") toggle(hash.slice(1),true);


function isAdd(idd) {
	if (confirm( ( ($('#swbutton-on-'+idd).attr("checked")=="checked")?"Отключить":"Включить") + " выбранный тариф?") == true) {
		$('#form-on-'+idd).submit();
	} else {
		$('#swbutton-on-'+idd).prop("checked", ($('#swbutton-on-'+idd).attr("checked")=="checked")?true:false  );
	}
}

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


