<?

function XconnectAllDemoWeb() {
	if (true) {
		$_host = "localhost";
		$_login = "root";
		$_pass = "";
		$_db = "n27sss";
		/*denwer*/
	} else {
		$_host = "localhost:3306";
		$_login = "id6434022_n27sss";
		$_pass = "nk2048nk";
		$_db = "id6434022_db";
		/*ru.000webhost.com*/
	}

	$link = mysql_connect($_host, $_login, $_pass);
	// utf8
	mysql_query("set NAMES utf8");
	mysql_query("SET NAMES 'utf8'"); 
	mysql_query("SET CHARACTER SET 'utf8'");
	mysql_query("SET SESSION collation_connection = 'utf8_general_ci'");
	if (!$link || !mysql_select_db($_db, $link)) { return false; }
	return $link;
}
?>