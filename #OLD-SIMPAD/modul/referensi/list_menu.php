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
$query = "SELECT * FROM public.menu ORDER BY menu_id,sub_id";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
?>
	<table class="table table-hover">
		<thead>
			<tr>
				<th>ID</th>
				<th>MENU INDUK</th>
				<th>SUB MENU</th>
				<th>LINK</th>
				<th>CLASS</th>
				<th>AKTIF</th>
			</tr>
		</thead>
		<tbody>
		<?php
			while ($row = pg_fetch_array($result)){
				?>
					<tr>
						<td><?php echo $row['menu_id'].".".$row['sub_id']; ?></td>
						<td><?php if ($row['sub_id']== 0) { echo $row['nm_menu'];} else {echo "";} ?></td>
						<td><?php if ($row['sub_id']!= 0) { echo $row['nm_menu'];} else {echo "";} ?></td>
						<td><?php echo $row['link']; ?></td>
						<td><?php echo $row['class']; ?></td>
						<td><?php if($row['aktif'] == 't'){ echo "Aktif"; } else { echo "Tidak Aktif"; } ?></td>
					</tr>
				<?php
			}
		?>
		</tbody>
	</table>
<?php
pg_free_result($result);
pg_close($dbconn);
?>
