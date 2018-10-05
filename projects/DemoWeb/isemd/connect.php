<?
function Xconnect() {
	
	/* ConnectAllDemoWeb */
	include "../connect.php";
	$link = XconnectAllDemoWeb() or die('Ошибочка: ' . mysql_error());

	/*$_host = "localhost";
	$_login = "root";
	$_pass = "";
	$_db = "isemd";
	$link = mysql_connect($_host, $_login, $_pass);
	mysql_query("set NAMES utf8");
	mysql_query("SET NAMES 'utf8'"); 
	mysql_query("SET CHARACTER SET 'utf8'");
	mysql_query("SET SESSION collation_connection = 'utf8_general_ci'");
	mysql_select_db($_db, $link) or die('нет бд' . mysql_error());
	/**/
	return $link;
}
?>