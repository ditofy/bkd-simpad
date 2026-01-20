<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
?>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script type="text/javascript">
function list_data(pg) {
	$('#list-restoran').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");	
	var inputs = $('#filter-data').serializeArray();	
			$.post( "modul/pelaporan/list_skpd.php", { postdata: inputs, page: pg })
  						.done(function( data1 ) {
							$('#list-restoran').html(data1);
  					});
};
function get_keg_usaha(kd) {
	if(kd != ""){
		$.post( "modul/pelaporan/get_keg_usaha.php", { kd_obj: kd })
  						.done(function( data1 ) {
							$('#keg-usaha').html(data1);
							$("#keg-usaha").select2("val", "");
  					});
	}
};
$(document).ready(function () {
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
				
	$('#resetfrm').click(function(event) {
		event.preventDefault();
		$('#filter-data')[0].reset();
		$("#keg-usaha").select2("val", "");		
		$("#bln-pjk").select2("val", "");
		$("#status-byr").select2("val", "");
		$("#kd-obj-pajak").select2("val", "");
		$("#keg-usaha").select2("val", "");
	});
	
	$('#tampilkan').click(function() {
		if($("#kd-obj-pajak").val() != null) {
			list_data(0);
		} else {
			notif('Notifikasi','Pilih Jenis Pajak','error');
		}
	});
	
	$("#nop").inputmask();
	$("#npwpd").inputmask();
	$("#no-surat").inputmask();
	$("#keg-usaha").select2("val", "");		
	$("#bln-pjk").select2("val", "");
	$("#status-byr").select2("val", "");
	$("#kd-obj-pajak").select2("val", "");
	$("#keg-usaha").select2("val", "");		
});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">DATA SKPD</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>
<div class="x_content">
<form id="filter-data" name="filter-data">
	<table width="100%" border="0" class="table table-striped">
	<tr>
	<td colspan="6">Filter Data</td>
	</tr>
  <tr>
    <td style="vertical-align:middle" align="right">NPWPD</td>
    <td><input type="text" id="npwpd" name="npwpd" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pokok Wajib Pajak Daerah" data-inputmask="'mask': '99.99.99.999999'"></td>
    <td style="vertical-align:middle" align="right">NOP</td>
    <td><input type="text" id="nop" name="nop" class="form-control col-md-7 col-xs-12" placeholder="Nomor Objek Pajak" data-inputmask="'mask': '99.99.99.99.9999'"></td>
    <td style="vertical-align:middle" align="right">Nama Objek </td>
    <td><input type="text" id="nm-usaha" name="nm-usaha" class="form-control col-md-7 col-xs-12" placeholder="Nama Usaha"></td>
  </tr>
  <tr>
    <td style="vertical-align:middle" align="right">NO. SKPD </td>
    <td><input type="text" id="no-surat" name="no-surat" class="form-control col-md-7 col-xs-12" placeholder="Nomor SKPD" data-inputmask="'mask': '99.9999.99.99.9999'"></td>
    <td style="vertical-align:middle" align="right">Keg Usaha </td>
    <td>
		<select name="keg-usaha" id="keg-usaha" class="select2_single" style="width:200px;">		
		</select>	</td>
    <td style="vertical-align:middle" align="right">Nama WP</td>
    <td>
	<input type="text" id="nm-wp" name="nm-wp" class="form-control col-md-7 col-xs-12" placeholder="Nama WP">
	</td>
  </tr>
  <tr>
    <td style="vertical-align:middle" align="right">Pajak</td>
    <td><select name="kd-obj-pajak" id="kd-obj-pajak" class="select2_single" style="width:150px;" onchange="get_keg_usaha($('#kd-obj-pajak').val());">
		<option value="01">Pajak ABT</option>
		<option value="02">Pajak Reklame</option>
		<option value="03">Pajak Restoran</option>
		<option value="06">Pajak Hiburan</option>
		
		</select></td>
    <td style="vertical-align:middle" align="right">Tahun</td>
    <td><select name="tahun-pajak" id="tahun-pajak" class="select2_single" style="width:60px;">
    <?php 
  	$thn = date('Y')+1;
  while($thn >= '2017') {
  	if($thn == date('Y')) {
    	echo "<option selected=\"selected\" value=\"$thn\">$thn</option>";
	} else {
		echo "<option value=\"$thn\">$thn</option>";
	}
   	$thn--; 
   } 
   ?>
  </select></td>
    <td style="vertical-align:middle" align="right">Bulan</td>
    <td><select name="bln-pjk" id="bln-pjk" class="select2_single" style="width:100px">
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
		</select></td>
  </tr>
  <tr> <td style="vertical-align:middle" align="left">Status Pembayaran</td><td><select name="status-byr" id="status-byr" class="select2_single" style="width:200px">
			<option value="0">Belum Bayar</option>
			<option value="1">Sudah Bayar</option>
			<option value="2">Batal</option>
		</select></td></tr>
  <tr>
    <td colspan="6" align="center" style="vertical-align:middle">
	<button id="tampilkan" type="button" class="btn btn-success">Tampilkan</button>
	<button id="resetfrm" type="reset" class="btn btn-primary">Reset</button>
	</td>
    </tr>
</table>
</form>
</div>
<div id="list-restoran">

</div>