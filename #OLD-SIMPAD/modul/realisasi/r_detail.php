<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include $_SESSION['base_dir']."inc/db.orcl.inc.php";
$tahun = date('Y');

?>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">REALISASI PAJAK DAERAH</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        
							<div class="row x_title">
                                <div class="col-md-6">
                                <h3><small>DETAIL PENERIMAAN PAJAK PER HARI</small></h3>    
                                </div>
                                
                            </div>
							<div class="x_content">
								<form class="form-horizontal form-label-left input_mask" id="frm-pembayaran" method="post" target="_blank" action="print/pbb_hari.php">
								<div class="col-md-4 col-sm-6 col-xs-12 form-group has-feedback">
                                            <input type="text" class="form-control has-feedback-left" id="tgl-pembayaran" name="tgl-pembayaran"  placeholder="Tanggal">
                                            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                </div>
								<div class="col-md-3 col-sm-6 col-xs-12 form-group has-feedback">
								<select class="form-control" id="jns-obj" name="jns-obj" onchange="get_jns_surat($('#jns-obj').val());">
                                                    <option value="01">Pajak ABT</option>
													<option value="02">Pajak Reklame</option>
													<option value="03">Pajak Restoran</option>
													<option value="04">Pajak Penerangan Jalan</option>
													<option value="05">Pajak Parkir</option>   
													<option value="06">Pajak Hiburan</option>  
													<option value="07">Pajak Hotel</option> 
													<option value="08">Pajak Galian C</option>  
													<option value="09">BPHTB</option>       
													<option value="10">Opsen PKB</option> 
													<option value="11">Opsen BBNKB</option>                 
                                                </select>
												
								</div>
								<div class="col-md-3 col-sm-6 col-xs-12 form-group has-feedback">
								<select class="form-control" id="jns-surat" name="jns-surat">
                                                                   
                                                </select>
												
								</div>
								&nbsp;&nbsp;<button type="button" class="btn btn-success" id="submit-detail-pemb">Submit</button>
								&nbsp;<button id="cetak-det-rinci" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak</button>		
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

            

				
						
				$('#tgl-pembayaran').daterangepicker({
					format: 'DD-MM-YYYY',
                	singleDatePicker: true,
                	calender_style: "picker_1"
            		}, function (start, end, label) {
                		console.log(start.toISOString(), end.toISOString(), label);
            	});
				$('#submit-detail-pemb').click(function(){
					if($('#tgl-pembayaran').val() != '') {
						$('#realisasi-detail').html("<img src='images/loading.gif' />");
						$("#submit-detail-pembayaran").attr("disabled", "disabled");
						$.post( "modul/realisasi/kategori_detail_pembayaran_rinci.php", { tgl_bayar: $('#tgl-pembayaran').val(), jns_obj: $('#jns-obj').val(), jns_surat: $('#jns-surat').val() })
  						.done(function( data ) {
   							$('#realisasi-detail').html(data);
							$("#submit-detail-pemb").removeAttr('disabled');
  						});
					} else {
						notif('Notifikasi','Silahkan Pilih Tanggal','error');
					}
				});
				
				$('#cetak-det-rinci').click(function(){
					if($('#tgl-pembayaran').val() == ''){
						$('#modal-cnt').html("<center><br><br><br>Silahkan Pilih Tanggal Pembayaran<br><br></center>");	        				
    				} else {
						$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
						$.post( "modul/realisasi/gen_pdf_ctt_pem_rinci.php", { jns_rincian: $('#jns-obj').val(), tgl_pembayaran: $('#tgl-pembayaran').val() }).done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
						});
					}
				});
				
});
</script>
