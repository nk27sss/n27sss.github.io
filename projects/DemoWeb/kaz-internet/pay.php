<?if (isset($_POST[username]) && isset($_POST[moneyuser]) ) {
	
	// Demo (free) moneycard
	include('connect.php');
	$link=Xconnect();
	$SQL = "UPDATE kaz_internet_users SET moneyuser=(moneyuser+$_POST[moneyuser]) WHERE (login = $_POST[username])";
	mysql_query($SQL, $link);

if ($link) mysql_close($link);
header("Location: ".$_POST[this_url]);exit;

} else { ?>

<div class="container">
<div class="panel panel-primary">

	<? if (!$admin) { ?>

	<div class='panel-heading' style="padding:15px">
		<span style='padding-right:20px;'> <b>Ваш баланс</b> </span>	<span style='color: #ff0; font-weight: bold;'> <span><?=$moneyuser;?></span> Тг. </span>
	</div>
	<div class='panel-body'>
		<ul class="nav nav-tabs" id="payformli">
			<li id="payform-mcardli" role="presentation">
				<a href="#" onclick="togglepay('payform-mcard');" role="tab" data-toggle="tab">
					<img src="img/pay_icon/mcard.png" width=64 height=64></a>
			</li>
			<li id="payform-qiwili" role="presentation">
				<a href="#" onclick="togglepay('payform-qiwi');" role="tab" data-toggle="tab">
					<img src="img/pay_icon/qiwi.png" width=64 height=64></a>
			</li>
			<li id="payform-wmoneyli" role="presentation">
				<a href="#" onclick="togglepay('payform-wmoney');" role="tab" data-toggle="tab">
					<img src="img/pay_icon/wmoney.png" width=64 height=64></a>
			</li>
			<li id="payform-bitcoinli" role="presentation">
				<a href="#" onclick="togglepay('payform-bitcoin');" role="tab" data-toggle="tab">
					<img src="img/pay_icon/bitcoin.png" width=64 height=64></a>
			</li>
			<li id="payform-demoli" role="presentation">
				<a href="#" onclick="togglepay('payform-demo');" role="tab" data-toggle="tab">
					<img src="img/pay_icon/demo.png" width=64 height=64></a>
			</li>
		</ul><br>
 		<div id='contenpay'></div> 







	</div>



	
	<?} else {?>

	<div class='panel-heading' style="padding:15px">
		<span style='padding-right:20px;'> <b>Управление счетами пользователей</b> </span>
	</div>
	<div class='panel-body'>
		 <br>
		<form method="POST" enctype="multipart/form-data" action="pay.php">
			<input type="hidden" name="this_url" value="<?=$this_url;?>">
			<div class="col-md-8">
				<div class="row">
					<div class="col-md-12" class="pull-center"><span><b>Пополнить счет.</b></span></div><hr>
				</div>
				<div class="row">
					<div class="col-md-5"><span class="pull-right">Логин пользователя</span></div>
					<div class="col-md-7"><input type="text" name="username" class="form-control" value=""></div>
				</div>
				<div class="row">
					<div class="col-md-5"><span class="pull-right">Сумма</span></div>
					<div class="col-md-7"><input type="text" name="moneyuser" class="form-control" value="1000.00"></div>
				</div>
				<div class="row">
					<div class="col-md-5"><br></div>
					<div class="col-md-7"><input type="submit" class="btn btn-success form-control" value="Пополнить"></div>
				</div>
			</div>
		</form>
		<br>
	</div>
	<? } ?>

</div>
</div>


<div id='payform-mcard' style='display: none'>
	<div class="col-md-8">
		<div class="row">
			<div class="col-md-12" class="pull-center"><span><b>Пополнить счет.</b></span></div><hr>
		</div>
		<div class="row">
			<div class="col-md-12"><span style="color:red">&#9888;</span> <b>требуется подключить модуль оплаты payform-mcard.js</b></div>
		</div>
	</div>
</div>

<div id='payform-qiwi' style='display: none'>
	<div class="col-md-8">
		<div class="row">
			<div class="col-md-12" class="pull-center"><span><b>Пополнить счет.</b></span></div><hr>
		</div>
		<div class="row">
			<div class="col-md-12"><span style="color:red">&#9888;</span> <b>требуется подключить модуль оплаты payform-qiwi.js</b></div>
		</div>
	</div>
</div>

<div id='payform-wmoney' style='display: none'>
	<div class="col-md-8">
		<div class="row">
			<div class="col-md-12" class="pull-center"><span><b>Пополнить счет.</b></span></div><hr>
		</div>
		<div class="row">
			<div class="col-md-12"><span style="color:red">&#9888;</span> <b>требуется подключить модуль оплаты payform-wmoney.js</b></div>
		</div>
	</div>
</div>

<div id='payform-bitcoin' style='display: none'>
	<div class="col-md-8">
		<div class="row">
			<div class="col-md-12" class="pull-center"><span><b>Пополнить счет.</b></span></div><hr>
		</div>
		<div class="row">
			<div class="col-md-12"><span style="color:red">&#9888;</span> <b>требуется подключить модуль оплаты payform-bitcoin.js</b></div>
		</div>
	</div>
</div>

<div id='payform-demo' style='display: none'>
	<form method="post" enctype="multipart/form-data" action="pay.php">
		<input type="hidden" name="this_url" value="<?=$this_url;?>">
		<input type="hidden" name="username" class="form-control" value="<?=$_SESSION[username];?>">
		<div class="col-md-8">
			<div class="row">
				<div class="col-md-12" class="pull-center"><span><b>Пополнить счет.</b></span></div><hr>
			</div>
			<div class="row">
				<div class="col-md-5"><span class="pull-right">Сумма</span></div>
				<div class="col-md-7"><input type="text" name="moneyuser" class="form-control" value="1000.00"></div>
			</div>
			<div class="row">
				<div class="col-md-5"><br></div>
				<div class="col-md-7"><input type="submit" class="btn btn-success form-control" value="Пополнить"></div>
			</div>
		</div>
	</form>
</div> 


<script type="text/javascript">
function togglepay(id) {  
	document.getElementById("contenpay").innerHTML = document.getElementById(id).innerHTML;
	$('#payformli li').removeClass('active');
	$('#'+id+'li ').addClass('active');	
}  


</script>
<? } ?>



