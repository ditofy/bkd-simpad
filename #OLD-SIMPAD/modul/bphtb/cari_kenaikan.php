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
                                                <th>NOP</th>
												 <th>KELAS TANAH  </th>
												 <th>NJOP PBB LAMA </th>
												 <th>KELAS TANAH BARU </th>
												 <th>NJOP PBB BARU</th>
												 <th>PBB LAMA</th>
												 <th>PBB BARU</th>
												
												                                  	
                                            </tr>
                                        </thead>
										<tbody>
<?php

///QUERY CARI DATA PBB ORACLE//
$stid = oci_parse($conn, "
SELECT A.KD_PROPINSI||'.'||A.KD_DATI2||'.'||A.KD_KECAMATAN||'.'||A.KD_KELURAHAN||'.'||A.KD_BLOK||'-'||A.NO_URUT||'.'||A.KD_JNS_OP AS NOP,B.KD_KLS_TANAH,B.NILAI_PER_M2_TANAH,TO_NUMBER(B.KD_KLS_TANAH) AS KELAS, 
A.LUAS_BUMI_SPPT,A.NJOP_BUMI_SPPT,A.NJOP_BNG_SPPT,A.NJOP_SPPT,A.NJOPTKP_SPPT,A.PBB_YG_HARUS_DIBAYAR_SPPT FROM PBB.SPPT A
LEFT JOIN PBB.KELAS_TANAH B ON B.KD_KLS_TANAH=A.KD_KLS_TANAH
WHERE A.THN_PAJAK_SPPT='2023' AND B.THN_AWAL_KLS_TANAH = '2011' AND B.THN_AKHIR_KLS_TANAH = '9999' 
ORDER BY NOP ASC");
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
$njop_sppt_lama=$row_pbb['NJOP_BUMI_SPPT']+$row_pbb['NJOP_BNG_SPPT']-$row_pbb['NJOPTKP_SPPT'];
$kelas_bumi_baru=$row_sim['NILAI_BARU']*1000;
$njop_bumi_baru=$row_pbb['LUAS_BUMI_SPPT']*$kelas_bumi_baru;
$njop_sppt_baru=$njop_bumi_baru+$row_pbb['NJOP_BNG_SPPT']-$row_pbb['NJOPTKP_SPPT'];
$per_ratio=$njop_sppt_baru*(0.1/100);
$pbb_naik=$per_ratio*($ratio/100);
//$pbb=$njop_pbb_sppt*0.1
//
//
/*$kelas_akhir=$row_sim['NILAI_BARU']*1000;
$kelas_awal=$row_pbb['NILAI_PER_M2_TANAH']*1000;
$kenaikan= $kelas_akhir-$kelas_awal;
$per_ratio=$kelas_akhir*($ratio/100);
$per_kenaikan=round(($per_ratio-$kelas_awal)/$kelas_awal*100,0);
$per_ratio=round(($kelas_akhir-$kenaikan)/$kelas_akhir*100,0);
$pembagi=round(($kelas_akhir-$kenaikan)/$kelas_akhir*100);
$nilai_baru=$pembagi*$kelas_akhir;*/
//
?>
<tr class="even pointer">
                                                <td class=" "><?php echo $no; ?></td>                                         
                                                <td class=" "><?php echo $row_pbb['NOP']; ?></td>
                                                <td class=" "><?php echo $row_pbb['KD_KLS_TANAH']; ?></td>
												<td class=" "><?php echo number_format($njop_sppt_lama); ?></td>
												<td class=" "><?php echo $jd_naik; ?></td>
												<td class=" "><?php echo number_format($njop_sppt_baru); ?></td>
												<td class=" "><?php echo number_format($row_pbb['PBB_YG_HARUS_DIBAYAR_SPPT']); ?></td>
												<td class=" "><?php echo number_format($pbb_naik); ?></td>
												
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
                    'iDisplayLength': 50,
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