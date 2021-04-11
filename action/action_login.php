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

set_flash_message('success','Вы успешно авторизованы в системе.');
redirect_to('../users.php');






























