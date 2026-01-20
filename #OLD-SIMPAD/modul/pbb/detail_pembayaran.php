<link href="css/datatables/tools/css/dataTables.tableTools.css" rel="stylesheet">
<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
function selisih($jam_keluar) {
$jam_selesai=date("H:i:s",mktime(date("H",strtotime($jam_keluar))-0,date("i",strtotime($jam_keluar))-0,date("s",strtotime($jam_keluar)),0,0,0));
return $jam_selesai;
}
include $_SESSION['base_dir']."inc/db.orcl.inc.php";
$tgl_bayar = pg_escape_string($_REQUEST['tgl_bayar']);
$jns_rincian = pg_escape_string($_REQUEST['jns_rincian']);
$total_pokok = 0;
$total_denda = 0;
$total = 0;
if ($jns_rincian == 'Detail') {
$stid = oci_parse($conn,"SELECT A.KD_PROPINSI||'.'||A.KD_DATI2||'.'||A.KD_KECAMATAN||'.'||A.KD_KELURAHAN||'.'||A.KD_BLOK||'.'||A.NO_URUT||'.'||A.KD_JNS_OP AS NOP,B.NM_WP_SPPT AS NAMA_WP,A.THN_PAJAK_SPPT AS THN_PAJAK,B.PBB_YG_HARUS_DIBAYAR_SPPT AS POKOK,A.DENDA_SPPT AS DENDA,
A.JML_SPPT_YG_DIBAYAR AS JML_BAYAR, TO_CHAR(A.TGL_REKAM_BYR_SPPT,'HH24:MI:SS') AS JAM,
A.JML_SPPT_YG_DIBAYAR AS TOTAL,B.PBB_YG_HARUS_DIBAYAR_SPPT AS TOT_POKOK,A.DENDA_SPPT AS TOT_DENDA,A.NM_CAB_BANK,A.NO_BUKTI,C.NAMA_TELLER,C.NAMA_TELLER
FROM PBB.SPPT B, PBB.PEMBAYARAN_SPPT A
LEFT JOIN PBB.REF_TELLER_BANK_NAGARI C ON SUBSTR(C.TELLER_ID,5,8) LIKE TRIM(substr(A.TERMINAL_ID,5,8))
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
?>
<div class="x_content">
<table id="t-detail-pembayaran" class="table table-striped responsive-utilities jambo_table" style="font-size:12px;">										
                                        <thead>
											<tr class="headings">
											 <td colspan="10" align="center"><h3>Detail Pembayaran PBB-P2 Tanggal : <?php echo $tgl_bayar; ?></h3></td>
											 </tr>
                                            <tr class="headings">                                         
                                                <th>JAM </th>
                                                <th>NOP </th>
                                                <th>Nama WP </th>
                                                <th>THN PAJAK </th>
                                                <th>POKOK </th>
												<th>DENDA </th>
												<th>JML BAYAR </th>
                                            	<th>NM TELLER </th>
                                            	<th>NM CAB BANK </th>
                                            	<th>NO BUKTI </th>                                            	
                                            </tr>
                                        </thead>
										<tbody>
<?php
$no = 0;
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
$no++;
$total_pokok = $total_pokok + $row['TOT_POKOK'];
$total_denda = $total_denda + $row['TOT_DENDA'];
$total = $total + $row['TOTAL'];
?>
<tr class="even pointer">
                                                <td class=" "><?php echo selisih($row['JAM']); ?></td>                                                
                                                <td class=" "><?php echo $row['NOP']; ?></td>
                                                <td class=" "><?php echo $row['NAMA_WP']; ?></td>
												<td class=" " align="center"><?php echo $row['THN_PAJAK']; ?></td>
												<td class=" " align="right"><?php echo number_format($row['POKOK']); ?></td>
												<td class=" " align="right"><?php echo number_format($row['DENDA']); ?></td>
												<td class=" " align="right"><?php echo number_format($row['JML_BAYAR']); ?></td>
												<td class=" " align="left"><?php echo $row['NAMA_TELLER']; ?></td>
												<td class=" " align="left"><?php echo $row['NM_CAB_BANK']; ?></td>
												<td class=" " align="center"><?php echo $row['NO_BUKTI']; ?></td>
                                            </tr>
<?php
}
oci_free_statement($stid);
oci_close($conn);
?>
<tr class="even pointer">
<td class=" "><strong>#TOTAL</strong></td>
<td class=" " align="center"><strong><?php echo $no." SPPT"; ?></strong></td>
<td class=" ">#</td>
<td class=" ">#</td>
<td class=" " align="right"><strong><?php echo number_format($total_pokok); ?></strong></td>
<td class=" " align="right"><strong><?php echo number_format($total_denda); ?></strong></td>
<td class=" " align="right"><strong><?php echo number_format($total); ?></strong></td>
<td class=" ">#</td>
<td class=" ">#</td>
<td class=" ">#</td>
</tr>
</tbody>

                                    </table>
                                </div>
<?php } else { 
if ($jns_rincian == 'Group Per Tahun Pajak') {
$stid = oci_parse($conn,"SELECT A.THN_PAJAK_SPPT AS THN_PAJAK,SUM(A.JML_SPPT_YG_DIBAYAR-A.DENDA_SPPT) AS POKOK,SUM(A.DENDA_SPPT) AS DENDA,
SUM(A.JML_SPPT_YG_DIBAYAR) AS JML_BAYAR
FROM PBB.PEMBAYARAN_SPPT A
WHERE A.TGL_PEMBAYARAN_SPPT=TO_DATE('$tgl_bayar','DD-MM-YYYY')
GROUP BY A.THN_PAJAK_SPPT
order by A.THN_PAJAK_SPPT");
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
?>
<div class="x_content">
<table id="t-detail-pembayaran" class="table table-striped responsive-utilities jambo_table" style="font-size:12px;">										
                                        <thead>
											<tr class="headings">
											 <td colspan="4" align="center"><h3>PENERIMAAN PBB-P2 PER TAHUN PAJAK<br />TANGGAL : <?php echo $tgl_bayar; ?></h3></td>
											 </tr>
                                            <tr class="headings">                                         
                                                <th><center>TAHUN PAJAK</center> </th>
                                                <th><center>POKOK</center> </th>
                                                <th><center>DENDA</center> </th>
                                                <th><center>JUMLAH</center> </th>                                           
                                            </tr>
                                        </thead>
										<tbody>
<?php
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
oci_free_statement($stid);
oci_close($conn);
?>
<tr class="even pointer">
<td class=" " align="center"><strong>#TOTAL</strong></td>
<td class=" " align="right"><strong><?php echo number_format($total_pokok); ?></strong></td>
<td class=" " align="right"><strong><?php echo number_format($total_denda); ?></strong></td>
<td class=" " align="right"><strong><?php echo number_format($total); ?></strong></td>
</tr>
</tbody>

                                    </table>
                                </div>
<?php
} else {
	$stid = oci_parse($conn,"SELECT B.TELLER,B. POKOK,B.DENDA,B.JML_BAYAR,C.NAMA_TELLER FROM
(SELECT A.TERMINAL_ID AS TELLER,SUM(A.JML_SPPT_YG_DIBAYAR-A.DENDA_SPPT) AS POKOK,SUM(A.DENDA_SPPT) AS DENDA,
SUM(A.JML_SPPT_YG_DIBAYAR) AS JML_BAYAR
FROM PBB.PEMBAYARAN_SPPT A
WHERE A.TGL_PEMBAYARAN_SPPT=TO_DATE('$tgl_bayar','DD-MM-YYYY')
GROUP BY A.TERMINAL_ID
order by A.TERMINAL_ID) B
LEFT JOIN PBB.REF_TELLER_BANK_NAGARI C ON SUBSTR(C.TELLER_ID,5,8) LIKE TRIM(substr(B.TELLER,5,8))");

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
?>
<div class="x_content">
<table id="t-detail-pembayaran" class="table table-striped responsive-utilities jambo_table" style="font-size:12px;">										
                                        <thead>
											<tr class="headings">
											 <td colspan="5" align="center"><h3>PENERIMAAN PBB-P2 PER TELLER BANK NAGARI<br />TANGGAL : <?php echo $tgl_bayar; ?></h3></td>
											 </tr>
                                            <tr class="headings">                                         
                                                <th><center>TERMINAL ID</center> </th>
												<th><center>NAMA TELLER</center> </th>
                                                <th><center>POKOK</center> </th>
                                                <th><center>DENDA</center> </th>
                                                <th><center>JUMLAH</center> </th>                                           
                                            </tr>
                                        </thead>
										<tbody>
<?php
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
$total_pokok = $total_pokok + $row['POKOK'];
$total_denda = $total_denda + $row['DENDA'];
$total = $total + $row['JML_BAYAR'];
?>
<tr class="even pointer">
                                                <td class=" " align="center"><?php echo $row['TELLER']; ?></td>
												<td class=" " align="center"><?php echo $row['NAMA_TELLER']; ?></td>
                                                <td class=" " align="right"><?php echo number_format($row['POKOK']); ?></td>
                                                <td class=" " align="right"><?php echo number_format($row['DENDA']); ?></td>
												<td class=" " align="right"><?php echo number_format($row['JML_BAYAR']); ?></td>
                                            </tr>
<?php
}
oci_free_statement($stid);
oci_close($conn);
?>
<tr class="even pointer">
<td class=" " align="center"><strong>#TOTAL</strong></td>
<td class=" " align="right"></td>
<td class=" " align="right"><strong><?php echo number_format($total_pokok); ?></strong></td>
<td class=" " align="right"><strong><?php echo number_format($total_denda); ?></strong></td>
<td class=" " align="right"><strong><?php echo number_format($total); ?></strong></td>
</tr>
</tbody>

                                    </table>
                                </div>
<?php	
}
} //end if
?>
<script src="js/datatables/js/jquery.dataTables.js"></script>
        <script src="js/datatables/tools/js/dataTables.tableTools.js"></script>
        <script>
            $(document).ready(function () {
                $('input.tableflat').iCheck({
                    checkboxClass: 'icheckbox_flat-green',
                    radioClass: 'iradio_flat-green'
                });
            });

            var asInitVals = new Array();
            $(document).ready(function () {
                var oTable = $('#t-detail-pembayaran').dataTable({
                    "oLanguage": {
                        "sSearch": "Cari semua kolom:"
                    },
                    "aoColumnDefs": [
                        {
                            'bSortable': false,
                            'aTargets': [0]
                        } //disables sorting for column one
            ],
                    'iDisplayLength': 12,
                    "sPaginationType": "full_numbers",
                    "dom": 'T<"clear">lfrtip',
                    "tableTools": {
                        "sSwfPath": "//simpad.payakumbuhkota.go.id/copy_csv_xls_pdf.swf"
                    }
                });
                $("tfoot input").keyup(function () {
                    /* Filter on the column based on the index of this element's parent <th> */
                    oTable.fnFilter(this.value, $("tfoot th").index($(this).parent()));
                });
                $("tfoot input").each(function (i) {
                    asInitVals[i] = this.value;
                });
                $("tfoot input").focus(function () {
                    if (this.className == "search_init") {
                        this.className = "";
                        this.value = "";
                    }
                });
                $("tfoot input").blur(function (i) {
                    if (this.value == "") {
                        this.className = "search_init";
                        this.value = asInitVals[$("tfoot input").index(this)];
                    }
                });
            });
        </script>