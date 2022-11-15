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
                                    <a class="nav-link active_color" href="../training.php">training</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../laporan.php">laporan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../testing.php">testing</a>
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
    <section class="breadcrumb breadcrumb_bg" style="min-height: 670px;">
        <div class="container" style="background: white;margin-top: 130px;padding-left: 0px;padding-right:  0px;">
            <div style="overflow-y: auto;height: 580px;padding: 20px;">
            

  <form class="form-contact comment_form" action="update.php" method="post" enctype="multipart/form-data">
    <?php 

  $ids = $_GET['id'];
  $query_mysql = "SELECT * FROM datas WHERE id_datas='$ids'";
  $result = $db->query($query_mysql);

  foreach($result as $id){
  ?>
                     <div class="row">
                        <div class="col-sm-6">
                           <div class="form-group" align="justify">
                            Nama Kasus
                            <input type="hidden" name="id" value="<?php echo $id['id_datas'] ?>">
                              <input class="form-control" name="name" id="name" type="text" placeholder="Nama Kasus" value="<?php echo $id['datas'] ?>">
                           </div>
                        </div>
                        <?php
      $querydata_mysql = "SELECT * FROM training WHERE id_datas='$ids' order by id_kriteria";
      $rslt = $db->query($querydata_mysql);

       foreach($rslt as $data){
          ?>
              <?php
               $ini=$kriteria[$data['id_kriteria']];
              if($ini=='KERUGIAN MATERIIL'||$ini=='JUMLAH ITEM YANG DIAMBIL'){
              ?>
                       <div class="col-sm-6">
                           <div class="form-group" align="justify">
                             <?php echo $ini;
                            ?>
              <?php echo '<input type="number"  class="form-control" name="'.$data['id_kriteria'].'" value="'.$data['id_parameter'].'">' ?>
              <?php 
              }else{?>
                         <div class="col-sm-6" style="margin-bottom:19px;">
                           <div class="form-group" align="justify" >
                             <?php echo $ini;
                            ?>
              
                <?php echo '<select class="form-control input-sm m-bot15" name="'.$data['id_kriteria'].'">' ?>
                <option value="<?php echo $data['id_parameter'] ?>"selected><?php echo $parameter[$data['id_kriteria']][$data['id_parameter']] ?></option>
                
              <?php
              for($w=1;$w<=count($parameter[$data['id_kriteria']]);$w++){?>
                <option value="<?php echo $w;?>"><?php echo $parameter[$data['id_kriteria']][$w]?></option>
            <?php }?>
                
                </select>
                    <?php }?>
              

                           </div>
                        </div>

 <?php

       }}
      ?>


                     </div>
                     <div class="form-group">
                        <button type="submit" name="upload" value="Upload" class="button button-contactForm btn_1 boxed-btn">Input Data</button>
                     </div>
                  </form>

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