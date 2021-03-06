<?php
session_start();
require_once "action/function.php";

if(!is_authorized()) {
    set_flash_message('danger','Сначала вы должны авторизоваться. Введите логин и пароль.');
    redirect_to('page_login.php');
}


$idUser = $_GET['id'];

if(empty($idUser)) {
    set_flash_message('danger','Требуется выбрать пользователя для просмотра');
    redirect_to('users.php');
}
$arrUser = get_user_by_id($idUser);

if($arrUser['error']) { //На случай если пользователь вдруг исчезнет, или если зададут ид через гет параметр которого нет в базе.
    set_flash_message('danger','При получении пользователя возникла ошибка.');
    redirect_to('users.php');
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Профиль пользователя</title>
    <meta name="description" content="Chartist.html">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="css/app.bundle.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-brands.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-regular.css">
</head>
    <body class="mod-bg-1 mod-nav-link">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
            <a class="navbar-brand d-flex align-items-center fw-500" href="#"><img alt="logo" class="d-inline-block align-top mr-2" src="img/logo.png"> Учебный проект</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item ">
                        <a class="nav-link" href="users.php">Главная</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="#">Войти</a>-->
<!--                    </li>-->
                    <li class="nav-item">
                        <a class="nav-link" href="action/action_logout.php">Выйти</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main id="js-page-content" role="main" class="page-content mt-3">
            <div class="subheader">
                <h1 class="subheader-title">
                    <i class='subheader-icon fal fa-user'></i> <? echo $arrUser['name']; ?>
                </h1>
            </div>
            <div class="row">
              <div class="col-lg-6 col-xl-6 m-auto">

                    <?
                    if($_SESSION['success'])
                        display_flash_message('success');


                        $arrStatus = [
                            'online'=>'success',
                            'eway'=>'warning',
                            'ofline'=>'error',
                            'notdisturb'=>'danger'
                        ];
                    ?>
                    <!-- profile summary -->
                    <div class="card mb-g rounded-top">
                        <div class="row no-gutters row-grid">
                            <div class="col-12">
                                <div class="d-flex flex-column align-items-center justify-content-center p-4">
                                    <span class="status status-<? echo $arrStatus[$arrUser['status']]; ?> mr-3">
                                        <img src="img/demo/avatars/<? echo has_image((string)$arrUser['avatar']) ? $arrUser['avatar']:'avatar-admin-lg.png'; ?>" class="rounded-circle shadow-2 img-thumbnail" alt="<? echo $arrUser['name']; ?>">
                                    </span>
                                    <h5 class="mb-0 fw-700 text-center mt-3">
                                        <? echo $arrUser['name']; ?>
                                        <small class="text-muted mb-0"><? echo $arrUser['address']; ?></small>
                                    </h5>
                                    <div class="mt-4 text-center demo">
                                        <a href="javascript:void(0);" class="fs-xl" style="color:#C13584">
                                            <i class="fab fa-instagram"><? echo $arrUser['instagram']; ?></i>
                                        </a>
                                        <a href="javascript:void(0);" class="fs-xl" style="color:#4680C2">
                                            <i class="fab fa-vk"><? echo $arrUser['social_link']; ?></i>
                                        </a>
                                        <a href="javascript:void(0);" class="fs-xl" style="color:#0088cc">
                                            <i class="fab fa-telegram"><? echo $arrUser['telegram']; ?></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 text-center">
                                    <a href="tel:+13174562564" class="mt-1 d-block fs-sm fw-400 text-dark">
                                        <i class="fas fa-mobile-alt text-muted mr-2"></i><? echo $arrUser['phone']; ?></a>
                                    <a href="mailto:oliver.kopyov@marlin.ru" class="mt-1 d-block fs-sm fw-400 text-dark">
                                        <i class="fas fa-mouse-pointer text-muted mr-2"></i><? echo $arrUser['email']; ?></a>
                                    <address class="fs-sm fw-400 mt-4 text-muted">
                                        <i class="fas fa-map-pin mr-2"></i><? echo $arrUser['address']; ?>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
               </div>
            </div>
        </main>
    </body>

    <script src="js/vendors.bundle.js"></script>
    <script src="js/app.bundle.js"></script>
    <script>

        $(document).ready(function()
        {

        });

    </script>
</html>