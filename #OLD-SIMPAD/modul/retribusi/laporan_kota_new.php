<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
?>
<link type="text/css" href="css/jquery-ui.css" rel="stylesheet" />
<script src="js/jquery-ui.js"></script>
<script>
function list_data(pg) {
	$('#list-inner').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");	
	var inputs = $('#filter-data').serializeArray();	
			$.post( "modul/retribusi/tampil_laporan_inner.php", { tgl_awal: $('#tgl-awal').val(),tgl_akhir: $('#tgl-akhir').val(), jns_objek: $('#jenis-objek').val(),jns_laporan: $('#jenis-laporan').val(),page: pg })
  						.done(function( data1 ) {
							$('#list-inner').html(data1);
  					});
};
$(document).ready(function () {
                $(".select2_single").select2({
                    placeholder: "Pilih                      ",
                    allowClear: false
                });
                $(".select2_group").select2({});
				
				
				
					
	
			
			
			$('#tgl-awal').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
			
			$('#tgl-akhir').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
			
			
			
			$('#cetak-kota').click(function(){
					if($('#tgl-awal').val() == ''  || ('#tgl-akhir').val == ''){
						$('#modal-cnt').html("<center><br><br><br>Silahkan Pilih Tanggal Pembayaran<br><br></center>");	        				
    				} else {
						$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
						$.post( "modul/retribusi/gen_pdf_kota_tanggal.php", {tgl_awal: $('#tgl-awal').val(),tgl_akhir: $('#tgl-akhir').val() }).done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
						});
					}
				});
			
			
		/*	$('#submit-detail-tanggal').click(function(){
					if($('#tgl-awal').val() != '') {
						$('#realisasi-tanggal').html("<img src='images/loading.gif' />");
						$("#submit-detail-tanggal").attr("disabled", "disabled");
						$.post( "modul/realisasi/kategori_tanggal_detail_pembayaran.php", { tgl_awal: $('#tgl-awal').val(),tgl_akhir: $('#tgl-akhir').val(), jns_objek: $('#jenis-objek').val(),jns_laporan: $('#jenis-laporan').val() })
  						.done(function( data ) {
   							$('#realisasi-tanggal').html(data);
							$("#submit-detail-tanggal").removeAttr('disabled');
  						});
					} else {
						notif('Notifikasi','Silahkan Pilih Tanggal','error');
					}
				});*/
});
			
			
				

</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">REALISASI PAD</h4>
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
                                <h3><small>DETAIL PENERIMAAN PAD KOTA</small></h3>    
                                </div>
                                
                            </div>
<form id="frm-laporan-kota" name="frm-laporan-kota" data-parsley-validate class="form-horizontal form-label-left">
	

	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal </label>
		<div class="col-md-3 col-sm-3 col-xs-6">
        	<input type="text" id="tgl-awal" name="tgl-awal" class="form-control has-feedback-left" placeholder="Tanggal Awal" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	<div class="col-md-3 col-sm-3 col-xs-6">
        	<input type="text" id="tgl-akhir" name="tgl-akhir" class="form-control has-feedback-left" placeholder="Tanggal Akhir" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	</div>
	
	
	
	
	
	
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
		
        
			<button id="cetak-kota" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak</button>        </div>
    </div>
</form>
<div id="list-inner">

</div>
<!--<div class="col-md-12 col-sm-3 col-xs-12 bg-white" id="realisasi-tanggal">		
							 
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
<?php
pg_close($dbconn);
?>