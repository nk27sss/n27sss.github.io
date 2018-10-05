<?
//Если есть переменная 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
include('connect.php');
$link=Xconnect();

if (isset($_POST[id])) {/*отсутствует защита*/

	if ($_POST[type]=='act') {
		$id=$_POST[id];
		if (isset($_POST[act])) {
			$act=$_POST[act];
			$SQL = "UPDATE kaz_internet_tariffs SET
			act = $act
			WHERE id = $id";
			mysql_query($SQL, $link);
			?>
				<input type='hidden' name='type' value='act'>
				<input type='hidden' name='id' value='<?=$id;?>'>
				<input type='hidden' name='act' value='<?=($act)?0:1;?>'>
				<button class='btn btn-primary glyphicon <?=($act)?"glyphicon-eye-open":"glyphicon-eye-close";?>'
					<?="onclick='AjaxFormRequest(\"form-ok-$id\",\"form-ok-$id\",\"update.php\"); return false;'";?>
					title='Активность'></button>
			<?
		}
	}


	mysql_close($link);
}}
?>