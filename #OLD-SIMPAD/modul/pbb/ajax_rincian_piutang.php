<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
?>


<?php
include $_SESSION['base_dir']."inc/db.orcl.inc.php";

//$tahun_pajak = pg_escape_string($_REQUEST['thnpajak']);
//$tahun_pajak = '2015';
$tgl_piu = pg_escape_string($_REQUEST['tgl_piutang']);
$thn_piu = pg_escape_string($_REQUEST['thn_piutang']);
//$rows = array();
//list($kd_propinsi,$kd_dati2,$kd_kecamatan) = explode(".",$kecamatan);

//if($tgl_piu == 0 OR ""){
$stid = oci_parse($conn, "select SPPT.KD_PROPINSI||'.'||SPPT.KD_DATI2||'.'||SPPT.KD_KECAMATAN||'.'||SPPT.KD_KELURAHAN||'.'||SPPT.KD_BLOK||'-'||SPPT.NO_URUT||'.'||SPPT.KD_JNS_OP AS NOP,SPPT.NM_WP_SPPT,SPPT.JLN_WP_SPPT,SPPT.KELURAHAN_WP_SPPT,B.NM_KECAMATAN AS KECAMATAN_OBJEK, C.NM_KELURAHAN AS KELURAHAN_OBJEK,D.JALAN_OP,SPPT.THN_PAJAK_SPPT,SPPT.PBB_YG_HARUS_DIBAYAR_SPPT AS POKOK,SPPT.TGL_TERBIT_SPPT AS TANGGAL_TERBIT_SPPT from PBB.SPPT SPPT
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
NOT IN (SELECT P.KD_PROPINSI,P.KD_DATI2,P.KD_KECAMATAN,P.KD_KELURAHAN,P.KD_BLOK,P.NO_URUT,P.KD_JNS_OP,P.THN_PAJAK_SPPT FROM PBB.PEMBAYARAN_SPPT P WHERE P.TGL_PEMBAYARAN_SPPT <= TO_DATE('$tgl_piu','DD-MM-YYYY') AND P.THN_PAJAK_SPPT ='$thn_piu')
AND SPPT.THN_PAJAK_SPPT ='$thn_piu' AND SPPT.STATUS_PEMBAYARAN_SPPT < '2'
ORDER BY SPPT.THN_PAJAK_SPPT,SPPT.KD_PROPINSI,SPPT.KD_DATI2,SPPT.KD_KECAMATAN,SPPT.KD_KELURAHAN,SPPT.KD_BLOK,SPPT.NO_URUT,SPPT.KD_JNS_OP");
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
<script language="javascript">
	$('#download-xls').click(function(){		
			var inputs = $('#filter-data').serializeArray();
			$.post('inc/to_xls.php', { data: inputs }, function(response){
    		if (!response.status) {
        		notif('Notifikasi','Error calling save','error');
        		return;
    		}
    		if (response.status != 'OK') {
        		notif('Notifikasi',response.status,'error');
        		return;
    		}
    		window.open('/print/list_rincian_piutang_pbb_xls.php');
			});	
	});
</script>
<button id="download-xls" type="button" class="btn btn-dark">Download XLS</button>
<div class="x_content">
<table id="t-rincian-piutang" class="table table-striped responsive-utilities jambo_table" style="font-size:12px;">										
                                        <thead>
											<tr class="headings">
											 <td colspan="7" align="center"><h3>Rincian Piutang PBB Tahun : <?php echo $thn_piu; ?></h3></td>
											</tr>
                                            <tr class="headings">                                         
                                                <th>NOP </th>
                                                <th>NAMA WP </th>
                                                <th>ALAMAT WP</th>
                                                <th>JALAN OBJEK </th>
												<th>KELURAHAN OBJEK </th>
												<th>KECAMATAN OP </th>
                                            	<th>POKOK PAJAK </th>
                                            </tr>
                                        </thead>
<tbody>
<?php
$no = 0;
$tot_piu=0;
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) 
{
$total_piutang = $total_piutang + $row['POKOK'];
$no++;
?>
<tr class="even pointer">
			 <td class=" "><?php echo $row['NOP']; ?></td>
			 <td class=" "><?php echo $row['NM_WP_SPPT']; ?></td>
			 <td class=" "><?php echo $row['JLN_WP_SPPT']; ?></td>
			 <td class=" "><?php echo $row['JALAN_OP']; ?></td>
			 <td class=" "><?php echo $row['KELURAHAN_OBJEK']; ?></td>
			 <td class=" "><?php echo $row['KECAMATAN_OBJEK']; ?></td>
			 <td class=" " align="right"><?php echo number_format($row['POKOK']); ?></td>
</tr>
		
<?php 
	
} ?>
<tr class="even pointer">
<td class=" "><strong>#TOTAL</strong></td>
<td class=" "><strong><?php echo number_format($no)." OBJEK"; ?></strong></td>
<td class=" ">#</td>
<td class=" ">#</td>
<td class=" ">#</td>
<td class=" ">#</td>
<td class=" " align="right"><strong><?php echo number_format($total_piutang); ?></strong></td>
</tr>
</tbody>
</table>
</div>
<?php

oci_free_statement($stid);
//unset($rows);
oci_close($conn);
?>
<script src="js/datatables/js/jquery.dataTables.js"></script>
<script src="js/datatables/tools/js/dataTables.tableTools.js"></script>
<script>
            var asInitVals = new Array();
            $(document).ready(function () {
                var oTable = $('#t-rincian-piutang').dataTable({
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
				
				  $('input.tableflat').iCheck({
                    checkboxClass: 'icheckbox_flat-green',
                    radioClass: 'iradio_flat-green'
                });
				
			
            });
			
        </script>