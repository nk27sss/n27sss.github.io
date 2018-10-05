<? if (isset($_SESSION[admin])) {
//планировщик, какбы

//прошарить каждого пользователя
$users_sql = mysql_query("SELECT id,moneyuser FROM `kaz_internet_users`", $link);
//массив польз
if ($users_sql)
while ($user_link = mysql_fetch_array($users_sql) ) {
	//$user_link[id]-пользователь
	// истекшие тарифы польз, но еще включенные
	$tariffs_old = mysql_query("SELECT id,id_tar FROM kaz_internet_con_tar WHERE (id_user=$user_link[id] && act=1 && e_date<NOW())", $link);
	
	// цикл тарифов
	if ($tariffs_old)
	while($tariffs_old_row = mysql_fetch_array($tariffs_old) )  {
		//$tariffs_old_row[id,id_tar]-id транзакции и id тарифа

		//стоимость тарифа
		$get_price_tar = mysql_query("SELECT price FROM kaz_internet_tariffs WHERE (id=$tariffs_old_row[id_tar])", $link);
		if ($get_price_tar) {
			$get_price_tar_r = mysql_fetch_array($get_price_tar); $price_tar = $get_price_tar_r[price];
		}
		else { $price_tar=0; }

		// если денег хватает
		if ($price_tar<=$user_link[moneyuser]) {
			// отняли деньги
			mysql_query("UPDATE kaz_internet_users SET moneyuser=".($user_link[moneyuser]-$price_tar)." WHERE (id = $user_link[id])", $link);
			
			// получить дату оконч старого тарифа
			$old_date = mysql_fetch_array(mysql_query("SELECT e_date FROM kaz_internet_con_tar WHERE (id_user=$user_link[id] && id_tar=$tariffs_old_row[id_tar] && act=1)", $link));
			
			// откл тариф
			mysql_query("UPDATE kaz_internet_con_tar SET act=0 WHERE (id_user=$user_link[id] && id_tar=$tariffs_old_row[id_tar])", $link);
			
			// , и заново подкл
			mysql_query("INSERT INTO kaz_internet_con_tar (kaz_internet_con_tar.id_tar, kaz_internet_con_tar.id_user, kaz_internet_con_tar.s_date, kaz_internet_con_tar.e_date, kaz_internet_con_tar.price_p) 
			VALUES ($id_tar, $id_user, $old_date[e_date], (SELECT  DATE_ADD($old_date[e_date], INTERVAL (kaz_internet_tariffs.coun) DAY ) FROM kaz_internet_tariffs  WHERE (kaz_internet_tariffs.id=$id_tar)), $getmoney[pricetar] )", $link);

		} else {
			// если денег нехв, то откл тариф
			mysql_query("UPDATE kaz_internet_con_tar SET act=0 WHERE (id_user=$user_link[id] && id_tar=$tariffs_old_row[id_tar])", $link);

		}

	}
}

}?>