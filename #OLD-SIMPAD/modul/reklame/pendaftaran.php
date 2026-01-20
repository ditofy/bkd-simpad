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
                    placeholder: "Pilih                      ",
                    allowClear: false
                });
                $(".select2_group").select2({});
                $(".select2_multiple").select2({
                    maximumSelectionLength: 4,
                    placeholder: "With Max Selection limit 4",
                    allowClear: true
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
			
			$('#resetfrm').click(function() {				
    			$('#frm-daftar-reklame')[0].reset();
				$("#kd-kecamatan").select2("val", "");
				$("#kd-kelurahan").select2("val", "");
				$("#kd-keg-usaha").select2("val", "");
				$("#jenis-penetapan").select2("val", "");
				$("#status-pemasangan").select2("val", "");
				$("#tarif").select2("val", "");
				$("#npwpd").removeAttr('readonly');				
				$("#kd-kelurahan").html("");
			});
			
			$('#tgl-daftar').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
			
			$('#tmt-awal').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
			
			$('#tmt-akhir').daterangepicker({
				format: 'DD-MM-YYYY',
                singleDatePicker: true,
                calender_style: "picker_1"
            }, function (start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
			
			$('#proses-daftar-reklame').click(function(){
if($('#npwpd').val() == '' || $('#kd-kecamatan').val() == null || $('#kd-kelurahan').val() == null || $('#kd-keg-usaha').val() == null || $('#nama-usaha').val() == '' || $('#alamat-usaha').val() == '' || $('#jenis-penetapan').val() == null || $('#tgl-daftar').val() == '' || $('#panjang').val() == '' || $('#lebar').val() == '' || $('#sisi').val() == '' || $('#jumlah').val() == '' || $('#tarif').val() == null || $('#lama-pasang').val() == '' || $('#status-pemasangan').val() == null){
	notif('Notifikasi','Isi Semua Data','error');			
} else {
	$("#proses-daftar-reklame").attr("disabled", "disabled");
	var inputs = $('#frm-daftar-reklame').serializeArray();
					$.post( "modul/reklame/p_tambah.php", { postdata: inputs })
  						.done(function( data1 ) {
   							notif('Notifikasi',data1,'success');
							$('#resetfrm').click();							
							$("#proses-daftar-reklame").removeAttr('disabled');
  					});
}	
});
			
			
				
			$("#kd-kecamatan").select2("val", "");
			$("#kd-keg-usaha").select2("val", "");
			$("#jenis-penetapan").select2("val", "");
			$("#tarif").select2("val", "");
			$("#status-pemasangan").select2("val", "");
			$("#panjang").numeric();
			$("#lebar").numeric();
			$("#sisi").numeric();
			$("#jumlah").numeric();
			$("#lama-pasang").numeric();
});
</script>
<form id="frm-daftar-reklame" name="frm-daftar-reklame" data-parsley-validate class="form-horizontal form-label-left">
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
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kecamatan
        </label>
       	<div class="col-md-6 col-sm-6 col-xs-12">
			<select name="kd-kecamatan" id="kd-kecamatan" class="select2_single" style="width:300px" onchange="get_kel($('#kd-kecamatan').val());">
			<?php 
			$sql = "SELECT kd_kecamatan||' - '||nm_kecamatan as value FROM public.kecamatan";
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
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kelurahan
		</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<select name="kd-kelurahan" id="kd-kelurahan" class="select2_single" style="width:300px">
			</select>
        </div>
	</div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Objek Pajak
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="kd-obj-pajak" name="kd-obj-pajak" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="02 - REKLAME">
        </div>
	</div>
	<div class="form-group">
    	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kegiatan Usaha
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        	<select name="kd-keg-usaha" id="kd-keg-usaha" class="select2_single" style="width:300px">
			<?php 
			$sql = "SELECT kd_keg_usaha||' - '||nm_keg_usaha AS value FROM public.keg_usaha WHERE kd_obj_pajak='02'";
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
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nama Reklame
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="nama-usaha" name="nama-usaha" class="form-control col-md-7 col-xs-12" placeholder="Nama Reklame">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alamat Reklame
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<input type="text" id="alamat-usaha" name="alamat-usaha" class="form-control col-md-7 col-xs-12" placeholder="Alamat Reklame">
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Jenis Penetapan
    	</label>
    	<div class="col-md-6 col-sm-6 col-xs-12">
    		<select name="jenis-penetapan" id="jenis-penetapan" class="select2_single" style="width:200px">
				<option value="01 - SKP">01 - SKP</option>
			</select>
    	</div>
    </div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Ukuran
    	</label>
    	<div class="col-md-1 col-sm-6 col-xs-12">
    		<input type="text" id="panjang" name="panjang" class="form-control col-md-7 col-xs-12" placeholder="Pjg">
    	</div>
		<div class="col-md-1 col-sm-6 col-xs-12">
    		<input type="text" id="lebar" name="lebar" class="form-control col-md-7 col-xs-12" placeholder="Lebar">
    	</div>
		<div class="col-md-1 col-sm-6 col-xs-12">
    		<input type="text" id="sisi" name="sisi" class="form-control col-md-7 col-xs-12" placeholder="Sisi">
    	</div>
		<div class="col-md-1 col-sm-6 col-xs-12">
    		<input type="text" id="jumlah" name="jumlah" class="form-control col-md-7 col-xs-12" placeholder="Jumlah">
    	</div>
    </div>
	<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tarif</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="tarif" id="tarif" class="select2_single" >
		<?php
			include_once $_SESSION['base_dir']."inc/db.inc.php";
			$sql = "SELECT * FROM public.tarif WHERE kd_obj_pajak='02' ORDER BY kd_obj_pajak,kd_tarif";
			$tampil = pg_query($sql) or die('Query failed: ' . pg_last_error());
			while ($row = pg_fetch_array($tampil))
			{
				echo "<option value=\"".$row['kd_tarif']."\">".$row['nm_tarif']." (Rp. ".number_format($row['tarif']).")</option>";				
			}
		?>
		</select>
    </div>
	</div>
	<div class="form-group">
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Lama Pemasangan</label>
    	<div class="col-md-1 col-sm-6 col-xs-12">
    		<input type="text" id="lama-pasang" name="lama-pasang" class="form-control col-md-7 col-xs-12" placeholder="Lama">
    	</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">TMT Awal</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tmt-awal" name="tmt-awal" class="form-control has-feedback-left" placeholder="TMT Awal" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
        </div>
	
	</div>
	<div class="form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">TMT Akhir</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
        	<input type="text" id="tmt-akhir" name="tmt-akhir" class="form-control has-feedback-left" placeholder="TMT Akhir" aria-describedby="inputSuccess2Status">
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>	
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
	<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Status Pemasangan</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
    	<select name="status-pemasangan" id="status-pemasangan" class="select2_single">
			<option value="PERMANEN">PERMANEN</option>
			<option value="SEMENTARA">SEMENTARA</option>
		</select>
    </div>
	</div>
	<div class="ln_solid"></div>
	<div class="form-group">
    	<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
        	<button id="proses-daftar-reklame" type="button" class="btn btn-success">Simpan</button>
			<button id="resetfrm" type="button" class="btn btn-primary">Reset</button>
        </div>
    </div>
</form>
<?php
pg_close($dbconn);
?>