<? session_start();
header('Content-Type: text/html; charset=utf-8');
// auth.php?this_url="index.php"

if (isset($_GET) || isset($_POST) ) {
//____________________________________//

function isValidLogin($v) { return preg_match("#^[aA-zZ0-9\-_]+$#",$v);  }
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

if (isset($_GET[this_url]) )  $this_url = $_GET[this_url];
if (isset($_POST[this_url]) ) $this_url = $_POST[this_url];


// закрыть сессию если GET[q]
if (isset($_GET[q])) {
	unset($_SESSION[idd]);
	unset($_SESSION[username]);
	unset($_SESSION[admin]);
	unset($_SESSION[ip]);
	header("Location: ".$this_url);exit;
}

// соеденение
include('connect.php');
$link=Xconnect();

//вывод сообщения
$hash="";


if (isset($_POST[login]) && isset($_POST[password]) ) {

	// если все поля, то регистрация
	if (isset($_POST[email]) && isset($_POST[name]) ) { 
		
		unset($_SESSION[username]);
		unset($_SESSION[admin]);
		unset($_SESSION[idd]);
		unset($_SESSION[ip]);
			// валидация
		if ( isValidLogin($_POST[login]) && isValidLogin($_POST[password]) ) {
			
			// проверка уникальности
			$result = mysql_query("SELECT * FROM `kaz_internet_users`   WHERE `login` = '".$_POST[login]."' ");
			$row = mysql_fetch_array($result);

			if (mysql_num_rows($result) == 0) {
				$SQL = "INSERT INTO `kaz_internet_users` (`login`, `password`, `email`, `name` )
					VALUES ('$_POST[login]', '$_POST[password]', '$_POST[email]', '$_POST[name]' ) ";
				mysql_query($SQL);
				$_SESSION[username]=$_POST[login];
				$_SESSION[ip] = get_ip();
				$hash = "";	//_Вы_зарегестрировались!!!

			} else { $hash = "#_Такой_логин_уже_есть!"; }

		} else { $hash = "#_Неверные_логин_или_пароль!"; }

	} else {

		// авторизация
		unset($_SESSION[username]);
		unset($_SESSION[admin]);
		unset($_SESSION[idd]);
		unset($_SESSION[ip]);
		// @ - админ, нет - пользователь
		if (substr($_POST[login], 0,1)!="@") {
			$SQL = "SELECT * FROM `kaz_internet_users` WHERE ((`login`='".$_POST[login]."' || `email`='".$_POST[login]."') && `password`='".$_POST[password]."')";
		}	else {
			$SQL = "SELECT * FROM `kaz_internet_admins` WHERE (`login`='".substr($_POST[login], 1)."' && `password`='".$_POST[password]."')";
		}
		$result = mysql_query($SQL, $link);
		$row = mysql_fetch_array($result);
		
		if (!$result || mysql_num_rows($result) == 0) {
			
			// если нет пользователя
			unset($_SESSION[username]);
			unset($_SESSION[idd]);
			unset($_SESSION[ip]);

			$hash = "#_Пользователь_не_найден!";
		} else {
			
			// если все ок
			$_SESSION[username]=$row[login];
			$_SESSION[ip] = get_ip();
			if (substr($_POST[login], 0,1)=="@") {
				$_SESSION[admin]=true;
			}
			$_SESSION[idd]=(!$_SESSION[admin]) ? $row[id]: -1;
			$hash = "";	//_Вы_вошли!!!
		}
	}
} else { $hash = "#_Поля_логин_и_пароль_обязательные!"; }









if ($link) mysql_close($link);
header("Location: ".$this_url.$hash);exit;

} //____________________________________//
?>