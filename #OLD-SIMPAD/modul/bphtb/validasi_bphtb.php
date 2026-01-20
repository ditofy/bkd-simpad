<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
?>
<link type="text/css" href="css/jquery-ui.css" rel="stylesheet" />
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.numeric.min.js"></script>
<script src="js/input_mask/jquery.inputmask.js"></script>
<script src="js/pemisahribuan.js" ></script>
<script>
$(document).ready(function () {
	$(".select2_single").select2({
                    placeholder: "Pilih                      ",
                    allowClear: false
                });
 	$("#no-pela").inputmask();	
	
	
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
	
		$("#no-pela").enterKey(function () {
				var cek_char = $('#no-pela').val();
    			if (cek_char.indexOf('_') == -1) {
					
   					$.post( "modul/bphtb/get_data_no_bphtb.php", { nomor: $('#no-pela').val() })
  						.done(function( data1 ) {							
							var obj = jQuery.parseJSON(data1);														
							if( obj.status == "ok") {
							            $("#nik").val(obj.nik);
										$("#nama-wp").val(obj.nama_wp);
										$("#alamat").val(obj.alamat_wp);
										$("#kelurahan").val(obj.kelurahan);
										$("#kecamatan").val(obj.kecamatan);
										$("#kota").val(obj.kota);
										$("#no-telp").val(obj.no_telp);
										$("#luas-bumi-transaksi").val(obj.luas_bumi_trk);
										$("#luas-bng-transaksi").val(obj.luas_bng_trk);
										$("#nomor-sertifikat").val(obj.nomor_sertifikat);
										$("#harga-trk").val(obj.harga_trk);
										$("#akumulasi").val(obj.akumulasi);
										$("#pengurangan").val(obj.pengurangan);
										$("#npwpd").val(obj.npwpd);
										$("#tgl-pela").val(obj.tgl_verifikasi);
										$("#nop").val(obj.nop);
										$("#nama-sppt").val(obj.nama_sppt);
										$("#letak-op").val(obj.alamat_op);
										$("#nomor-op").val(obj.nomor_op);
										$("#rt-op").val(obj.rt_op);
										$("#rw-op").val(obj.rw_op);
										$("#nama-kel").val(obj.kelurahan_op);
										$("#nama-kec").val(obj.kecamatan_op);
										$("#njop-bumi").val(obj.njop_bumi);
										$("#njop-bangunan").val(obj.njop_bng);
										$("#luas-bumi").val(obj.luas_bumi);
										$("#luas-bangunan").val(obj.luas_bng);
										$("#nama-ppat").val(obj.nama_ppat);
										$("#npoptkp").val(obj.npoptkp);
										$("#harga-trk-val").focus();
									   
								
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
	
	
	$("#tgl-validasi").inputmask();		
	$('#tgl-validasi').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
	
	$('#proses-sspd-bphtb').click(function(){
		if( $('#harga-trk-val').val() == '' || $('#ttd').val() == '' || $('#tgl-validasi').val() == '' || $('#pengurangan').val() == '' || $('#npoptkp').val() == '' ) {
			notif('Notifikasi','Isi Semua Data','error');
		} else {
			$("#proses-sspd-bphtb").attr("disabled", "disabled");
			var inputs = $('#frm-sspd-bphtb').serializeArray();
					$.post( "modul/bphtb/penetapan_p_simpan_sspd.php", { postdata: inputs })
  							.done(function( data1 ) {
   							notif('Notifikasi',data1,'success');
							$('#resetfrm').click();
							$("#proses-daftar-bphtb").removeAttr('disabled');
  					});
						}	//$('#reset').click();														
  					});
		

	
	$('#resetfrm').click(function(){
		$('#frm-sspd-bphtb')[0].reset();
		$("#no-sspd").val('');		
		$("#ttd").select2("val", "");
		$("#npoptkp").select2("val", "");
		$("#t-pengurangan").select2("val", "");
		$("#cetak").attr("disabled", "disabled");
		$("#proses-sspd-bphtb").removeAttr('disabled');
		$("#no-pela").removeAttr('readonly');
	});
	
	

	$("#ttd").select2("val", "");
	$("#t-pengurangan").select2("val", "");
	
	$("#cetak").attr("disabled", "disabled");
});
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                           
				<h4 class="modal-title" id="myModalLabel">SSPD BPHTB</h4>
			</div>
			<div class="modal-body" id="modal-cnt">
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="batal">Tutup</button>
			</div>
		</div>
	</div>
</div>
								
<form id="frm-sspd-bphtb" name="frm-sspd-bphtb" data-parsley-validate class="form-horizontal form-label-left">
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No SSPD</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="no-sspd" name="no-sspd" class="form-control col-md-7 col-xs-12" placeholder="Nomor SSPD" readonly="readonly">
    </div>
</div>


<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NO PELAYANAN</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="no-pela" name="no-pela" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pelayanan " data-inputmask="'mask': '9999.9999'">
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">N P O P</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="harga-trk-val" name="harga-trk-val" class="form-control col-md-7 col-xs-12" placeholder="Harga NPOP Validasi" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NPOPTKP</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="npoptkp" name="npoptkp" class="form-control col-md-7 col-xs-12" placeholder="NPOPTKP" readonly="readonly">
    </div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">PENGURANGAN </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="t-pengurangan" id="t-pengurangan" class="select2_single" style="width:200px">
		<?php
			include_once $_SESSION['base_dir']."inc/db.inc.php";
			$sql = "SELECT * FROM public.tarif WHERE kd_obj_pajak='09' ORDER BY kd_obj_pajak,kd_tarif";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
				$persen_tarif = $row['tarif'] * 100;
				echo "<option value=\"".$row['tarif']."\">".$persen_tarif."%</option>";				
			}
			pg_free_result($tampil);
			
		?>
		</select>
    </div>
</div>
<!--<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NPOPTKP </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="npoptkp" id="npoptkp" class="select2_single">
			<?php
			/*include_once $_SESSION['base_dir']."inc/db.inc.php";
			$sql = "SELECT * FROM public.tarif WHERE kd_obj_pajak='09' ORDER BY kd_obj_pajak,kd_tarif";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
				$persen_tarif = $row['tarif'] * 100;
				echo "<option value=\"".$row['tarif']."\">".$persen_tarif."%</option>";				
			}
			pg_free_result($tampil);
			*/
		?>
			
			<option value="0">0</option>
			<option value="60000000">60.000.000</option>	
			<option value="300000000">300.000.000</option>			
			</select>
    </div>
</div>-->
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Pejabat </label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="ttd" id="ttd" class="select2_single">
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
<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Validasi</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tgl-validasi" name="tgl-validasi" class="form-control has-feedback-left" placeholder="Tanggal Penetapan" aria-describedby="inputSuccess2Status" data-inputmask="'mask': '99-99-9999'">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>	
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NIK</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="nik" name="nik" class="form-control col-md-7 col-xs-12" placeholder="Nomor Identitas Kependudukan" readonly="readonly">
	</div>
</div>
<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NPWPD</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="npwpd" name="npwpd" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pokok Wajib Pajak Daerah" readonly="readonly">
	</div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama WP</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="nama-wp" name="nama-wp" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Nama Wajib Pajak">
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat</label>
	<div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="alamat" name="alamat" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Alamat Wajib Pajak">
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NOP</label>
	<div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="nop" name="nop" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="NOP PBB">
    </div>
</div>


<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama SPPT</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="nama-sppt" name="nama-sppt" class="form-control col-md-7 col-xs-12" placeholder="Nama di SPPT" readonly="readonly">
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat Objek</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="letak-op" name="letak-op" class="form-control col-md-7 col-xs-12" placeholder="Alamat Objek" readonly="readonly">
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Harga Transaksi</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="harga-trk" name="harga-trk" class="form-control col-md-7 col-xs-12" placeholder="Harga Transaksi Awal" readonly="readonly">
    </div>
</div>

<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Notaris</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<input type="text" id="nama-ppat" name="nama-ppat" class="form-control col-md-7 col-xs-12" placeholder="Nama Notaris" readonly="readonly">
    </div>
</div>


	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Pelayanan</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tgl-pela" name="tgl-pela" class="form-control has-feedback-left" placeholder="Tanggal Pelayanan" aria-describedby="inputSuccess2Status" data-inputmask="'mask': '99-99-9999'" readonly="readonly">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>	
	</div>

	
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-sspd-bphtb" type="button" class="btn btn-success">Simpan</button>
			<button id="resetfrm" type="button" class="btn btn-primary">Reset</button>
			<button id="cetak" type="button" class="btn btn-dark" data-toggle="modal" data-target=".bs-example-modal-lg">Cetak SKP</button>			
        </div>
    </div>
</form>