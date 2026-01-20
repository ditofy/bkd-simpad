<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include $_SESSION['base_dir']."inc/db.orcl.inc.php";
$tahun = date('Y');
$stid = oci_parse($conn, "SELECT COUNT(*) AS JUMLAH FROM PBB.SPPT A WHERE A.THN_PAJAK_SPPT = '$tahun'");
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
$jumlah_sppt = $row['JUMLAH'];

$stid = oci_parse($conn, "SELECT SUM(A.PBB_YG_HARUS_DIBAYAR_SPPT) AS KETETAPAN FROM PBB.SPPT A WHERE A.THN_PAJAK_SPPT = '$tahun' AND A.STATUS_PEMBAYARAN_SPPT < '2'");
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
$ketetapan = $row['KETETAPAN'];
oci_free_statement($stid);
oci_close($conn);
?>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">REALISASI PBB</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<div class="row tile_count">
<div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
	<div class="left"></div>
	<div class="right">
		<span class="count_top"><i class="fa fa-user"></i> Total SPPT</span>
		<div class="count green"><?php echo number_format($jumlah_sppt); ?></div>
		<span class="count_bottom">Tahun <?php echo $tahun; ?></span>	</div>
</div>
<div class="animated flipInY col-md-4 col-sm-4 col-xs-4 tile_stats_count">
	<div class="left"></div>
	<div class="right">
		<span class="count_top"><i class="fa fa-user"></i> Total Ketetapan</span>
		<div class="count green"><?php echo number_format($ketetapan); ?></div>
		<span class="count_bottom">Tahun <?php echo $tahun; ?></span>	</div>
</div>
</div>

<div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="dashboard_graph">                            
                            
                            <div class="col-md-12 col-sm-3 col-xs-12 bg-white" id="realisasi-pbb">		
							 
                            </div>
							<div class="row x_title">
                                <div class="col-md-6">
                                <h3><small>DETAIL PENERIMAAN PBB-P2 PER HARI</small></h3>    
                                </div>
                                
                            </div>
							<div class="x_content">
								<form class="form-horizontal form-label-left input_mask" id="frm-pembayaran" method="post" target="_blank" action="print/pbb_hari.php">
								<div class="col-md-4 col-sm-6 col-xs-12 form-group has-feedback">
                                            <input type="text" class="form-control has-feedback-left" id="tgl-pembayaran" name="tgl-pembayaran"  placeholder="Tanggal">
                                            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                </div>
								<div class="col-md-3 col-sm-6 col-xs-12 form-group has-feedback">
								<select class="form-control" id="jns-rincian" name="jns-rincian">
                                                    <option>Group Per Tahun Pajak</option>
													<option>Group Per Teller BANK NAGARI</option>
                                                    <option>Detail</option>                                                    
                                                </select>
								</div>
								&nbsp;&nbsp;<button type="button" class="btn btn-success" id="submit-detail-pembayaran">Submit</button>
								&nbsp;<button id="cetak-det" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak</button>		
								</form>
								</div>
							<div class="col-md-12 col-sm-3 col-xs-12 bg-white" id="realisasi-detail">		
							 
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                </div>
				<br />
<script>
$(document).ready(function () {
				$('#realisasi-pbb').html("<img src='images/loading.gif' />");
				$.ajax({
                	type: "GET",
                	url: 'modul/pbb/r_pbb.php',
                	data: '',
               		success: function(data){
						
						$('#realisasi-pbb').html(data);	
					
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#realisasi-pbb').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						
      				}
            	});				
				$('#tgl-pembayaran').daterangepicker({
					format: 'DD-MM-YYYY',
                	singleDatePicker: true,
                	calender_style: "picker_1"
            		}, function (start, end, label) {
                		console.log(start.toISOString(), end.toISOString(), label);
            	});
				$('#submit-detail-pembayaran').click(function(){
					if($('#tgl-pembayaran').val() != '') {
						$('#realisasi-detail').html("<img src='images/loading.gif' />");
						$("#submit-detail-pembayaran").attr("disabled", "disabled");
						$.post( "modul/pbb/detail_pembayaran.php", { tgl_bayar: $('#tgl-pembayaran').val(), jns_rincian: $('#jns-rincian').val() })
  						.done(function( data ) {
   							$('#realisasi-detail').html(data);
							$("#submit-detail-pembayaran").removeAttr('disabled');
  						});
					} else {
						notif('Notifikasi','Silahkan Pilih Tanggal','error');
					}
				});
				$('#cetak-det').click(function(){
					if($('#tgl-pembayaran').val() == ''){
						$('#modal-cnt').html("<center><br><br><br>Silahkan Pilih Tanggal Pembayaran<br><br></center>");	        				
    				} else {
						$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
						$.post( "modul/pbb/gen_pdf_ctt_pem_det.php", { jns_rincian: $('#jns-rincian').val(), tgl_pembayaran: $('#tgl-pembayaran').val() }).done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
						});
					}
				});
				
});
</script>
<?php
include_once $_SESSION['base_dir']."footer.php";
?>