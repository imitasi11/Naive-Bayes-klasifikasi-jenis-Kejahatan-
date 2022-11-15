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
//-- query untuk mendapatkan semua data training di tabel nbc_datas dan nbc_data
$sql = 'SELECT * FROM training a JOIN datas b USING(id_datas) ORDER BY b.id_datas';
$result = $db->query($sql);
//-- menyiapkan variable penampung berupa array
$data=array();
$datas=array();
$id_datas=0;
//-- melakukan iterasi pengisian array untuk tiap record data yang didapat
foreach ($result as $row) {
    if($id_datas!=$row['id_datas']){
        $datas[$row['id_datas']]=$row['datas'];
        $data[$row['id_datas']]=array();
        $id_datas=$row['id_datas'];
    }
    $data[$row['id_datas']][$row['id_kriteria']]=$row['id_parameter'];
}
$value=array();
for($i=1;$i<=count($data);$i++){
$value[$i]["hasil"]=$data[$i][2];
}
function disortseklur($sekolah_a, $sekolah_b) {
    if ($sekolah_a["hasil"]==$sekolah_b["hasil"]) return 0;
  return ($sekolah_a["hasil"]<$sekolah_b["hasil"])?-1:1;
}
if(!empty($value)){
usort($value, "disortseklur");
}
$values=array();
for($i=1;$i<=count($data);$i++){
    $j=$i-1;
$values[$i]=$value[$j]["hasil"];
}


// do the math
// pass in the array of values and the quartile you are looking
function Quartiles($Array, $Quartile) {
    
    // quartile position is number in array + 1 multiplied by the quartile i.e. 0.25, 0.5, 0.75
    $pos = (count($Array) + 1) * $Quartile;

    // if the position is a whole number
    // return that number as the quarile placing
    if ( fmod($pos, 1) == 0)
    {
        return $Array[$pos];
    }
    else
    {
        // get the decimal i.e. 5.25 = .25
        $fraction = $pos - floor($pos);

        // get the values in the array before and after position
        $lower_num = $Array[floor($pos)-1];
        $upper_num = $Array[ceil($pos)-1];

        // get the difference between the two
        $difference = $upper_num - $lower_num;
    
        // the quartile value is then the difference multipled by the decimal
        // add to the lower number
        return $lower_num + ($difference * $fraction);
    }
}


$first = Quartiles($values, 0.25);
$second = Quartiles($values, 0.5);
$third = Quartiles($values, 0.75);


for($i=1;$i<=count($data);$i++){
    if($data[$i][2]>$third){
        $data[$i][2]=4;
    }elseif ($data[$i][2]>$second) {
        $data[$i][2]=3;
    }elseif ($data[$i][2]>=$first){
        $data[$i][2]=2;
    }else{
        $data[$i][2]=1;
    }

}

/////////////////////////////////////////////////
$val=array();
for($i=1;$i<=count($data);$i++){
$val[$i]["hasil"]=$data[$i][3];
}
if(!empty($val)){
usort($val, "disortseklur");
}
$vals=array();
for($i=1;$i<=count($data);$i++){
    $j=$i-1;
$vals[$i]=$val[$j]["hasil"];
}
$firsts = Quartiles($vals, 0.25);
$seconds = Quartiles($vals, 0.5);
$thirds = Quartiles($vals, 0.75);

