<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
?>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">PIUTANG PBB</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<div class="">
<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
				<h2>Data Rekapitulasi Piutang PBB</h2>
			<div class="x_title">
			</div>
    				

<div class="col-md-4 col-sm-6 col-xs-12 form-group has-feedback">
<input type="text" class="form-control has-feedback-left" id="tgl-piutang" name="tgl-piutang"  placeholder="Tanggal">
<span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
</div>

		<div class="col-md-12 col-sm-3 col-xs-12 bg-white" id="rekap-piutang">		
							 
                            </div>
		<button type="button" class="btn btn-success" id="submit-tampilkan-piutang">Tampilkan</button>
		</div>
	</div>
</div>
<?php
include_once $_SESSION['base_dir']."footer.php";
?>
</div>
<script>
        $(document).ready(function () {
			/*$('#det-piutang').html("<img src='images/loading.gif' />");
			$.post( "modul/pbb/ajax_piutang.php", {tgl_piutang: $('#tgl-piutang').val() })
  						.done(function( data ) {
   							$('#det-piutang').html(data);
  						});*/
			$('#tgl-piutang').daterangepicker({
					format: 'DD-MM-YYYY',
                	singleDatePicker: true,
                	calender_style: "picker_1"
            		}, function (start, end, label) {
                		console.log(start.toISOString(), end.toISOString(), label);
            	});
			$('#submit-tampilkan-piutang').click(function(){
					if($('#tgl-piutang').val() != '') {
						$('#rekap-piutang').html("<img src='images/loading.gif' />");
						$("#submit-tampilkan-piutang").attr("disabled", "disabled");
						$.post( "modul/pbb/ajax_piutang.php", { tgl_piutang: $('#tgl-piutang').val()})
  						.done(function( data ) {
   						$('#rekap-piutang').html(data);
							$("#submit-tampilkan-piutang").removeAttr('disabled');
  						});
					} else {
						notif('Notifikasi','Silahkan Pilih Tanggal','error');
					}
				});
        });
</script>