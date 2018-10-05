<?
function deleteGET($url, $name, $amp = false) {
	$url = str_replace("&amp;", "&", $url);
	list($url_part, $qs_part) = array_pad(explode("?", $url), 2, "");
	parse_str($qs_part, $qs_vars);
	unset($qs_vars[$name]);
	if (count($qs_vars) > 0) {
		$url = $url_part."?".http_build_query($qs_vars);
		if ($amp) $url = str_replace("&", "&amp;", $url);
	}
	else $url = $url_part;
	return $url;
}

if (isset($_GET[q])) {
	echo "<script>window.location.href=\"".deleteGET($this_url,"q")."\";</script>";
	unset($_SESSION[username]);
	unset($_SESSION[admin]);
}

if ($_POST) {
	if (empty($_POST[login]) || empty($_POST[password])) {
		echo '<script language="JavaScript">window.location.href=\'index.php\';</script>';
		die("Поля логин и пароль обязательные!");
	}
	if ($link){
		if (substr($_POST[login], 0,1)!="@") {
			$SQL = "SELECT * FROM `emark_users`   WHERE ((`login` = '".$_POST[login]."' OR `email` = '".$_POST[login]."') AND `password` = '".$_POST[password]."')";
		}
		else {
			$SQL = "SELECT * FROM `emark_admins`   WHERE (`login` = '".substr($_POST[login], 1)."' AND `password` = '".$_POST[password]."')";			
		}
		$result = mysql_query($SQL, $link);
		$row = mysql_fetch_array($result);
		if (!$result || mysql_num_rows($result) == 0) {
			mysql_close($link);
			echo '<script language="JavaScript">window.location.href=\'index.php\';</script>';
			die("Пользователь не найден!");
			$auth=false;
			unset($_SESSION[username]);
			unset($_SESSION[admin]);
		} else {
			$auth=true;
			$_SESSION[username]=$row[login];
			if (substr($_POST[login], 0,1)=="@") {
				$_SESSION[admin]=true;
				$admin=true;
			}
		}
	}
}	

function modals_auth($this_url) {
echo ('
<div id="login-modal" class="modal fade">
	<div class="modal-dialog">
	<form method="post" action="'.$this_url.'">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h4 class="modal-title">Авторизация</h4>
		</div>
		<div class="modal-body">
			<div class="form-group">
				<label for="E-mail">Логин</label>
				<input type="text" id="login" name="login" class="form-control">
			</div>
			<div class="form-group">
				<label for="Пароль">Пароль</label>
				<input type="password" id="password" name="password" class="form-control">
			</div>
			<div class="form-group">
				<a href="#register-modal" data-toggle="modal" class="btn btn-info btn-xs" onclick=\'$("#login-modal").modal("hide");\'>Зарегистрироватся</a>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
			<button type="submit" class="btn btn-primary">Войти</button>
		</div>
	</div>
	</form>
	</div>
</div>

<div id="register-modal" class="modal fade">
	<div class="modal-dialog">
	<form method="post" action="user.php">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h4 class="modal-title">Регистрация</h4>
		</div>
		<div class="modal-body">
			<div class="form-group">
				<label for="E-mail">ФИО</label>
				<input type="text" id="regname" name="name" class="form-control">
			</div>
			<div class="form-group">
				<label for="E-mail">E-mail</label>
				<input type="text" id="regemail" name="email" class="form-control">
			</div>
			<div class="form-group">
				<label for="E-mail">Логин</label>
				<input type="text" id="reglogin" name="login" class="form-control">
			</div>
			<div class="form-group">
				<label for="Пароль">Пароль</label>
				<input type="password" id="regpassword" name="password" class="form-control">
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
			<button type="submit" class="btn btn-primary" id="regsubmit" >Зарегистрироватся</button>
		</div>
	</div>
	</form>
	</div>
</div>
');
}