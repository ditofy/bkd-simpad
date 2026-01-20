<?php
$bdir = $_GET['bdir'];
$data_ser = $_GET['data'];
$schema = "reklame";
$data = unserialize(urldecode($data_ser));
$arr_data = array();
foreach ($data as $value) {
    $$value["name"] = $value["value"];
    $arr_data[$value["name"]] = pg_escape_string($$value["name"]);
}
list($kd_kecamatan,$kd_kelurahan,$kd_obj_pajak,$kd_keg_usaha,$no_reg) = explode(".",$arr_data['nop']);
list($kd_prov,$kd_kota,$kd_jns,$no_reg_wp) = explode(".",$arr_data['npwpd']);
$nm_usaha = $arr_data['nm-usaha'];
$nm_wp = strtoupper($arr_data['nm-wp']);
echo $arr_data['npwpd'];
?>