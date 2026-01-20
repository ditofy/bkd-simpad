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
	$("#kd-ppat").select2("val", "");	
	$("#kd-transaksi").select2("val", "");	
	$("#status").select2("val", "");	
	$("#pengurangan").select2("val", "");				
			
	
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
						$('#nama-wp').val(obj[0].nama);
						$('#alamat').val(obj[0].alamat);
						$('#no-telp').val(obj[0].telp);
						$('#kelurahan').val(obj[0].kelurahan);
						$('#kecamatan').val(obj[0].kecamatan);
						$('#kota').val(obj[0].kota);
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						notif('Notifikasi',ajaxOptions+' '+xhr.status+' : '+thrownError,'error');
      				}
            	});
			} 	
				}
    		});	
	
	
            
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
				var cek_char = $('#nop').val();
    			if (cek_char.indexOf('_') == -1) {
					
   					$.post( "modul/bphtb/get_data_no_bphtb.php", { nomor: $('#no-pelayanan').val() })
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
										$("#id-pln").val(obj.id_pln);
										$("#nomor-sertifikat").val(obj.nomor_sertifikat);
										$("#harga-trk").val(obj.harga_trk);
										$("#akumulasi").val(obj.akumulasi);
										$("#pengurangan").val(obj.pengurangan);
										$("#tgl-verifikasi").val(obj.tgl_verifikasi);
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
										$("#kd-ppat").focus();
									
									
								
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
			
			
			$("#nop").enterKey(function () {
				var cek_char = $('#nop').val();
    			if (cek_char.indexOf('_') == -1) {
					
   					$.post( "inc/get_data_nop_pbb.php", { nop: $('#nop').val() })
  						.done(function( data1 ) {							
							var obj = jQuery.parseJSON(data1);														
							if( obj.status == "ok") {
							            $("#nop").val(obj.nop);
										$("#nama-sppt").val(obj.nama_sppt);
										$("#letak-op").val(obj.letak_op);
										$("#nomor-op").val(obj.nomor_op);
										$("#rt-op").val(obj.rt_op);
										$("#rw-op").val(obj.rw_op);
										$("#nama-kel").val(obj.nama_kel);
										$("#nama-kec").val(obj.nama_kec);
										$("#njop-bumi").val(obj.njop_tanah);
										$("#njop-bangunan").val(obj.njop_bng);
										$("#luas-bumi").val(obj.luas_bumi);
										$("#luas-bangunan").val(obj.luas_bng);
										$("#jml-bayar").val(obj.jml_bayar);
										$("#nip-pemungut").val(obj.nip_pemungut);
										$("#no-bukti").focus();
									
									
								
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
			
			$('#tgl-verifikasi').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
			
			$('#proses-update-bphtb').click(function(){
if($('#nik').val() == '' || $('#nama-wp').val() == '' || $('#alamat').val() == '' || $('#kelurahan').val() == '' || $('#kecamatan').val() == '' || $('#kota').val() == '' || $('#nama-sppt').val() == '' || $('#kd-ppat').val() == null || $('#kd-transaksi').val() == null || $('#luas-bng-transaksi').val() == '' || $('#luas-bumi-transaksi').val() == '' || $('#harga-trk').val() == '' || $('#nomor-transaksi').val() == '' || $('#akumulasi').val() == '' || $('#pengurangan').val() == null || $('#no-pelayanan').val() == '' || $('#tgl-verifikasi').val() == '' || $('#status').val() == null || $('#kd-sh').val() == null){
	notif('Notifikasi','Isi Semua Data','error');			
} else {
	$("#proses-update-bphtb").attr("disabled", "disabled");
	var inputs = $('#frm-update-bphtb').serializeArray();
					$.post( "modul/bphtb/p_update.php", { postdata: inputs })
  						.done(function( data1 ) {
   							notif('Notifikasi',data1,'success');
							$('#resetfrm').click();
							$("#proses-update-bphtb").removeAttr('disabled');
  					});
}	
});
			
			
				
			
				
			
});
</script>
<form id="frm-update-bphtb" name="frm-update-bphtb" data-parsley-validate class="form-horizontal form-label-left">
<div class="x_panel">
                                <div class="x_title">
                                    <h2>NO PELAYANAN</h2>   <div class="clearfix"></div>
									<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nomor Pelayanan
        </label>
       	<div class="col-md-3 col-sm-6 col-xs-12">
			<input type="text" id="no-pelayanan" name="no-pelayanan" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pelayanan " data-inputmask="'mask': '9999.9999'">
        </div>
	</div>
	</div></div>

	 <div class="x_panel">
                                <div class="x_title">
                                    <h2>DATA WAJIB PAJAK</h2>   <div class="clearfix"></div>
									
  <div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NIK
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nik" name="nik" class="form-control col-md-7 col-xs-12" placeholder="Nomor Induk Kependudukan">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama WP
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-wp" name="nama-wp" class="form-control col-md-7 col-xs-12"  placeholder="Nama Wajib Pajak">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat
		</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="alamat" name="alamat" class="form-control col-md-7 col-xs-12"  placeholder="Alamat Wajib Pajak">
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kelurahan
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
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis WP</label>
       <div class="col-md-6 col-sm-6 col-xs-12">
        	<select name="jenis-wp" id="jenis-wp" class="select2_single" style="width:200px">
				<option value="01 - Orang Pribadi">01 - Orang Pribadi</option>
				<option value="02 - Badan">02 - Badan</option>
			</select>
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No Telp
		</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="no-telp" name="no-telp" class="form-control col-md-7 col-xs-12"  placeholder="No Telepon">
        </div>
	</div>
	
	
	
	</div></div>
	
	
	
	<div class="x_panel">
                                <div class="x_title">
                                    <h2>DATA TRANSAKSI</h2>   <div class="clearfix"></div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">PPAT
        </label>
       	<div class="col-md-6 col-sm-6 col-xs-12">
			<select name="kd-ppat" id="kd-ppat" class="select2_single" style="width:300px">
			<?php 
			$sql = "SELECT * FROM public.ppat";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
			
				echo "<option value=\"".$row['id']."\">".$row['nama_ppat']."</option>";
			}
			pg_free_result($tampil);
			?>
			</select>
        </div>
	</div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis Transaksi
        </label>
       	<div class="col-md-6 col-sm-6 col-xs-12">
			<select name="kd-transaksi" id="kd-transaksi" class="select2_single" style="width:300px">
			<?php 
			$sql = "SELECT * FROM public.transaksi";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
			
				echo "<option value=\"".$row['id']."\">".$row['nm_transaksi']."</option>";
			}
			pg_free_result($tampil);
			?>
			</select>
        </div>
	</div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis Kepemilikan
        </label>
       	<div class="col-md-6 col-sm-6 col-xs-12">
			<select name="kd-sh" id="kd-sh" class="select2_single" style="width:300px">
			<?php 
			$sql = "SELECT * FROM public.keg_usaha where kd_obj_pajak='09'";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
			
				echo "<option value=\"".$row['kd_keg_usaha']."\">".$row['nm_keg_usaha']."</option>";
			}
			pg_free_result($tampil);
			?>
			</select>
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Luas Bumi 
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="luas-bumi-transaksi" name="luas-bumi-transaksi" class="form-control col-md-7 col-xs-12" placeholder="Luas Bumi Transaksi">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Luas Bangunan
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="luas-bng-transaksi" name="luas-bng-transaksi" class="form-control col-md-7 col-xs-12" placeholder="Luas Bangunan Transaksi">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">ID PLN
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="id-pln" name="id-pln" class="form-control col-md-7 col-xs-12" placeholder="Nomor Pelanggan PLN">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nomor Sertifikat
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nomor-sertifikat" name="nomor-sertifikat" class="form-control col-md-7 col-xs-12" placeholder="Nomor Sertifikat">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Harga Transaksi
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="harga-trk" name="harga-trk" class="form-control col-md-7 col-xs-12" placeholder="Harga Transaksi / Nilai Pasar">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Akumulasi Nilai Sebelumnya
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="akumulasi" name="akumulasi" class="form-control col-md-7 col-xs-12" placeholder="Akumulasi Nilai Perolehan Sebelumnya">
    	</div>
    </div>
	<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Pengurangan</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="pengurangan" id="pengurangan" class="select2_single" style="width:200px">
		<?php
			
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
	
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tanggal Verifikasi</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tgl-verifikasi" name="tgl-verifikasi" class="form-control has-feedback-left" placeholder="Tanggal Verifikasi" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	
	</div>
	
	</div></div>
	
	
	 <div class="x_panel">
                                <div class="x_title">
                                    <h2>DATA OBJEK PAJAK</h2>   <div class="clearfix"></div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NOP
        </label>
       	<div class="col-md-3 col-sm-6 col-xs-12">
			<input type="text" id="nop" name="nop" class="form-control col-md-7 col-xs-12" placeholder="NOP " data-inputmask="'mask': '99.99.999.999.999.9999.9'">
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama SPPT
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-sppt" name="nama-sppt" class="form-control col-md-7 col-xs-12"  placeholder="Nama SPPT" readonly="readonly">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Letak OP
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="letak-op" name="letak-op" class="form-control col-md-7 col-xs-12"  placeholder="Lokasi OP" readonly="readonly">
    	</div>
		
    </div>
	
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
    	</label>
    	
		<div class="col-md-2 col-sm-2 col-xs-1">
    		<input type="text" id="nomor-op" name="nomor-op" class="form-control col-md-6 col-xs-12"  placeholder="Nomor" readonly="readonly">
    	</div>
		
		<div class="col-md-1 col-sm-1 col-xs-1">
    		<input type="text" id="rt-op" name="rt-op" class="form-control col-md-3 col-xs-12"  placeholder="RT" readonly="readonly">
    	</div>
		
		<div class="col-md-1 col-sm-1 col-xs-1">
    		<input type="text" id="rw-op" name="rw-op" class="form-control col-md-1 col-xs-3"  placeholder="RW" readonly="readonly">
    	</div>
    </div>
	
		<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kelurahan
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-kel" name="nama-kel" class="form-control col-md-7 col-xs-12"  placeholder="Kelurahan OP" readonly="readonly">
    	</div>
    </div>		
<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kecamatan
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-kec" name="nama-kec" class="form-control col-md-7 col-xs-12"  placeholder="Kecamatan OP" readonly="readonly">
    	</div>
    </div>		
	
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NJOP Bumi
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="njop-bumi" name="njop-bumi" class="form-control col-md-7 col-xs-12"  placeholder="NJOP Bumi" readonly="readonly" >
    	</div>
    </div>	
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NJOP Bangunan
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="njop-bangunan" name="njop-bangunan" class="form-control col-md-7 col-xs-12"  placeholder="NJOP Bangunan" readonly="readonly">
    	</div>
    </div>	
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Luas Bumi
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="luas-bumi" name="luas-bumi" class="form-control col-md-7 col-xs-12"  placeholder="Luas Bumi" readonly="readonly">
    	</div>
    </div>	
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Luas Bangunan
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="luas-bangunan" name="luas-bangunan" class="form-control col-md-7 col-xs-12"  placeholder="Luas Bangunan" readonly="readonly">
    	</div>
    </div>	
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Status Pelayanan</label>
       <div class="col-md-6 col-sm-6 col-xs-12">
        	<select name="status" id="status" class="select2_single" style="width:200px">
				<option value="01">01 -Aktif</option>
				<option value="02">02 - Batal</option>
			</select>
        </div>
	</div>
	
	</div></div>
	
	 
	
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-update-bphtb" type="button" class="btn btn-success">Update</button>
			<button id="resetfrm" type="reset" class="btn btn-primary">Reset</button>
        </div>
    </div>
</form>
<?php
pg_close($dbconn);
?>