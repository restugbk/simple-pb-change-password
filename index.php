<?php
include("lib/function.php");

if (isset($_POST['change'])) {
    $getUsername = filter($_POST['username']);
    $getPassword = filter($_POST['password']);
    $getNewPassword = filter($_POST['new_password']);

    if (empty($getUsername) || empty($getPassword) || empty($getNewPassword)) {
        $response = "failed";
        $message = "Data tidak boleh kosong!";
    } else if ($getPassword == $getNewPassword) {
        $response = "failed";
        $message = "Password baru tidak boleh sama dengan password lama!";
    } else {
        $getCookies = getCookies();
        $loginPointBlank = authLogin($getUsername, $getPassword, $getCookies);
        
        if ($loginPointBlank == 'success') {
            $changePassword = changePassword($getPassword, $getNewPassword, $getCookies);
            if ($changePassword != "1") {
                $response = "failed";
                $message = "Password anda tidak sesuai!";
            } else {
                $response = "success";
                $message = "Password anda berhasil diubah!";
            }
        } else {
            $response = "failed";
            $message = "Username / Password anda salah!";
        }
    }
}

?>
<!doctype html>
<html lang="en" data-bs-theme="auto">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Hugo 0.122.0">
        <title>Point Blank Password Changer</title>
        <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/checkout/">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="./assets/css/checkout.css" rel="stylesheet">
        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }

            .b-example-divider {
                width: 100%;
                height: 3rem;
                background-color: rgba(0, 0, 0, .1);
                border: solid rgba(0, 0, 0, .15);
                border-width: 1px 0;
                box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
            }

            .b-example-vr {
                flex-shrink: 0;
                width: 1.5rem;
                height: 100vh;
            }

            .bi {
                vertical-align: -.125em;
                fill: currentColor;
            }

            .nav-scroller {
                position: relative;
                z-index: 2;
                height: 2.75rem;
                overflow-y: hidden;
            }

            .nav-scroller .nav {
                display: flex;
                flex-wrap: nowrap;
                padding-bottom: 1rem;
                margin-top: -1px;
                overflow-x: auto;
                text-align: center;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
            }

            .btn-bd-primary {
                --bd-violet-bg: #712cf9;
                --bd-violet-rgb: 112.520718, 44.062154, 249.437846;
                --bs-btn-font-weight: 600;
                --bs-btn-color: var(--bs-white);
                --bs-btn-bg: var(--bd-violet-bg);
                --bs-btn-border-color: var(--bd-violet-bg);
                --bs-btn-hover-color: var(--bs-white);
                --bs-btn-hover-bg: #6528e0;
                --bs-btn-hover-border-color: #6528e0;
                --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
                --bs-btn-active-color: var(--bs-btn-hover-color);
                --bs-btn-active-bg: #5a23c8;
                --bs-btn-active-border-color: #5a23c8;
            }

            .bd-mode-toggle {
                z-index: 1500;
            }

            .bd-mode-toggle .dropdown-menu .active .bi {
                display: block !important;
            }
        </style>
    </head>
    <body class="bg-body-tertiary">
        <div class="container">
            <main>
                <div class="py-5 text-center">
                    <h2>Simple PB Change Password</h2>
                    <p class="lead">Website sederhana untuk mengganti password pointblank dengan mudah dan cepat!</p>
                </div>
                <div class="row g-5">
                    <form method="POST">

                        <?php if (isset($response)) { ?>
                            <?php if ($response == 'success') { ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo $message; ?>
                                </div>
                            <?php } else { ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $message; ?>
                                </div>
                            <?php } ?>
                        <?php } ?>

                        <div class="col-12">
                            <h4 class="mb-3">Data Akun</h4>
                            <div class="row g-3">
                                <div class="col-sm-12">
                                    <label for="form-username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="form-username" name="username" value="" required>
                                </div>
                                <div class="col-sm-6">
                                    <label for="form-password" class="form-label">Password Lama</label>
                                    <input type="text" class="form-control" id="form-password" name="password" value="" required>
                                </div>
                                <div class="col-sm-6">
                                    <label for="form-new-password" class="form-label">Password Baru</label>
                                    <input type="text" class="form-control" id="form-new-password" name="new_password" value="" required>
                                </div>

                                <div class="col-sm-12">
                                    <button class="w-100 btn btn-primary btn-lg" type="submit" name="change">SUBMIT</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
            
            <footer class="my-5 pt-5 text-body-secondary text-center text-small">
                <p class="mb-1">&copy; <?php echo date('Y'); ?> All Rights Reserved.</p>
            </footer>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="./assets/js/checkout.js"></script>
    </body>
</html>