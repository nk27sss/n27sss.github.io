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
	include ("connect.php");
	$link=Xconnect();

	// Функция изменения размера
	function resize($file, $quality = null) {
		$width = 320;
		$height = 240;
	
		if ($quality == null)
			$quality = 75;

		if ($file['type'] == 'image/jpeg')
			$source = imagecreatefromjpeg($file['tmp_name']);
		elseif ($file['type'] == 'image/png')
			$source = imagecreatefrompng($file['tmp_name']);
		elseif ($file['type'] == 'image/gif')
			$source = imagecreatefromgif($file['tmp_name']);
		else
			return false;
			
		$src = $source;

		$w_src = imagesx($src); 
		$h_src = imagesy($src);

		// Если ширина больше заданной
		if ($w_src > $width || $h_src > $height)
		{
			$ratioWidth = $w_src/$width;
			$ratioHeight = $h_src/$height;

			if($ratioWidth < $ratioHeight)  {
				$w_dest = intval($w_src/$ratioHeight);
				$h_dest = $height;
			}
			else {
				$w_dest = $width;
				$h_dest = intval($h_src/$ratioWidth);
			}

			// Создаём пустую картинку
			$dest = imagecreatetruecolor($w_dest, $h_dest);
			
			// Копируем старое изображение в новое с изменением параметров
			imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);

			// Вывод картинки и очистка памяти
			imagejpeg($dest, $file['tmp_name'], $quality);
			imagedestroy($dest);
			imagedestroy($src);

			return $file['tmp_name'];
		}
		else
		{
			imagejpeg($src, $file['tmp_name'], $quality);
			imagedestroy($src);

			return $file['tmp_name'];
		}
	}

	$img_tit="";
	$img="";
	$imgi="";
	$path = 'i/';
	$types = array('image/gif', 'image/png', 'image/jpeg');
	$size = 2048000;
	
	if ($_FILES[img][error] == 0 
	&& $_FILES['picture']['size'] <= $size
	&& in_array($_FILES[img][type], $types) ) {

		$name = resize($_FILES[img]);

		$img = file_get_contents( $name );
		$img = mysql_escape_string( $img );

		$img_tit=", `img`";
		$imgi =  ",'".$img."'"; 

		unlink($name);           
	};

	$SQL="";
	if (!isset($id)) {
		$SQL = "INSERT INTO `emark_products` (`title`,`price`,`fab`,`coun`,`description` $img_tit) 
		VALUES ('$_POST[title]', '$_POST[price]','$_POST[fab]', '$_POST[coun]','$_POST[description]' $imgi)"; 
	} elseif ( $delete==1 ) {
		$SQL = "DELETE FROM `emark_products` WHERE `id`=$id"; 

	} elseif ( isset($_POST[title]) || isset($_POST[price]) || isset($_POST[fab]) || isset($_POST[coun]) || isset($_POST[description]) ) {
		$SQL = "UPDATE `emark_products` SET  ";
		if (isset($_POST[title]) ) $SQL .= " `title`='$_POST[title]',";
		if (isset($_POST[price]) ) $SQL .= " `price`='$_POST[price]',";
		if (isset($_POST[fab]) ) $SQL .= " `fab`='$_POST[fab]',";
		if (isset($_POST[coun]) ) $SQL .= " `coun`='$_POST[coun]',";
		if (isset($_POST[description]) ) $SQL .= " `description`='$_POST[description]',";
		if ($img<>'' ) $SQL .= " `img`='$img',";
		
		$SQL=substr($SQL, 0,strlen($SQL)-1);

		$SQL .= " WHERE id = $_POST[id]";
	}

	mysql_query($SQL, $link);

	$SQL = "SELECT COUNT(*) as num FROM `emark_products`";
	$total_pages = mysql_fetch_array(mysql_query($SQL));
	$total_pages = $total_pages[num];

	mysql_close($link);

	if (!isset($_POST[id])) {
		echo '<script>window.location.href=\'index.php?page='.ceil($total_pages/3).'\';</script>';
	} else {
		echo '<script>window.location.href=\'index.php?f=*'.$_POST[id].'\';</script>';
	}

if ($link) mysql_close($link);
} else {
	
	echo '<script>window.location.href=\'index.php\';</script>';
}
?>