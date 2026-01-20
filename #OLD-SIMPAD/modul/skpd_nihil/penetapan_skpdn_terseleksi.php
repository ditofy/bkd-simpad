<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	die('Silahkan Login');
}
?>
<link type="text/css" href="css/jquery-ui.css" rel="stylesheet" />
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.numeric.min.js"></script>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script>

$(document).ready(function () {   
			$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
				             		
			$('#resetfrm').click(function() {				
				$("#no-surat").removeAttr('readonly');
				$("#no-surat").focus();
				$("#tanda-tangan").select2("val", "");
			});
			
			$('#tgl-penetapan').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
												
			
			$('#proses-pembayaran').click(function(){
				if($("#nop").val() == '' || $("#no-bukti").val() == '' || $("#tgl-bayar").val() == '' || $("#tanda-tangan").val() == null || $("#tgl-penetapan").val() == '') {
					notif('Notifikasi','Isi Semua Data','error');
				} else {
					$("#proses-pembayaran").attr("disabled", "disabled");
					$.post( "modul/skpd_nihil/proses_penetapan_skpdn.php", { no_surat: $('#no-surat').val(), pokok_pajak: $("#pokok-pajak").val(), denda: $("#denda").val(), tgl_bayar: $("#tgl-bayar").val(), no_bukti: $("#no-bukti").val() })
  						.done(function( data1 ) {							
							var obj = jQuery.parseJSON(data1);														
							if( obj.status == "ok") {
								notif('Notifikasi',obj.msg,'success');
							} else {
								$("#no-surat").removeAttr('readonly');
								notif('Notifikasi',obj.error_msg,'error');									
							}
							$('#resetfrm').click();
							$("#proses-pembayaran").removeAttr('disabled');
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
			$("#tanda-tangan").select2("val", "");
			$("#no-surat").enterKey(function () {
				var cek_char = $('#no-surat').val();
    			if (cek_char.indexOf('_') == -1) {
					$("#no-surat").attr("readonly", "readonly");
   					$.post( "modul/skpd_nihil/get_info_sptpd.php", { no_surat: $('#no-surat').val() })
  						.done(function( data1 ) {							
							var obj = jQuery.parseJSON(data1);														
							if( obj.status == "ok") {
								switch(obj.status_pembayaran) {
									case '0':
										$("#no-surat").removeAttr('readonly');
										notif('Notifikasi','No Surat '+$("#no-surat").val()+' Belum Melakukan Pembayaran','notice');										
										break;
									case '1':										
										$("#jenis-surat").val(obj.jns_surat);
										$("#jenis-pd").val(obj.jns_pajak);
										$("#nop").val(obj.nop);
										$("#nama-objek").val(obj.nm_objek);
										$("#npwpd").val(obj.npwpd);
										$("#nama-wp").val(obj.nm_wp);
										$("#pokok-pajak").val(obj.pokok_pajak);
										$("#denda").val(obj.denda);
										$("#jml-bayar").val(obj.jml_bayar);
										$("#no-bukti").val(obj.no_bukti);
										$("#tgl-bayar").val(obj.tgl_bayar);
										$("#tanda-tangan").focus();
										break;
									case '2':
										$("#no-surat").removeAttr('readonly');
										notif('Notifikasi','No Surat '+$("#no-surat").val()+' Sudah Dibatalkan','notice');										
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
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No SPTPD
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="no-surat" name="no-surat" class="form-control col-md-7 col-xs-12" placeholder="No Surat" data-inputmask="'mask': '99.9999.99.99.9999'">
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
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Denda
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="denda" name="denda" class="form-control col-md-7 col-xs-12" placeholder="Denda" readonly="readonly">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jumlah Yang Sudah Dibayar
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="jml-bayar" name="jml-bayar" class="form-control col-md-7 col-xs-12" placeholder="Jumlah Bayar" readonly="readonly">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No Bukti
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="no-bukti" name="no-bukti" class="form-control col-md-7 col-xs-12" placeholder="Nomor Bukti" readonly="readonly">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Bayar</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tgl-bayar" name="tgl-bayar" class="form-control has-feedback-left" placeholder="Tanggal Bayar" aria-describedby="inputSuccess2Status" data-inputmask="'mask': '99-99-9999'" readonly="readonly">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	</div>
	<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Pejabat </label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="tanda-tangan" id="tanda-tangan" class="select2_single">
			<?php
			include_once $_SESSION['base_dir']."inc/db.inc.php";
			$sql = "SELECT A.nip,B.jabatan FROM public.tt A INNER JOIN public.user B ON B.nip=A.nip";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
				echo "<option value=\"".$row['nip']."\">".$row['jabatan']."</option>";				
			}
			pg_free_result($tampil);
			pg_close($dbconn);
			?>
			</select>
    	</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Penetapan</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tgl-penetapan" name="tgl-penetapan" class="form-control has-feedback-left" placeholder="Tanggal Penetapan" aria-describedby="inputSuccess2Status" data-inputmask="'mask': '99-99-9999'">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	</div>
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-pembayaran" type="button" class="btn btn-success">Tetapkan</button>
			<button id="resetfrm" type="reset" class="btn btn-primary">Reset</button>
        </div>
    </div>
</form>