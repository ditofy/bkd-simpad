<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
?>
<script language="javascript">
$(document).ready(function () {
	$('#cetak-rpbb').click(function(){
			$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
			$.post( "modul/pbb/gen_pdf_realisasi.php", { thnpajak: $('#thn-pajak').val(), kecamatan: $('#kecamatan').val() })
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
  					});
	});
});
</script>

<?php
include $_SESSION['base_dir']."inc/db.orcl.inc.php";

//$tahun_pajak = pg_escape_string($_REQUEST['thnpajak']);
//$tahun_pajak = '2015';
$thn_p= date('Y')-1;
$tgl_piu = pg_escape_string($_REQUEST['tgl_piutang']);
list($day,$month,$year)=explode('-',$tgl_piu);
if($month='MEI'){
$month='MAY';
}
if($month='OKT'){
$month="OCT";
}
if($month='DES'){
$month='DEC';
}
$data_piu=array($day,$month,$year);
$jatuh_piu = implode('-',$data_piu);

$awal = "1/1/".$tahun_pajak;
if($tahun_pajak < date('Y')) {
	$akhir = "31/12/".$tahun_pajak;
} else {
	$akhir = date('d/m/Y');
}
$rows = array();
list($kd_propinsi,$kd_dati2,$kd_kecamatan) = explode(".",$kecamatan);

//if($tgl_piu == 0 OR ""){
$stid = oci_parse($conn, "select SPPT.THN_PAJAK_SPPT,SUM(SPPT.PBB_YG_HARUS_DIBAYAR_SPPT) AS POKOK  from PBB.SPPT 
INNER JOIN PBB.REF_KECAMATAN B ON
B.KD_KECAMATAN = SPPT.KD_KECAMATAN
INNER JOIN PBB.REF_KELURAHAN C ON
C.KD_KECAMATAN = SPPT.KD_KECAMATAN AND
C.KD_KELURAHAN = SPPT.KD_KELURAHAN 
INNER JOIN PBB.DAT_OBJEK_PAJAK D ON
SPPT.KD_KECAMATAN=D.KD_KECAMATAN AND
SPPT.KD_KELURAHAN=D.KD_KELURAHAN AND
SPPT.KD_BLOK=D.KD_BLOK AND
SPPT.NO_URUT=D.NO_URUT AND
SPPT.KD_JNS_OP=D.KD_JNS_OP 
where (SPPT.KD_PROPINSI,SPPT.KD_DATI2,SPPT.KD_KECAMATAN,SPPT.KD_KELURAHAN,SPPT.KD_BLOK,SPPT.NO_URUT,SPPT.KD_JNS_OP,SPPT.THN_PAJAK_SPPT)
NOT IN (SELECT P.KD_PROPINSI,P.KD_DATI2,P.KD_KECAMATAN,P.KD_KELURAHAN,P.KD_BLOK,P.NO_URUT,P.KD_JNS_OP,P.THN_PAJAK_SPPT FROM PBB.PEMBAYARAN_SPPT P WHERE P.TGL_PEMBAYARAN_SPPT <= TO_DATE('$tgl_piu','DD-MM-YYYY') AND P.THN_PAJAK_SPPT BETWEEN '2009' AND '$year')
AND  SPPT.STATUS_PEMBAYARAN_SPPT < '2' AND SPPT.THN_PAJAK_SPPT BETWEEN '2009' AND '$year' GROUP BY SPPT.THN_PAJAK_SPPT
ORDER BY SPPT.THN_PAJAK_SPPT desc");
	if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
?>
<!--<button id="cetak-rpbb" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak</button>-->
<div style="width:50%">
<table class="table table-bordered" style="font-size:14px;" width="50%">
	<thead>
		
		<tr>
    		<th style="text-align:center;">TAHUN PAJAK</th>
   			<th style="text-align:center;">JUMLAH PIUTANG</th>
    		
  		</tr>
	</thead>
	<tbody>	

<?php
$r = oci_execute($stid);
$tot_piu=0;
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) 
{
?>
<tr>
			<td align="center"><?php echo $row['THN_PAJAK_SPPT']; ?></td>
			<td align="right"><?php echo number_format($row['POKOK']); ?></td>
		
<?php 
	$grand_piu=$grand_piu+$row['POKOK'];
} ?>
</tr>
<tr>
<td align="center"><b>TOTAL PIUTANG</b></td>
<td align="right"><b><?php echo number_format($grand_piu); ?></b></td>
</tr>
</tbody>
</table></div>
<?php		
oci_free_statement($stid);
?>
		
