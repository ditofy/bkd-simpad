<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	die('Silahkan Login');
}
if (array_key_exists('data', $_POST)) {
        $_SESSION['data'] = urlencode(serialize($_REQUEST['data']));			    
        $msg = 'OK';
    } else {
        $msg = 'No data was supplied';
    }
    Header('Content-Type: application/json; charset=utf8');
    die(json_encode(array('status' => $msg)));
?>