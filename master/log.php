<?php
session_start();
	include "koneksi.php";
	
	$user=$_POST['username'];
	$pass=$_POST['password'];
	
	
	$query = "select * from login where id = '".$_POST['username']."' and pass ='".$_POST['password']."'";
	$hasil = mysqli_query($connect,$query);
	$row  = mysqli_fetch_array($hasil);
	$cekrow = mysqli_num_rows($hasil);
	
	//session_destroy();
	//profil echo
	
	
	if ($cekrow>=1){
	
	$_SESSION['username'] = $user;
	echo "<script>alert('berhasil');window.location='training.php'</script>";
	}
	else{
	echo "<script>alert('gagal');window.location='index.php'</script>";
}
?>