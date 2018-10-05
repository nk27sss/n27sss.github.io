<? session_start();
header('Content-Type: text/html; charset=utf-8');

if (!isset($_POST[this_url])) $_POST[this_url]='index.php';

//вывод сообщения
$hash="";

/// совершение покупки пользователем
if ( isset($_SESSION[username]) && isset($_POST[id]) && isset($_POST[on_tar]) && !isset($_SESSION[admin]) ) {
	include ("connect.php");
	$link=Xconnect();



	$id_tar=$_POST[id];
	$on_tar=($_POST[on_tar])?false:true;
	$id_user=$_SESSION[idd];


	$SQL = "SELECT COUNT(*) as num FROM kaz_internet_con_tar WHERE (id_user=$id_user && id_tar=$id_tar)";
	$total = mysql_fetch_array(mysql_query($SQL, $link));
	$total = $total[num];
	if ($total>0 && $on_tar) {
		// отключение тарифа (обозначение неактивным по текущую дату)
		mysql_query("UPDATE kaz_internet_con_tar SET e_date=NOW(), act=0 WHERE (id_user=$id_user && id_tar=$id_tar && act=1)", $link);
	}
	if (!$on_tar) {
		$SQLgetmoney = "SELECT kaz_internet_tariffs.price as `pricetar`,kaz_internet_users.moneyuser as `moneyuser` FROM kaz_internet_tariffs, kaz_internet_users WHERE (kaz_internet_users.id=$id_user && kaz_internet_tariffs.id=$id_tar)";
		$getmoney = mysql_fetch_array(mysql_query($SQLgetmoney, $link));


		//если денег хватает, то подкл, и откл приведущий
		/* не оч корректно, отрубить все тарифы. Надо бы по dev проверку*/
		if ($getmoney[pricetar]<=$getmoney[moneyuser]) {
			mysql_query("UPDATE kaz_internet_users SET moneyuser=".($getmoney[moneyuser]-$getmoney[pricetar])." WHERE (id = $id_user)", $link);
			
			mysql_query("UPDATE kaz_internet_con_tar SET e_date=NOW(), act=0 WHERE (id_user=$id_user && act=1 )", $link); 
			

			mysql_query("INSERT INTO kaz_internet_con_tar (kaz_internet_con_tar.id_tar, kaz_internet_con_tar.id_user, kaz_internet_con_tar.s_date, kaz_internet_con_tar.e_date, kaz_internet_con_tar.price_p) 
			VALUES ($id_tar, $id_user, NOW(), (SELECT  DATE_ADD(NOW(), INTERVAL (kaz_internet_tariffs.coun) DAY ) FROM kaz_internet_tariffs  WHERE (kaz_internet_tariffs.id=$id_tar)), $getmoney[pricetar] )", $link);


		} else {
			$hash = "#_недостаточно_средств";
		}
	}




} else echo "<h4>Вы не вошли</h4>";
if ($link) mysql_close($link);

header("Location: ".$_POST[this_url].$hash);exit;

?>


