<?php
session_start();
require_once "function.php";



if(!is_authorized()) {
    set_flash_message('danger','Сначала вы должны авторизоваться. Введите логин и пароль.');
    redirect_to('page_login.php');
}

$user = get_user_by_email($_SESSION['user_email']);



//Получаем идентификатор редактируемого пользователя.
$idUser = $_SESSION['edit_user_id'];
unset($_SESSION['edit_user_id']);

if($user['user_status'] !== 'admin' && !is_author($user['id'],$idUser)) { //Проверка на всякий случай, вдруг пользователь попадёт по ссылке на эту страницу.
    set_flash_message('danger','У вас недостаточно прав.');
    redirect_to('users.php');
}


$name = $_POST['name'];
$position = $_POST['position'];
$phone = $_POST['phone'];
$address = $_POST['address'];


if(empty($name)) {
    set_form_warning('name');
}
if(empty($position)) {
    set_form_warning('position');
}
if(empty($phone)) {
    set_form_warning('phone');
}
if(empty($address)) {
    set_form_warning('address');
}

if(get_form_warning()) {
    set_flash_message('danger','Не оставляйте пустые поля, все поля обязательны к заполнению.');
    redirect_to('../edit.php?id='.$idUser);
}

//Если все поля имя/позиция/телефон/адрес установлены дописываем их в бд.
write_db_user_data( $name, $position, $address, $phone, $idUser);

set_flash_message('success','Данные пользователя успешно обновлены.');
redirect_to('../users.php');















