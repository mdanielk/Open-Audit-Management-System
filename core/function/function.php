<?php
/**
*	function.php
*	created by : Metri Daniel Kurnia
**/

function netralize($str){
	$str=str_replace('[^a-z0-9]', "", $str);
	$str=str_replace('<', "&lt;", $str);
	$str=str_replace('>', "&gt;", $str);
	return $str;
}
function base_url($url){
	$str=__HOMEPAGE__.'/'.$url;
	return $str;
}
function check_var($method,$var){
	if ($method=='get'){
		return isset($_GET[$var])?netralize($_GET[$var]):'';
	}
	elseif ($method=='post'){
		return isset($_POST[$var])?netralize($_POST[$var]):'';
	}	
	elseif ($method=='session'){
		return isset($_SESSION[$var])?netralize($_SESSION[$var]):'';
	}	
}

function rupiah($nilai){
	if($nilai!==''){
		return 'Rp. '.number_format($nilai,'0',',','.');
	}
}

function i_rupiah($nilai){
	if($nilai!==''){
		return number_format($nilai,'0',',','.');
	}
}
?>