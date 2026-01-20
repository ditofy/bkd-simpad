<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
?>
<script>
$(document).ready(function () {
	$(".select2_single").select2({
                    placeholder: "Pilih",
                    allowClear: false
                });
	$('#jenis-penetapan').change(function(){
		if ($("#jenis-penetapan").val() != null) {
			var lkcnt = '';
			$('#content-stpd').html("<center><br><br><img src='images/loading.gif' /><br>Loading<br><br></center>");
			if ($("#jenis-penetapan").val() == '01') {
				lkcnt = 'penetapan_teguran_sptpd_terseleksi.php';
			} else {
				lkcnt = 'penetapan_teguran_sptpd_massal.php';
			}
			$.ajax({
                	type: "GET",
                	url: "modul/hotel/"+lkcnt,                	
               		success: function(data){						
						$('#content-stpd').html(data);	
						$('#content-stpd').fadeIn("fast");
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#content-stpd').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						$('#content-stpd').fadeIn("fast");
      				}
            	});
		}	
	});	
	$("#jenis-penetapan").select2("val", "");
});
</script>				
<select name="jenis-penetapan" id="jenis-penetapan" class="select2_single" style="width:200px">
	<option value="01">Terseleksi</option>
	<option value="02">Masal</option>			
</select>
<div class="x_title">
</div>
<div id="content-stpd">
</div>
    