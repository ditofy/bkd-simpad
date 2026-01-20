<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
	
}
?>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script>
$(document).ready(function () {
	$('#cetak').click(function(){
		$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");		
			$.post( "modul/restoran/gen_pdf_skp.php", { no_skp: $('#no-skp').val() })
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
  					});
	});			
	
	$('#cetak-masal').click(function(){
		if($('#bulan-pajak').val() == null) {
			$('#modal-cnt').html("<center><br><br>Silahkan Pilih Bulan Pajak<br><br></center>");
			return;
		}
		$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");		
		var es;
		es = new EventSource('/modul/restoran/gen_pdf_skp_masal.php?thn_pajak='+$('#tahun-pajak').val()+'&bln_pajak='+$('#bulan-pajak').val());
		es.addEventListener('message', function(e) {
        	var result = JSON.parse( e.data );
			if(e.lastEventId == 'SELESAI') {
				es.close();
            	$('#modal-cnt').html("<object type='application/pdf' data='"+result.message+"' width='100%' height='500'>this is not working as expected</object>");				
        	} else {
				$('#modal-cnt').html(result.message+"<center><img src='images/loading.gif' /><br>Loading</center>");
			}
		});
		
	});
	
	$('#frm-cetak-skp').submit(function() {
  		return false;
	});
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
	$("#no-skp").inputmask();
	$("#bulan-pajak").select2("val", "");
});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">SKPD</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<form id="frm-cetak-skp" name="frm-cetak-skp" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No SKP</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="no-skp" name="no-skp" class="form-control col-md-7 col-xs-12" placeholder="Nomor SKP" data-inputmask="'mask': '99.9999.99.99.9999'">
    </div>
</div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">        				
			<button id="cetak" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak SKP</button>			
		</div>
	</div>
</form>
<div class="ln_solid"></div>
<form id="frm-restoran-skp-mas" name="frm-restoran-skp-mas" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tahun Pajak</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="tahun-pajak" id="tahun-pajak" class="select2_single" style="width:200px">
			<option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
		</select>
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Bulan Pajak</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="bulan-pajak" id="bulan-pajak" class="select2_single" style="width:200px">
			<option value="01">Januari</option>
			<option value="02">Februari</option>
			<option value="03">Maret</option>
			<option value="04">April</option>
			<option value="05">Mei</option>
			<option value="06">Juni</option>
			<option value="07">Juli</option>
			<option value="08">Agustus</option>
			<option value="09">September</option>
			<option value="10">Oktober</option>
			<option value="11">November</option>
			<option value="12">Desember</option>
		</select>
    </div>
</div>
<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">			
			<button id="cetak-masal" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak SKP Masal</button>			
		</div>
</div>
</form>