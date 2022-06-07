<?php
include("../screen/dbconnect.php");
define('CLIENT_ID', 'NbIj4ngBk4yM4uyEjCOFTp');
define('LINE_API_URI', 'https://notify-bot.line.me/oauth/authorize?');
define('CALLBACK_URI', 'https://scgot.online/line/callback.php');

$queryStrings = [
    'response_type' => 'code',
    'client_id' => CLIENT_ID,
    'redirect_uri' => CALLBACK_URI,
    'scope' => 'notify',
    'state' => 'abcdef123456'
];

$queryString = LINE_API_URI . http_build_query($queryStrings);

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>แบบฟอร์มกรอก</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../../vendors/feather/feather.css">
  <link rel="stylesheet" href="../../vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="../../vendors/select2/select2.min.css">
  <link rel="stylesheet" href="../../vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../images/favicon.png" />

  <script>
    //   inputBox.onkeyup = function(){
    //       document.getElementById('printhour').innerHTML = <?= $difference ?>;
    //   }
  </script>
</head>

<body>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-12  stretch-card">
                  <div class="card data-icon-card-primary">
                    <div class="card-body  text-white " style="text-align: center; font-size: 150%;">แบบฟอร์มอนุญาติแจ้งเตือนผ่าน LINE NOTIFY</div>
                  </div>
                </div>
              </div>
            </div>
          </div><br>
          <div class="row">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <center>
                        <div class="form-group"><br>
                            <label for="exampleTextarea1">หลังยืนยันการอนุมัติเมื่อไปยังหน้าถัดไปให้เลือก </label>
                            <label class="text-success" for="exampleTextarea1">การแจ้งเตือนแบบตัวต่อตัวจาก LINE NOTIFY </label><br><br>
                            <a href="<?php echo $queryString; ?>" name="submit" class="btn btn-primary mr-2 col-12">ยืนยันการอนุญาติ</a>
                        </div>
                   </center>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="main-panel">
            <div class="content-wrapper">
            </div>
        </div>
      </div>
   
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
        <!--<footer class="footer">-->
        <!--  <div class="d-sm-flex justify-content-center justify-content-sm-between">-->
        <!--    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021. Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>-->
        <!--    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>-->
        <!--  </div>-->
        <!--</footer>-->
        <!-- partial -->
      <!--</div>-->
      <!-- main-panel ends -->
    <!-- page-body-wrapper ends -->
  <!--</div>-->
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="../../vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="../../vendors/typeahead.js/typeahead.bundle.min.js"></script>
  <script src="../../vendors/select2/select2.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/hoverable-collapse.js"></script>
  <script src="../../js/template.js"></script>
  <script src="../../js/settings.js"></script>
  <script src="../../js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="../../js/file-upload.js"></script>
  <script src="../../js/typeahead.js"></script>
  <script src="../../js/select2.js"></script>
  <!-- End custom js for this page-->
</body>

</html>

<?php
mysqli_close($con);
?>