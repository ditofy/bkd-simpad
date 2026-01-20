<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
if (array_key_exists('npwpd', $_POST)) {
        $_SESSION['npwpd'] = $_POST['npwpd'];
		$_SESSION['hal'] = $_POST['hal'];
        // Let us let the client know what happened
        $msg = 'OK';
    } else {
        $msg = 'No data was supplied';
    }
    Header('Content-Type: application/json; charset=utf8');
    die(json_encode(array('status' => $msg)));
?>