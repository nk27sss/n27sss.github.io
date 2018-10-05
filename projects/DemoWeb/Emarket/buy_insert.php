<? session_start();
header('Content-Type: text/html; charset=utf-8');
$this_url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

/// совершение покупки пользователем
if ( isset($_SESSION[username]) && isset($_POST[id]) && isset($_POST[n_card]) && !isset($_SESSION[admin]) ) {
	include ("connect.php");
	$link=Xconnect();

	$id_prod=$_POST[id];
	$n_card=$_POST[n_card];
	
	// определение покупаемого товара
	if (!isset($_POST[count])) $kol=1; else $kol=$_POST[count];
	if ($kol<1) $kol=1;
	$kol=floor($kol);

	// запрос на количество имеющегося товара
	$SQL = "SELECT * FROM `emark_products` WHERE id=$id_prod";
	$row = mysql_fetch_array(mysql_query($SQL, $link));
	$coun=$row[coun];
	
	// запрос на id пользователя
	$SQL = "SELECT * FROM `emark_users` WHERE login='".$_SESSION[username]."'";
	$row = mysql_fetch_array(mysql_query($SQL, $link));
	$id_user=$row[id];

	if ($kol<=$coun) {
		// запись в покупки
		$SQL = "INSERT INTO `emark_sales` (`id_prod`, `id_user`, `coun`, `s_date`, `n_card`) VALUES ($id_prod, $id_user, $kol, NOW(), '$n_card')"; 
		mysql_query($SQL, $link);

		$SQL = "UPDATE `emark_products` SET `coun`=".($coun-$kol)." WHERE id = $id_prod";
		mysql_query($SQL, $link);
	}

	echo '<script>window.location.href=\'buyslog.php\';</script>';
}
if ($link) mysql_close($link); ?>


<h4>Вы не вошли</h4>