<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light"
    data-menu-styles="dark" data-toggled="close">
  <head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?= $this->security->get_csrf_hash(); ?>">
    <title>Billing Software</title>
    <style>
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        @media only screen and (max-width: 600px) {
            #invoice_list {
                font-size: 12px;
                min-width: 700px;
            }
            #invoice_list th,
            #invoice_list td {
                white-space: nowrap;
            }
            .card-body {
                padding: 10px;
            }
        }
        input.expand-on-focus {
          transition: width 0.3s ease;
          width: 80px; /* default */
        }

        input.expand-on-focus:focus {
          width: 140px; /* expanded width */
        }

    </style>
    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('assets/images/brand-logos/favicon.ico'); ?>" type="image/x-icon">
    <!-- Choices JS -->
    <script src="<?= base_url('assets/libs/choices/public/assets/scripts/choices.min.js'); ?>"></script>
    <!-- Main Theme Js -->
    <script src="<?= base_url('assets/js/main.js'); ?>"></script>
    <!-- Bootstrap Css -->
    <link id="style" href="<?= base_url('assets/libs/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- Style Css -->
    <link href="<?= base_url('assets/css/styles.min.css'); ?>" rel="stylesheet">
    <!-- Icons Css -->
    <link href="<?= base_url('assets/css/icons.css'); ?>" rel="stylesheet">
    <!-- Custom Css -->
    <link href="<?= base_url('assets/css/custom.css'); ?>" rel="stylesheet">
    <!-- Node Waves Css -->
    <link href="<?= base_url('assets/libs/node-waves/waves.min.css'); ?>" rel="stylesheet">
    <!-- Simplebar Css -->
    <link href="<?= base_url('assets/libs/simplebar/simplebar.min.css'); ?>" rel="stylesheet">
    <!-- Color Picker Css -->
    <link rel="stylesheet" href="<?= base_url('assets/libs/flatpickr/flatpickr.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/@simonwep/pickr/themes/nano.min.css'); ?>">
    <!-- Choices Css -->
    <link rel="stylesheet" href="<?= base_url('assets/libs/choices/public/assets/styles/choices.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/jsvectormap/css/jsvectormap.min.css'); ?>">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/daterangepicker/daterangepicker.css'); ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/datatable/dataTables.bootstrap5.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/datatable/responsive.bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/datatable/buttons.bootstrap5.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/toastify.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/sweet-alert.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome-6.4.2-web/css/all.min.css'); ?>">
    <!-- jQuery -->
    <script src="<?= base_url('assets/libs/jquery/jquery-3.6.4-jquery.min.js'); ?>"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- jQuery Validate -->
    <script src="<?= base_url('assets/libs/jquery/jquery-validate-1.19.5-jquery.validate.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/toastify.js'); ?>"></script>
    <script src="<?= base_url('assets/js/sweet-alert.min.js'); ?>"></script>
    <!-- Daterange picker -->
    <script type="text/javascript" src="<?= base_url('assets/daterangepicker/moment.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/daterangepicker/daterangepicker.js'); ?>"></script>
    <style>
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        @media only screen and (max-width: 600px) {
            #invoice_list {
                font-size: 12px;
                min-width: 250px;
            }
            #invoice_list th,
            #invoice_list td {
                white-space: nowrap;
            }
            .card-body {
                padding: 10px;
            }
        }
    </style>
<body>
    <!-- Loader -->
    <div id="loader">
        <img src="<?= base_url('assets/images/media/loader.svg'); ?>" alt="">
    </div>
    <!-- Loader -->
    <div class="page">
        <!-- app-header -->
        <header class="app-header">
            <!-- Start::main-header-container -->
            <div class="main-header-container container-fluid">
                <!-- Start::header-content-left -->
                <div class="header-content-left">
                    <!-- Start::header-element -->
                    <div class="header-element">
                        <div class="horizontal-logo">
                        </div>
                    </div>
                    <!-- Start::header-element -->
                    <div class="header-element">
                        <!-- Start::header-link -->
                        <a aria-label="Hide Sidebar"
                            class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle"
                            data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
                    </div>
                    <!-- End::header-element -->
                </div>
                <div class="header-content-right">
                    <!-- Start::header-element -->
                    <div class="header-element">
                        <!-- Start::header-link|dropdown-toggle -->
                        <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div class="me-sm-2 me-0">
                                    <!-- <img src="<?= base_url('assets/images/faces/9.jpg'); ?>" alt="img" width="32" height="32" class="rounded-circle"> -->
                                    <i class="bx bx-user fs-5"></i>
                                </div>
                                <div class="d-sm-block d-none">
                                    <p class="fw-semibold mb-0 lh-1">Admin</p>
                                    <span class="op-7 fw-normal d-block fs-11"></span>
                                </div>
                            </div>
                        </a>
                        <ul class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end"
                            aria-labelledby="mainHeaderProfile">
                            <li><a class="dropdown-item d-flex" href="<?= base_url('logout') ?>"><i
                                        class="ti ti-logout fs-18 me-2 op-7"></i>Log Out</a></li>
                        </ul>
                    </div>
                    <!-- End::header-element -->
                </div>
            </div>
            <!-- End::main-header-container -->
        </header>