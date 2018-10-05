<? session_start();
header('Content-Type: text/html; charset=utf-8');
$this_url=$_SERVER[REQUEST_URI];
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
include('connect.php');
$link=Xconnect();

$auth = isset($_SESSION[username]) && $_SESSION[ip] == get_ip();
$admin = isset($_SESSION[admin]);

if ($auth) {
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
}


//CRON
include('plan.php');

?>
<!DOCTYPE html>
<html>
<head>
	<title>Kaz Internet - Список услуг</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<script src="js/jquery-3.0.0.min.js"></script>
	<script src="js/bootstrap.js"></script>

	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/bootstrap-theme.css">
	
	<link rel="stylesheet" href="css/swbutton.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="shortcut icon" href="img/favicon.png" type="image/png">

	<style type="text/css">	.cbalink {display: none;}



	</style>
</head>
<body>
<?

$order="";
switch ($_GET[s]) {
	case 'title': $order='title'; break;
	case 'price': $order='price'; break;
	case 'coun': $order='coun'; break;
	case 'dev': $order='dev'; break;
	default: $order='id'; break;
}
switch ($_GET[a]) {
	case '1': $order.=' desc'; break;
	default: $order.=''; break;
}
$where="";
if (+$_GET[d]>1) {$where.="(dev=".$_GET[d]." || dev=1) &&";}

if ($_GET[f]) {
	if (substr($_GET[f], 0,1)=="*") {
		$where .= "(id=".substr($_GET[f], 1).") &&";
	}	else {
		$where .= " (title LIKE '%".$_GET[f]."%')||(description LIKE '%".$_GET[f]."%')||(dev LIKE '%".$_GET[f]."%')||(price LIKE '%".$_GET[f]."%') &&";
	}
}
if (!$admin) $where .= " (act=true) &&";

if ($where!="") {
	$where=" WHERE (".substr($where,0,-2).")";
}



$_ok=" class='glyphicon glyphicon-ok pull-right'";
$_de=" ";
$iss=($_GET[s])?"s=$_GET[s]&":"";
$isf=($_GET[f])?"f=$_GET[f]&":"";
$isd=($_GET[d])?"d=$_GET[d]&":"";
$isp=($_GET[p])?"p=$_GET[p]&":"";



function devtext($n) {
	switch ($n) {
		case 1:	return '<span class="glyphicon glyphicon-th-large"></span> Все платформы
';break;
		case 2:	return '<span class="glyphicon glyphicon-globe"></span> Интернет';break;
		case 3:	return '<span class="glyphicon glyphicon-phone"></span> Мобильная связь';break;
		case 4:	return '<span class="glyphicon glyphicon-film"></span> Телевидение';break;
		case 5:	return '<span class=""></span> Другой';break;
		default: return 'none';break;
	}
}


