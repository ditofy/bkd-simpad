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

$q_kel = "SELECT kelurahan_op FROM bphtb.sspd WHERE kd_kecamatan='$kd_kecamatan' and kd_kelurahan='$kd_kelurahan' group by kelurahan_op";
$rs_kel=pg_query($q_kel);
$row_kel = pg_fetch_array($rs_kel);
// Perform the logic of the query

?>
<div class="x_content">
<table id="t-detail-pembayaran" class="table table-striped responsive-utilities jambo_table" style="font-size:12px;">										
                                        <thead>
											<tr class="headings">
											 <td colspan="10" align="center"><h3>DATA NIR PBB KELURAHAN  <?php echo $row_kel['kelurahan_op']; ?></h3></td>
											 </tr>
                                            <tr class="headings">  
											 	<th>NO </th>                                       
                                                <th>KODE ZNT</th>
                                                <th>NIR </th>
												                                  	
                                            </tr>
                                        </thead>
										<tbody>
<?php

///QUERY CARI DATA PBB ORACLE//
$stid = oci_parse($conn, "SELECT B.KD_ZNT,A.NIR FROM PBB.DAT_NIR A LEFT JOIN PBB.DAT_ZNT B ON
A.KD_KECAMATAN=B.KD_KECAMATAN AND
A.KD_KELURAHAN=B.KD_KELURAHAN AND 
A.KD_ZNT=B.KD_ZNT
WHERE
A.KD_KECAMATAN = '$kd_kecamatan' AND
A.KD_KELURAHAN = '$kd_kelurahan' AND
A.THN_NIR_ZNT='2023'
ORDER BY B.KD_ZNT DESC");
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

?>
<tr class="even pointer">
                                                <td class=" "><?php echo $no; ?></td>                                         
                                                <td class=" "><?php echo $row_pbb['KD_ZNT']; ?></td>
                                                <td class=" "><?php echo number_format($row_pbb['NIR']*1000); ?></td>
												
												
                                            </tr>
<?php

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