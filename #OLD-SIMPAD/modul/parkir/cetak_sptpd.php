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
	$('#cetak-sptpd').click(function(){
		$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");		
			$.post( "modul/parkir/gen_pdf_sptpd.php", { no_sptpd: $('#no-sptpd').val() })
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
  					});
	});	
	$('#cetak-sspd').click(function(){
		$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");		
			$.post( "modul/parkir/gen_pdf_sspd.php", { no_sspd: $('#no-sspd').val() })
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
			$.post( "modul/parkir/gen_pdf_sptpd_masal.php", { thn_pajak: $('#tahun-pajak').val(), bln_pajak: $('#bulan-pajak').val() })
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
  					});
	});	
	$('#frm-cetak-skp').submit(function() {
  		return false;
	});
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
	$("#no-sptpd").inputmask();
	$("#no-sspd").inputmask();
	$("#bulan-pajak").select2("val", "");
});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">SPTPD</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<form id="frm-cetak-sspd" name="frm-cetak-sspd" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No SSPD</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="no-sspd" name="no-sspd" class="form-control col-md-7 col-xs-12" placeholder="Nomor SSPD" data-inputmask="'mask': '99.9999.99.99.9999'">
    </div>
</div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">        				
			<button id="cetak-sspd" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak SSPD</button>			
		</div>
	</div>
</form>
<div class="ln_solid"></div>
<form id="frm-cetak-skp" name="frm-cetak-skp" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No SPTPD</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="no-sptpd" name="no-sptpd" class="form-control col-md-7 col-xs-12" placeholder="Nomor SPTPD" data-inputmask="'mask': '99.9999.99.99.9999'">
    </div>
</div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">        				
			<button id="cetak-sptpd" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak SPTPD</button>			
		</div>
	</div>
</form>
<div class="ln_solid"></div>
<form id="frm-restoran-skp-mas" name="frm-restoran-skp-mas" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tahun Pajak</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="tahun-pajak" id="tahun-pajak" class="select2_single" style="width:200px">
					 <?php
		$thn = date('Y');
        while($thn >= '2017') {
  	if($thn == date('Y')) {
    	echo "<option selected=\"selected\" value=\"$thn\">$thn</option>";
	} else {
		echo "<option value=\"$thn\">$thn</option>";
	}
   	$thn--; 
   } 
        ?>
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
			<button id="cetak-masal" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak SPTPD Masal</button>			
		</div>
</div>
</form>