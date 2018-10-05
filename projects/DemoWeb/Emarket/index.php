<? session_start();
header('Content-Type: text/html; charset=utf-8');
$this_url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

include('connect.php'); 

	
$link=Xconnect();

include('auth.php');
$auth = isset($_SESSION[username]);
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
	<title>&lt;E&gt;Market</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<script src="js/jquery-3.0.0.min.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
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
<?

$auth=false;
$order="";
switch ($_GET[s]) {
	case 'title': $order='title'; break;
	case 'price': $order='price'; break;
	case 'count': $order='count'; break;
	case 'fab': $order='fab'; break;
	default: $order='id'; break;
}
switch ($_GET[a]) {
	case '1': $order.=' desc'; break;
	default: $order.=''; break;
}
$where="";
if ($_GET[f]) $where="WHERE (`title` LIKE '%".$_GET[f]."%')||(`description` LIKE '%".$_GET[f]."%')||(`fab` LIKE '%".$_GET[f]."%')||(`price` LIKE '%".$_GET[f]."%')";
if (substr($_GET[f], 0,1)=="*") $where="WHERE (`id`=".substr($_GET[f], 1).")";


$act=" class='glyphicon glyphicon-ok pull-right'";
$isf=($_GET[f])?"f=$_GET[f]&":"";
?>
<header class="navbar navbar-inverse">
<div class="container-fluid">
	<div class="navbar-header">
		<a class="navbar-brand" href="index.php">&lt;E&gt; Market - каталог товаров</a>
	</div>

	<ul class="nav navbar-nav navbar">
		<li>
			<a href="buyslog.php" >Купленные товары</a>
		</li>


		<li class="dropdown">
		<a href="#" data-toggle="dropdown" class="dropdown-toggle">Сортировка по <b class="caret"></b></a>
		<ul class="dropdown-menu" style="min-width: 250px;">
			<li>
				<a href="index.php?<?=$isf;?>s=id" >По времени
					<span <?=($_GET[s]!="title" && $_GET[s]!="price" && $_GET[s]!="count" && $_GET[s]!="fab") ? $act : "";?> ></span>
				</a>
			</li>
			<li>
				<a href="index.php?<?=$isf;?>s=title" >По наименованию
					<span <?=($_GET[s]=="title")?$act:"";?> ></span>
				</a>
			</li>
			<li>
				<a href="index.php?<?=$isf;?>s=price" >По цене
					<span <?=($_GET[s]=="price")?$act:"";?> ></span>
				</a>
			</li>
			<li>
				<a href="index.php?<?=$isf;?>s=count" >По количеству
					<span <?=($_GET[s]=="count")?$act:"";?> ></span>
				</a>
			</li>
			<li>
				<a href="index.php?<?=$isf;?>s=fab" >По производителю
					<span <?=($_GET[s]=="fab")?$act:"";?> ></span>
				</a>
			</li>
			<li class="divider"></li>
			<li>
				<a <? echo("href='index.php".(($_GET[s]!='')?"?".$isf."s=".$_GET[s]."":"")."'"); ?> >По возрастанию
					<span <?=($_GET[a]<>"1")?$act:"";?> ></span>
				</a>
			</li>
			<li>
				<a <? echo("href='index.php".(($_GET[s]!='')?"?".$isf."s=".$_GET[s]."&a=1":"?a=1")."'"); ?> >По убыванию
					<span <?=($_GET[a]=="1")?$act:"";?> ></span>
				</a>
			</li>
		</ul>
		</li>


		<li class="dropdown">
		<a href="#" data-toggle="dropdown" class="dropdown-toggle" <?=($_GET[f])?"style='text-decoration:underline;'":""?> >Найти товар <b class="caret"></b></a>
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


	<? if ($admin) { ?>
	<li>
		<a href="admin.php"><span class=" glyphicon glyphicon-plus"></span> Добавить товар</a>
	</li>
	<? } ?>

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

<div class="container">

