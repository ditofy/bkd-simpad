<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
?>
<link type="text/css" href="css/jquery-ui.css" rel="stylesheet" />
<script src="js/jquery-ui.js"></script>
<script>
$(document).ready(function () {
				$('#tgl-daftar').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
			 $(".select2_single").select2({
                    placeholder: "Pilih                      ",
                    allowClear: false
                });
			$('#reset').click(function(){
				$("#npwpd").removeAttr('readonly');
				$("#proses-update-wp").removeAttr('disabled');
			});
			
			$('#npwpd').autocomplete({
      			source: "inc/get_npwpd.php",
				select: function(event, ui) {
            		if (ui.item.value != 'Data Tidak Ada')  {
					$("#npwpd").attr("readonly", "readonly");
				$.ajax({
                	type: "GET",
                	url: "inc/get_detail_wp.php",
                	data: 'search='+ui.item.value,
               		success: function(data){
						var obj = jQuery.parseJSON(data);
						$('#nik').val(obj[0].nik);
						$('#nama-wp').val(obj[0].nama);
						$('#alamat').val(obj[0].alamat);
						$('#kelurahan').val(obj[0].kelurahan);
						$('#kecamatan').val(obj[0].kecamatan);
						$('#kota').val(obj[0].kota);
						$('#npwp').val(obj[0].npwp);
						$('#no-telp').val(obj[0].telp);
						$('#tgl-daftar').val(obj[0].tgl_daf);
						$("#status-wp").select2("val", obj[0].status_wp);
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						notif('Notifikasi',ajaxOptions+' '+xhr.status+' : '+thrownError,'error');
      				}
            	});
			} 	
				}
    		});
				
            });

$('#proses-update-wp').click(function(){
if($('#npwpd').val() == '' || $('#nik').val() == '' || $('#nama-wp').val() == '' || $('#alamat').val() == '' || $('#kelurahan').val() == '' || $('#kecamatan').val() == '' || $('#kota').val() == '' || $('#no-telp').val() == '' || $('#tgl-daftar').val() == '' || $('#npwp').val() == ''  || $("#status-wp").val() == null){
	notif('Notifikasi','Isi Semua Data','error');			
} else {
	$("#proses-update-wp").attr("disabled", "disabled");
	var varnik = $('#nik').val();
	$.post( "inc/get_wp.php", { nik: varnik })
  				.done(function( data ) {
				if(data != '[]'){
					var obj = jQuery.parseJSON(data);
					if(obj[0].npwpd == $('#npwpd').val()){
						var inputs = $('#frm-update-wp').serializeArray();
						$.post( "modul/wp/p_update_wp.php", { postdata: inputs })
  						.done(function( data1 ) {
							$('#reset').click();		
   							notif('Notifikasi',data1,'success');
							$("#proses-update-wp").removeAttr('disabled');
					
  						});
					} else {
						notif('ERROR','NIK sudah terdaftar dengan NPWPD : '+obj[0].npwpd,'error');
						$("#proses-update-wp").removeAttr('disabled');	
					}
				} else {
					var inputs = $('#frm-update-wp').serializeArray();
					$.post( "modul/wp/p_update_wp.php", { postdata: inputs })
  						.done(function( data1 ) {
							$('#reset').click();		
   							notif('Notifikasi',data1,'success');
							$("#proses-update-wp").removeAttr('disabled');
					
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
			
				<h2>Update Data Wajib Pajak</h2>
			<div class="x_title">
			</div>
			<form id="frm-update-wp" name="frm-update-wp" data-parsley-validate class="form-horizontal form-label-left">
			<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NPWPD
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="npwpd" name="npwpd" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pokok Wajib Pajak">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NIK
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nik" name="nik" class="form-control col-md-7 col-xs-12" placeholder="Nomor Induk Kependudukan">
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
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Pendaftaran</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tgl-daftar" name="tgl-daftar" class="form-control has-feedback-left" placeholder="Tanggal Pendaftaran" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Status WP
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<select name="status-wp" id="status-wp" class="select2_single" style="width:200px">
				<option value="1">1 - AKTIF</option>
				<option value="0">0 - TIDAK AKTIF</option>
			</select>
    	</div>
    </div>
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-update-wp" type="button" class="btn btn-success">Update</button>
			<button id="reset" type="reset" class="btn btn-primary">Reset</button>
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
