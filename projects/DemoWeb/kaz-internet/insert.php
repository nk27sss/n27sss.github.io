<? session_start();
header('Content-Type: text/html; charset=utf-8');
$this_url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

if (isset($_SESSION[admin]) ) {

	$id=$_POST[id];
	$delete=$_POST[delete];
	if (isset($_GET[id]) && isset($_GET[delete])) {
		$id=$_GET[id];
		$delete=$_GET[delete];
	}
	$act = (isset($_POST[act]) )?1:0;

	include ("connect.php");
	$link=Xconnect();



	$SQL="";
	if (!isset($id)) {
		$SQL = "INSERT INTO `kaz_internet_tariffs` (`title`,`price`,`dev`,`coun`,`description`,`act`) 
		VALUES ('$_POST[title]', '$_POST[price]','$_POST[dev]', '$_POST[coun]','$_POST[description]',$act ) "; 
	} elseif ( $delete==1 ) {
		$SQL = "DELETE FROM `kaz_internet_tariffs` WHERE `id`=$id"; 

	} elseif ( isset($_POST[title]) || isset($_POST[price]) || isset($_POST[dev]) || isset($_POST[coun]) || isset($_POST[description]) ) {
		$SQL = "UPDATE `kaz_internet_tariffs` SET  ";
		if (isset($_POST[title]) ) $SQL .= " `title`='$_POST[title]',";
		if (isset($_POST[price]) ) $SQL .= " `price`='$_POST[price]',";
		if (isset($_POST[dev]) ) $SQL .= " `dev`='$_POST[dev]',";
		if (isset($_POST[coun]) ) $SQL .= " `coun`='$_POST[coun]',";
		if (isset($_POST[description]) ) $SQL .= " `description`='$_POST[description]',";

		
		$SQL=substr($SQL, 0,strlen($SQL)-1);

		$SQL .= " WHERE id = $_POST[id]";
	}

	mysql_query($SQL, $link);

	$SQL = "SELECT COUNT(*) as num FROM `kaz_internet_tariffs`";
	$total_pages = mysql_fetch_array(mysql_query($SQL));
	$total_pages = $total_pages[num];

	mysql_close($link);

	if (!isset($_POST[id])) {
		header("Location: index.php?page=".ceil($total_pages/3)); exit;
	} else {
		header("Location: index.php?f=*".$_POST[id]); exit;
	}
	

if ($link) mysql_close($link);
} else {header("Location: index.php");exit;}
?>