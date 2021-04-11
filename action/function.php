<?php
session_start();

/*
 * Parameter: Имейл пользователя
 *
 * Description: Проверяет наличие такого имейл адреса в бд.
 *
 * Return value: Возвращаем массив данных пользователя или boolean(false)
*/
function get_user_by_email(string $email) {
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
function add_user(string $email,string $password) :void {
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
function set_flash_message(string $name,string $message) :void {
    $_SESSION[$name] = $message;
}
/*
 * Parameter: Принимает наименование класса из бутстрап, который является ключем сообещния передаваемого в сессии.
 *
 * Description: Выводит сообщение обернутое в класс
 *
 * Return value: void
*/
function display_flash_message(string $name) : void {
    echo '<div class="alert alert-' . $name . ' text-dark" role="alert">'. $_SESSION[$name] . '</div>';
    unset($_SESSION[$name]);
}

/*
 * Parameter: Путь по которому требуется перейти
 *
 * Description: Переводит на указанную страницу
 *
 * Return: void
 */
function redirect_to(string $path) :void {
    header("Location: $path");
    exit;
}

/*
 * Parameter:
 *          string $email
 *          string $password
 *
 * Description: Производит авторизацию пользователей в систему
 *
 * Return: boolean true/false
 */
function login(string $email,string $password) :bool {

    $user = get_user_by_email($email);
    if(!$user || !password_verify($password, $user['password'])) {

        set_flash_message('danger','Данные введены не правильные.');
        return false;
    }

    //Считаем что он авторизован если у нас есть его данные. Нет смысла проверять каждый раз логин и пароль.
    $_SESSION['user_email'] = $user['email'];

    return true;
}

/*
 * Parameter: void
 *
 * Description: Проверяет авторизован пользователь или нет.
 *
 * Return: boolean
 *
 */
function is_authorized() :bool {
    if(isset($_SESSION['user_email']))
        return true;

    return false;
}


/*
 * Parameter: void
 *
 * Description: Получает все данные пользователей для отображения списка пользователей.
 *
 * Return: Array user| bool false
 */
function get_all_user() {

    $pdo = new PDO('mysql:host=localhost;dbname=task2', 'mysql','mysql');
    $statment = $pdo->query('SELECT * FROM users;');
    $user = $statment->fetchAll(PDO::FETCH_ASSOC);

    return $user;
}









