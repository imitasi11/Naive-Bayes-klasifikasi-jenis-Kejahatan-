  
<?php 

include "../koneksi.php";
$jml_kriteria=0;
$count=1;
$well="";
$sql = 'SELECT * FROM kriteria order by id_kriteria';
$result = $db->query($sql);
$nokriteria=array();
foreach ($result as $row) {

    $nokriteria[$count]=$row['id_kriteria'];
    $jml_kriteria=$jml_kriteria+1 ;
    $count=$count+1;
    }
$tampung=array();
for($i=1;$i<=$jml_kriteria;$i++){
    $tampung[$i] = $_POST[$nokriteria[$i]];
}

$id = $_POST['id'];
$nama = $_POST['name'];

print_r($tampung);
print_r($nokriteria);

for($i=1;$i<=$jml_kriteria;$i++){
    $query_mysql = "UPDATE training SET id_parameter ='$tampung[$i]' WHERE id_datas='$id' AND id_kriteria='$nokriteria[$i]'";
    $result = $db->query($query_mysql);
    
}
$querys_mysql = "UPDATE datas SET datas ='$nama' WHERE id_datas='$id'";
$resultname= $db->query($querys_mysql);
if($resultname){
           header('Location: ../training.php');
   
}

?>