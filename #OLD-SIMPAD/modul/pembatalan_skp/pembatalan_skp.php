<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
?>
<link type="text/css" href="css/jquery-ui.css" rel="stylesheet" />
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.numeric.min.js"></script>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script>

$(document).ready(function () {                		
			$('#resetfrm').click(function() {				
				$("#no-surat").removeAttr('readonly');
				$("#no-surat").focus();
			});
			
			$('#tgl-bayar').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
												
			
			$('#proses-pembatalan').click(function(){
				if($("#nop").val() == '' || $("#keterangan").val() == '') {
					notif('Notifikasi','Isi Semua Data','error');
				} else {
					$("#proses-pembatalan").attr("disabled", "disabled");
					$.post( "modul/pembatalan_skp/proses_pembatalan_skp.php", { no_surat: $('#no-surat').val(), keterangan: $('#keterangan').val() })
  						.done(function( data1 ) {							
							var obj = jQuery.parseJSON(data1);														
							if( obj.status == "ok") {
								notif('Notifikasi',obj.msg,'success');
							} else {
								$("#no-surat").removeAttr('readonly');
								notif('Notifikasi',obj.error_msg,'error');									
							}
							$('#resetfrm').click();
							$("#proses-pembatalan").removeAttr('disabled');
  					});
				}
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
			$("#no-surat").inputmask();
			$("#tgl-bayar").inputmask();
			$("#no-surat").focus();
			$("#no-surat").enterKey(function () {
				var cek_char = $('#no-surat').val();
    			if (cek_char.indexOf('_') == -1) {
					$("#no-surat").attr("readonly", "readonly");
   					$.post( "modul/pembatalan_skp/get_info_skp.php", { no_surat: $('#no-surat').val() })
  						.done(function( data1 ) {							
							var obj = jQuery.parseJSON(data1);														
							if( obj.status == "ok") {
								switch(obj.status_pembayaran) {
									case '0':
										$("#jenis-surat").val(obj.jns_surat);
										$("#jenis-pd").val(obj.jns_pajak);
										$("#nop").val(obj.nop);
										$("#nama-objek").val(obj.nm_objek);
										$("#npwpd").val(obj.npwpd);
										$("#nama-wp").val(obj.nm_wp);
										$("#pokok-pajak").val(obj.pokok_pajak);										
										$("#keterangan").focus();
										break;
									case '1':
										$("#no-surat").removeAttr('readonly');
										notif('Notifikasi','SKPD No '+$("#no-surat").val()+' Sudah Dibayar','notice');										
										break;
									case '2':
										$("#no-surat").removeAttr('readonly');
										notif('Notifikasi','SKPD No '+$("#no-surat").val()+' Sudah Dibatalkan','notice');										
										break;
								}
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
});
</script>
<form id="frm-bayar-pd" name="frm-bayar-pd" data-parsley-validate class="form-horizontal form-label-left">	
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No SKPD/SSPD
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="no-surat" name="no-surat" class="form-control col-md-7 col-xs-12" placeholder="No Surat Ketetapan Pajak Daerah" data-inputmask="'mask': '99.9999.99.99.9999'">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis Surat
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="jenis-surat" name="jenis-surat" class="form-control col-md-7 col-xs-12" placeholder="Jenis Surat" readonly="readonly">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis Pajak
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="jenis-pd" name="jenis-pd" class="form-control col-md-7 col-xs-12" placeholder="Jenis Pajak" readonly="readonly">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NOP
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nop" name="nop" class="form-control col-md-7 col-xs-12" placeholder="Nomor Objek Pajak" readonly="readonly">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Objek
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-objek" name="nama-objek" class="form-control col-md-7 col-xs-12" placeholder="Nama Objek" readonly="readonly">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NPWPD
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="npwpd" name="npwpd" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pokok Wajib Pajak Daerah" readonly="readonly">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama WP
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-wp" name="nama-wp" class="form-control col-md-7 col-xs-12" placeholder="Nama Wajib Pajak" readonly="readonly">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Pokok Pajak
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="pokok-pajak" name="pokok-pajak" class="form-control col-md-7 col-xs-12" placeholder="Pokok Pajak" readonly="readonly">
    	</div>
    </div>	
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Keterangan
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="keterangan" name="keterangan" class="form-control col-md-7 col-xs-12" placeholder="Keterangan">
    	</div>
    </div>	
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-pembatalan" type="button" class="btn btn-success">Batalkan</button>
			<button id="resetfrm" type="reset" class="btn btn-primary">Reset</button>
        </div>
    </div>
</form>
<?php
pg_close($dbconn);
include_once $_SESSION['base_dir']."footer.php";
?>