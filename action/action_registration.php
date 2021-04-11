<?php
session_start();


require_once "function.php";

$user = get_user_by_email($_POST['email']);

if(!empty($user)) {
    set_flash_message('danger','<strong>Уведомление!</strong> Этот эл. адрес уже занят другим пользователем.');

    redirect_to('../page_register.php');
} else {
    if($_POST['password'] == $_POST['confirm_password']) {
        add_user($_POST['email'],$_POST['password']);
        set_flash_message('success','Регистрация прошла успешно');

        redirect_to('../page_login.php');
    } else {
        set_flash_message('danger','<strong>Уведомление!</strong> Введённые пароли не совпадают.');

        redirect_to('../page_register.php');
    }
}




























