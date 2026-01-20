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
			
			$('#tgl-setor').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });									
			

	
		$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });		
	
$("#nip-pemungut").select2("val", "");	
			$('#proses-pembayaran').click(function(){
				if($("#nop").val() == '' || $("#no-bukti").val() == '' || $("#tgl-bayar").val() == '' || $("#nip-pemungut").val() == '' ) {
					notif('Notifikasi','Isi Semua Data','error');
				} else {
					$("#proses-pembayaran").attr("disabled", "disabled");
					$.post( "modul/pembayaran/proses_pembayaran.php", { no_surat: $('#no-surat').val(), pokok_pajak: $("#pokok-pajak").val(), denda: $("#denda").val(), tgl_bayar: $("#tgl-bayar").val(), no_bukti: $("#no-bukti").val() , nip_pemungut: $("#nip-pemungut").val() , no_setor: $("#no-setor").val() , tgl_setor: $("#tgl-setor").val(), namawp: $("#nama-wp").val(), nmobj: $("#nama-objek").val(), alamatwp: $("#alamat-wp").val(),mtgkey: $("#mtgkey").val()})
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
			$("#no-surat").enterKey(function () {
				var cek_char = $('#no-surat').val();
    			if (cek_char.indexOf('_') == -1) {
					$("#no-surat").attr("readonly", "readonly");
   					$.post( "modul/pembayaran/get_info_surat.php", { no_surat: $('#no-surat').val() })
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
										$("#alamat-wp").val(obj.alamat_wp);
										$("#pokok-pajak").val(obj.pokok_pajak);
										$("#denda").val(obj.denda);
										$("#mtgkey").val(obj.mtgkey);
										$("#jml-bayar").val(obj.jml_bayar);
										$("#nip-pemungut").val(obj.nip_pemungut);
										$("#no-bukti").focus();
										break;
									case '1':
										$("#no-surat").removeAttr('readonly');
										notif('Notifikasi','No Surat '+$("#no-surat").val()+' Sudah Dibayar','notice');										
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
</script>
<div class="">
<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
				<h2>Pembayaran Pajak Daerah</h2>
			<div class="x_title">
			</div>
			
<form id="frm-bayar-pd" name="frm-bayar-pd" data-parsley-validate class="form-horizontal form-label-left">	
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
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat WP
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="alamat-wp" name="alamat-wp" class="form-control col-md-7 col-xs-12" placeholder="Alamat Wajib Pajak" readonly="readonly">
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
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jumlah Bayar
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="jml-bayar" name="jml-bayar" class="form-control col-md-7 col-xs-12" placeholder="Jumlah Bayar" readonly="readonly">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">MTGKEY
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="mtgkey" name="mtgkey" class="form-control col-md-7 col-xs-12" placeholder="MTGKEY" readonly="readonly">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No Bukti
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="no-bukti" name="no-bukti" class="form-control col-md-7 col-xs-12" placeholder="Nomor Bukti">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Pemungut
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<select name="nip-pemungut" id="nip-pemungut" class="select2_single" style="width:200px">
			<?php
$query = "SELECT nama,nip FROM public.user where pemungut='3' order by nama ASC";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());		
	while ($row = pg_fetch_array($result))
			{
				echo "<option value=\"".$row['nip']."\">".$row['nama']."</option>";	
			}
?>
</select>
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Bayar</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tgl-bayar" name="tgl-bayar" class="form-control has-feedback-left" placeholder="Tanggal Bayar" aria-describedby="inputSuccess2Status" data-inputmask="'mask': '99-99-9999'">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No Setoran
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="no-setor" name="no-setor" class="form-control col-md-7 col-xs-12" placeholder="Nomor Setoran">
    	</div>
    </div>
	
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Setoran</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tgl-setor" name="tgl-setor" class="form-control has-feedback-left" placeholder="Tanggal Setoran" aria-describedby="inputSuccess2Status" data-inputmask="'mask': '99-99-9999'">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	
	</div>
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-pembayaran" type="button" class="btn btn-success">Bayar</button>
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