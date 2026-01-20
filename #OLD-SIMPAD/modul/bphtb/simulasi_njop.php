<link href="css/datatables/tools/css/dataTables.tableTools.css" rel="stylesheet">
<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}

include $_SESSION['base_dir']."inc/db.orcl.coba.php";
//include $_SESSION['base_dir']."inc/db.inc.php";
$kecamatan = pg_escape_string($_REQUEST['kd_kecamatan']);
$kelurahan = pg_escape_string($_REQUEST['kd_kelurahan']);
$kelas = pg_escape_string($_REQUEST['kelas']);

?>
<div class="x_content">
<table id="t-detail-simulasi" class="table table-striped responsive-utilities jambo_table" style="font-size:12px;">										
                                        <thead>
											<tr class="headings">
											 <td colspan="18" align="center"><h3>SIMULASI KENAIKAN KELAS TANAH PBB DAN ASESSMENT RATIO</h3></td>
											 </tr>
                                            <tr class="headings">  
											 	<th>NO </th>                                       
                                                <th>NOP</th>
												<th>LUAS BUMI  </th>
												<th>NJOP BUMI </th>
												<th>NJOP BANGUNAN</th>
												<th>NJOPTKP</th>
												<th>NJOP AWAL</th>
												<th>KELAS AWAL</th>
												<th>KELAS BARU</th>
												<th>NJOP BUMI SIMU</th>
												<th>KENAIKAN NJOP</th>
												<th>PERSEN NJOP SIMULASI</th>
												<th>PERSEN KLASIFIKASI</th>
												<th>NJOP HASIL PERSENTASE</th>
												<th>PBB AWAL</th>
												<th>PBB SIMULASI</th>
												<th>KENAIKAN PBB</th>
												
												                                  	
                                            </tr>
                                        </thead>
										<tbody>
<?php

///QUERY CARI DATA PBB ORACLE//
$stid = oci_parse($conn, "
SELECT A.KD_PROPINSI||'.'||A.KD_DATI2||'.'||A.KD_KECAMATAN||'.'||A.KD_KELURAHAN||'.'||A.KD_BLOK||'-'||A.NO_URUT||'.'||A.KD_JNS_OP AS NOP,B.KD_KLS_TANAH,B.NILAI_PER_M2_TANAH,TO_NUMBER(B.KD_KLS_TANAH) AS KELAS, 
A.LUAS_BUMI_SPPT,A.NJOP_BUMI_SPPT,A.NJOP_BNG_SPPT,A.NJOP_SPPT,A.NJOPTKP_SPPT,A.PBB_YG_HARUS_DIBAYAR_SPPT FROM PBB.SPPT A
LEFT JOIN PBB.KELAS_TANAH B ON B.KD_KLS_TANAH=A.KD_KLS_TANAH
WHERE A.THN_PAJAK_SPPT='2023' AND B.THN_AWAL_KLS_TANAH = '2011' AND B.THN_AKHIR_KLS_TANAH = '9999' AND A.KD_KECAMATAN='$kecamatan' AND A.KD_KELURAHAN='$kelurahan'
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
$i=0;
while ($row_pbb = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
$i++;
$kd=$row_pbb['KELAS'];
$jadi_naik=$kd-$kelas;
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
$kenaikan=$njop_sppt_baru-$njop_sppt_lama;
$persen=round($kenaikan/$njop_sppt_baru*100,0);
//$pbb=$njop_pbb_sppt*0.1
//
//$rat=pg_query("SELECT B.PERSEN_AKHIR FROM RATIO B") or die('Query failed: ' . pg_last_error());
//$row_rat = pg_fetch_array($rat);
if($persen == 0)
{
$per_rat=100;
} else if ($persen > 0 and $persen <= 5)
{
$per_rat=95;
} else if ($persen > 5 and $persen <= 10)
{
$per_rat=90;
} else if ($persen > 10 and $persen <= 15)
{
$per_rat=85;
} else if ($persen > 15 and $persen <= 20)
{
$per_rat=80;
} else if ($persen > 20 and $persen <= 25)
{
$per_rat=75;
} else if ($persen > 25 and $persen <= 30)
{
$per_rat=70;
} else if ($persen > 30 and $persen <= 35)
{
$per_rat=65;
} else if ($persen > 35 and $persen <= 40)
{
$per_rat=60;
} else if ($persen > 40 and $persen <= 45)
{
$per_rat=55;
} else if ($persen > 45 and $persen <= 50)
{
$per_rat=50;
} else if ($persen > 50 and $persen <= 55)
{
$per_rat=45;
} else if ($persen > 55 and $persen <= 60)
{
$per_rat=40;
} else if ($persen > 60 and $persen <= 65)
{
$per_rat=35;
} else if ($persen > 65 and $persen <= 70)
{
$per_rat=30;
} else if ($persen > 70 and $persen <= 75)
{
$per_rat=25;
}
else if ($persen > 75 and $persen <= 100)
{
$per_rat=20;
}
$njop_baru=($per_rat/100)*$njop_sppt_baru;
$pbb_baru=$njop_baru*(0.1/100);

if($pbb_baru < 5000){
$pbb_baru_kali=5000;
}
else{
$pbb_baru_kali=$pbb_baru;
}
$kenaik=$pbb_baru_kali-$row_pbb['PBB_YG_HARUS_DIBAYAR_SPPT'];
?>
<tr class="even pointer">
                                                <td class=" "><?php echo $i; ?></td>                                         
                                                <td class=" "><?php echo $row_pbb['NOP']; ?></td>
												<td class=" "><?php echo $row_pbb['LUAS_BUMI_SPPT']; ?></td>
												<td class=" "><?php echo number_format($row_pbb['NJOP_BUMI_SPPT']); ?></td>
												<td class=" "><?php echo number_format($row_pbb['NJOP_BNG_SPPT']); ?></td>
												<td class=" "><?php echo number_format($row_pbb['NJOPTKP_SPPT']); ?></td>
												<td class=" "><?php echo number_format($row_pbb['NJOP_SPPT']);?></td>
                                                <td class=" "><?php echo $row_pbb['KD_KLS_TANAH']; ?></td>
												<td class=" "><?php echo $jd_naik; ?></td>
												<td class=" "><?php echo number_format($njop_sppt_baru); ?></td>
												<td class=" "><?php echo number_format($kenaikan); ?></td>
												<td class=" "><?php echo $persen; ?> %</td>
												<td class=" "><?php echo $per_rat; ?> %</td>
												<td class=" "><?php echo number_format($njop_baru); ?></td>
												<td class=" " bgcolor="#FFFF00"><?php echo number_format($row_pbb['PBB_YG_HARUS_DIBAYAR_SPPT']); ?></td>
												<td class=" " bgcolor="#00CC99"><?php echo number_format($pbb_baru_kali); ?></td>
												<td class=" "><?php echo number_format($kenaik); ?></td>
												
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
                var oTable = $('#t-detail-simulasi').dataTable({
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