<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
?>
<script>
$(document).ready(function () {

                $(".select2_single").select2({
                    placeholder: "Pilih                      ",
                    allowClear: false
                });
                $(".select2_group").select2({});
                $(".select2_multiple").select2({
                    maximumSelectionLength: 4,
                    placeholder: "With Max Selection limit 4",
                    allowClear: true
                });
				$('#tgl-daftar').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
				
            });
///
function hanyaAngka(evt){
	var charCode = (evt.which) ? evt.which : event.keyCode
	if(charCode > 31 && (charCode < 48 || charCode > 57))
	return false;
	return true;
	}
//
$('#proses-daftar-wp').click(function(){
if($('#nik').val() == '' || $('#nama-wp').val() == '' || $('#alamat').val() == '' || $('#kelurahan').val() == '' || $('#kecamatan').val() == '' || $('#kota').val() == '' || $('#no-telp').val() == '' || $('#jenis-wp').val() == '' || $('#npwp').val() == ''|| $('#tgl-daftar').val() == ''){
	notif('Notifikasi','Isi Semua Data','error');			
} else {
	$("#proses-daftar-wp").attr("disabled", "disabled");
	var varnik = $('#nik').val();
	$.post( "inc/get_wp.php", { nik: varnik })
  				.done(function( data ) {
				if(data != '[]'){
					var obj = jQuery.parseJSON(data);
   					notif('ERROR','NIK sudah terdaftar dengan NPWPD : '+obj[0].npwpd,'error');
					$("#proses-daftar-wp").removeAttr('disabled');
				} else {
					var inputs = $('#frm-daftar-wp').serializeArray();
					$.post( "modul/wp/p_tambah_wp.php", { postdata: inputs })
  						.done(function( data1 ) {
							document.getElementById("frm-daftar-wp").reset();		
   							notif('Notifikasi',data1,'success');
							$("#proses-daftar-wp").removeAttr('disabled');
					
  					});
				}
  			});
	
}
  		
});
</script>
<div class="">
<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			
				<h2>Pendaftaran Wajib Pajak</h2>
			<div class="x_title">
			</div>
			<form id="frm-daftar-wp" name="frm-daftar-wp" data-parsley-validate class="form-horizontal form-label-left">
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NIK
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" maxlength="16" id="nik" name="nik" class="form-control col-md-7 col-xs-12" placeholder="Nomor Induk Kependudukan" onkeypress="return hanyaAngka(event)">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NPWP
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="npwp" name="npwp" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pokok Wajib Pajak">
    	</div>
    </div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama WP
        </label>
       	<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="nama-wp" name="nama-wp" required="required" class="form-control col-md-7 col-xs-12" placeholder="Nama Wajib Pajak">
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat
		</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<textarea id="alamat" required="required" name="alamat" class="form-control col-md-7 col-xs-12" placeholder="Alamat Lengkap"></textarea>
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kelurahan / Desa
		</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="kelurahan" name="kelurahan" class="form-control col-md-7 col-xs-12"  placeholder="Kelurahan Wajib Pajak">
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kecamatan
		</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="kecamatan" name="kecamatan" class="form-control col-md-7 col-xs-12"  placeholder="Kecamatan Wajib Pajak">
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kab / Kota
		</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="kota" name="kota" class="form-control col-md-7 col-xs-12"  placeholder="Kabupaten / Kota Wajib Pajak">
        </div>
	</div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No Telp / HP
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="no-telp" name="no-telp" required="required" class="form-control col-md-7 col-xs-12" placeholder="Nomor Telephone / HP">
        </div>
	</div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis WP</label>
       <div class="col-md-6 col-sm-6 col-xs-12">
        	<select name="jenis-wp" id="jenis-wp" class="select2_single" style="width:200px">
				<option value="01 - Orang Pribadi">01 - Orang Pribadi</option>
				<option value="02 - Badan">02 - Badan</option>
			</select>
        </div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Pendaftaran</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tgl-daftar" name="tgl-daftar" class="form-control has-feedback-left" placeholder="Tanggal Pendaftaran" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	
	</div>
	
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-daftar-wp" type="button" class="btn btn-success">Simpan</button>
        </div>
    </div>
</form>
		</div>
	</div>
</div>
<?php
include_once $_SESSION['base_dir']."footer.php";
?>
</div>
