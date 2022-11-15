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
    <link rel="icon" href="../img/favicon.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- animate CSS -->
    <link rel="stylesheet" href="../css/animate.css">
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <!-- themify CSS -->
    <link rel="stylesheet" href="../css/themify-icons.css">
    <!-- flaticon CSS -->
    <link rel="stylesheet" href="../css/flaticon.css">
    <!-- swiper CSS -->
    <link rel="stylesheet" href="../css/slick.css">
    <link rel="stylesheet" href="../css/nice-select.css">
    <link rel="stylesheet" href="../css/all.css">
    <link rel="stylesheet" href="../css/intlInputPhone.min.css">
    <!-- style CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <script>
    function printContent(el){
    var restorepage = document.body.innerHTML;
    var printcontent = document.getElementById(el).innerHTML;
    document.body.innerHTML = printcontent;
    window.print();
    document.body.innerHTML = restorepage;
}
</script>
    <?php 
$nomer=1;
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


?>
</head>
<body>
    <!--::header part start::-->
     <header class="main_menu home_menu" style="height: 130px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="#" style="display: inline;"> <img style="width: 90px;display: inline-block;margin-top: 13px;" src="../img/favicon.png" alt="logo"> <div style="font-size: 30px;color: white;display: inline-block;position: absolute;margin-left: 13px;margin-top: 37px;">DATA KRIMINALITAS</div></a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="menu_icon"></span>
                        </button>

                        <div class="collapse navbar-collapse main-menu-item" id="navbarSupportedContent">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="../training.php">training</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../laporan.php">laporan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active_color" href="../testing.php">testing</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../index.php">logout</a>
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
    <section class="breadcrumb breadcrumb_bg" style="min-height: 700px;">
        <div class="container" style="background: white;margin-top: 130px;padding-left: 0px;padding-right:  0px;">
            <div style="overflow-y: auto;height: 640px;padding: 20px;">
                <div id="div1">
                <p align="left"><b style="margin-left: 85px;color: black;">POLRES SUKOHARJO<br><u style="color: black;">SENTRA PELAHANAN KEPOLISIAN TERPADU</u></b></p>

                <h3 align="center"><img style="width: 90px;display: inline-block;margin-top: 13px;" src="../img/favicon.png" alt="logo">
                </h3>
                <p style="margin-bottom: 30px;" align="center"><u style="color: black;">TANDA BUKTI LAPORAN</u></p>
                <div align="center" style="font-size: 20px;" style="padding-left: 20px;padding-right: 20px;">
                 <?php 

  $ids = $_GET['id'];
  $query_mysql = "SELECT * FROM datas_test WHERE id_datas='$ids'";
  $result = $db->query($query_mysql);

  foreach($result as $id){
  ?>
                    <table>
                    <tr>
                        <td style="width: 400px;">1. Nama Tersangka </td><td style="width: 13px;"> : </td>
                        <td><?php echo $id['datas'] ?></td>
                    </tr>

                    <?php
      $querydata_mysql = "SELECT * FROM testing WHERE id_datas='$ids' order by id_kriteria";
      $rslt = $db->query($querydata_mysql);
      $j=2;
      $key=0;
      $harem=0;
       foreach($rslt as $data){
          ?>
                <?php
                if($data['id_kriteria']==1){
                    $key=$parameter[$data['id_kriteria']][$data['id_parameter']];
                }else{
                    if($data['id_kriteria']==2||$data['id_kriteria']==3){
                        $harem=$data['id_parameter'];
                        if($data['id_kriteria']==2){
    if($harem>$third){
        $harem=4;
    }elseif ($harem>$second) {
        $harem=3;
    }elseif ($harem>=$first){
        $harem=2;
    }else{
        $harem=1;
    }}elseif($data['id_kriteria']==3){
        if($harem>$thirds){
        $harem=4;
    }elseif ($harem>$seconds) {
        $harem=3;
    }elseif ($harem>=$firsts){
        $harem=2;
    }else{
        $harem=1;
    }
    }
    ?>
    <tr>
                        <td><?php echo $j;?>. <?php echo $kriteria[$data['id_kriteria']];?></td><td> : </td>
                        <td><?php echo $parameter[$data['id_kriteria']][$harem];?></td>
                        <?php $j=$j+1;?>
                    </tr>
                    <?php
                    }else{
















                    ?>
                    <tr>
                        <td><?php echo $j;?>. <?php echo $kriteria[$data['id_kriteria']];?></td><td> : </td>
                        <td><?php echo $parameter[$data['id_kriteria']][$data['id_parameter']];?></td>
                        <?php $j=$j+1;?>
                    </tr>
                   
                    <?php
                
                    }}}

                ?>
                <tr>
                        <td><?php echo $jml_kriteria+1;?>. Tanggal</td> <td> : </td>
                        <td><?php echo $id['date'];?></td>
                </tr>
            </table>
            <h3><?php echo $key;}?></h3>
          </div></div><div style="margin-top: 13px;">
          <a class="btn btn-primary" href="../laporan.php">Ok</a>
          <a class="btn btn-primary" onclick="printContent('div1')" href="#">Print</a></div>
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
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                                </div>
                                <div class="col-lg-4">
                                    <div class="social_icon">
                                        <a href="#"> <i class="ti-facebook"></i> </a>
                                        <a href="#"> <i class="ti-twitter-alt"></i> </a>
                                        <a href="#"> <i class="ti-instagram"></i> </a>
                                        <a href="#"> <i class="ti-skype"></i> </a>
                                    </div>
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
    <script src="../js/jquery-1.12.1.min.js"></script>
    <!-- popper js -->
    <script src="../js/popper.min.js"></script>
    <!-- bootstrap js -->
    <script src="../js/bootstrap.min.js"></script>
    <!-- easing js -->
    <script src="../js/jquery.magnific-popup.js"></script>
    <!-- swiper js -->
    <script src="../js/swiper.min.js"></script>
    <!-- swiper js -->
    <script src="../js/masonry.pkgd.js"></script>
    <!-- particles js -->
    <script src="../js/owl.carousel.min.js"></script>
    <!-- swiper js -->
    <script src="../js/slick.min.js"></script>
    <script src="../js/gijgo.min.js"></script>
    <script src="../js/jquery.nice-select.min.js"></script>
    <script src="../js/intlInputPhone.min.js"></script>
    <!-- contact js -->
    <script src="../js/jquery.ajaxchimp.min.js"></script>
    <script src="../js/jquery.form.js"></script>
    <script src="../js/jquery.validate.min.js"></script>
    <script src="../js/mail-script.js"></script>
    <script src="../js/contact.js"></script>
    <!-- custom js -->
    <script src="../js/custom.js"></script>
</body>

</html>