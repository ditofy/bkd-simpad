<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}

include $_SESSION['base_dir']."inc/db.orcl.inc.php";
$awal = "1/1/".date('Y');
$akhir = date('d/m/Y');
$tahun_sekarang = date('Y');
//$awal = "1/1/2015";
//$akhir = "31/12/2015";
//$tahun_sekarang = '2015';
$stid = oci_parse($conn, "SELECT SUM(B.PBB_YG_HARUS_DIBAYAR_SPPT) AS POKOK,SUM(A.DENDA_SPPT) AS DENDA FROM PBB.PEMBAYARAN_SPPT A, PBB.SPPT B WHERE
A.TGL_PEMBAYARAN_SPPT = TO_DATE('$akhir','DD-MM-YYYY') 
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
//echo "$nrows rows fetched<br>\n";
//print "<table border='1'>\n";
$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
$pokok = $row['POKOK'];
$pokokp = $row['POKOK'];
$denda = $row['DENDA'];
$tot = $row['TOTAL'];

$stid = oci_parse($conn, "SELECT SUM(B.PBB_YG_HARUS_DIBAYAR_SPPT) AS POKOK FROM PBB.SPPT B WHERE
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
$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
$ketetapan = $row['POKOK'];
$ketetapanp = $row['POKOK'];

$stid = oci_parse($conn, "SELECT SUM(B.PBB_YG_HARUS_DIBAYAR_SPPT) AS POKOK,SUM(A.DENDA_SPPT) AS DENDA FROM PBB.PEMBAYARAN_SPPT A, PBB.SPPT B WHERE A.TGL_PEMBAYARAN_SPPT=TO_DATE('$akhir','DD-MM-YYYY')
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
$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
$pokok_hr_ini = $row['POKOK'];
$denda_hr_ini = $row['DENDA'];
$tot_hr_ini = $pokok_hr_ini+$denda_hr_ini;
oci_free_statement($stid);
oci_close($conn);
$persen = ($pokokp/$ketetapanp)*100;

?>
<table border="0">
<tr>

<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>

</tr>
<tr>
<td>
Pokok&nbsp;:
</td>
<td align="right">
 <?php echo number_format($pokok_hr_ini); ?>
</td>
<td>
&nbsp;
</td>
<td>
Denda&nbsp;:
</td>
<td align="right">
&nbsp; <?php echo number_format($denda_hr_ini); ?>
</td>
<td>&nbsp;</td>
<td>
Total&nbsp;:
</td>
<td align="right">
&nbsp; <?php echo number_format($tot_hr_ini); ?>
</td>
</tr>
<tr>

</tr>
</table>
<p>(<?php echo round($persen,2); ?>%)</p>
<div class="">
	<div class="progress">
		<div class="progress-bar" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $persen; ?>%"><?php echo round($persen,2)."%"; ?></div>										
	</div>
</div>
