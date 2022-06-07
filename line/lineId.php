<?php
$user_id = $_GET['user_id'];
?>
</html>
<!DOCTYPE html>
<html>
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
        <meta charset="utf-8" />
        <meta
          name="viewport"
          content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0,viewport-fit=cover"
        />
        <title>LIFF: LINE Front-end Framework</title>
        <script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
    </head>
    <body id="body">
          <div class="container-scroller">
            <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
              <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-5" href="../../index.html"><img src="../../images/logo.svg" class="mr-2" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="../../index.html"><img src="../../images/logo-mini.svg" alt="logo" /></a>
              </div>
              <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <!--<span class="text-dark">คุณ <?= $rs->employee_name ?></span>-->
              </div>
            </nav>
            <div class="container-fluid page-body-wrapper">
               
              <div class="theme-setting-wrapper">
                <div id="settings-trigger"><i class="ti-settings"></i></div>
                <div id="theme-settings" class="settings-panel">
                  <i class="settings-close ti-close"></i>
                  <p class="settings-heading">SIDEBAR SKINS</p>
                  <div class="sidebar-bg-options selected" id="sidebar-light-theme">
                    <div class="img-ss rounded-circle bg-light border mr-3"></div>Light
                  </div>
                  <div class="sidebar-bg-options" id="sidebar-dark-theme">
                    <div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark
                  </div>
                  <p class="settings-heading mt-2">HEADER SKINS</p>
                  <div class="color-tiles mx-0 px-4">
                    <div class="tiles success"></div>
                    <div class="tiles warning"></div>
                    <div class="tiles danger"></div>
                    <div class="tiles info"></div>
                    <div class="tiles dark"></div>
                    <div class="tiles default"></div>
                  </div>
                </div>
              </div>
              
              <div class="main-panel">
                <div class="content-wrapper">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-12  stretch-card">
                          <div class="card data-icon-card-light">
                            <section id="profile">
                              <!--<img id="pictureUrl" src="https://mokmoon.com/images/ic_liff.png" />-->
                              <p id="userId"></p>
                              <p id="displayName"></p>
                              <p id="statusMessage"></p>
                              <p id="email"></p>
                            </section>
                        
                            <section id="feature"></section>
                        
                            <section id="button">
                              <!--<button id="btnSend" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Send Message</button>-->
                              <button id="btnLogIn" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Log In</button>
                              <button id="btnLogOut" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Log Out</button>
                            </section>
                          </div>
                        </div> 
                      </div>
                    </div>
                  </div>
                            
                </div>
              </div> 
            </div>
          </div>

            <script>
                async function main() {
                  // Initialize LIFF app)
                  await liff.init({ liffId: '1656632478-Wl457ZM8' });
                
                  // Try a LIFF function
                  switch (liff.getOS()) {
                    case 'android':
                      body.style.backgroundColor = '#d1f5d3';
                      break;
                    case 'ios':
                      body.style.backgroundColor = '#eeeeee';
                      break;
                  }
                
                  getUserProfile();
                  if (!liff.isInClient()) {
                    if (liff.isLoggedIn()) {
                      getUserProfile();
                    } else {
                      btnLogIn.style.display = 'block';
                      btnLogOut.style.display = 'none';
                    }
                  } else {
                    btnSend.style.display = 'block';
                    getUserProfile();
                  }
                }
                main();
                
                async function getUserProfile() {
                  const profile = await liff.getProfile();
                  var id = '<?=$user_id?>';
                //   console.log("insertLineId.php?w1=" + profile.userId + "&user_id=" + id); 
                  window.location.href = "insertLineId.php?w1=" + profile.userId + "&user_id=" + id;
        
                }
                
                btnLogIn.onclick = () => {
                  liff.login();
                  window.location.href = "insertLineId.php?w1=" + profile.userId + "&user_id=" + id;

                };
                
                btnLogOut.onclick = () => {
                  liff.logout();
                  window.location.reload();
                };
        
            </script>
  </body>
</html>
