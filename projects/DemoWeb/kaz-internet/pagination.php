<?php
//$tableName;
//$limit = 3;

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


if ($limit<=0) $limit=1000;


$targetpage = deleteGET($this_url,'page');
$targetpage .= ((strripos($targetpage,'?') )?"&":"?");

$q_pagination_count = "SELECT COUNT(*) as num FROM $tableName ".$where;
$total_pages = mysql_fetch_array(mysql_query($q_pagination_count));
$total_pages = $total_pages[num];

$stages = 3;
$page = mysql_escape_string($_GET['page']);
if($page){
	$start = ($page - 1) * $limit;
}else{
	$start = 0;
	}   

$q_pagination = "SELECT * FROM $tableName ".$where." ORDER BY ".$order." LIMIT $start, $limit";
$result = mysql_query($q_pagination);

if ($page == 0){$page = 1;}
$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total_pages/$limit);
$LastPagem1 = $lastpage - 1;                    

$paginate = "<div>";
if($lastpage > 1)
{   

	$paginate .= "<ul class='pagination'>";
	if ($page > 1){
		$paginate.= "<li> <a href='".$targetpage."page=$prev'>&laquo;</a></li>";
	}else{
		$paginate.= "<li class='disabled'> <a>&laquo;</a></li>"; }

	if ($lastpage < 7 + ($stages * 2))   
	{
		for ($counter = 1; $counter <= $lastpage; $counter++)
		{
			if ($counter == $page){
				$paginate.= "<li class='active'><a>$counter</a></li>";
			}else{
				$paginate.= "<li> <a href='".$targetpage."page=$counter'>$counter</a></li>";}
		}
	}
	elseif($lastpage > 5 + ($stages * 2))    
	{
		if($page < 1 + ($stages * 2))
		{
			for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
			{
				if ($counter == $page){
					$paginate.= "<li class='active'><a>$counter</a></li>";
				}else{
					$paginate.= "<li> <a href='".$targetpage."page=$counter'>$counter</a></li>";}
			}
			$paginate.= "<li><span>...</span></li>";
			$paginate.= "<li> <a href='".$targetpage."page=$LastPagem1'>$LastPagem1</a></li>";
			$paginate.= "<li> <a href='".$targetpage."page=$lastpage'>$lastpage</a></li>";
		}
		elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
		{
			$paginate.= "<li> <a href='".$targetpage."page=1'>1</a></li>";
			$paginate.= "<li> <a href='".$targetpage."page=2'>2</a></li>";
			$paginate.= "<li><span>...</span></li>";
			for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
			{
				if ($counter == $page){
					$paginate.= "<li class='active'><a>$counter</a></li>";
				}else{
					$paginate.= "<li> <a href='".$targetpage."page=$counter'>$counter</a></li>";}
			}
			$paginate.= "<li><span>...</span></li>";
			$paginate.= "<li> <a href='".$targetpage."page=$LastPagem1'>$LastPagem1</a></li>";
			$paginate.= "<li> <a href='".$targetpage."page=$lastpage'>$lastpage</a></li>";
		}
		else
		{
			$paginate.= "<li> <a href='".$targetpage."page=1'>1</a></li>";
			$paginate.= "<li> <a href='".$targetpage."page=2'>2</a></li>";
			$paginate.= "<li><span>...</span></li>";
			for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
			{
				if ($counter == $page){
					$paginate.= "<li class='active'> <a>$counter</a></li>";
				}else{
					$paginate.= "<li> <a href='".$targetpage."page=$counter'>$counter</a></li>";}
			}
		}
	}
	if ($page < $counter - 1){
		$paginate.= "<li> <a href='".$targetpage."page=$next'>&raquo;</a></li>";
	}else{
		$paginate.= "<li class='disabled'> <a>&raquo;</a></li>";
		}
	$paginate.= "</ul></div>";       
}
//echo $total_pages.'x';
//echo $paginate;
?>
