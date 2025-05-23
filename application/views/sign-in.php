<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light"
    data-header-styles="light" data-menu-styles="light" data-toggled="close">
<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Billing - Login </title>
    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('assets/images/brand-logos/favicon.ico'); ?>" type="image/x-icon">
    <!-- Main Theme Js -->
    <script src="<?= base_url('assets/js/authentication-main.js'); ?>"></script>
    <!-- Bootstrap Css -->
    <link id="style" href="<?= base_url('assets/libs/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- Style Css -->
    <link href="<?= base_url('assets/css/styles.min.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/toastify.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/sweet-alert.css'); ?>">
    <script src="<?= base_url('assets/js/toastify.js'); ?>"></script>
    <script src="<?= base_url('assets/js/sweet-alert.min.js'); ?>"></script>
    <!-- Icons Css -->
    <link href="<?= base_url('assets/css/icons.min.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/libs/swiper/swiper-bundle.min.css'); ?>">
    <style>
        @media only screen and (min-width:321px) and (max-width:992px) and (orientation: portrait) {
            .h-100 {
                height: 73% !important;
            }
        }
    </style>
</head>
<body class="bg-white">
    <div class="row authentication mx-0">
        <div class="col-xxl-3 col-xl-3 col-lg-3 d-xl-block d-none px-0">
        </div>
        <div class="col-xxl-6 col-xl-6 col-lg-6 col-lg-12">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-xxl-6 col-xl-7 col-lg-7 col-md-7 col-sm-8 col-12">
                    <div class="p-5">
                        <div class="mb-3">
                            <a href="javascript:">
                            </a>
                        </div>
                        <div style="text-align:center"><img style="height: 190px;"
                                src="<?= base_url('assets/images/invoicelogo.png'); ?>" alt="logo" class="signinlogo desktop-dark">
                        </div>
                        <p class="h5 fw-semibold text-center mb-2">Login</p> <br>
                        <div class="text-center my-4 authentication-barrier">
                            <div style="display:none;" class="alert alert-success" role="alert">Login Success</div>
                        </div>
                        <form method="post">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
                                value="<?= $this->security->get_csrf_hash(); ?>">
                            <div class="row gy-3">
                                <div class="col-xl-12 mt-0">
                                    <label for="signin-username" class="form-label text-default">User Name <span
                                            class="text-danger">*</span> </label>
                                    <input type="text" class="form-control form-control-lg" name="username"
                                        id="signin-username">
                                </div>
                                <div class="col-xl-12 mb-3">
                                    <label for="signin-password" class="form-label text-default d-block">
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control form-control-lg"
                                            id="signin-password">
                                        <button class="btn btn-light" type="button"
                                            onclick="createpassword('signin-password',this)" id="button-addon2"><i
                                                class="ri-eye-off-line align-middle"></i></button>
                                    </div>
                                    <div class="mt-2">
                                        <div class="form-check">
                                            <label class="form-check-label text-muted fw-normal" for="defaultCheck1">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 d-grid mt-2">
                                    <input type="submit" name="submit" class="btn btn-sm btn-primary" value="Login">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Include jQuery if not already present -->
    <script src="<?= base_url('assets/js/jquery-3.6.0.min.js'); ?>"></script>
    <script>
        $(document).ready(function () {
            // Restrict special characters in username input
            $('#signin-username').on('keypress', function (e) {
                var regex = /^[a-zA-Z0-9_]+$/;
                var key = String.fromCharCode(e.which);
                if (!regex.test(key)) {
                    e.preventDefault();
                }
            });
            // Handle form submission
            $('form').on('submit', function (e) {
                e.preventDefault();
                var username = $.trim($('#signin-username').val());
                var password = $.trim($('#signin-password').val());
                // Validate inputs
                if (username === '' || password === '') {
                    Toastify({
                        text: "Both username and password are required.",
                        style: {
                            background: "linear-gradient(to right, #ff4d4d, #ff0000)",
                        },
                        gravity: "top",
                        position: 'right',
                        duration: 3000,
                        close: true
                    }).showToast();
                    return;
                }
                // Extra validation for allowed characters
                var validUsername = /^[a-zA-Z0-9_]+$/;
                if (!validUsername.test(username)) {
                    Toastify({
                        text: "Username can only contain letters, numbers, and underscores.",
                        style: {
                            background: "linear-gradient(to right, #ff4d4d, #ff0000)",
                        },
                        gravity: "top",
                        position: 'right',
                        duration: 3000,
                        close: true
                    }).showToast();
                    return;
                }
                var formData = $('form').serialize();
                // AJAX request
                $.ajax({
                    url: "<?= base_url('login_submit') ?>",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function (response) {
                        if (response.status === 1) {
                            Toastify({
                                text: "Logged in Successfully!!",
                                style: {
                                    background: "linear-gradient(to right, #28a745, #218838)",
                                },
                                gravity: "top",
                                position: 'right',
                                duration: 3000,
                                close: true
                            }).showToast();
                            setTimeout(function () {
                                window.location.href = response.redirect_url ||
                                    "<?= base_url('home') ?>";
                            }, 3000);
                        } else {
                            Toastify({
                                text: "Login failed!!",
                                style: {
                                    background: "linear-gradient(to right, #ff4d4d, #ff0000)",
                                },
                                gravity: "top",
                                position: 'right',
                                duration: 3000,
                                close: true
                            }).showToast();
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
    <!-- toastify JS -->
    <script src="<?= base_url('assets/js/toastify.js'); ?>"></script>
    <!-- toastify JS -->
    <script src="<?= base_url('assets/js/sweet-alert.min.js'); ?>"></script>
    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- Swiper JS -->
    <script src="<?= base_url('assets/libs/swiper/swiper-bundle.min.js'); ?>"></script>
    <!-- Internal Sing-Up JS -->
    <script src="<?= base_url('assets/js/authentication.js'); ?>"></script>
    <!-- Show Password JS -->
    <script src="<?= base_url('assets/js/show-password.js'); ?>"></script>
</body>
</html>