<?php
if (!isset($_SESSION)) { session_start(); }
if(!isset($_SESSION['base_dir']) && !isset($_SESSION['user'])) {
	header('Location: /login.php');
}
if ($_SESSION['level'] != '0')
{
	die('Hak Akses Diberhentikan');
}
include_once $_SESSION['base_dir']."inc/db.inc.php";
$sql = "SELECT * FROM public.user";
$result = pg_query($sql) or die('Query failed: ' . pg_last_error());
$no = 0;
if(pg_num_rows($result) != '0')
{
	echo "<table class='table table-bordered table-hover'>";
	echo "<thead><tr>";
	echo "<th>No</th>";
	echo "<th>User Name</th>";
	echo "<th>Nip</th>";
	echo "<th>Nama</th>";
	echo "<th>Jabatan</th>";
	echo "<th>Level</th>";
	echo "<th>Status</th>";
	echo "<th>Non Aktif</th>";
	echo "<th>Hapus</th>";
	echo "</tr></thead><tbody>";
	while ($row = pg_fetch_array($result))
	{
		$no++;
		$key = $row['username'];
		echo "<tr>";
		echo "<th scope='row'>".$no."</th>";
		echo "<td>".$row['username']."</td>";
		echo "<td>".$row['nip']."</td>";
		echo "<td>".$row['nama']."</td>";
		echo "<td>".$row['jabatan']."</td>";
		if($row['level'] == '0') {
			echo "<td>Administrators</td>";
		} else {
			echo "<td>User</td>";
		}
		if($row['status'] == '1') {
			echo "<td>Aktif</td>";
			if($row['username'] != 'admin') {
				if($row['username'] == $_SESSION['user']){
				echo "<td>Sedang Login</td>";
			} else {
				//echo "<td><input name='ganti' type='submit' id='ganti' value='Non Aktif' class='tombolsmall' onClick=\"manipulasi('nonaktif','$key')\"></td>";
				echo "<td><button type='button' class='btn btn-round btn-warning' onClick=\"manipulasi('nonaktif','$key')\"><i class='fa fa-lock'></i> Non Aktif</button></td>";
				}
			} else {
				echo "<td>Super Admin</td>";
			}
		} else {
			echo "<td>Tidak Aktif</td>";
			if($row['username'] != 'admin') {
				if($row['username'] == $_SESSION['user']){
				echo "<td>Sedang Login</td>";
			} else {
				//echo "<td><input name='ganti' type='submit' id='ganti' value='Aktifkan' class='tombolsmall' onClick=\"manipulasi('aktif','$key')\"></td>";
				echo "<td><button type='button' class='btn btn-round btn-warning' onClick=\"manipulasi('aktif','$key')\"><i class='fa fa-unlock'></i> Aktifkan</button></td>";
				}
			} else {
				echo "<td>Super Admin</td>";
			}
		}
		if($row['username'] != 'admin') {
			if($row['username'] == $_SESSION['user']){
				echo "<td>Sedang Login</td>";
			} else {
			//echo "<td><input name='ganti' type='submit' id='ganti' value='Hapus' class='tombolsmall' onClick=\"manipulasi('hapus','$key')\"></td>";
			echo "<td><button type='button' class='btn btn-round btn-danger' onClick=\"manipulasi('hapus','$key')\"><i class='fa fa-trash-o'></i> Hapus</button></td>";
			}
		} else {
			echo "<td>Super Admin</td>";
		}
		echo "<tr>";
	} 
	echo "</table>";
	echo "<br>";
} else {
	echo "Data Tidak Ada";
}
pg_free_result($result);
pg_close($dbconn);
?>