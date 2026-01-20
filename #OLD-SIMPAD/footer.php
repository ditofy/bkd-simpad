<?php
date_default_timezone_set('Asia/Jakarta');
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
?>
<footer>
<div class="">
<p class="pull-right">R.BETA.2 DPPKA KOTA PAYAKUMBUH<br />
&copy; 2015-<?php echo date('Y'); ?> All Rights Reserved
</p>
</div>
<div class="clearfix"></div>
</footer>