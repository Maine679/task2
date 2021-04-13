<?php
session_start();
require_once "function.php";

$email = $_POST['email'];
$password = $_POST['password'];


if(empty($email)) {
    set_flash_message('danger','Обязательно заполните поле email.');
    set_form_warning('email');
    redirect_to('../create_user.php');
}

$userNew = get_user_by_email($email);

if(!empty($userNew)) {
    set_flash_message('danger', 'Такой электронный адрес уже используется.');
    redirect_to('../create_user.php');
}

if(empty($password)) {
    set_flash_message('danger', 'Поле пароль нельзя оставлять пустым!');
    set_form_warning('password');
    redirect_to('../create_user.php');
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
    set_flash_message('danger','Поля: email, пароль, имя, позиция, телефон, адресс обзательны к заполнению.');
    redirect_to('../create_user.php');
}

$idUser = add_user($email, $password);


if(!get_form_warning()) { //Если все поля имя/позиция/телефон/адрес установлены дописываем их в бд.
    write_db_user_data( $name, $position, $address, $phone, $idUser);
}


$status = $_POST['status'];
if(!empty($status)) {
    write_db_user_status( (int)$status, $idUser);
}


$photo = $_FILES['photo'];
if(!empty($photo)) {
    $newFileName = upload_photo($photo);

    write_db_user_image( $newFileName, $idUser);
}

$social_link = $_POST['social_link'];
$telegram = $_POST['telegram'];
$instagram = $_POST['instagram'];

write_db_user_social_link( $social_link, $telegram, $instagram, $idUser);


set_flash_message('success', 'Пользователь был успешно добавлен!');
redirect_to('../users.php');



































