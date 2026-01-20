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
<script src="js/pemisahribuan.js" type="text/javascript"></script>
<script>

$(document).ready(function () {                		
			$('#resetfrm').click(function() {				
				$("#no-surat").removeAttr('readonly');
				$("#no-surat").focus();
			});

		$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });		
	
		$("#tanda-tangan").select2("val", "");	
			$('#proses-skpdkb').click(function(){
				if($("#nop").val() == '' || $("#no-surat").val() == '' || $("#tanda-tangan").val() == '' || $("#pajak-bayar").val() == '' ) {
					notif('Notifikasi','Isi Semua Data','error');
				} else {
					$("#proses-skpdkb").attr("disabled", "disabled");
					$.post( "modul/skpdkb/proses_simpan_skpdkb.php", { no_surat: $('#no-surat').val(), denda: $("#denda").val(), pajak_kurang: $("#pajak-kurang").val(), nik: $("#nik").val() ,dasar_skpdkb: $("#dasar-skpdkb").val() , nop: $("#nop").val() , tanda_tangan: $("#tanda-tangan").val(), namawp: $("#nama-wp").val(), pajak_bayar: $("#pokok-pajak").val(),pajak_seharusnya: $("#pajak-seharusnya").val(), kd_kecamatan: $("#kd-kecamatan").val(), kd_kelurahan: $("#kd-kelurahan").val(), kd_keg_usaha: $("#kd-keg-usaha").val()})
  						.done(function( data1 ) {							
							var obj = jQuery.parseJSON(data1);														
							if( obj.status == "ok") {
								notif('Notifikasi',obj.msg,'success');
							} 
							else if( obj.status == "update") {
								notif('Notifikasi',obj.msg,'success');
							}
							else if( obj.status == "bayar") {
								notif('Notifikasi',obj.msg,'warning');
							}
							else {
								$("#no-surat").removeAttr('readonly');
								notif('Notifikasi',obj.error_msg,'error');									
							}
							$('#resetfrm').click();
							$("#proses-skpdkb").removeAttr('disabled');
							$("#tanda-tangan").select2("val", "");	
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
   					$.post( "modul/skpdkb/get_info_surat.php", { no_surat: $('#no-surat').val() })
  						.done(function( data1 ) {							
							var obj = jQuery.parseJSON(data1);														
							if( obj.status == "ok") {
								switch(obj.status_pembayaran) {
									case '0':
										$("#no-surat").removeAttr('readonly');
										notif('Notifikasi','No Surat '+$("#no-surat").val()+' Belum Dibayar','notice');										
										break;
									case '1':
										$("#jenis-surat").val(obj.jns_surat);
										$("#jenis-pd").val(obj.jns_pajak);
										$("#nop").val(obj.nop);
										$("#nama-objek").val(obj.nm_objek);
										$("#npwpd").val(obj.npwpd);
										$("#nik").val(obj.nik);
										$("#nama-wp").val(obj.nm_wp);
										$("#alamat-wp").val(obj.alamat_wp);
										$("#pokok-pajak").val(obj.pokok_pajak);
										$("#pajak").val(obj.pajak);
										$("#dasar").val(obj.dasar);
										$("#denda").val(obj.denda);
										$("#kd-kecamatan").val(obj.kd_kecamatan);
										$("#kd-kelurahan").val(obj.kd_kelurahan);
										$("#kd-keg-usaha").val(obj.kd_keg_usaha);
										$("#jml-bayar").val(obj.jml_bayar);
										$("#masa-pajak").val(obj.masa_pajak);
										$("#no-bukti").focus();
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
function cleanNumberToInt(str) {
  return parseInt(str.replace(/\D/g, ''), 10);
}
function formatNumberID(num) {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}


$("#pajak-seharusnya").keyup(function(){
   var a = cleanNumberToInt($("#pajak-seharusnya").val());
   var b = cleanNumberToInt($("#pokok-pajak").val());
   var c = a-b;
 $("#pajak-kurang").val(formatNumberID(c));
 });

</script>
<div class="">
<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
				<h2>Penetapan SKPDKB Pajak Daerah</h2>
			<div class="x_title">
			</div>
			
<form id="frm-bayar-pd" name="frm-bayar-pd" data-parsley-validate class="form-horizontal form-label-left">	
	<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No SKPDKB</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="no-skpdkb" name="no-skpdkb" class="form-control col-md-7 col-xs-12" placeholder="Nomor SKPDKB" readonly="readonly">
    </div>
</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No SKP/SPTPD/STPD/SSPD
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
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Masa Pajak
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="masa-pajak" name="masa-pajak" class="form-control col-md-7 col-xs-12" placeholder="Bulan - Tahun" readonly="readonly">
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
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NIK
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nik" name="nik" class="form-control col-md-7 col-xs-12" placeholder="Nomor Induk Kependudukan" readonly="readonly">
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
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat WP
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		
			<textarea id="alamat-wp" name="alamat-wp" class="form-control col-md-7 col-xs-12" placeholder="Alamat Wajib Pajak" readonly="readonly"></textarea>
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Dasar Pengenaan Pajak
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="dasar" name="dasar" class="form-control col-md-7 col-xs-12" placeholder="Dasar Pengenaan" readonly="readonly">
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
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Dasar Pengenaan Pajak Baru
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="dasar-skpdkb" name="dasar-skpdkb" class="form-control col-md-7 col-xs-12" placeholder="Dasar Pengenaan SKPDKB" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jumlah Pajak Seharusnya Dibayar
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="pajak-seharusnya" name="pajak-seharusnya" class="form-control col-md-7 col-xs-12" placeholder="Jumlah Pajak Yang Seharusnya Dibayar" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jumlah Pajak Sudah DiBayar
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="jml-bayar" name="jml-bayar" class="form-control col-md-7 col-xs-12" placeholder="Jumlah Bayar" readonly="readonly" >
			<input type="hidden" id="pokok-pajak" name="pokok-pajak" class="form-control col-md-7 col-xs-12" placeholder="Pokok Pajak" readonly="readonly" >
			<input type="hidden" id="kd-kecamatan" name="kd-kecamatan" class="form-control col-md-7 col-xs-12" placeholder="Kecamatan" readonly="readonly" >
			<input type="hidden" id="kd-kelurahan" name="kd-kelurahan" class="form-control col-md-7 col-xs-12" placeholder="Kelurahan" readonly="readonly" >
			<input type="hidden" id="kd-keg-usaha" name="kd-keg-usaha" class="form-control col-md-7 col-xs-12" placeholder="Keg Usaha" readonly="readonly" >
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jumlah Kekurangan Pajak
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="pajak-kurang" name="pajak-kurang" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Jumlah Pajak Kurang Bayar" value="0" >
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
			?>
			</select>
    </div>
</div>
	
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-skpdkb" type="button" class="btn btn-success">Simpan</button>
			<button id="resetfrm" type="reset" class="btn btn-primary">Reset</button>
        </div>
    </div>
</form>
</div>
</div>
</div>
</div>
<?php
pg_close($dbconn);
include_once $_SESSION['base_dir']."footer.php";
?>