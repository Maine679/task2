<?php
session_start();
require_once "function.php";

$email = $_POST['email'];

if(empty($email)) {
    set_flash_message('danger','Обязательно заполните поле email.');
    redirect_to('../create_user.php');
}

$userNew = get_user_by_email($email);

if(!empty($userNew)) {
    set_flash_message('danger', 'Такой электронный адрес уже используется.');

    redirect_to('../create_user.php');
}

























