<?php
session_start();
require_once "function.php";



if(!is_authorized()) {
    set_flash_message('danger','Сначала вы должны авторизоваться. Введите логин и пароль.');
    redirect_to('../page_login.php');
}

$user = get_user_by_email($_SESSION['user_email']);

//Для передачи идентификатора в обработчик.
$idUser = $_SESSION['edit_user_id'];
unset($_SESSION['edit_user_id']);

if($user['user_status'] !== 'admin' && !is_author((int)$user['id'],(int)$idUser)) { //Проверка на всякий случай, вдруг пользователь попадёт по ссылке на эту страницу.
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


$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirm_password'];


if(empty($email)) {
    set_form_warning('email');
    set_flash_message('danger','Поле email не должно быть пустым ' . $email);
    redirect_to('../security.php?id='.$idUser);
}

//Записываем изменённый имейл что бы вывести его если потребуется.
$_SESSION['edit_email'] = $email;

if(!empty($password) && empty($confirmPassword) || empty($password) && !empty($confirmPassword) || $password !== $confirmPassword) {
    set_form_warning('password');
    set_form_warning('confirm_password');
    set_flash_message('danger','Что бы изменить пароль, введённые данные должны совпадать.');
    redirect_to('../security.php?id='.$idUser);
}


//Записываем имейл который будет устанавливать. (На случай если он поменялся)
$newEmail = $email;

if(!empty($email) && $email == $arrUser['email']) {
    $newEmail = $arrUser['email'];
} else {
    $pdo = new PDO('mysql:host=localhost;dbname=task2', 'mysql', 'mysql');
    $statment = $pdo->prepare("SELECT * FROM users WHERE email=:email");
    $statment->execute(['email' => $email]);

    $count = $statment->rowCount();

    if ($count > 0) {
        set_form_warning('email');
        set_flash_message('danger', 'Этот имейл уже используется в системе. Вы не можете его занять.');
        redirect_to('../security.php?id=' . $idUser);
    }
}

$pdo = new PDO("mysql:host=localhost;dbname=task2","mysql","mysql");
$statment = $pdo->prepare('UPDATE users SET email=:email, password=:password WHERE id=:userId;');

$hashPassowrd = password_hash($password,PASSWORD_DEFAULT );

$statment->execute([
    'email'=>$newEmail,
    'password'=>$hashPassowrd,
    'userId' => $arrUser['id']
]);




//Что бы авторизовать пользователя после изменения почты (так проще чем если выкидывать его для повторной авторизации).
if(is_author((int)$user['id'],(int)$idUser)) {
    //Считаем что он авторизован если у нас есть его данные. Нет смысла проверять каждый раз логин и пароль.
    $_SESSION['user_email'] = $newEmail;
}


set_flash_message('success','Данные пользователя успешно обновлены.');
redirect_to('../page_profile.php?id='.$arrUser['id']);

















