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
			$.post( "modul/skpdkb/gen_pdf_skpdkb.php", { no_skpdkb: $('#no-skpdkb').val() })
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
  					});
	});	
	
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
	
	$('#frm-cetak-skpdkb').submit(function() {
  		return false;
	});
	
	$("#no-skpdkb").inputmask();

});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">SKPDKB</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<form id="frm-cetak-skpdkb" name="frm-cetak-skpdkb" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No SKPDKB</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="no-skpdkb" name="no-skpdkb" class="form-control col-md-7 col-xs-12" placeholder="Nomor SKPDKB" data-inputmask="'mask': '99.9999.99.99.9999'">
    </div>
</div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">        				
			<button id="cetak" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak SKPDKB</button>			
		</div>
	</div>
</form>
<div class="ln_solid"></div>
<form id="frm-cetak-skpdkb-mas" name="frm-cetak-skpdkb-mas" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tahun Pajak</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="tahun-pajak" id="tahun-pajak" class="select2_single" style="width:200px">
			<option value="2018">2018</option>
			<option value="2019">2019</option>
			
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
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis Pajak</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select class="form-control" id="jns-obj" name="jns-obj">
                                                    <option value="01">Pajak ABT</option>
													<option value="02">Pajak Reklame</option>
													<option value="03">Pajak Restoran</option>
													<option value="04">Pajak Penerangan Jalan</option>
													<option value="05">Pajak Parkir</option>   
													<option value="06">Pajak Hiburan</option>  
													<option value="07">Pajak Hotel</option> 
													<option value="08">Pajak Galian C</option>  
													<option value="09">BPHTB</option>                       
                                                </select>
    </div>
</div>
<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">			
			<button id="cetak-masal" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak SKPDKB Masal</button>			
		</div>
</div>
</form>
