<?php
session_start();

/*
 * Parameter: Имейл пользователя
 *
 * Description: Проверяет наличие такого имейл адреса в бд.
 *
 * Return value: Возвращаем массив данных пользователя или boolean(false)
*/
function get_user_by_email($email) {
    $pdo = new PDO("mysql:host=localhost;dbname=task2","mysql","mysql");
    $statment = $pdo->prepare("SELECT * FROM users WHERE email=:email;");
    $statment->execute(['email'=>$email]);

    $user = $statment->fetch(PDO::FETCH_ASSOC);

    return $user;
}

/*
 * Parameter: Почта, пароль введённые
 *
 * Description: Добавляет нового пользователя в систему
 *
 * Return value: void
*/
function add_user($email, $password) :void {
    $pdo = new PDO("mysql:host=localhost;dbname=task2","mysql","mysql");
    $statment = $pdo->prepare('INSERT INTO users (email, password) values (:email,:password);');

    $hashPassowrd = password_hash($password,PASSWORD_DEFAULT );

    $statment->execute(['email'=>$email,'password' => $hashPassowrd]);
}
/*
 * Parameter: Имя ключа, сообщение которое будет в него записано
 *
 * Description: Устанавливает информацию о сообщении которое нужно вывести.
 *
 * Return value: void
*/
function set_flash_message($name, $message) :void {
    $_SESSION[$name] = $message;
}
/*
 * Parameter: Принимает наименование класса из бутстрап, который является ключем сообещния передаваемого в сессии.
 *
 * Description: Выводит сообщение обернутое в класс
 *
 * Return value: void
*/
function display_flash_message($name) : void {

}

/*
 * Parameter: Путь по которому требуется перейти
 *
 * Description Переводит на указанную страницу
 *
 * Return: void
 */
function redirect_to($path) :void {
    header("Location: $path");
    exit;
}













