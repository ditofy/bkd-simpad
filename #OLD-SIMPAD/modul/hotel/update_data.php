<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
?>
<link type="text/css" href="css/jquery-ui.css" rel="stylesheet" />
<script src="js/jquery-ui.js"></script>
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
				
				$('#nop').autocomplete({
      			source: "modul/hotel/get_nop.php",
				select: function(event, ui) {
            		if (ui.item.value != 'Data Tidak Ada')  {
					$("#nop").attr("readonly", "readonly");
				$.ajax({
                	type: "GET",
                	url: "modul/hotel/get_detail_objek.php",
                	data: 'search='+ui.item.value,
               		success: function(data){
						var obj = jQuery.parseJSON(data);
						$('#npwpd').val(obj[0].npwpd);
						$('#nik').val(obj[0].nik);
						$('#nama-wp').val(obj[0].nama);
						$('#alamat').val(obj[0].alamat);
						$('#nama-usaha').val(obj[0].nm_usaha);
						$('#alamat-usaha').val(obj[0].alamat_usaha);
						$('#ketetapan').val(obj[0].ketetapan);
						$('#tgl-daftar').val(obj[0].tgl_daf);
						$("#status-pajak").select2("val", obj[0].status_pajak);
						$('#keterangan').val(obj[0].ket);
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						notif('Notifikasi',ajaxOptions+' '+xhr.status+' : '+thrownError,'error');
      				}
            	});
			} 	
				}
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
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						notif('Notifikasi',ajaxOptions+' '+xhr.status+' : '+thrownError,'error');
      				}
            	});
			} 	
				}
    		});	
			
			$('#reset').click(function(){
				$("#nop").removeAttr('readonly');
				$("#npwpd").removeAttr('readonly');
				$("#status-pajak").select2("val", "");
			});
			
			$('#tgl-daftar').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
			
			$('#masa-berlaku').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
			
			$('#proses-update-hotel').click(function(){
if($('#nop').val() == '' || $('#npwpd').val() == '' || $('#nama-usaha').val() == '' || $('#alamat-usaha').val() == '' || $('#tgl-daftar').val() == '' || $("#status-pajak").val() == null ){
	notif('Notifikasi','Isi Semua Data','error');			
} else {
	$("#proses-update-hotel").attr("disabled", "disabled");
	var inputs = $('#frm-update-hotel').serializeArray();
					$.post( "modul/hotel/p_update.php", { postdata: inputs })
  						.done(function( data1 ) {
   							notif('Notifikasi',data1,'success');
							$('#reset').click();
							$("#proses-update-hotel").removeAttr('disabled');
  					});
}	
});
			
			$("#status-pajak").select2("val", "");
			$("#metode-pembukuan").select2("val", "");	
			$("#sumber-air").select2("val", "");
			$("#parkir").select2("val", "");
});
</script>
<form id="frm-update-hotel" name="frm-update-hotel" data-parsley-validate class="form-horizontal form-label-left">
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NOP
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nop" name="nop" class="form-control col-md-7 col-xs-12" placeholder="Nomor Objek Pajak">
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
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama WP
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-wp" name="nama-wp" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Nama Wajib Pajak">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat
		</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="alamat" name="alamat" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Alamat Wajib Pajak">
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">NIK
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nik" name="nik" class="form-control col-md-7 col-xs-12" readonly="readonly" placeholder="Nomor Induk Kependudukan">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Usaha
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-usaha" name="nama-usaha" class="form-control col-md-7 col-xs-12" placeholder="Nama Usaha">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat Usaha
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="alamat-usaha" name="alamat-usaha" class="form-control col-md-7 col-xs-12" placeholder="Alamat Usaha">
    	</div>
    </div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kegiatan Usaha
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<select name="kd-keg-usaha" id="kd-keg-usaha" class="select2_single" style="width:300px">
			<?php 
			$sql = "SELECT kd_keg_usaha||' - '||nm_keg_usaha AS value FROM public.keg_usaha WHERE kd_obj_pajak='07'";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
			
				echo "<option value=\"".$row['value']."\">".$row['value']."</option>";
			}
			pg_free_result($tampil);
			?>
			</select>
        </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Izin Usaha
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="izin-usaha" name="izin-usaha" class="form-control col-md-7 col-xs-12" placeholder="Izin Usaha">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="masa-berlaku" name="masa-berlaku" class="form-control has-feedback-left" placeholder="Masa Berlaku Izin" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Metode Pembukuan
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<select name="metode-pembukuan" id="metode-pembukuan" class="select2_single" style="width:200px">
				<option value="1">1 - PEMBUKUAN</option>
				<option value="2">2 - CASH REGISTER</option>
			</select>
    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Sumber Air
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<select name="sumber-air" id="sumber-air" class="select2_single" style="width:200px">
				<option value="PDAM">1 - PDAM</option>
				<option value="SUMUR BOR">2 - SUMUR BOR</option>
			</select>
    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Luas Tempat Usaha
    	</label>
    	<div class="col-md-3 col-sm-3 col-xs-3">
    		<input type="text" id="bumi" name="bumi" class="form-control col-md-7 col-xs-12" placeholder="Luas Bumi">
    </div>
	<div class="col-md-3 col-sm-3 col-xs-3">
    		<input type="text" id="bangunan" name="bangunan" class="form-control col-md-7 col-xs-12" placeholder="Luas Bangunan">
    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jumlah Karyawan
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="karyawan" name="karyawan" class="form-control col-md-7 col-xs-12" placeholder="Jumlah Karyawan">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Rata-rata Occupancy Kamar Per Hari
    	</label>
    	<div class="col-md-3 col-sm-3 col-xs-6">
    		<input type="text" id="occupancy" name="occupancy" class="form-control col-md-7 col-xs-12" placeholder="Jumlah Kunjungan">
    	</div>
		<div class="col-md-3 col-sm-3 col-xs-6">
    		%
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Fasilitas Parkir
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<select name="parkir" id="parkir" class="select2_single" style="width:200px">
				<option value="Bayar">1 - Bayar</option>
				<option value="Gratis">2 - Gratis</option>
			</select>
    </div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">No Telp Kantor
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="no-telp" name="no-telp" class="form-control col-md-7 col-xs-12" placeholder="No Telepon Kantor">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jumlah Ketetapan
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="ketetapan" name="ketetapan" class="form-control col-md-7 col-xs-12" placeholder="Jumlah Ketetapan">
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
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Status Pajak
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<select name="status-pajak" id="status-pajak" class="select2_single" style="width:200px">
				<option value="1">1 - AKTIF</option>
				<option value="0">0 - TIDAK AKTIF</option>
			</select>
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
        	<button id="proses-update-hotel" type="button" class="btn btn-success">Update</button>
			<button id="reset" type="reset" class="btn btn-primary">Reset</button>
        </div>
    </div>
</form>