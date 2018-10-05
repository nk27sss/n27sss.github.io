<?if ($auth) {
if (!$admin) { ?>

<div class="container">
<div class="panel panel-primary">
	<div class='panel-heading' style="padding:15px">
		<span style='padding-right:20px;'> <b>Ваши данные</b> </span>	
	</div>
	<div class='panel-body'>
		<b>Имя:</b> <span><?=$user_row[name];?></span><br>
		<b>Логин:</b> <span><?=$user_row[login];?></span><br>
		<b>E-mail:</b> <span><?=$user_row[email];?></span><br>
		<b>IP:</b> <span><?=$_SESSION[ip];?></span><br><br>
	</div>

</div>
</div>

<? } else { ?>

<div class="container" style="width: 100%">
<div class="panel panel-primary">

<div class='panel-heading' style="padding:15px">
	<span style='padding-right:20px;'> <b>Пользователи</b> </span>	
</div>
<div class='panel-body'>
<?
	$result = mysql_query("SELECT * FROM kaz_internet_users ");
	echo "<table class='tablesorter'><thead><tr>
		<th>№</th>
		<th>ФИО</th>
		<th>Логин</th>
		<th>E-mail</th>
		<th>Счет</th>
		</tr></thead><tbody>";
	while ($row = mysql_fetch_array($result)) {
		echo "<tr>
		<td>$row[id]</td>
		<td>$row[name]</td>
		<td>$row[login]</td>
		<td>$row[email]</td>
		<td>$row[moneyuser]</td>
		</tr>";
	}
	echo "</tbody></table>";

?> 


</div>
</div>
</div>
<? } ?>

<? } ?>