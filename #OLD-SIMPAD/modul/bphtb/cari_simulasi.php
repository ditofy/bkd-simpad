<link href="css/datatables/tools/css/dataTables.tableTools.css" rel="stylesheet">
<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}

include $_SESSION['base_dir']."inc/db.orcl.inc.php";
include $_SESSION['base_dir']."inc/db.inc.php";
$naik = pg_escape_string($_REQUEST['naik']);
$ratio = pg_escape_string($_REQUEST['ratio']);

?>
<div class="x_content">
<table id="t-detail-pembayaran" class="table table-striped responsive-utilities jambo_table" style="font-size:12px;">										
                                        <thead>
											<tr class="headings">
											 <td colspan="10" align="center"><h3>SIMULASI KENAIKAN KELAS TANAH PBB DAN ASESSMENT RATIO</h3></td>
											 </tr>
                                            <tr class="headings">  
											 	<th>NO </th>                                       
                                                <th>KELAS TANAH</th>
												 <th>NILAI PER METER </th>
												 <th>NILAI PER METER REKLAS </th>
												 <th>KENAIKAN </th>
												 <th align="center">NILAI PBB BARU (<?php echo $ratio; ?>%)</th>
												 <th>% KENAIKAN</th>
												 <th>JUMLAH OBJEK</th>
												                                  	
                                            </tr>
                                        </thead>
										<tbody>
<?php

///QUERY CARI DATA PBB ORACLE//
$stid = oci_parse($conn, "SELECT COUNT(A.KD_KLS_TANAH) AS JUM,A.KD_KLS_TANAH,B.KD_KLS_TANAH,B.NILAI_PER_M2_TANAH,TO_NUMBER(B.KD_KLS_TANAH) AS KELAS FROM PBB.SPPT A
LEFT JOIN PBB.KELAS_TANAH B ON B.KD_KLS_TANAH=A.KD_KLS_TANAH
WHERE A.THN_PAJAK_SPPT='2023' AND B.THN_AWAL_KLS_TANAH = '2011' AND B.THN_AKHIR_KLS_TANAH = '9999' 
GROUP BY A.KD_KLS_TANAH,B.KD_KLS_TANAH,B.NILAI_PER_M2_TANAH
ORDER BY A.KD_KLS_TANAH ASC");
if (!$stid) {
    	 $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
	$r = oci_execute($stid);
	if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$no=0;
while ($row_pbb = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
$no++;
$kd=$row_pbb['KELAS'];
$jadi_naik=$kd-$naik;
$jd_naik=sprintf("%03d", $jadi_naik);
//
$cari_kenaikan = oci_parse($conn, "SELECT (A.NILAI_PER_M2_TANAH) AS NILAI_BARU FROM PBB.KELAS_TANAH A
WHERE A.THN_AWAL_KLS_TANAH = '2011' AND A.THN_AKHIR_KLS_TANAH = '9999' AND A.KD_KLS_TANAH='$jd_naik'
ORDER BY A.NILAI_MIN_TANAH ASC");
if (!$cari_kenaikan ) {
    	 $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
	$r = oci_execute($cari_kenaikan );
	if (!$r) {
    $e = oci_error($cari_kenaikan );
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

while ($row_sim = oci_fetch_array($cari_kenaikan, OCI_ASSOC+OCI_RETURN_NULLS)) {
//
$kelas_akhir=$row_sim['NILAI_BARU']*1000;
$kelas_awal=$row_pbb['NILAI_PER_M2_TANAH']*1000;
$kenaikan= $kelas_akhir-$kelas_awal;
$per_ratio=$kelas_akhir*($ratio/100);
$per_kenaikan=round(($per_ratio-$kelas_awal)/$kelas_awal*100,0);
$per_ratio=round(($kelas_akhir-$kenaikan)/$kelas_akhir*100,0);
$pembagi_p=($kelas_akhir-$kenaikan)/$kelas_akhir;
$pembagi=round(($kelas_akhir-$kenaikan)/$kelas_akhir*100);
$nilai_baru=$pembagi_p*$kelas_akhir;
//
?>
<tr class="even pointer">
                                                <td class=" "><?php echo $no; ?></td>                                         
                                                <td class=" "><?php echo $row_pbb['KD_KLS_TANAH']; ?></td>
                                                <td class=" "><?php echo number_format($row_pbb['NILAI_PER_M2_TANAH']*1000); ?></td>
												<td class=" "><?php echo number_format($row_sim['NILAI_BARU']*1000); ?></td>
												<td class=" "><?php echo number_format($kenaikan); ?></td>
												<?php 
												if($per_ratio > $kelas_awal){
												?>
												<td class=" " bgcolor="#00FF00" align="center"><b><?php echo number_format($nilai_baru); ?></b></td>
												<?php  }
											    else  {
												?>
												<td class=" " bgcolor="#FF0000" align="center"><b><?php echo number_format($nilai_baru); ?></b></td>
												<?php } ?> 
												<td class=" "><?php echo $pembagi; ?>%</td>
												
												<td class=" "><?php echo number_format($row_pbb['JUM']); ?></td>
												
												
												
                                            </tr>
<?php
}
}

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