?>


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
				<li><a href="perarea.php#tab-buyslog" >Подключенные тарифы</a></li>
				<li class="divider-vertical"></li>
				<li><a href="news.php" >Новости</a></li>
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
						<li><a href="perarea.php#tab-pay"><span style='color: #aa0; font-weight: bold;'><span><?=$moneyuser;?></span> Тг. </span></a></li>
					<?endif;?>
						<li><a href="perarea.php#tab-user">Личный кабинет</a></li>
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
		<li><a class="navbar" href="<?=$_SERVER[PHP_SELF];?>"><table><tr>
				<td><img style="width: 80px; margin-right: 10px;" src="img/logo.png"></td>
				<td style="color:#344;"><h2 style="margin-top:0px; font-weight:bold;"> Kaz Internet</h2><b>Cписок услуг</b></td>
			</tr></table></a></li>
		<li class="divider-vertical"></li>
		<li><a href="<?=$_SERVER[PHP_SELF].'?'.$isf.$iss;?>d=1" <?=($_GET[d]==1 || empty($_GET[d]) )?'style="color:#18D;"':'';?> >
			<span class="glyphicon glyphicon-th-large"></span>
			<span class="glyphicon-class">Все</span>
		</a></li>
		<li class="divider-vertical"></li>
		<li><a href="<?=$_SERVER[PHP_SELF].'?'.$isf.$iss;?>d=2" <?=($_GET[d]==2)?'style="color:#18D;"':'';?> >
			<span class="glyphicon glyphicon-globe"></span>
			<span class="glyphicon-class">Интернет</span>
			</a></li>
		<li class="divider-vertical"></li>
		<li><a href="<?=$_SERVER[PHP_SELF].'?'.$isf.$iss;?>d=3" <?=($_GET[d]==3)?'style="color:#18D;"':'';?> >
			<span class="glyphicon glyphicon-phone"></span>
			<span class="glyphicon-class">Мобильная связь</span>
			</a></li>
		<li class="divider-vertical"></li>
		<li><a href="<?=$_SERVER[PHP_SELF].'?'.$isf.$iss;?>d=4" <?=($_GET[d]==4)?'style="color:#18D;"':'';?> >
			<span class="glyphicon glyphicon-film"></span>
			<span class="glyphicon-class">Телевидение</span>
			</a></li>
		<li class="divider-vertical" style="margin-right: 400px"></li>
		<li class="dropdown">
		<a href="#" data-toggle="dropdown" class="dropdown-toggle">Сортировка по <b class="caret"></b></a>
		<ul class="dropdown-menu" style="min-width: 250px;">
			<li>
				<a href="<?=$_SERVER[PHP_SELF].'?'.$isf.$isd;?>s=id" >По времени
					<span <?=($_GET[s]!="title" && $_GET[s]!="price" && $_GET[s]!="count" && $_GET[s]!="dev") ? $_ok : "";?> ></span>
				</a>
			</li><li>
				<a href="<?=$_SERVER[PHP_SELF].'?'.$isf.$isd;?>s=title" >По наименованию
					<span <?=($_GET[s]=="title")?$_ok:"";?> ></span>
				</a>
			</li><li>
				<a href="<?=$_SERVER[PHP_SELF].'?'.$isf.$isd;?>s=price" >По цене
					<span <?=($_GET[s]=="price")?$_ok:"";?> ></span>
				</a>
			</li><li>
				<a href="<?=$_SERVER[PHP_SELF].'?'.$isf.$isd;?>s=coun" >По количеству
					<span <?=($_GET[s]=="count")?$_ok:"";?> ></span>
				</a>
			</li><li>
				<a href="<?=$_SERVER[PHP_SELF].'?'.$isf.$isd;?>s=dev" >По типу
					<span <?=($_GET[s]=="dev")?$_ok:"";?> ></span>
				</a>
			</li><li class="divider"></li><li>
				<a <?="href='".$_SERVER[PHP_SELF]."?".substr($iss.$isf.$isd,0,-1)."'";?> >По возрастанию
					<span <?=($_GET[a]<>"1")?$_ok:"";?> ></span>
				</a>
			</li><li>
				<a <?="href='".$_SERVER[PHP_SELF]."?".$iss.$isf.$isd."a=1'";?> >По убыванию
					<span <?=($_GET[a]=="1")?$_ok:"";?> ></span>
				</a>
			</li>
		</ul>
		</li>
		<li class="divider-vertical"></li>
		<li class="dropdown">
		<a href="#" data-toggle="dropdown" class="dropdown-toggle" <?=($_GET[f])?"style='color:#18D;'":""?> >Найти тариф <b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li style=" margin-left: 10px; margin-right: 10px;">
				<form method="GET" action="" class="form-inline">
				<div class="form-group has-feedback">
					<div class="input-group">
						<input type="text" name="f" class="form-control" value="<?=$_GET[f];?>" style="min-width: 200px;">
						<span class="input-group-btn">
							<button class="btn btn-secondary" type="submit"><span class=" glyphicon glyphicon-search"></span></button>
						</span>
					</div>
				</div>
				</form>
			</li>
		</ul>
		</li>
		<li class="divider-vertical"></li>
		<? if ($admin) { ?>
			<li><a href="add_tar.php"><span class=" glyphicon glyphicon-plus"></span> Добавить</a></li>
			<li class="divider-vertical"></li>
		<? } ?>

		</ul>
	</div></div>
</header>
<br>







<div class="container">

