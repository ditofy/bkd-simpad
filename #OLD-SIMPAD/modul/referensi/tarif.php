<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
	exit;
}
if ($_SESSION['level'] != '0')
{
	die('Hak Akses Diberhentikan');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$query = "SELECT * FROM public.obj_pajak";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());	
?>
<script>
function load_list_tarif() {
if ($( "#kd_obj" ).val() != null) {
			$.ajax({
                	type: "GET",
                	url: "modul/referensi/list_tarif.php",
                	data: 'kd_obj='+$( "#kd_obj" ).val(),
               		success: function(data){
						$('#list-tarif').hide();
						$('#list-tarif').html(data);	
						$('#list-tarif').fadeIn("fast");
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#list-tarif').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						$('#list-tarif').fadeIn("fast");
      				}
            	});	
		}
}

$(document).ready(function () {
	$(".select2_single").select2({
                    placeholder: "Pilih                      ",
                    allowClear: false
                });
	
	$( "#kd_obj" ).change(function() {
  		load_list_tarif();
	});
				
	$("#kd_obj").select2("val", "");
});
</script>
<div class="form-group">
<select name="kd_obj" id="kd_obj" class="select2_single" style="width:300px">
<?php
	while ($row = pg_fetch_array($result))
			{
				echo "<option value=\"".$row['kd_obj_pajak']."\">".$row['kd_obj_pajak'].".".$row['nm_obj_pajak']."</option>";	
			}
?>
</select>
</div>
<?php
pg_free_result($result);
pg_close($dbconn);
?>
<div class="x_panel" id="list-tarif">

</div>