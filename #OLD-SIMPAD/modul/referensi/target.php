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
?>
<script>
function load_list_target() {
if ($( "#thn-pajak" ).val() != null) {
			$.ajax({
                	type: "GET",
                	url: "modul/referensi/list_target.php",
                	data: 'thn_pajak='+$( "#thn-pajak" ).val(),
               		success: function(data){
						$('#list-target').hide();
						$('#list-target').html(data);	
						$('#list-target').fadeIn("fast");
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#list-target').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						$('#list-target').fadeIn("fast");
      				}
            	});	
		}
}

$(document).ready(function () {
	$(".select2_single").select2({
                    placeholder: "Pilih                      ",
                    allowClear: false
                });
	
	$( "#thn-pajak" ).change(function() {
  		load_list_target();
	});
				
	$("#thn-pajak").select2("val", "");
});
</script>
<div class="form-group">
<select name="thn-pajak" id="thn-pajak" class="select2_single" style="width:300px">
<?php
	for ($i=2016;$i<=date('Y');$i++)
			{
				echo "<option value=\"".$i."\">".$i."</option>";	
			}
?>
</select>
</div>
<div class="x_panel" id="list-target">

</div>