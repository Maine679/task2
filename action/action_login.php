<?php
session_start();
require_once "function.php";

$email = $_POST['email'];
$password = $_POST['password'];


if(!isset($email)) {
    set_flash_message('danger','Вы не ввели email в форму.');
    redirect_to('../page_login.php');
}

if(!login($email, $password)) {
    redirect_to('../page_login.php');
}

$arrUser = get_user_by_email($email);

write_db_user_status('online',(int)$arrUser['id']);

set_flash_message('success','Вы успешно авторизованы в системе.');
redirect_to('../users.php');






























