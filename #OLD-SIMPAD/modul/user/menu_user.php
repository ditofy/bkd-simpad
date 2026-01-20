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
$query = "SELECT username FROM public.user";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());			
?>
<script>
function load_list_menu_user() {
if ($( "#user" ).val() != null) {
			$.ajax({
                	type: "GET",
                	url: "modul/user/list_menu_user.php",
                	data: 'u='+$( "#user" ).val(),
               		success: function(data){
						$('#list-menu-user').hide();
						$('#list-menu-user').html(data);	
						$('#list-menu-user').fadeIn("fast");
                	},
					error: function (xhr, ajaxOptions, thrownError) {
						$('#list-menu-user').html(ajaxOptions+' '+xhr.status+' : '+thrownError);
						$('#list-menu-user').fadeIn("fast");
      				}
            	});	
		}
}

$(document).ready(function () {
	$(".select2_single").select2({
                    placeholder: "Pilih                      ",
                    allowClear: false
                });
	
	$( "#user" ).change(function() {
  		load_list_menu_user();
	});
				
	$("#user").select2("val", "");
});
</script>
<div class="form-group">
<select name="user" id="user" class="select2_single" style="width:200px">
<?php
	while ($row = pg_fetch_array($result))
			{
				echo "<option value=\"".$row['username']."\">".$row['username']."</option>";	
			}
?>
</select>

</div>
<?php
pg_free_result($result);
pg_close($dbconn);
?>
<div class="x_panel" id="list-menu-user">

</div>