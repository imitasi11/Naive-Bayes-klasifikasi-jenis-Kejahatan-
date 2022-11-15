<?php
session_start();
	include "koneksi.php";
	
	$user=$_POST['username'];
	$pass=$_POST['password'];
	
	
	$query = "INSERT INTO login values('$user','$pass')";
	    $datas_result = $db->query($query);
	    
	echo "<script>alert('berhasil');window.location='index.php'</script>";
	
	//session_destroy();
	//profil echo
	
?>