<?php
session_start();
require_once "action/function.php";

if(!is_authorized()) {
    set_flash_message('danger','Сначала вы должны авторизоваться. Введите логин и пароль.');
    redirect_to('page_login.php');
}

$user = get_user_by_email($_SESSION['user_email']);

//Получаем идентификатор редактируемого пользователя.
$idUser = $_GET['id'];


if($user['user_status'] !== 'admin' && !is_author($user['id'],$idUser)) { //Проверка на всякий случай, вдруг пользователь попадёт по ссылке на эту страницу.
    set_flash_message('danger','У вас недостаточно прав.');
    redirect_to('users.php');
}


if(empty($idUser)) {
    set_flash_message('danger','Требуется выбрать пользователя для редактирования');
    redirect_to('users.php');
}
$arrUser = get_user_by_id($idUser);

if($arrUser['error']) { //На случай если пользователь вдруг исчезнет, или если зададут ид через гет параметр которого нет в базе.
    set_flash_message('danger','При получении пользователя возникла ошибка.');
    redirect_to('users.php');
}


//Для передачи идентификатора в обработчик.
$_SESSION['edit_user_id'] = $idUser;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <meta name="description" content="Chartist.html">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="css/app.bundle.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-brands.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
        <a class="navbar-brand d-flex align-items-center fw-500" href="users.php"><img alt="logo" class="d-inline-block align-top mr-2" src="img/logo.png"> Учебный проект</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="users.php">Главная <span class="sr-only">(current)</span></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link" href="page_login.php">Войти</a>-->
<!--                </li>-->
                <li class="nav-item">
                    <a class="nav-link" href="page_login.php">Выйти</a>
                </li>
            </ul>
        </div>
    </nav>
    <main id="js-page-content" role="main" class="page-content mt-3">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-plus-circle'></i> Редактировать
            </h1>

        </div>

        <?
        if($_SESSION['danger'])
            display_flash_message('danger');
        if($_SESSION['success'])
            display_flash_message('success');
        ?>

        <form action="action/action_edit.php" method="post">
            <div class="row">
                <div class="col-xl-6">
                    <div id="panel-1" class="panel">
                        <div class="panel-container">
                            <div class="panel-hdr">
                                <h2>Общая информация</h2>
                            </div>
                            <div class="panel-content">
                                <!-- username -->
                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Имя</label>
                                    <input type="text" id="simpleinput" name="name" class="form-control <? display_form_warning('name') ?>" role="alert" value="<? echo $arrUser['name']; ?>">
                                </div>

                                <!-- title -->
                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Место работы</label>
                                    <input type="text" id="simpleinput" name="position" class="form-control <? display_form_warning('position') ?>" role="alert" value="<? echo $arrUser['position']; ?>">
                                </div>

                                <!-- tel -->
                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Номер телефона</label>
                                    <input type="text" id="simpleinput" name="phone" class="form-control <? display_form_warning('phone') ?>" role="alert" value="<? echo $arrUser['phone']; ?>">
                                </div>

                                <!-- address -->
                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Адрес</label>
                                    <input type="text" id="simpleinput" name="address" class="form-control <? display_form_warning('address') ?>" role="alert" value="<? echo $arrUser['address']; ?>">
                                </div>
                                <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                    <button class="btn btn-warning">Редактировать</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <script src="js/vendors.bundle.js"></script>
    <script src="js/app.bundle.js"></script>
    <script>

        $(document).ready(function()
        {

            $('input[type=radio][name=contactview]').change(function()
                {
                    if (this.value == 'grid')
                    {
                        $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-g');
                        $('#js-contacts .col-xl-12').removeClassPrefix('col-xl-').addClass('col-xl-4');
                        $('#js-contacts .js-expand-btn').addClass('d-none');
                        $('#js-contacts .card-body + .card-body').addClass('show');

                    }
                    else if (this.value == 'table')
                    {
                        $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-1');
                        $('#js-contacts .col-xl-4').removeClassPrefix('col-xl-').addClass('col-xl-12');
                        $('#js-contacts .js-expand-btn').removeClass('d-none');
                        $('#js-contacts .card-body + .card-body').removeClass('show');
                    }

                });

                //initialize filter
                initApp.listFilter($('#js-contacts'), $('#js-filter-contacts'));
        });

    </script>
</body>
</html>