<?
	  
	$tableName="kaz_internet_tariffs";
	if (!isset($_GET[p])) $_GET[p]=3;
	$limit = ($_GET[p]>=0) ? $_GET[p] : 3;
	include('pagination.php');


	if ($auth && !$admin) {
		$q_atu_res = mysql_query("SELECT id_tar FROM kaz_internet_con_tar WHERE (id_user=$_SESSION[idd] && act=1) ");
		$on_tar=array();
		while($row = mysql_fetch_array($q_atu_res)){
			$on_tar[]=$row[id_tar];
		}
	}


	if ($result||$total_pages==0) {
	while($row = mysql_fetch_array($result))
	{ ?>
	<div class="panel panel-primary">
		<div id='head-<?=$row[id]?>' class='panel-heading' style="padding:15px">
		<div class="row">	<div class="col-md-7">
			<span style='font-size: 16px; margin-top: 5px;'>
				<?if ($admin):?><span style='margin-right: 2px;'><?=$row[id];?>) </span><?endif;?>
				<span id='title-<?=$row[id]?>'> <?=$row[title]?> </span>
			</span>
		</div>
		<div class="col-md-5">
		<span class='' style='margin-top:-5px;'>
		<?if ($admin):?>
			<a class='btn btn-info glyphicon glyphicon-pencil' href='add_tar.php?id=<?=$row[id]?>' title='Изменить'></a>
			<a class='btn btn-danger glyphicon glyphicon-trash' onclick="isDel(<?=$row[id]?>)" title='Удалить'></a>
			
			<span class='pull-right' style='margin-left: 5px; margin-right: 5px;'>

				<form id='form-ok-<?=$row[id];?>' enctype='multipart/form-data' action='' method='post'>
				<input type='hidden' name='type' value='act'>
				<input type='hidden' name='id' value='<?=$row[id];?>'>
				<input type='hidden' name='act' value='<?=($row[act])?0:1;?>'>
				<button class='btn btn-primary glyphicon <?=($row[act])?"glyphicon-eye-open":"glyphicon-eye-close";?>'
					<?="onclick='AjaxFormRequest(\"form-ok-$row[id]\",\"form-ok-$row[id]\",\"update_act.php\"); return false;'";?>
					title='Активность'></button>
				</form>

			</span>			
		<?endif;?>
			</span>
			<?if ($auth && !$admin):?>
			<span class='pull-right' style='margin-left: 5px; margin-top: 5px'>

				<form id='form-on-<?=$row[id];?>' enctype='multipart/form-data' action='buy_insert.php' method='post'>
				<input type='hidden' name='id' value='<?=$row[id];?>'>
				<input type='hidden' name='this_url' value='<?=$this_url?>'>
				<input type='hidden' name='on_tar' value='<?=(in_array($row[id], $on_tar))?0:1;?>'>
				<div class='swbutton'>
					<input type='checkbox' id='swbutton-on-<?=$row[id];?>' <?=(in_array($row[id], $on_tar))? "checked":"";?> 
					onclick='isAdd(<?=$row[id]?>);'>
					<label><i></i></label>
				</div>
				</form>

			</span>
			<?endif;?>
			<span class="pull-right" style='margin-left: 10px; margin-right: 10px; margin-top: 5px; color: #ff0; font-weight: bold;font-size: 16px;'> <span id='price-<?=$row[id]?>'><?=$row[price]?></span> Тг. </span>
		</div></div>

		</div>
		<div class='panel-body' id='body-<?=$row[id]?>'>
			<div class='row'></div>
			<div class='col-md-7 mycontent-left'>
					<p id="description-<?=$row[id]?>"><?=$row[description]?></p> 
			</div>
			<div class='col-md-5'>
				<b>Время действия:</b> <span id="coun-<?=$row[id]?>"><?=$row[coun];?></span> дн.<br>
				<b>Платформа:</b> <span id="dev-<?=$row[id]?>"><?=devtext($row[dev])?></span>
			</div>
		</div>
	</div>




	<?
	}
	echo "</div>";
	}else{echo "<b>Тарифы отсутствуют</b>";}

	?>
	<div class='container' align=center>
		<table><td><?=$paginate;?></td><td><small style="margin-left: 10px">
			<a href="<?=$_SERVER[PHP_SELF].'?'.$iss.$isf.$isd.(($_GET[p]!=0) ? 'p=0' : '');?>" title="<?=($_GET[p]!=0) ? 'Показать все' : 'Показать по страницам';?>">Всего <?=$total_pages;?></a>
		</small></td></table>
	</div>
</div>
<br>



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


<script>
function pre_modal(num) { var ititle = $("#title-" + num).html(); var iprice = $("#price-" + num).html(); var icoun = $("#coun-" + num).html(); var idev = $("#dev-" + num).html(); var idescription = $("#description-" + num).html(); var iimg = $("#img-" + num).html();
	$("#title-m").html(ititle);
	$("#price-m").html(iprice);
	$("#cena").html(+iprice);
	$("#coun-m").html(icoun);
	$("#dev-m").html(idev);
	$("#description-m").html(idescription);
	$("#img-m").html(iimg);
	$("#f-form-id").val(num); if (icoun) { $("#f-form-submit").removeProp('disabled'); } else { $("#f-form-submit").prop('disabled', true); } }

function readURL(input) { if (input.files && input.files[0]) { var reader = new FileReader();
		reader.onload = function(e) { $('#img-pre').attr('src', e.target.result);
			$('#img-img').attr('src', e.target.result); };
		reader.readAsDataURL(input.files[0]); } }


function isDel(idd) {
	if (confirm("Вы точно хотите удалить этот тариф?") == true) {
		window.location.href = "insert.php?id=" + idd + "&delete=1"
	};
}

function isAdd(idd) {
	if (confirm( ( ($('#swbutton-on-'+idd).attr("checked")=="checked")?"Отключить":"Включить") + " выбранный тариф?") == true) {
		$('#form-on-'+idd).submit();
	} else {
		$('#swbutton-on-'+idd).prop("checked", ($('#swbutton-on-'+idd).attr("checked")=="checked")?true:false  );
	}
}

function AjaxFormRequest(result_id,formMain,url) {
	jQuery.ajax({
		url:	 url,
		type:	 "POST",
		dataType: "html",
		data: jQuery("#"+formMain).serialize(),
		success: function(response) {
			document.getElementById(result_id).innerHTML = response;
		},
		error: function(response) {
			document.getElementById(result_id).innerHTML = "<b>error</b>";
		}
	});
}


</script>
</body>
</html>
<? if ($link) mysql_close($link); ?>
