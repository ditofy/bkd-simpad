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
<script src="js/jquery.numeric.min.js"></script>
<script>
function get_kel(kd) {
	if(kd != ""){
	$.ajax({
                	type: "GET",
                	url: "inc/get_kel.php",
                	data: 'kd='+kd,
               		success: function(data){
						$('#kd-kelurahan').html(data);
						$("#kd-kelurahan").select2("val", "");
                	}
            });	
	}		
};

$(document).ready(function () {
  $(".select2_single").select2({
                    placeholder: "Pilih ",
                    allowClear: false
                });
				
	$("#nop").inputmask();
	$("#no-pelayanan").inputmask();		
	$("#jenis-layanan").select2("val", "");					

       $(".select2_group").select2({})
	   
       $(".select2_multiple").select2({
                    maximumSelectionLength: 4,
                    placeholder: "With Max Selection limit 4",
                    allowClear: true
                });
				
		
	$.fn.enterKey = function (fnc) {
    return this.each(function () {
        $(this).keypress(function (ev) {
            var keycode = (ev.keyCode ? ev.keyCode : ev.which);
            if (keycode == '13') {
                fnc.call(this, ev);
            }
        })
    })
}				
			
			$("#no-pelayanan").enterKey(function () {
				var cek_char = $('#no-pelayanan').val();
    			if (cek_char.indexOf('_') == -1) {
   					$.post( "modul/bphtb/get_data_pelayanan.php", { nomor: $('#no-pelayanan').val(), jns_layanan: $('#jenis-layanan').val() })
  						.done(function( data1 ) {							
							var obj = jQuery.parseJSON(data1);														
							if( obj.status == "ok") {
							            $("#nik").val(obj.nik);
										$("#nama-wp").val(obj.nama_wp);
										$("#alamat").val(obj.alamat_wp);
										$("#layanan").val(obj.layanan);
										$("#tgl-selesai").focus();
	
								
							} else {
								$("#no-surat").removeAttr('readonly');
								notif('Notifikasi',obj.error_msg,'error');									
							}
							//$('#reset').click();														
  					});
				} else {
					notif('Notifikasi','No Surat Belum Lengkap','error');
				}
			});
			

			$('#resetfrm').click(function(){
				$("#nik").val("");
				$("#nama-wp").val("");
				$("#no-pelayanan").val("");
				
			});
			
			$('#tgl-selesai').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
			
			$('#proses-update-berkas').click(function(){
if($('#jenis-layanan').val() == null || $('#no-pelayanan').val() == '' || $('#tgl-selesai').val() == '' || $('#nama-ambil').val() == ''){
	notif('Notifikasi','Isi Semua Data','error');			
} else {
	$("#proses-update-berkas").attr("disabled", "disabled");
	var inputs = $('#frm-update-berkas').serializeArray();
					$.post( "modul/bphtb/p_update_berkas.php", { postdata: inputs })
  						.done(function( data1 ) {
   							notif('Notifikasi',data1,'success');
							$('#resetfrm').click();
							$("#proses-update-berkas").removeAttr('disabled');
  					});
}	
});
			
});
</script>
<form id="frm-update-berkas" name="frm-update-berkas" data-parsley-validate class="form-horizontal form-label-left">
<div class="x_panel">
                            <div class="x_title">
            <h2>UPDATE PELAYANAN SELESAI</h2>   <div class="clearfix"></div>
		<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis Pelayanan
        </label>
       	<div class="col-md-6 col-sm-6 col-xs-12">
			<select name="jenis-layanan" id="jenis-layanan" class="select2_single" style="width:300px" onChange="">
				<option value="01">01 - PBB - P2</option>
				<option value="02">02 - BPHTB</option>
			</select>
        </div>
	</div>
		<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nomor Pelayanan
        </label>
       	<div class="col-md-3 col-sm-6 col-xs-12">
			<input type="text" id="no-pelayanan" name="no-pelayanan" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pelayanan">
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NIK
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nik" name="nik" class="form-control col-md-7 col-xs-12" placeholder="Nomor Induk Kependudukan" readonly="readonly">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama WP
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-wp" name="nama-wp" class="form-control col-md-7 col-xs-12"  placeholder="Nama Wajib Pajak" readonly="readonly">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat
		</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="alamat" name="alamat" class="form-control col-md-7 col-xs-12"  placeholder="Alamat Wajib Pajak" readonly="readonly">
        </div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Layanan
		</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="layanan" name="layanan" class="form-control col-md-7 col-xs-12"  placeholder="Jenis Layanan" readonly="readonly">
        </div>
	</div>
	</div></div>
	

	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Selesai</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tgl-selesai" name="tgl-selesai" class="form-control has-feedback-left" placeholder="Tanggal Pengambilan Berkas" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Pengambil Berkas
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-ambil" name="nama-ambil" class="form-control col-md-7 col-xs-12"  placeholder="Nama Pengambil Berkas">
    	</div>
    </div>
	</div></div>

	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-update-berkas" type="button" class="btn btn-success">Update</button>
			<button id="resetfrm" type="reset" class="btn btn-primary">Reset</button>
        </div>
    </div>
</form>
<?php
pg_close($dbconn);
?>