for($i=1;$i<=count($data);$i++){
    if($data[$i][3]>$thirds){
        $data[$i][3]=4;
    }elseif ($data[$i][3]>$seconds) {
        $data[$i][3]=3;
    }elseif ($data[$i][3]>=$firsts){
        $data[$i][3]=2;
    }else{
        $data[$i][3]=1;
    }

}
////////////////////////////////////////////////////////
$status=array();
foreach($data as $id_datas=>$dt_kriteria){
   for($i=1;$i<=count($parameter[1]);$i++){
        if($dt_kriteria[1]==$i){
        if(!isset($status[$i])){
                    $status[$i]=1;
                }else{
                    $a=$status[$i];
                    $status[$i]=$a+1;
                }
    }
   }
   $sum=$sum+1;
}
$statusprob=array();
for($i=1;$i<=count($status);$i++){
    $statusprob[$i]=$status[$i]/$sum;
}
//condition probabilities
foreach ($data as $value){
for($j=1;$j<=count($parameter[1]);$j++){
    for($i=2;$i<=$jml_kriteria;$i++){
        //filter layak atau tidak

            for($k=1;$k<=count($parameter[$i]);$k++){
        $l=$i-1;
        if($value[1]==$j){
            if($value[$i]==$k){
                if(!isset($isi[$j][$l][$k])){
                    $isi[$j][$l][$k]=1;
                }else{
                    $isi[$j][$l][$k]++;
                }
            }
    }
}
}
}
}
for($j=1;$j<=count($parameter[1]);$j++){
    for($i=2;$i<=$jml_kriteria;$i++){
        for($k=1;$k<=count($parameter[$i]);$k++){
            $l=$i-1;
            if(!isset($isi[$j][$l][$k])){

                $isi[$j][$l][$k]=0;

            }else{
                $isi[$j][$l][$k]=($isi[$j][$l][$k]/$status[$j]);
            }
            
        }
    }
}

/////////////////////////
$nama = $_POST['name'];
$id= $_POST['id'];
$tampung=array();
$training=array();
$training[0] = $nama;
$satu=1;
for($i=2;$i<=$jml_kriteria;$i++){
    $number=$i-$satu;
    $tampung[$i] = $_POST[$nokriteria[$i]];
    $training[$number] = $_POST[$nokriteria[$i]];
}
if($training[1]>$third){
        $training[1]=4;
    }elseif ($training[1]>$second) {
        $training[1]=3;
    }elseif ($training[1]>$first){
        $training[1]=2;
    }else{
        $training[1]=1;
    }
if($training[2]>$thirds){
        $training[2]=4;
    }elseif ($training[2]>$seconds) {
        $training[2]=3;
    }elseif ($training[2]>$firsts){
        $training[2]=2;
    }else{
        $training[2]=1;
    }
for($j=1;$j<=count($status);$j++){
for($i=1;$i<count($training);$i++){
    $trainings[$j][$i]=$isi[$j][$i][$training[$i]];
}
}

for($i=1;$i<=count($status);$i++){
for($a=1; $a<=count($trainings[$i]); $a++){
$lort=$status[$i];
if($trainings[$i][$a]==0){
    for($g=1;$g<=count($trainings[$i]);$g++){
        if($trainings[$i][$g]!=0){
                $trainings[$i][$g]=(($trainings[$i][$g]*$lort)+1)/($lort+count($trainings[$i]));
        }else{
            $trainings[$i][$g]=1/($lort+count($trainings[$i]));
        }
    }
}
}
}

$jumlaht1=1;
$jumlaht2=1;
$jumlaht3=1;
$jumlaht4=1;

for($a=1; $a<=count($trainings[1]); $a++){
$jumlaht1=$jumlaht1*$trainings[1][$a];
}
for($a=1; $a<=count($trainings[2]); $a++){
$jumlaht2=$jumlaht2*$trainings[2][$a];}

for($a=1; $a<=count($trainings[3]); $a++){
$jumlaht3=$jumlaht3*$trainings[3][$a];
}
for($a=1; $a<=count($trainings[4]); $a++){
$jumlaht4=$jumlaht4*$trainings[4][$a];
}

$hasil=array();
$hasil[1]=$jumlaht1;
$hasil[2]=$jumlaht2;
$hasil[3]=$jumlaht3;
$hasil[4]=$jumlaht4;

$hasilakhir=0;
$maksimal=max($hasil);
for($i=1;$i<=count($hasil);$i++){
    if($maksimal==$hasil[$i]){
        $hasilakhir=$i;
    }
}
$tampung[1]=$hasilakhir;




print_r($tampung);
print_r($nokriteria);

for($i=1;$i<=$jml_kriteria;$i++){
    $query_mysql = "UPDATE testing SET id_parameter ='$tampung[$i]' WHERE id_datas='$id' AND id_kriteria='$nokriteria[$i]'";
    $result = $db->query($query_mysql);
    if($result){

    }
    
}
$querys_mysql = "UPDATE datas_test SET datas ='$nama' WHERE id_datas='$id'";
$resultname= $db->query($querys_mysql);
if($resultname){
       header('Location: ../laporan.php');         
}
 
?>