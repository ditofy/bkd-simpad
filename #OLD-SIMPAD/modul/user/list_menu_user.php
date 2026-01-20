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
$user = pg_escape_string($_GET['u']);
$query = "SELECT menu FROM public.user WHERE username='$user'";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$row = pg_fetch_array($result);
$menu_user = $row['menu'];
$query = "SELECT * FROM public.menu ORDER BY menu_id,sub_id";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
?>
<script>
$(document).ready(function () {
	$('#update-menu-user').click(function(){
		$("#update-menu-user").attr("disabled", "disabled");
		var menu_user = $('#frm-menu-user').serializeArray();
		$.post( "modul/user/update_menu_user.php", { postdata: menu_user, user: $( "#user" ).val() })
  						.done(function( data ) {
   							notif('Notifikasi',data,'success');
							load_list_menu_user();							
  					});
	});
});
</script>
<form id="frm-menu-user">
<table class="table table-hover">
		<thead>
			<tr>
				<th>ID</th>
				<th>MENU INDUK</th>
				<th>SUB MENU</th>
				<th>AKSES</th>				
			</tr>
		</thead>
		<tbody>
		<?php
		
			while ($row = pg_fetch_array($result)){
				$menu_id = $row['menu_id'].".".$row['sub_id'];
				?>
					<tr>
						<td><?php echo $row['menu_id'].".".$row['sub_id']; ?></td>
						<td><?php if ($row['sub_id']== 0) { echo $row['nm_menu'];} else {echo "";} ?></td>
						<td><?php if ($row['sub_id']!= 0) { echo $row['nm_menu'];} else {echo "";} ?></td>
						<td><?php if (strpos($menu_user, $menu_id) !== false) { echo "<input type=\"checkbox\" name=\"menuid\" value=\"".$menu_id."\" checked=\"checked\">"; } else { echo "<input type=\"checkbox\" name=\"menuid\" value=\"".$menu_id."\">"; } ?>
					</tr>
				<?php
			}
		?>
		<tr>
			<td colspan="4" align="center"><button type="button" class="btn btn-primary" id="update-menu-user">Update</button></td>
		</tr>
		</tbody>
	</table>
</form>
<?php
pg_free_result($result);
pg_close($dbconn);
?>
