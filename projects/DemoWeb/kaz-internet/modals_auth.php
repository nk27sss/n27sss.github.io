<div id="login-modal" class="modal fade">
	<div class="modal-dialog">
	<form method="post" action="auth.php">
	<input type="hidden" name="this_url" value="<?=$this_url;?>">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h4 class="modal-title">Авторизация</h4>
		</div>
		<div class="modal-body">
			<div class="form-group">
				<label for="E-mail">Логин(телефон)</label>
				<input type="text" id="login" name="login" class="form-control">
			</div>
			<div class="form-group">
				<label for="Пароль">Пароль</label>
				<input type="password" id="password" name="password" class="form-control">
			</div>
			<div class="form-group">
				<a href="#regpassword-modal" data-toggle="modal" class="btn btn-info btn-xs" onclick='$("#login-modal").modal("hide");\'>Зарегистрироватся</a>
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

<div id="regpassword-modal" class="modal fade">
	<div class="modal-dialog">
	<form method="post" action="auth.php">
	<input type="hidden" name="this_url" value="<?=$this_url;?>">
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
				<label for="E-mail">Логин(телефон)</label>
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
<script type="text/javascript">
	function edit_form() { var email = $("#regemail").val(); var login = $("#reglogin").val(); var pass = $("#regpassword").val(); var vemail = isValidEmailAddress(email); var vlogin = isValidLogin(login); var vpass = isValidLogin(pass); if (vemail || !email) { $("#regemail").css({ "background-color": "#fff" }); } else { $("#regemail").css({ "background-color": "#f88" }); } if (vlogin || !login) { $("#reglogin").css({ "background-color": "#fff" }); } else { $("#reglogin").css({ "background-color": "#f88" }); } if (vpass || !pass) { $("#regpassword").css({ "background-color": "#fff" }); } else { $("#regpassword").css({ "background-color": "#f88" }); } if (1) { $('#regsubmit').removeProp('disabled'); } else { $('#regsubmit').prop('disabled', true); } }

function isValidEmailAddress(v) { var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i); return (pattern.test(v) && v != ''); }

function isValidLogin(v) { var pattern = new RegExp(/[A-Za-z0-9]/); return (pattern.test(v) && v != ''); } $(document).ready(function() { $("#regemail").keyup(edit_form);
	$("#regemail").focusout(edit_form);
	$("#reglogin").keyup(edit_form);
	$("#reglogin").focusout(edit_form);
	$("#regpassword").keyup(edit_form);
	$("#regpassword").focusout(edit_form); });
$("#count").on('input keyup', function(e) {
	var count = +$("#count").val();
	var price = +$("#price-m").html();
	var coun = +$("#coun-m").html();
	if (count <
		1) count = 1;
	if (count > coun) count = coun;
	$("#cena").html(price * count);
});
$("#count").change(function() {
	var count = +$("#count").val();
	var coun = +$("#coun-m").html();
	if (count <
		1) { $("#count").val(1); }
	if (count > coun) { $("#count").val(coun); }
});

</script>