<?php
/*}/* else {
	
$stid = oci_parse($conn, "select SPPT.THN_PAJAK_SPPT,SUM(SPPT.PBB_YG_HARUS_DIBAYAR_SPPT) AS POKOK  from PBB.SPPT 
INNER JOIN PBB.REF_KECAMATAN B ON
B.KD_KECAMATAN = SPPT.KD_KECAMATAN
INNER JOIN PBB.REF_KELURAHAN C ON
C.KD_KECAMATAN = SPPT.KD_KECAMATAN AND
C.KD_KELURAHAN = SPPT.KD_KELURAHAN 
INNER JOIN PBB.DAT_OBJEK_PAJAK D ON
SPPT.KD_KECAMATAN=D.KD_KECAMATAN AND
SPPT.KD_KELURAHAN=D.KD_KELURAHAN AND
SPPT.KD_BLOK=D.KD_BLOK AND
SPPT.NO_URUT=D.NO_URUT AND
SPPT.KD_JNS_OP=D.KD_JNS_OP 
where (SPPT.KD_PROPINSI,SPPT.KD_DATI2,SPPT.KD_KECAMATAN,SPPT.KD_KELURAHAN,SPPT.KD_BLOK,SPPT.NO_URUT,SPPT.KD_JNS_OP,SPPT.THN_PAJAK_SPPT)
NOT IN (SELECT P.KD_PROPINSI,P.KD_DATI2,P.KD_KECAMATAN,P.KD_KELURAHAN,P.KD_BLOK,P.NO_URUT,P.KD_JNS_OP,P.THN_PAJAK_SPPT FROM PBB.PEMBAYARAN_SPPT P WHERE P.TGL_PEMBAYARAN_SPPT <= TO_DATE('$tgl_piu','DD-MM-YYYY') AND P.THN_PAJAK_SPPT BETWEEN '2009' AND '$thn_p')
AND  SPPT.STATUS_PEMBAYARAN_SPPT < '2' AND SPPT.THN_PAJAK_SPPT BETWEEN '2009' AND '$thn_p' GROUP BY SPPT.THN_PAJAK_SPPT
ORDER BY SPPT.THN_PAJAK_SPPT desc");
	if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
?>
<button id="cetak-rpbb" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak</button>
<div style="width:50%">
<table class="table table-bordered" style="font-size:14px;" width="50%">
	<thead>
		
		<tr>
    		<th style="text-align:center;">TAHUN PAJAK</th>
   			<th style="text-align:center;">JUMLAH PIUTANG</th>
    		
  		</tr>
	</thead>
	<tbody>	

<?php
$r = oci_execute($stid);
$tot_piu=0;
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) 
{
?>
<tr>
			<td align="center"><?php echo $row['THN_PAJAK_SPPT']; ?></td>
			<td align="right"><?php echo number_format($row['POKOK']); ?></td>
		
<?php 
	$grand_piu=$grand_piu+$row['POKOK'];
} ?>
</tr>
<tr>
<td align="center"><b>TOTAL PIUTANG</b></td>
<td align="right"><b><?php echo number_format($grand_piu); ?></b></td>
</tr>
</tbody>
</table></div>
<?php		

}*/
oci_free_statement($stid);
unset($rows);
oci_close($conn);
?>
