<link href="css/datatables/tools/css/dataTables.tableTools.css" rel="stylesheet">
<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}

include $_SESSION['base_dir']."inc/db.orcl.inc.php";
include $_SESSION['base_dir']."inc/db.inc.php";
$kd_kecamatan = pg_escape_string($_REQUEST['kd_kecamatan']);
$kd_kelurahan = pg_escape_string($_REQUEST['kd_kelurahan']);

$stid = oci_parse($conn, "select A.KD_PROPINSI||'.'||A.KD_DATI2||'.'||A.KD_KECAMATAN||'.'||A.KD_KELURAHAN||'.'||A.KD_BLOK||'-'||A.NO_URUT||'.'||A.KD_JNS_OP AS NOP,C.NM_WP,C.JALAN_WP,C.RT_WP,C.RW_WP,C.KELURAHAN_WP,B.JALAN_OP,B.RT_OP,B.RW_OP,B.BLOK_KAV_NO_OP AS NOMOR,B.TOTAL_LUAS_BUMI, B.TOTAL_LUAS_BNG, D.NM_KECAMATAN, E.NM_KELURAHAN,F.JML_SATUAN AS KWH from pbb.DAT_OP_BUMI A
INNER JOIN PBB.DAT_OBJEK_PAJAK B ON
a.KD_PROPINSI=b.KD_PROPINSI 
and a.KD_DATI2=b.KD_DATI2 
and a.KD_KECAMATAN=b.KD_KECAMATAN 
and a.KD_KELURAHAN=b.KD_KELURAHAN 
and a.kd_blok=b.kd_blok 
and a.no_urut=b.no_urut
and a.kd_jns_op=b.kd_jns_op
INNER JOIN PBB.DAT_SUBJEK_PAJAK C ON
B.SUBJEK_PAJAK_ID=C.SUBJEK_PAJAK_ID
INNER JOIN PBB.REF_KECAMATAN D ON
A.KD_KECAMATAN=D.KD_KECAMATAN
INNER JOIN PBB.REF_KELURAHAN E ON
A.KD_KECAMATAN=E.KD_KECAMATAN AND
A.KD_KELURAHAN=E.KD_KELURAHAN
LEFT JOIN PBB.DAT_FASILITAS_BANGUNAN F ON
B.KD_PROPINSI=F.KD_PROPINSI 
and B.KD_DATI2=F.KD_DATI2 
and B.KD_KECAMATAN=F.KD_KECAMATAN 
and B.KD_KELURAHAN=F.KD_KELURAHAN 
and B.kd_blok=F.kd_blok 
and B.no_urut=F.no_urut
and B.kd_jns_op=F.kd_jns_op
WHERE 
A.KD_KECAMATAN = '$kd_kecamatan' AND
A.KD_KELURAHAN = '$kd_kelurahan' AND
A.JNS_BUMI <> '4' 
ORDER BY F.JML_SATUAN ASC
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
?>

<table id="t-detail-pembayaran" class="table table-striped responsive-utilities jambo_table" style="font-size:12px;">										 
<thead>
<tr>
	<td colspan='16' style="font-size:18px;">
		<center>FORMULIR DATA OBJEK PBB BERDASARKAN PENGGUNA LAYANAN PLN TAHUN 2024<BR>
		KECAMATAN <?php echo $row['NM_KECAMATAN']; ?>
		KELURAHAN <?php echo $row['NM_KELURAHAN']; ?></center>
	</td>
  </tr>	
                                        
	<tr>
	<th>NO </th>
	<th>NOP </th>
    <th>NAMA WAJIB PAJAK </th>
    <th>ALAMAT OBJEK PAJAK</th>
    <th>RT</th>
    <th>RW</th>
    <th>NOMOR</th>
    <th>LUAS<br>BUMI<br>(M&sup2;) </th>
    <th>LUAS<br>BANGUNAN<br>(M&sup2;)</th>
    <th>KWH DAT PBB</th>

    
  </tr>
                                        </thead>
										<tbody>
<?php
$NOMOR = 1;
	$r = oci_execute($stid);
	while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
?>
<tr class="even pointer">
                                               r>
  	<td><?php echo $NOMOR; ?></td>
	<td><?php echo $row['NOP'] ?></td>
	<td><?php echo $row['NM_WP'] ?></td>
	<td><?php echo $row['JALAN_OP'] ?></td>
	<td align="center"><?php echo $row['RT_OP'] ?></td>
	<td align="center"><?php echo $row['RW_OP'] ?></td>
	<td align="center"><?php echo $row['NOMOR'] ?></td>
	<td align="right"><?php echo $row['TOTAL_LUAS_BUMI'] ?></td>
	<td align="right"><?php echo $row['TOTAL_LUAS_BNG'] ?></td>
	<td align="right"><?php echo number_format($row['KWH']) ?></td>

                                            </tr>
<?php

  	$NOMOR++;
  	}
	oci_free_statement($stid);
oci_close($conn);//TUTUP PBB


?>

</tr>
</tbody>

                                    </table>
                                </div>
<?php  //end if
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