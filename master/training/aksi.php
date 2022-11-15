
$id_datas_test=rand(1,100);
$cek_id= "SELECT * FROM datas_test where id_datas ='$id_datas_test' ";
$cek_result = mysqli_query($connect,$cek_id);
$numrow = mysqli_num_rows($cek_result);


while($numrow > 0){
    $id_datas_test=0;
    $id_datas_test=rand(1,100);
    $cek_id= "SELECT * FROM datas_test where id_datas ='$id_datas_test' ";
    $cek_result = mysqli_query($connect,$cek_id);
    $numrow = mysqli_num_rows($cek_result);

}


    $input_datas = "INSERT INTO datas_test VALUES('$id_datas_test','$nama','$tanggal') ";
    $datas_result = $db->query($input_datas);

    for($i=1;$i<=$jml_kriteria;$i++){
        $a=$nokriteria[$i];
        $b=$tampung[$i];
    $input_data = "INSERT INTO testing (id_data,id_datas,id_kriteria,id_parameter) VALUES(NULL,'$id_datas_test','$a','$b') ";
    $data_result = $db->query($input_data);
    }
<?php 

$nomer=1;
$sum=0;
$jml_kriteria=0;
$isi=array();
include "../koneksi.php";

$count=1;

$sql = 'SELECT * FROM kriteria ORDER BY id_kriteria' ;
$result = $db->query($sql);
//-- menyiapkan variable penampung berupa array
$kriteria=array();
$nokriteria=array();
//-- melakukan iterasi pengisian array untuk tiap record data yang didapat
foreach ($result as $row) {
    $kriteria[$row['id_kriteria']]=$row['kriteria'];
    $nokriteria[$count]=$row['id_kriteria'];
    $jml_kriteria=$jml_kriteria+1 ;
    $count=$count+1;
    }
//-- query untuk mendapatkan semua data kriteria di tabel nbc_kriteria
$sql = 'SELECT * FROM parameter ORDER BY id_kriteria,id_parameter';
$result = $db->query($sql);
//-- menyiapkan variable penampung berupa array
$parameter=array();
$id_kriteria=0;
//-- melakukan iterasi pengisian array untuk tiap record data yang didapat
foreach ($result as $row) {
    if($id_kriteria!=$row['id_kriteria']){
        $parameter[$row['id_kriteria']]=array();
        $id_kriteria=$row['id_kriteria'];
    }
    $parameter[$row['id_kriteria']][$row['nilai']]=$row['parameter'];
}

/////////////////////////
$nama = $_POST['name'];
$tampung=array();
$satu=1;
for($i=1;$i<=$jml_kriteria;$i++){
    $tampung[$i] = $_POST[$nokriteria[$i]];
}

$id_datas_test=rand(1,999);
$cek_id= "SELECT * FROM datas where id_datas ='$id_datas_test' ";
$cek_result = mysqli_query($connect,$cek_id);
$numrow = mysqli_num_rows($cek_result);


while($numrow > 0){
    $id_datas_test=0;
    $id_datas_test=rand(1,999);
    $cek_id= "SELECT * FROM datas where id_datas ='$id_datas_test' ";
    $cek_result = mysqli_query($connect,$cek_id);
    $numrow = mysqli_num_rows($cek_result);

}


    $input_datas = "INSERT INTO datas VALUES('$id_datas_test','$nama') ";
    $datas_result = $db->query($input_datas);

    for($i=1;$i<=$jml_kriteria;$i++){
        $a=$nokriteria[$i];
        $b=$tampung[$i];
    $input_data = "INSERT INTO training (id_data,id_datas,id_kriteria,id_parameter) VALUES(NULL,'$id_datas_test','$a','$b') ";
    $data_result = $db->query($input_data);
    if($i==$jml_kriteria){

    echo "<script>alert('berhasil');window.location='../training.php'</script>";
    }
    }?>
