<?php
/*if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}*/
$bdir = pg_escape_string($_GET['bdir']);
function selisih($jam_keluar) {
$jam_selesai=date("H:i:s",mktime(date("H",strtotime($jam_keluar))-0,date("i",strtotime($jam_keluar))-0,date("s",strtotime($jam_keluar)),0,0,0));
return $jam_selesai;
}
include $bdir."inc/db.orcl.inc.php";
$jns_rincian = pg_escape_string($_GET['jns-rincian']);
$tgl_bayar = pg_escape_string($_GET['tgl-pembayaran']);
?>
<style type="text/css">
table, tr, td, th, tbody, thead, tfoot {
    page-break-inside: avoid !important;
	font-family: "Verdana";
}
</style>
<?php
echo "<title>Penerimaan PBB</title>";
$total_pokok = 0;
$total_denda = 0;
$total = 0;
if ($jns_rincian == 'Detail') {
$stid = oci_parse($conn,"SELECT A.KD_PROPINSI||'.'||A.KD_DATI2||'.'||A.KD_KECAMATAN||'.'||A.KD_KELURAHAN||'.'||A.KD_BLOK||'.'||A.NO_URUT||'.'||A.KD_JNS_OP AS NOP,B.NM_WP_SPPT AS NAMA_WP,A.THN_PAJAK_SPPT AS THN_PAJAK,B.PBB_YG_HARUS_DIBAYAR_SPPT AS POKOK,A.DENDA_SPPT AS DENDA,
A.JML_SPPT_YG_DIBAYAR AS JML_BAYAR, TO_CHAR(A.TGL_REKAM_BYR_SPPT,'HH24:MI:SS') AS JAM,
A.JML_SPPT_YG_DIBAYAR AS TOTAL,B.PBB_YG_HARUS_DIBAYAR_SPPT AS TOT_POKOK,A.DENDA_SPPT AS TOT_DENDA
FROM PBB.PEMBAYARAN_SPPT A, PBB.SPPT B
WHERE A.TGL_PEMBAYARAN_SPPT=TO_DATE('$tgl_bayar','DD-MM-YYYY')
and a.KD_PROPINSI=b.KD_PROPINSI 
and a.KD_DATI2=b.KD_DATI2 
and a.KD_KECAMATAN=b.KD_KECAMATAN 
and a.KD_KELURAHAN=b.KD_KELURAHAN 
and a.kd_blok=b.kd_blok 
and a.no_urut=b.no_urut
and a.kd_jns_op=b.kd_jns_op 
and a.THN_PAJAK_SPPT=b.THN_PAJAK_SPPT 
and a.KD_PROPINSI='13' 
and a.kd_dati2='76'
order by A.TGL_REKAM_BYR_SPPT");
?>
<table border="1" style="font-size:12px;border:1px solid black;border-collapse:collapse;" align="center" cellpadding="2px" width="100%">
<tr class="headings">
	<td colspan="8" align="center"><h3>Detail Pembayaran PBB-P2 Kota Payakumbuh<br>Tanggal : <?php echo $tgl_bayar; ?></h3></td>
</tr>
<tr class="headings">                                         
	<td align="center">NO </td>
	<td align="center">JAM </td>
	<td align="center">NOP </td>
    <td align="center">Nama WP </td>
    <td align="center">THN PAJAK </td>
    <td align="center">POKOK </td>
	<td align="center">DENDA </td>
	<td align="center">JML BAYAR </td>                                          
</tr>
<?php
} else {
$stid = oci_parse($conn,"SELECT A.THN_PAJAK_SPPT AS THN_PAJAK,SUM(A.JML_SPPT_YG_DIBAYAR-A.DENDA_SPPT) AS POKOK,SUM(A.DENDA_SPPT) AS DENDA,
SUM(A.JML_SPPT_YG_DIBAYAR) AS JML_BAYAR
FROM PBB.PEMBAYARAN_SPPT A
WHERE A.TGL_PEMBAYARAN_SPPT=TO_DATE('$tgl_bayar','DD-MM-YYYY')
GROUP BY A.THN_PAJAK_SPPT
order by A.THN_PAJAK_SPPT");
?>
<table border="1" style="font-size:12px;border:1px solid black;border-collapse:collapse;" align="center" cellpadding="2px" width="100%">
<tr class="headings">
	<td colspan="4" align="center"><h3>PENERIMAAN PBB-P2 KOTA PAYAKUMBUH PER TAHUN PAJAK<br />TANGGAL : <?php echo $tgl_bayar; ?></h3></h3></td>
</tr>
<tr class="headings">                                         
	<td align="center">TAHUN PAJAK </td>
	<td align="center">POKOK </td>
	<td align="center">DENDA </td>
    <td align="center">JUMLAH </td>                                     
</tr>
<?php
}
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
if ($jns_rincian == 'Detail') {
$no = 0;
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
$no++;
$total_pokok = $total_pokok + $row['TOT_POKOK'];
$total_denda = $total_denda + $row['TOT_DENDA'];
$total = $total + $row['TOTAL'];
?>
<tr class="even pointer">
	<td class=" "><?php echo $no; ?></td>       
	<td class=" "><?php echo selisih($row['JAM']); ?></td>                                                
    <td class=" "><?php echo $row['NOP']; ?></td>
    <td class=" "><?php echo $row['NAMA_WP']; ?></td>
	<td class=" " align="center"><?php echo $row['THN_PAJAK']; ?></td>
	<td class=" " align="right"><?php echo number_format($row['POKOK']); ?></td>
	<td class=" " align="right"><?php echo number_format($row['DENDA']); ?></td>
	<td class=" " align="right"><?php echo number_format($row['JML_BAYAR']); ?></td>
</tr>
<?php
}
?>
<tr class="even pointer">
	<td class=" " colspan="5" align="center"><strong>#TOTAL</strong></td>
	<td class=" " align="right"><strong><?php echo number_format($total_pokok); ?></strong></td>
	<td class=" " align="right"><strong><?php echo number_format($total_denda); ?></strong></td>
	<td class=" " align="right"><strong><?php echo number_format($total); ?></strong></td>
</tr>
<?php
} else {
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
$total_pokok = $total_pokok + $row['POKOK'];
$total_denda = $total_denda + $row['DENDA'];
$total = $total + $row['JML_BAYAR'];
?>
<tr class="even pointer">
	<td class=" " align="center"><?php echo $row['THN_PAJAK']; ?></td>
	<td class=" " align="right"><?php echo number_format($row['POKOK']); ?></td>
	<td class=" " align="right"><?php echo number_format($row['DENDA']); ?></td>
	<td class=" " align="right"><?php echo number_format($row['JML_BAYAR']); ?></td>
</tr>
<?php
}
?>
<tr class="even pointer">
	<td class=" " align="center"><strong>#TOTAL</strong></td>
	<td class=" " align="right"><strong><?php echo number_format($total_pokok); ?></strong></td>
	<td class=" " align="right"><strong><?php echo number_format($total_denda); ?></strong></td>
	<td class=" " align="right"><strong><?php echo number_format($total); ?></strong></td>
</tr>
<?php
}
oci_free_statement($stid);
oci_close($conn);
?>
</table>
<?php
//include_once $_SESSION['base_dir']."print/ttd.php";
?>