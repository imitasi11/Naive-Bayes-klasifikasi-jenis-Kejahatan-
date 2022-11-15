<!doctype html>
<?php 
session_start();
if(!empty($_SESSION['username'])){
}else{
    echo "<script>alert('login terlebih dahulu');window.location='index.php'</script>";
}?>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>logistic</title>
    <link rel="icon" href="img/favicon.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- animate CSS -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <!-- themify CSS -->
    <link rel="stylesheet" href="css/themify-icons.css">
    <!-- flaticon CSS -->
    <link rel="stylesheet" href="css/flaticon.css">
    <!-- swiper CSS -->
    <link rel="stylesheet" href="css/slick.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/intlInputPhone.min.css">
    <!-- style CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<script>
    function printContent(el){
    var restorepage = document.body.innerHTML;
    var printcontent = document.getElementById(el).innerHTML;
    document.body.innerHTML = printcontent;
    window.print();
    document.body.innerHTML = restorepage;
}</script>
<?php 
$nomer=1;
$jml_kriteria=0;
$isi=array();
include "koneksi.php";

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
$dataset=array();
$datasset=array();
$id_datasset=0;
//-- melakukan iterasi pengisian array untuk tiap record data yang didapat
foreach ($result as $row) {
    if($id_datasset!=$row['id_datas']){
        $datasset[$row['id_datas']]=$row['datas'];
        $dataset[$row['id_datas']]=array();
        $id_datasset=$row['id_datas'];
    }
    $dataset[$row['id_datas']][$row['id_kriteria']]=$row['id_parameter'];
}
$value=array();
for($i=1;$i<=count($dataset);$i++){
$value[$i]["hasil"]=$dataset[$i][2];
}
function disortseklur($sekolah_a, $sekolah_b) {
    if ($sekolah_a["hasil"]==$sekolah_b["hasil"]) return 0;
  return ($sekolah_a["hasil"]<$sekolah_b["hasil"])?-1:1;
}
if(!empty($value)){
usort($value, "disortseklur");
}
$values=array();
for($i=1;$i<=count($dataset);$i++){
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

///////////////
$first = Quartiles($values, 0.25);
$second = Quartiles($values, 0.5);
$third = Quartiles($values, 0.75);



/////////////////////////////////////////////////
$val=array();
for($i=1;$i<=count($dataset);$i++){
$val[$i]["hasil"]=$dataset[$i][3];
}
if(!empty($val)){
usort($val, "disortseklur");
}
$vals=array();
for($i=1;$i<=count($dataset);$i++){
    $j=$i-1;
$vals[$i]=$val[$j]["hasil"];
}
$firsts = Quartiles($vals, 0.25);
$seconds = Quartiles($vals, 0.5);
$thirds = Quartiles($vals, 0.75);
/////////////////////////////////////////
$sql = 'SELECT * FROM testing a JOIN datas_test b USING(id_datas) ORDER BY b.id_datas';
$result = $db->query($sql);
//-- menyiapkan variable penampung berupa array
$data=array();
$datas=array();
$nodatas=array();
$id_datas=0;
$count=1;
//-- melakukan iterasi pengisian array untuk tiap record data yang didapat
foreach ($result as $row) {
    if($id_datas!=$row['id_datas']){
        $datas[$row['id_datas']]=$row['datas'];
        $data[$row['id_datas']]=array();
        $id_datas=$row['id_datas'];
        $nodatas[$count]=$row['id_datas'];
        $count=$count+1;
    }
    $data[$row['id_datas']][$row['id_kriteria']]=$row['id_parameter'];
}
/////////////////////////////////////////
$count=1;

for($i=1;$i<=count($data);$i++){
    if($data[$nodatas[$i]][2]>$third){
        $data[$nodatas[$i]][2]=4;
    }elseif ($data[$nodatas[$i]][2]>$second) {
        $data[$nodatas[$i]][2]=3;
    }elseif ($data[$nodatas[$i]][2]>=$first){
        $data[$nodatas[$i]][2]=2;
    }else{
        $data[$nodatas[$i]][2]=1;
    }
}

for($i=1;$i<=count($data);$i++){
    if($data[$nodatas[$i]][3]>$thirds){
        $data[$nodatas[$i]][3]=4;
    }elseif ($data[$nodatas[$i]][3]>$seconds) {
        $data[$nodatas[$i]][3]=3;
    }elseif ($data[$nodatas[$i]][3]>=$firsts){
        $data[$nodatas[$i]][3]=2;
    }else{
        $data[$nodatas[$i]][3]=1;
    }

}

?>
<body>
    <!--::header part start::-->
    <header class="main_menu home_menu" style="height: 130px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="#" style="display: inline;"> <img style="width: 90px;display: inline-block;margin-top: 13px;" src="img/favicon.png" alt="logo"> <div style="font-size: 30px;color: white;display: inline-block;position: absolute;margin-left: 13px;margin-top: 37px;">DATA KRIMINALITAS</div></a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="menu_icon"></span>
                        </button>

                        <div class="collapse navbar-collapse main-menu-item" id="navbarSupportedContent">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="training.php">training</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active_color" href="laporan.php">laporan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="testing.php">testing</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php">logout</a>
                                </li>
                            </ul>
                        </div>
                       
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- Header part end-->

    <!-- breadcrumb start-->
    <section class="breadcrumb breadcrumb_bg" style="min-height: 670px;">
        <div class="container" align="right" style="margin-top: 130px;">
        </div>
        <div class="container" style="background: white;margin-top: 7px;padding-left: 0px;padding-right:  0px;">
            <div style="overflow-y: auto;height: 580px;">

                <div id="div1">
            <table id="bootstrap-data-table" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Nama</th>
                    <?php 
                    
                        //-- menampilkan header table
                        for($i=2;$i<=count($kriteria);$i++){ 
                            echo "<th style='text-align:center'>{$kriteria[$nokriteria[$i]]}</th>";
                        }
                        ?>
                        <th style="text-align:center"><?php echo $kriteria[$nokriteria[1]];?></th>
                        <th colspan="3" align="center" style="text-align:center">Aksi</th>
                  </tr>
                </thead>
                   <tbody>
                        <?php
                        //-- menampilkan data secara literal
                        foreach($data as $id_datas=>$dt_kriteria){
                           
                            echo "<tr><td>{$datas[$id_datas]}</td>";
                            for($i=2;$i<=count($kriteria);$i++){ 
                                echo "<td>{$parameter[$nokriteria[$i]][$dt_kriteria[$nokriteria[$i]]]}</td>";
                            }
                            
                            echo "<td>{$parameter[1][$dt_kriteria[1]]}</td>";
                        ?>  <td><a class="btn btn-success" href="testing/print.php?id=<?php echo $id_datas; ?>">print</a></td>
                            <td><a class="btn btn-primary" href="testing/edit.php?id=<?php echo $id_datas; ?>">edit</a></td>
                            <td><a class="btn btn-danger" href="testing/hapus.php?id=<?php echo $id_datas; ?>">delete</a></td></tr> <?php

                            }

                        ?>
                  </tr>
                </tbody>
              </table>
          </div>

          
          </div>
        </div>
    </section>
    <!-- breadcrumb start-->

    <!-- footer part start-->
    <footer class="footer-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="copyright_part_text">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-8">
                                    <p class="footer-text m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This web is made by Dicky Nur Hidayat supported with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer part end-->

    <!-- jquery plugins here-->
    <!-- jquery -->
    <script src="js/jquery-1.12.1.min.js"></script>
    <!-- popper js -->
    <script src="js/popper.min.js"></script>
    <!-- bootstrap js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- easing js -->
    <script src="js/jquery.magnific-popup.js"></script>
    <!-- swiper js -->
    <script src="js/swiper.min.js"></script>
    <!-- swiper js -->
    <script src="js/masonry.pkgd.js"></script>
    <!-- particles js -->
    <script src="js/owl.carousel.min.js"></script>
    <!-- swiper js -->
    <script src="js/slick.min.js"></script>
    <script src="js/gijgo.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/intlInputPhone.min.js"></script>
    <!-- contact js -->
    <script src="js/jquery.ajaxchimp.min.js"></script>
    <script src="js/jquery.form.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/mail-script.js"></script>
    <script src="js/contact.js"></script>
    <!-- custom js -->
    <script src="js/custom.js"></script>
</body>

</html>