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

$tahun_pajak = pg_escape_string($_REQUEST['thnpajak']);
//$tahun_pajak = '2015';
$kecamatan = pg_escape_string($_REQUEST['kecamatan']);

$awal = "1/1/".$tahun_pajak;
if($tahun_pajak < date('Y')) {
	$akhir = "31/12/".$tahun_pajak;
} else {
	//$akhir = date('d/m/Y');
	$akhir ="30/9/".$tahun_pajak;
}

$rows = array();
list($kd_propinsi,$kd_dati2,$kd_kecamatan) = explode(".",$kecamatan);

if($kd_kecamatan == 'Kota Payakumbuh') {
	$stid = oci_parse($conn, "SELECT A.NM_KECAMATAN,SUM(B.PBB_YG_HARUS_DIBAYAR_SPPT) AS KETETAPAN
FROM PBB.SPPT B,PBB.REF_KECAMATAN A
WHERE
B.THN_PAJAK_SPPT = '$tahun_pajak' AND B.STATUS_PEMBAYARAN_SPPT < '2'  AND
A.KD_PROPINSI = B.KD_PROPINSI AND
A.KD_DATI2 = B.KD_DATI2 AND
A.KD_KECAMATAN = B.KD_KECAMATAN
GROUP BY A.NM_KECAMATAN,B.KD_PROPINSI,B.KD_DATI2,B.KD_KECAMATAN ORDER BY B.KD_PROPINSI,B.KD_DATI2,B.KD_KECAMATAN");
	if (!$stid) {
    	$e = oci_error($conn);
    	die($e['message']." Baris: ".__LINE__);
	}
	$r = oci_execute($stid);
	if (!$r) {
    	$e = oci_error($stid);
    	die($e['message']." Baris: ".__LINE__);
	}
	while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
		$rows[] = $row;
	}
	oci_free_statement($stid);
	$arrlength = count($rows);
	for($x = 0; $x < $arrlength; $x++) {
    	$rows[$x]['POKOK'] = 0;
		$rows[$x]['PIUTANG'] = 0;
		$rows[$x]['DENDA'] = 0;
	}
	$stid = oci_parse($conn, "SELECT B.NM_KECAMATAN,(SUM(A.JML_SPPT_YG_DIBAYAR)-SUM(A.DENDA_SPPT)) AS POKOK,SUM(A.DENDA_SPPT) AS DENDA FROM PBB.PEMBAYARAN_SPPT A, PBB.REF_KECAMATAN B WHERE A.TGL_PEMBAYARAN_SPPT BETWEEN TO_DATE('$awal','DD-MM-YYYY') AND TO_DATE('$akhir','DD-MM-YYYY') AND
B.KD_PROPINSI = A.KD_PROPINSI AND
B.KD_DATI2 = A.KD_DATI2 AND
B.KD_KECAMATAN = A.KD_KECAMATAN
AND A.THN_PAJAK_SPPT='$tahun_pajak'
GROUP BY B.NM_KECAMATAN,A.KD_PROPINSI,A.KD_DATI2,A.KD_KECAMATAN");
	if (!$stid) {
    	$e = oci_error($conn);
    	die($e['message']." Baris: ".__LINE__);
	}
	$r = oci_execute($stid);
	if (!$r) {
    	$e = oci_error($stid);
    	die($e['message']." Baris: ".__LINE__);
	}
	while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
		for($x = 0; $x < $arrlength; $x++) {
			if($row['NM_KECAMATAN'] == $rows[$x]['NM_KECAMATAN']){
				$rows[$x]['POKOK'] = $row['POKOK'];
				$rows[$x]['DENDA'] = $row['DENDA'];
			}
		}	
	}
	oci_free_statement($stid);
	$stid = oci_parse($conn, "SELECT B.NM_KECAMATAN,SUM(A.JML_SPPT_YG_DIBAYAR)-SUM(A.DENDA_SPPT) AS PIUTANG,SUM(A.DENDA_SPPT) AS DENDA FROM PBB.PEMBAYARAN_SPPT A, PBB.REF_KECAMATAN B WHERE A.TGL_PEMBAYARAN_SPPT BETWEEN TO_DATE('$awal','DD-MM-YYYY') AND TO_DATE('$akhir','DD-MM-YYYY') AND
B.KD_PROPINSI = A.KD_PROPINSI AND
B.KD_DATI2 = A.KD_DATI2 AND
B.KD_KECAMATAN = A.KD_KECAMATAN
AND A.THN_PAJAK_SPPT < '$tahun_pajak'
GROUP BY B.NM_KECAMATAN,A.KD_PROPINSI,A.KD_DATI2,A.KD_KECAMATAN");
	if (!$stid) {
    	$e = oci_error($conn);
    	die($e['message']." Baris: ".__LINE__);
	}
	$r = oci_execute($stid);
	if (!$r) {
    	$e = oci_error($stid);
    	die($e['message']." Baris: ".__LINE__);
	}
	while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
		for($x = 0; $x < $arrlength; $x++) {
			if($row['NM_KECAMATAN'] == $rows[$x]['NM_KECAMATAN']){
				$rows[$x]['PIUTANG'] = $row['PIUTANG'];
				$rows[$x]['DENDA'] = $row['DENDA']+$rows[$x]['DENDA'];
			}
		}	
	}
?>
<button id="cetak-rpbb" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak</button>
<table class="table table-bordered" style="font-size:14px;">
	<thead>
		<tr>
			<th rowspan="2" style="text-align:center;">KECAMATAN</th>
			<th rowspan="2" style="text-align:center;">KETETAPAN</th>
			<th colspan="3" style="text-align:center;">REALISASI</th>
			<th rowspan="2" style="text-align:center;">( % )</th>
			<th rowspan="2" style="text-align:center;">DENDA</th>	
		</tr>
		<tr>
    		<th style="text-align:center;">PIUTANG</th>
   			<th style="text-align:center;">POKOK</th>
    		<th style="text-align:center;">JUMLAH</th>
  		</tr>
	</thead>
	<tbody>
	<?php
	$tot_a = 0;
	$tot_b = 0;
	$tot_c = 0;
	$tot_d = 0;
	for($x = 0; $x < $arrlength; $x++) {
		$tot_a = $tot_a + $rows[$x]['KETETAPAN'];
		$tot_b = $tot_b + $rows[$x]['PIUTANG'];
		$tot_c = $tot_c + $rows[$x]['POKOK'];
		$tot_d = $tot_d + $rows[$x]['DENDA'];
		?>
		<tr>
			<td><?php echo $rows[$x]['NM_KECAMATAN']; ?></td>
			<td align="right"><?php echo number_format($rows[$x]['KETETAPAN']); ?></td>
			<td align="right"><?php echo number_format($rows[$x]['PIUTANG']); ?></td>
			<td align="right"><?php echo number_format($rows[$x]['POKOK']); ?></td>
			<td align="right"><?php echo number_format($rows[$x]['PIUTANG']+$rows[$x]['POKOK']); ?></td>
			<td align="center"><?php echo number_format( (($rows[$x]['PIUTANG']+$rows[$x]['POKOK'])/$rows[$x]['KETETAPAN']*100),2); ?></td>
			<td align="right"><?php echo number_format($rows[$x]['DENDA']); ?></td>
		</tr>
		<?php
	}
	?>
		<tr>
			<td align="center"><strong>#TOTAL</strong></td>
			<td align="right"><strong><?php echo number_format($tot_a); ?></strong></td>
			<td align="right"><strong><?php echo number_format($tot_b); ?></strong></td>
			<td align="right"><strong><?php echo number_format($tot_c); ?></strong></td>
			<td align="right"><strong><?php echo number_format($tot_b+$tot_c); ?></strong></td>
			<td align="center"><strong><?php echo number_format(($tot_b+$tot_c)/$tot_a*100,2); ?></strong></td>
			<td align="right"><strong><?php echo number_format($tot_d); ?></strong></td>
		</tr>
	</tbody>
</table>
<?php
} else {
	$stid = oci_parse($conn, "SELECT A.NM_KELURAHAN,SUM(B.PBB_YG_HARUS_DIBAYAR_SPPT) AS KETETAPAN
FROM PBB.SPPT B,PBB.REF_KELURAHAN A
WHERE
B.THN_PAJAK_SPPT = '$tahun_pajak' AND B.STATUS_PEMBAYARAN_SPPT < '2'  AND
B.KD_PROPINSI = '$kd_propinsi' AND
B.KD_DATI2 = '$kd_dati2' AND
B.KD_KECAMATAN = '$kd_kecamatan' AND
A.KD_PROPINSI = B.KD_PROPINSI AND
A.KD_DATI2 = B.KD_DATI2 AND
A.KD_KECAMATAN = B.KD_KECAMATAN AND
A.KD_KELURAHAN = B.KD_KELURAHAN
GROUP BY A.NM_KELURAHAN,B.KD_PROPINSI,B.KD_DATI2,B.KD_KECAMATAN,B.KD_KELURAHAN ORDER BY B.KD_PROPINSI,B.KD_DATI2,B.KD_KECAMATAN,B.KD_KELURAHAN");
	if (!$stid) {
    	$e = oci_error($conn);
    	die($e['message']." Baris: ".__LINE__);
	}
	$r = oci_execute($stid);
	if (!$r) {
    	$e = oci_error($stid);
    	die($e['message']." Baris: ".__LINE__);
	}
	while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
		$rows[] = $row;
	}
	oci_free_statement($stid);
	$arrlength = count($rows);
	for($x = 0; $x < $arrlength; $x++) {
    	$rows[$x]['POKOK'] = 0;
		$rows[$x]['PIUTANG'] = 0;
		$rows[$x]['DENDA'] = 0;
	}
	$stid = oci_parse($conn, "SELECT B.NM_KELURAHAN,(SUM(A.JML_SPPT_YG_DIBAYAR)-SUM(A.DENDA_SPPT)) AS POKOK,SUM(A.DENDA_SPPT) AS DENDA FROM PBB.PEMBAYARAN_SPPT A, PBB.REF_KELURAHAN B WHERE A.TGL_PEMBAYARAN_SPPT BETWEEN TO_DATE('$awal','DD-MM-YYYY') AND TO_DATE('$akhir','DD-MM-YYYY') AND
A.KD_PROPINSI = '$kd_propinsi' AND
A.KD_DATI2 = '$kd_dati2' AND
A.KD_KECAMATAN = '$kd_kecamatan' AND
B.KD_PROPINSI = A.KD_PROPINSI AND
B.KD_DATI2 = A.KD_DATI2 AND
B.KD_KECAMATAN = A.KD_KECAMATAN AND
B.KD_KELURAHAN = A.KD_KELURAHAN
AND A.THN_PAJAK_SPPT='$tahun_pajak'
GROUP BY B.NM_KELURAHAN,A.KD_PROPINSI,A.KD_DATI2,A.KD_KECAMATAN,A.KD_KELURAHAN");
	if (!$stid) {
    	$e = oci_error($conn);
    	die($e['message']." Baris: ".__LINE__);
	}
	$r = oci_execute($stid);
	if (!$r) {
    	$e = oci_error($stid);
    	die($e['message']." Baris: ".__LINE__);
	}
	while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
		for($x = 0; $x < $arrlength; $x++) {
			if($row['NM_KELURAHAN'] == $rows[$x]['NM_KELURAHAN']){
				$rows[$x]['POKOK'] = $row['POKOK'];
				$rows[$x]['DENDA'] = $row['DENDA'];
			}
		}	
	}
	oci_free_statement($stid);
	$stid = oci_parse($conn, "SELECT B.NM_KELURAHAN,SUM(A.JML_SPPT_YG_DIBAYAR)-SUM(A.DENDA_SPPT) AS PIUTANG,SUM(A.DENDA_SPPT) AS DENDA FROM PBB.PEMBAYARAN_SPPT A, PBB.REF_KELURAHAN B WHERE A.TGL_PEMBAYARAN_SPPT BETWEEN TO_DATE('$awal','DD-MM-YYYY') AND TO_DATE('$akhir','DD-MM-YYYY') AND
A.KD_PROPINSI = '$kd_propinsi' AND
A.KD_DATI2 = '$kd_dati2' AND
A.KD_KECAMATAN = '$kd_kecamatan' AND
B.KD_PROPINSI = A.KD_PROPINSI AND
B.KD_DATI2 = A.KD_DATI2 AND
B.KD_KECAMATAN = A.KD_KECAMATAN AND
B.KD_KELURAHAN = A.KD_KELURAHAN
AND A.THN_PAJAK_SPPT < '$tahun_pajak'
GROUP BY B.NM_KELURAHAN,A.KD_PROPINSI,A.KD_DATI2,A.KD_KECAMATAN,A.KD_KELURAHAN");
	if (!$stid) {
    	$e = oci_error($conn);
    	die($e['message']." Baris: ".__LINE__);
	}
	$r = oci_execute($stid);
	if (!$r) {
    	$e = oci_error($stid);
    	die($e['message']." Baris: ".__LINE__);
	}
	while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
		for($x = 0; $x < $arrlength; $x++) {
			if($row['NM_KELURAHAN'] == $rows[$x]['NM_KELURAHAN']){
				$rows[$x]['PIUTANG'] = $row['PIUTANG'];
				$rows[$x]['DENDA'] = $row['DENDA']+$rows[$x]['DENDA'];
			}
		}	
	}
?>
<button id="cetak-rpbb" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak</button>
<table class="table table-bordered" style="font-size:14px;">
	<thead>
		<tr>
			<th rowspan="2" style="text-align:center;">KELURAHAN</th>
			<th rowspan="2" style="text-align:center;">KETETAPAN</th>
			<th colspan="3" style="text-align:center;">REALISASI</th>
			<th rowspan="2" style="text-align:center;">( % )</th>
			<th rowspan="2" style="text-align:center;">DENDA</th>	
		</tr>
		<tr>
    		<th style="text-align:center;">PIUTANG</th>
   			<th style="text-align:center;">POKOK</th>
    		<th style="text-align:center;">JUMLAH</th>
  		</tr>
	</thead>
	<tbody>
	<?php
	$tot_a = 0;
	$tot_b = 0;
	$tot_c = 0;
	$tot_d = 0;
	for($x = 0; $x < $arrlength; $x++) {
		$tot_a = $tot_a + $rows[$x]['KETETAPAN'];
		$tot_b = $tot_b + $rows[$x]['PIUTANG'];
		$tot_c = $tot_c + $rows[$x]['POKOK'];
		$tot_d = $tot_d + $rows[$x]['DENDA'];
		?>
		<tr>
			<td><?php echo $rows[$x]['NM_KELURAHAN']; ?></td>
			<td align="right"><?php echo number_format($rows[$x]['KETETAPAN']); ?></td>
			<td align="right"><?php echo number_format($rows[$x]['PIUTANG']); ?></td>
			<td align="right"><?php echo number_format($rows[$x]['POKOK']); ?></td>
			<td align="right"><?php echo number_format($rows[$x]['PIUTANG']+$rows[$x]['POKOK']); ?></td>
			<td align="center"><?php echo number_format( (($rows[$x]['PIUTANG']+$rows[$x]['POKOK'])/$rows[$x]['KETETAPAN']*100),2); ?></td>
			<td align="right"><?php echo number_format($rows[$x]['DENDA']); ?></td>
		</tr>
		<?php
	}
	?>
		<tr>
			<td align="center"><strong>#TOTAL</strong></td>
			<td align="right"><strong><?php echo number_format($tot_a); ?></strong></td>
			<td align="right"><strong><?php echo number_format($tot_b); ?></strong></td>
			<td align="right"><strong><?php echo number_format($tot_c); ?></strong></td>
			<td align="right"><strong><?php echo number_format($tot_b+$tot_c); ?></strong></td>
			<td align="center"><strong><?php echo number_format(($tot_b+$tot_c)/$tot_a*100,2); ?></strong></td>
			<td align="right"><strong><?php echo number_format($tot_d); ?></strong></td>
		</tr>
	</tbody>
</table>
<?php
}
oci_free_statement($stid);
unset($rows);
oci_close($conn);
?>
