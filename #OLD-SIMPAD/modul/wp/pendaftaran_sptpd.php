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
			
			$('#reset').click(function(){
				$("#npwpd").removeAttr('readonly');
				$("#proses-update-wp").removeAttr('disabled');
			});
			
			$('#nik').autocomplete({
      			source: "inc/get_nik.php",
				select: function(event, ui) {
            		if (ui.item.value != 'Data Tidak Ada')  {
					$("#npwpd").attr("readonly", "readonly");
				$.ajax({
                	type: "GET",
                	url: "inc/get_detail_wp_nik.php",
                	data: 'search='+ui.item.value,
               		success: function(data){
						var obj = jQuery.parseJSON(data);
						$('#nik').val(obj[0].nik);
						$('#npwpd').val(obj[0].npwpd);
						$('#nama-wp').val(obj[0].nama);
						$('#alamat').val(obj[0].alamat);
						$('#kelurahan').val(obj[0].kelurahan);
						$('#kecamatan').val(obj[0].kecamatan);
						$('#kota').val(obj[0].kota);
						$('#npwp').val(obj[0].npwp);
						$('#no-telp').val(obj[0].telp);
						$('#tgl-daftar').val(obj[0].tgl_daf);
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						notif('Notifikasi',ajaxOptions+' '+xhr.status+' : '+thrownError,'error');
      				}
            	});
			} 	
				}
    		});
				
            });
$('#proses-daftar-user').click(function(){
if($('#nik').val() == '' || $('#nama-wp').val() == '' || $('#alamat').val() == '' || $('#kelurahan').val() == '' || $('#kecamatan').val() == '' || $('#kota').val() == '' || $('#no-telp').val() == '' || $('#jenis-wp').val() == '' || $('#npwp').val() == ''|| $('#tgl-daftar').val() == ''){
	notif('Notifikasi','Isi Semua Data','error');			
} else {
	$("#proses-daftar-user").attr("disabled", "disabled");
	var varnik = $('#nik').val();
	$.post( "inc/get_user.php", { nik: varnik })
  				.done(function( data ) {
				if(data != '[]'){
					var obj = jQuery.parseJSON(data);
   					notif('ERROR','NIK sudah terdaftar dengan NPWPD : '+obj[0].npwpd,'error');
					$("#proses-daftar-wp").removeAttr('disabled');
				} else {
					var inputs = $('#frm-tambah-user').serializeArray();
					$.post( "modul/wp/p_tambah_user.php", { postdata: inputs })
  						.done(function( data1 ) {
						var obj = jQuery.parseJSON(data1);
							if( obj.status == "ok") {
								notif('Notifikasi','User Berhasil Disimpan NPWPD : '+obj.npwpd,'success');
								$("#password").val(obj.pass);
								//$("#cetak-user").removeAttr('disabled');
							} else {
								notif('Notifikasi',obj.error_msg,'error');	
							}
							
								
   							
					
  					});
				}
  			});
	
}
  		
});

$('#proses-cetak-user').click(function(){
	if($('#npwpd').val() == ''){
		$('#modal-cnt').html("NPWPD Tidak Ditemukan");			
	} else {
		$('#modal-cnt').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");		
			$.post( "modul/wp/gen_pdf_cetak_user.php", { npwpd: $('#npwpd').val(), nama: $('#nama-wp').val(), pass: $('#password').val()})
  						.done(function( data1 ) {
							$('#modal-cnt').html("<object type='application/pdf' data='"+data1+"' width='100%' height='500'>this is not working as expected</object>");
		});
	}
  		
});

</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">NPWPD</h4>
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
			
				<h2>Penambahan User SPTPD</h2>
			<div class="x_title">
			</div>
			<form id="frm-tambah-user" name="frm-tambah-user" data-parsley-validate class="form-horizontal form-label-left">
			<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NIK
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nik" name="nik" class="form-control col-md-7 col-xs-12" placeholder="Nomor Induk Kependudukan">
    	</div>
    </div>
			<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NPWPD
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="npwpd" name="npwpd" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pokok Wajib Pajak Daerah">
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
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Email
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="email" name="email" required="required" class="form-control col-md-7 col-xs-12" placeholder="Alamat E Mail">
        </div>
	</div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Password
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="password" name="password" required="required" class="form-control col-md-7 col-xs-12" placeholder="Password" readonly="readonly">
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
        	<button id="proses-daftar-user" type="button" class="btn btn-success">Tambah</button>
			<button id="proses-cetak-user" type="button" class="btn btn-warning" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak User SPTPD</button>
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
