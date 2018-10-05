<?
if ($auth) {
if (!$admin) {
?>
<div class="container">
<?
function devtext($n) {
	switch ($n) {
		case 1:	return '<span class="glyphicon glyphicon-th-large"></span> Все платформы';break;
		case 2:	return '<span class="glyphicon glyphicon-globe"></span> Интернет';break;
		case 3:	return '<span class="glyphicon glyphicon-phone"></span> Мобильная связь';break;
		case 4:	return '<span class="glyphicon glyphicon-film"></span> Телевидение';break;
		case 5:	return '<span class=""></span> Другой';break;
		default: return 'none';break;
	}
}


$_ok=" class='glyphicon glyphicon-ok pull-right'";
$_de=" ";
$iss=($_GET[s])?"s=$_GET[s]&":"";
$isf=($_GET[f])?"f=$_GET[f]&":"";
$isd=($_GET[d])?"d=$_GET[d]&":"";
$isp=($_GET[p])?"p=$_GET[p]&":"";





	$SQL = "SELECT kaz_internet_con_tar.id AS idu,
				kaz_internet_tariffs.id AS id,
				kaz_internet_tariffs.title AS title,
				kaz_internet_tariffs.description AS description,
				kaz_internet_tariffs.dev AS dev,
				kaz_internet_tariffs.coun AS coun,

				kaz_internet_con_tar.id_tar AS id_tar,
				kaz_internet_con_tar.price_p AS price,
				kaz_internet_con_tar.s_date AS s_date,
				kaz_internet_con_tar.e_date AS e_date,
				kaz_internet_con_tar.act AS acttar
		FROM kaz_internet_con_tar, kaz_internet_tariffs, kaz_internet_users 
		WHERE (kaz_internet_con_tar.id_tar = kaz_internet_tariffs.id && kaz_internet_con_tar.id_user = kaz_internet_users.id && kaz_internet_users.login = '".$_SESSION[username]."')
		ORDER BY acttar desc";	




	$result = mysql_query($SQL);
	$nutar = true;
	if ($result)
	while($row = mysql_fetch_array($result))  { 
	if ($nutar==true) $nutar = false; 	?>



		<div class="panel panel-primary"  <?=(!$row[acttar])?'style="border-color: #9AB;"':""; ?>  >
		<div id='head-<?=$row[id]?>' class='panel-heading' style="padding:15px;<?=(!$row[acttar])?"background: #9AB;":"" ;?>"  >
		<div class="row">	<div class="col-md-5">
			<span style='font-size: 16px; margin-top: 5px;'>
				<?if ($admin):?><span style='margin-right: 2px;'><?=$row[id];?>) </span><?endif;?>
				<span id='title-<?=$row[id]?>'> <?=$row[title]?> </span>
			</span>
		</div>
		<div class="col-md-7">
		<span class='col-md-7' style='margin-top:-5px;'>
			<div class='row'>
				<div class='col-md-6'><table><tr><td><b>Дата подкл.</b></td></tr><tr><td><?=$row[s_date];?> </td></tr></table></div>
				<div class='col-md-6'><table><tr><td><b>Дата оконч.</b></td></tr><tr><td><?=$row[e_date];?> </td></tr></table></div>	
			</div>
		</span>

			<?if ($auth && !$admin && $row[acttar]):?>
			
			<span class='pull-right' style='margin-left: 5px; margin-top: 5px'>
				<form id='form-on-<?=$row[id];?>' enctype='multipart/form-data' action='buy_insert.php' method='post'>
				<input type='hidden' name='id' value='<?=$row[id];?>'>
				<input type='hidden' name='this_url' value='<?=$this_url?>'>
				<input type='hidden' name='on_tar' value='<?=(true)?0:1;?>'>
				<div class='swbutton'>
					<input type='checkbox' id='swbutton-on-<?=$row[id];?>' <?=(true)? "checked":"";?> 
					onclick='isAdd(<?=$row[id]?>);'>
					<label><i></i></label>
				</div>
				</form>
			</span>

		<?endif;?>

			<span class="pull-right" style='margin-left: 10px; margin-right: 10px; margin-top: 5px; color: #ff0; font-weight: bold;font-size: 16px;'> <span id='price-<?=$row[id]?>'><?=$row[price]?></span> Тг. </span>

		</div></div>

		</div>
		<div class='panel-body' id='body-<?=$row[id]?>'>
			<div class='row'>
				<div class='col-md-7 mycontent-left'>
						<p id="description-<?=$row[id]?>"><?=$row[description]?></p> 
				</div>
				<div class='col-md-5'>
					<b>Время действия:</b> <span id="coun-<?=$row[id]?>"><?=$row[coun];?></span> дн.<br>
					<b>Платформа:</b> <span id="dev-<?=$row[id]?>"><?=devtext($row[dev])?></span>
				</div>
			</div>
		</div>
	</div>	
	<?
	}
	if ($nutar==true) {echo "<b>Тарифы отсутствуют. <a href='index.php'>Подключить</a></b>";}
	?>
</div>
<br>




<? } else { ?>
<div class="container" style="width: 100%;min-width: 550px;">
<div class="panel panel-primary"style="padding-left: 2px; padding-right: 2px">
<?

$query = "SELECT kaz_internet_con_tar.id AS id,
				kaz_internet_tariffs.id AS idt,
				kaz_internet_users.name AS fio,
				kaz_internet_tariffs.title AS title,
				kaz_internet_users.login AS phone,
				kaz_internet_con_tar.price_p AS price_p,
				kaz_internet_con_tar.s_date AS s_date,
				kaz_internet_con_tar.e_date AS e_date,
				kaz_internet_con_tar.act AS acttar

		FROM kaz_internet_con_tar, kaz_internet_tariffs, kaz_internet_users 
		WHERE (kaz_internet_con_tar.id_tar = kaz_internet_tariffs.id && kaz_internet_con_tar.id_user = kaz_internet_users.id)
		ORDER BY acttar desc";

$result = mysql_query($query);
	echo "<table class='tablesorter'><thead><tr>
		<th style='width: 20px;'>№</th>
		<th>Логин</th>
		<th>Тариф</th>
		<th>Дата начала</th>
		<th>Дата окончания</th>
		<th>Оплоченно</th>
		<th>Текущий тариф</th>
		<th>Перейти к тарифу</th>

		</tr></thead><tbody>";
	while ($row = mysql_fetch_array($result)) {
		echo "<tr>
		<td>$row[id]</td>
		<td title='$row[idt]'>$row[phone]</td>
		<td title='$row[idt]'>$row[title]</td>
		<td>$row[s_date]</td>
		<td>$row[e_date]</td>
		<td>$row[price_p]</td>
		<td>".(($row[acttar]==1)?"да":"нет")."</td>
		<td><a href='index.php?f=%20$row[idt]'>Перейти к тарифу  <span class='glyphicon glyphicon-share'></span></a></td>
		</tr>";
	}
	echo "</tbody></table>";

?>
</div>
</div>
<? }
} ?>