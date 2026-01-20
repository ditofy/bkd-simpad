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
$kd_obj = pg_escape_string($_GET['kd_obj']);
$query = "SELECT * FROM public.tarif WHERE kd_obj_pajak ='$kd_obj' ORDER BY kd_obj_pajak,kd_tarif";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
?>
<table class="table table-hover">
		<thead>
			<tr>
				<th>ID</th>
				<th>NAMA TARIF</th>
				<th>TARIF</th>				
			</tr>
		</thead>
		<tbody>
		<?php
			while ($row = pg_fetch_array($result)){
				?>
					<tr>
						<td><?php echo $row['kd_tarif'];?></td>
						<td><?php echo $row['nm_tarif']; ?></td>
						<td align="right"><?php echo $row['tarif']; ?></td>
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