<?
	
	$tableName="emark_products";
	$limit = 3;
	include('pagination.php');

	if ($result||$total_pages==0) {
	while($row = mysql_fetch_array($result))
	{	?>
	<div class="panel panel-primary">
		<div id='head-<?=$row[id]?>' class='panel-heading' style="padding:15px">
			<span id='title-<?=$row[id]?>' style='padding-right:20px;'> <?=$row[title]?> </span>
			
			<span class='pull-right' style='margin-top:-5px;'>

			<?if ($admin):?>
			<span style='color: #fff;padding-right:10px;'> <?=$row[id];?> </span>
			<a class='btn btn-info glyphicon glyphicon-pencil' href='admin.php?id=<?=$row[id]?>' title='Изменить'></a>
			<a class='btn btn-danger glyphicon glyphicon-trash' onclick="isDel(<?=$row[id]?>)" title='Удалить'></a>
			<?endif;?>

			<button  href="#pre-modal" onclick="pre_modal('<?=$row[id]?>')" class='btn btn-success' style="color: #ff0; width: 150px;" id="pre-<?=$row[id]?>" data-toggle="modal"  title='Купить'>
				<span class="glyphicon glyphicon-shopping-cart"></span> <span id='price-<?=$row[id]?>'><?=$row[price]?></span> Тг. 
			</button>

			</span>
		</div>

		<div class='panel-body' id='body-<?=$row[id]?>'>
			<div class='col-md-8 '>
				<b>В наличии:</b> <span id="coun-<?=$row[id]?>"><?=($row[coun]>0)?$row[coun]:" отсутствуют."; ?></span><br>
				<b>Производитель:</b> <span id="fab-<?=$row[id]?>"><?=$row[fab]?></span><hr class="hr">
				<p id="description-<?=$row[id]?>"><?=$row[description]?></p> 
			</div>
			<div class='col-md-4 pull-right' id="img-<?=$row[id]?>">
				<img class='pull-center img-thumbnail' src='<?=($row[img]<>"")?("data:image/png;base64,".base64_encode($row[img])) : "" ?>'><br>

			</div>
		</div>
	</div>
	<?
	}
	echo "</div>";
	}else{echo "<b>Данные отсутствуют</b>";}

	?>
	<div class='container' align=center>
		<?=$paginate;?><small>Всего <?=$total_pages;?></small>
	</div>
	</div><br>
</div>
</div>



<div id="pre-modal" class="modal fade">
	<div class="modal-dialog  modal-lg">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 class="modal-title">Предварительный просмотр <?=(!$admin)?"покупки товара":""?></h4>
		</div>
		<div class="modal-body">

		<div class="panel panel-primary">
			<div class="panel-heading" id="head-m">
				<span id='title-m' style='padding-right:20px;'> ... </span> 
				<span class=' pull-right' style="color: #ff0;" >
					<span class="glyphicon glyphicon-shopping-cart"></span> <span id="price-m"></span> Тг. </span>  
			</div>
			<div class="panel-body" id="body-m">
				<div class='col-md-8 '>
					<b>В наличии:</b> <span id="coun-m"> </span><br>
					<b>Производитель:</b> <span id="fab-m"></span><hr class="hr">
					<p id="description-m"></p> 
				</div>

				<div id="img-m" class='col-md-4 pull-right'> ... </div>
			</div>
		</div>
		
		<form action="buy_insert.php" method="post" id="f-form">
			<input type="hidden" id="f-form-id" name="id" value="">
				<div class="container panel panel-primary" style="padding:20px; max-width: 100%; ">
				<div class="row">
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-12">Количество</div>
							<div class="col-md-12"><input type="text" class="form-control" id="count" name="count" value="1"></div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-12">Кредитная карта</div>
							<div class="col-md-12"><input type="text" class="form-control" id="n_card" name="n_card" value="123456789"></div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-12">Сумма к оплате</div>
							<div class="col-md-12"><span class="form-control form-control-static"><b><span id="cena"></span> Тг. </b></span></div>
							
						</div>
					</div>
				</div>
			</div>
		</form>
		</div>

		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
		<? if (isset($_SESSION[username])) { ?>
		<button type="button" class="btn btn-success" id="f-form-submit" <?=($admin)?"disabled":""; ?> onclick="$('#f-form').submit(); alert('Вы приобрели товар\nСпасибо за покупку!')"><span class="glyphicon glyphicon-shopping-cart"></span> Купить</button>
		<? } else {?>
		<button type="button" class="btn btn-success" href="#login-modal" data-toggle="modal" onclick='$("#pre-modal").modal("hide");'><span class="glyphicon glyphicon-user"></span> Войти</button>
		<? } ?>
		</div>
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


