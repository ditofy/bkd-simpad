<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
function no_reg($no)
{
	$pjg_char = strlen($no);
	switch ($pjg_char) {
		case 1:
			$add = "000";
			break;
		case 2:
			$add = "00";
			break;
		case 3:
			$add = "0";
			break;
		case 4:
			$add = "";
			break;
	}
	return $add.$no;
}
?>