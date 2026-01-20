<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}

include $_SESSION['base_dir']."inc/db.orcl.inc.php";
$awal = "1/1/".date('Y');
$akhir = date('d/m/Y');
//$awal = "1/1/2015";
//$akhir = "31/12/2015";
$tahun_sekarang = date('Y');
$stid = oci_parse($conn, "SELECT TO_CHAR(SUM(B.PBB_YG_HARUS_DIBAYAR_SPPT), '999,999,999,999') AS POKOK,SUM(B.PBB_YG_HARUS_DIBAYAR_SPPT) AS POKOKPERSEN,TO_CHAR(SUM(A.DENDA_SPPT), '999,999,999,999') AS DENDA,TO_CHAR(SUM(A.JML_SPPT_YG_DIBAYAR), '999,999,999,999') AS TOTAL FROM PBB.PEMBAYARAN_SPPT A, PBB.SPPT B WHERE
A.TGL_PEMBAYARAN_SPPT BETWEEN TO_DATE('$awal','DD-MM-YYYY') AND TO_DATE('$akhir','DD-MM-YYYY')
and a.KD_PROPINSI=b.KD_PROPINSI 
and a.KD_DATI2=b.KD_DATI2 
and a.KD_KECAMATAN=b.KD_KECAMATAN 
and a.KD_KELURAHAN=b.KD_KELURAHAN 
and a.kd_blok=b.kd_blok 
and a.no_urut=b.no_urut
and a.kd_jns_op=b.kd_jns_op 
and a.THN_PAJAK_SPPT=b.THN_PAJAK_SPPT 
");
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Perform the logic of the query
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
// Fetch the results of the query
$nrows = oci_fetch_all($stid, $res);
//echo "$nrows rows fetched<br>\n";
//print "<table border='1'>\n";
$r = oci_execute($stid);
$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
$pokok = $row['POKOK'];
$pokokp = $row['POKOKPERSEN'];
$denda = $row['DENDA'];
$tot = $row['TOTAL'];

$stid = oci_parse($conn, "SELECT TO_CHAR(SUM(B.PBB_YG_HARUS_DIBAYAR_SPPT), '999,999,999,999') AS POKOK,SUM(B.PBB_YG_HARUS_DIBAYAR_SPPT) AS POKOKPERSEN FROM PBB.SPPT B WHERE
B.THN_PAJAK_SPPT = '$tahun_sekarang' AND B.STATUS_PEMBAYARAN_SPPT < '2'");
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Perform the logic of the query
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$r = oci_execute($stid);
$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
$ketetapan = '1.939.302.672';
$ketetapanp='1939302672';
//$ketetapanp = $row['POKOKPERSEN'];
oci_free_statement($stid);
oci_close($conn);
$persen = ($pokokp/$ketetapanp)*100;
?>
<p>Ketetapan : Rp. <?php echo $ketetapan; ?></p>
<p>Realisasi : Rp. <?php echo $pokok; ?> (<?php echo round($persen,2); ?>%)</p>
<div class="">
	<div class="progress">
		<div class="progress-bar" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $persen; ?>%"><?php echo round($persen,2)."%"; ?></div>										
	</div>
</div>
