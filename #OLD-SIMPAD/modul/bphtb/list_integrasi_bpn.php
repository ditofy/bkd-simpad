<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include $_SESSION['base_dir']."inc/db.orcl.inc.php";
$tahun = date('Y');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X- 
Request-With');
?>
<script>
function get_jns_surat(kd) {
	if(kd != ""){
		$.post( "modul/realisasi/get_jns_surat.php", { kd_obj: kd })
  						.done(function( data1 ) {
							$('#jns-surat').html(data1);
							$("#jns-surat").select2("val", "");
  					});
	}
};
$(document).ready(function () {

            

				$('#tgl-data').daterangepicker({
					format: 'DD-MM-YYYY',
                	singleDatePicker: true,
                	calender_style: "picker_1"
            		}, function (start, end, label) {
                		console.log(start.toISOString(), end.toISOString(), label);
            	});
				$('#submit-data-bpn').click(function(){
					if($('#tgl-data').val() != '') {
					
						$('#data-bpn').html("<img src='images/loading.gif' />");
						$("#submit-data-bpn").attr("disabled", "disabled");
					//	$.post( "http://180.250.38.34:10/Api/getBPHTBService/", { "USERNAME": $("#username").val(), "PASSWORD": $("#password").val(),"NTPD": $("#ntpd").val(),"NOP": $("#nop").val() })
					$.post( "https://services.atrbpn.go.id/BPNApiService/api/bphtb/getDataATRBPN/", { "USERNAME": $("#username").val(), "PASSWORD": $("#password").val(),"TANGGAL": $("#tgl-data").val() })
  						.done(function( data ) {
   							$('#data-bpn').html(data);
							$("#submit-data-bpn").removeAttr('disabled');
  						});
					} else {
						notif('Notifikasi','Silahkan Pilih Tanggal','error');
					}
				});
				
				$('#cetak-det').click(function(){
					if($('#tgl-data').val() == ''){
						$('#modal-cnt').html("<center><br><br><br>Silahkan Pilih Tanggal Data<br><br></center>");	        				
    				} else {
						$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
						$.post( "modul/realisasi/gen_pdf_ctt_pem_kategori.php", { jns_obj: $('#jns-obj').val(),jns_surat: $('#jns-surat').val(), tgl_pembayaran: $('#tgl-pembayaran').val() }).done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
						});
					}
				});
				
});
</script>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">INTEGRASI DATA AKTA DENGAN BPN</h4>
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
                                <h3><small>INTEGRASI DATA AKTA DENGAN BPN</small></h3>    
                                </div>
                                
                            </div>
							<div class="x_content">
								<form class="form-horizontal form-label-left input_mask" id="frm-pembayaran" method="post" target="" action="">
								<div class="col-md-4 col-sm-6 col-xs-12 form-group has-feedback">
                                            <input type="text" class="form-control has-feedback-left" id="tgl-data" name="tgl-data"  placeholder="Tanggal">
											<input type="hidden" class="form-control has-feedback-left" id="username" name="username" value="bapendapayakumbuh">
											<input type="hidden" class="form-control has-feedback-left" id="password" name="password" value="c">
											<input type="hidden" class="form-control has-feedback-left" id="ntpd" name="ntpd" value="04202009090127">
											<input type="hidden" class="form-control has-feedback-left" id="nop" name="nop" value="137601001300400240">
											
                                            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                </div>
								
								&nbsp;&nbsp;<button type="button" class="btn btn-success" id="submit-data-bpn">Tampilkan</button>
								&nbsp;<button id="cetak-det" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak</button>		
								</form>
								</div>
							<div class="col-md-12 col-sm-3 col-xs-12 bg-white" id="data-bpn">		
							 
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                </div>
				<br />
