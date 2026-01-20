<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$tahun_pajak = date('Y');
$awal = "1/1/".date('Y');
$akhir = date('d/m/Y');
$query = "SELECT SUM(bbnkb_pokok) AS r_bbnkb FROM bbnkb.pembayaran_opsen WHERE tgl_bayar = TO_DATE('$akhir','DD-MM-YYYY')";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$data = pg_fetch_array($result);
$pokok = $data['r_bbnkb'];
$query = "SELECT target FROM public.target WHERE thn_pajak='$tahun_pajak' AND kd_obj_pajak='11'";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$data = pg_fetch_array($result);
$target = $data['target'];
$persen = ($pokok/$target)*100;
pg_free_result($result);
pg_close($dbconn);
?>
<p>Target : Rp. <?php echo number_format($target); ?></p>
<p>Realisasi : Rp. <?php echo number_format($pokok); ?> (<?php echo round($persen,2); ?>%)</p>
<div class="">
	<div class="progress">
		<div class="progress-bar" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $persen; ?>%"><?php echo round($persen,2)."%"; ?></div>										
	</div>
</div>
