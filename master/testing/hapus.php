<?php 
include '../koneksi.php';
$id = $_GET['id'];
$delete_id= "DELETE FROM datas_test where id_datas ='$id' ";
$id_result = $db->query($delete_id);
$delete_data= "DELETE FROM testing where id_datas ='$id' ";
$data_result = $db->query($delete_data);
header("location:../laporan.php");
?>