<script>
function pre_modal(num) { var ititle = $("#title-" + num).html(); var iprice = $("#price-" + num).html(); var icoun = $("#coun-" + num).html(); var ifab = $("#fab-" + num).html(); var idescription = $("#description-" + num).html(); var iimg = $("#img-" + num).html(); $("#title-m").html(ititle); $("#price-m").html(iprice); $("#cena").html(+iprice); $("#coun-m").html(icoun); $("#fab-m").html(ifab); $("#description-m").html(idescription); $("#img-m").html(iimg); $("#f-form-id").val(num); if (icoun) { $("#f-form-submit").removeProp('disabled'); } else { $("#f-form-submit").prop('disabled', true); } } function readURL(input) { if (input.files && input.files[0]) { var reader = new FileReader(); reader.onload = function(e) { $('#img-pre').attr('src', e.target.result); $('#img-img').attr('src', e.target.result); }; reader.readAsDataURL(input.files[0]); } } function edit_form() { var email = $("#regemail").val(); var login = $("#reglogin").val(); var pass = $("#regpassword").val(); var vemail = isValidEmailAddress(email); var vlogin = isValidLogin(login); var vpass = isValidLogin(pass); if (vemail || !email) { $("#regemail").css({ "background-color": "#fff" }); } else { $("#regemail").css({ "background-color": "#f88" }); } if (vlogin || !login) { $("#reglogin").css({ "background-color": "#fff" }); } else { $("#reglogin").css({ "background-color": "#f88" }); } if (vpass || !pass) { $("#regpassword").css({ "background-color": "#fff" }); } else { $("#regpassword").css({ "background-color": "#f88" }); } if (1 ) { $('#regsubmit').removeProp('disabled'); } else { $('#regsubmit').prop('disabled', true); } } function isValidEmailAddress(v) { var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i); return (pattern.test(v) && v != ''); } function isValidLogin(v) { var pattern = new RegExp(/[A-Za-z0-9]/); return (pattern.test(v) && v != ''); } $(document).ready(function() { $("#regemail").keyup(edit_form); $("#regemail").focusout(edit_form); $("#reglogin").keyup(edit_form); $("#reglogin").focusout(edit_form); $("#regpassword").keyup(edit_form); $("#regpassword").focusout(edit_form); }); $("#count").on('input keyup', function(e) { var count = +$("#count").val(); var price = +$("#price-m").html(); var coun = +$("#coun-m").html(); if (count
< 1) count=1 ; if (count> coun) count = coun; $("#cena").html(price * count); }); $("#count").change(function() { var count = +$("#count").val(); var coun = +$("#coun-m").html(); if (count
	< 1) { $( "#count").val(1); } if (count> coun) { $("#count").val(coun); } }); function isDel(idd) { var c = confirm("Вы точно хотите удалить этот товар?"); if (c == true) { window.location.href = "insert.php?id=" + idd + "&delete=1" }; } function AjaxFormRequest(result_id, formMain, url) { jQuery.ajax({ url: url, type: "POST", dataType: "html", data: jQuery("#" + formMain).serialize(), success: function(response) { document.getElementById(result_id).innerHTML = response; }, error: function(response) { document.getElementById(result_id).innerHTML = "<b>error</b>"; } }); }
</script>
</body>
</html>
<? if ($link) mysql_close($link); ?>
