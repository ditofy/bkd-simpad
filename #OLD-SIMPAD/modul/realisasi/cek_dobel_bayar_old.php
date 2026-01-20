<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
?>
<link type="text/css" href="css/jquery-ui.css" rel="stylesheet" />
<script src="js/jquery-ui.js"></script>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script src="js/pemisahribuan.js" ></script>

<script>

$(document).ready(function () {
 
			$('#date-start').daterangepicker({
				format: 'Y-m-d',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
			
			$('#date-end').daterangepicker({
				format: 'Y-m-d',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });

			$('#cetak-dobel').click(function(){
					if($('#tgl-awal').val() == ''  || ('#tgl-akhir').val == ''){
						$('#modal-cnt').html("<center><br><br><br>Silahkan Pilih Tanggal Awal <br><br></center>");	        				
    				} else {
						$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
						$.post( "modul/realisasi/gen_pdf_dobel.php", {tgl_awal: $('#date-start').val(),tgl_akhir: $('#date-end').val(), }).done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
						});
					}
				});
			
			$('#tampilkan-dobel').click(function(){
					if($('#date-start').val() != '') {
						$('#list-dobel').html("<img src='images/loading.gif' />");
						$.post( "modul/realisasi/tampil_dobel_bayar.php", { tgl_awal: $('#date-start').val(), tgl_akhir: $('#date-end').val(), })
  						.done(function( data ) {
   							$('#list-dobel').html(data);
							$("#tampilkan-dobel").removeAttr('disabled');
  						});
					} else {
						notif('Notifikasi','Silahkan Pilih Tanggal','error');
					}
				});

});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">CEK DATA PEMBAYARAN PAJAK DAERAH</h4>
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
                                <h3><small>CEK DOBEL PEMBAYARAN PAJAK DAERAH</small></h3>    
                                </div>
                                
                            </div>
<form id="frm-laporan-inner" name="frm-laporan-inner" data-parsley-validate class="form-horizontal form-label-left">
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal </label>
		<div class="col-md-3 col-sm-3 col-xs-6">
        	<input type="text" id="date-start" name="date-start" class="form-control has-feedback-left" placeholder="Tanggal Awal" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	<div class="col-md-3 col-sm-3 col-xs-6">
        	<input type="text" id="date-end" name="date-end" class="form-control has-feedback-left" placeholder="Tanggal Akhir" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
		
	</div>

	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        <button id="tampilkan-dobel" type="button" class="btn btn-dark">Tampilkan</button>
		<button id="cetak-dobel" type="button" class="btn btn-success" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak</button></div>
    </div>
</form>
<div class="col-md-12 col-sm-3 col-xs-12 bg-white" id="list-dobel">	

</div>
<!--<div class="col-md-12 col-sm-3 col-xs-12 bg-white" id="realisasi-tanggal">		
							 
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
<?php
pg_close($dbconn);
?>