<?php
session_start();
require_once "function.php";


if(!is_authorized()) {
    set_flash_message('danger','Сначала вы должны авторизоваться. Введите логин и пароль.');
    redirect_to('../page_login.php');
}

$user = get_user_by_email($_SESSION['user_email']);

//Получаем идентификатор редактируемого пользователя.
$idUser = $_SESSION['edit_user_id'];


if($user['user_status'] !== 'admin' && !is_author($user['id'],$idUser)) { //Проверка на всякий случай, вдруг пользователь попадёт по ссылке на эту страницу.
    set_flash_message('danger','У вас недостаточно прав.');
    redirect_to('../users.php');
}


if(empty($idUser)) {
    set_flash_message('danger','Требуется выбрать пользователя для редактирования');
    redirect_to('../users.php');
}
$arrUser = get_user_by_id($idUser);

if($arrUser['error']) { //На случай если пользователь вдруг исчезнет, или если зададут ид через гет параметр которого нет в базе.
    set_flash_message('danger','При получении пользователя возникла ошибка.');
    redirect_to('../users.php');
}


$status = $_POST['status'];


write_db_user_status((string)$status,(int)$arrUser['id']);

set_flash_message('success','Профиль пользователя успешно обновлён');
redirect_to('../page_profile.php?id='.$arrUser['id']);














