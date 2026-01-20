<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
?>
<script language="javascript">
$(document).ready(function () {
	$('#cetak-ctt').click(function(){
			$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
			$.post( "modul/pbb/gen_pdf_ctt_pem.php", { nop: $('#nop1').val() })
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
  					});
	});
});
</script>
<?php
include $_SESSION['base_dir']."inc/db.orcl.inc.php";
$nop = pg_escape_string($_REQUEST['nop']);
list($kd_prop,$kd_dati2,$kd_kec,$kd_kel,$kd_blok,$no_urut,$kd_jns) = explode(".",$nop);
$stid = oci_parse($conn,"select A.THN_PAJAK_SPPT AS THN_PAJAK,A.PBB_YG_HARUS_DIBAYAR_SPPT AS POKOK,B.DENDA_SPPT AS DENDA,B.JML_SPPT_YG_DIBAYAR AS JUMLAH,A.TGL_JATUH_TEMPO_SPPT AS JATUH_TEMPO,B.TGL_PEMBAYARAN_SPPT AS TGL_BAYAR,A.NM_WP_SPPT AS NAMA, A.STATUS_PEMBAYARAN_SPPT AS STATUS FROM PBB.SPPT A left join PBB.PEMBAYARAN_SPPT B on 
A.THN_PAJAK_SPPT=B.THN_PAJAK_SPPT and
A.KD_PROPINSI=B.KD_PROPINSI and
A.KD_DATI2=B.KD_DATI2 and
A.KD_KECAMATAN=B.KD_KECAMATAN and
A.KD_KELURAHAN=B.KD_KELURAHAN and
A.KD_BLOK=B.KD_BLOK and
A.NO_URUT=B.NO_URUT
where
A.KD_PROPINSI='$kd_prop' and
A.KD_DATI2='$kd_dati2' and
A.KD_KECAMATAN='$kd_kec' and
A.KD_KELURAHAN='$kd_kel' and
A.KD_BLOK='$kd_blok' and
A.NO_URUT='$no_urut' and
A.KD_JNS_OP='$kd_jns'
order by A.THN_PAJAK_SPPT DESC"); 
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
<div class="x_content">
NOP : <?php echo "<input id=\"nop1\" type=\"text\" disabled=\"disabled\" value=\"".$nop."\">"; ?>
&nbsp;&nbsp;<button id="cetak-ctt" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak</button>
<table class="table table-bordered" style="font-size:11px;">
	<thead>
    	<tr>
        	<th>THN PAJAK</th>
            <th>NAMA WP</th>
            <th>JATUH TEMPO</th>
            <th>POKOK</th>
			<th>DENDA</th>
			<th>JUMLAH BAYAR</th>
			<th>TANGGAL BAYAR</th>
		</tr>
	</thead>
	<tbody>
<?php
$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
if($row == NULL) {
?>
		<tr>
			<td colspan="7" align="center">NOP TIDAK TERDAFTAR / TIDAK ADA CATATAN PEMBAYARAN</td>
		</tr>
<?php
} else {
$r = oci_execute($stid);
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
?>
		<tr>
			<td align="center"><?php echo $row['THN_PAJAK']; ?></td>
			<td align="center"><?php echo $row['NAMA']; ?></td>
			<td align="center"><?php echo $row['JATUH_TEMPO']; ?></td>
			<td align="right"><?php echo number_format($row['POKOK']); ?></td>
			<td align="right"><?php echo number_format($row['DENDA']); ?></td>
			<td align="right"><?php echo number_format($row['JUMLAH']); ?></td>
			<td align="center"><?php echo $row['TGL_BAYAR']; ?></td>
		</tr>
<?php
}
}
oci_free_statement($stid);
oci_close($conn);
?>
	</tbody>

</table>

</div>