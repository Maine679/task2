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
function add_user(string $email,string $password) :int {
    $pdo = new PDO("mysql:host=localhost;dbname=task2","mysql","mysql");
    $statment = $pdo->prepare('INSERT INTO users (email, password) values (:email,:password);');

    $hashPassowrd = password_hash($password,PASSWORD_DEFAULT );

    $statment->execute(['email'=>$email,'password' => $hashPassowrd]);
    $id = $pdo->lastInsertId();
    return $id;
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
 * Parameter: Принимает наименование параметра
 *
 * Description: Выводит класс для визуального отображения ошибки.
 *
 * Return value: void
*/
function display_form_warning(string $name) : void {
    if(isset($_SESSION['danger_' . $name])) {
        echo 'alert alert-danger';
        unset($_SESSION['danger_' . $name]);
        unset($_SESSION['danger_message']);
    }
}
/*
 * Parameter: string наименование параметра
 *
 * Description: Устанавливает информацию что бы вывести предупреждение о незаполненном поле
 *
 * Return value: void
*/
function set_form_warning(string $name) :void {
    $_SESSION['danger_' . $name] = 1;
    $_SESSION['danger_message'] = 1;
}
/*
 * Parameter: void
 *
 * Description: Возвращает установлена ошибка незаполненных полей или нет.
 *
 * Return value: void
*/
function get_form_warning() :bool {
    if(isset($_SESSION['danger_message']))
        return true;
    return false;
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


/*
 * Parameter: string $name, string $position, string $address, string $phone, int $id
 *
 * Description: Устанавливает дополнительные поля для пользователей в бд. Имя, позиция, телефон, адрес.
 *
 * Return: void
 */
function write_db_user_data(string $name, string $position, string $address, string $phone, int $id) : void {

    $pdo = new PDO("mysql:host=localhost;dbname=task2","mysql","mysql");
    $statment = $pdo->prepare("UPDATE users SET name=:name, phone=:phone, address=:address, position=:position WHERE id=:idUser");

    $statment->execute([
        'name'=>$name,
        'phone'=>$phone,
        'address'=>$address,
        'position'=>$position,
        'idUser'=>$id
    ]);
}
/*
 * Parameter: string $status, int $id
 *
 * Description: Устанавливает значение поля статус в бд
 *
 * Return: void
 */
function write_db_user_status(string $status, int $id) :void {

    $arrStatus = [
        'online'=>'online',
        'eway'=>'eway',
        'ofline'=>'ofline',
        'notdisturb'=>'notdisturb'
    ];

    if(!array_key_exists($status, $arrStatus)) {
        $status = 'online';
    }

    $pdo = new PDO("mysql:host=localhost;dbname=task2","mysql","mysql");
    $statment = $pdo->prepare("UPDATE users SET status=:status WHERE id=:idUser");

    $statment->execute([
        'status'=>$status,
        'idUser'=>$id
    ]);
}

/*
 * Parameter: array PhotoData[]
 *
 * Description: Для загрузки картинки на сервер
 *
 * Return: string $fileLocation
 */
function upload_photo($photo) :string {
    $tmpName = $photo['tmp_name'];
    $error = $photo['error'];

    if($error) {
        set_flash_message('danger','При загрузки изображения что-то пошло не так, попробуйте ещё раз.');
        redirect_to('../create_user.php');
    }

    $fi = finfo_open(FILEINFO_MIME_TYPE);
    $mime = (string) finfo_file($fi, $tmpName);


    if (strpos($mime, 'image') === false) {
        set_flash_message('danger','Можно загружать только изображения.');
        redirect_to('../create_user.php');
    }

    $image = getimagesize($tmpName);
    $format = image_type_to_extension($image[2]);

    $newFileName = 'img_' . md5(microtime()) . $format;
    if (!move_uploaded_file($tmpName,'W:\domains\task2\img\demo\avatars\\' . $newFileName)) {
        set_flash_message('danger','При загрузке произошла ошибка, попробуйте ещё раз.');
        redirect_to('../create_user.php');
    }

    return $newFileName;
}

/*
 * Parameter: string $fileName, int $id
 *
 * Description: Для записи картинки пользователю в бд
 *
 * Return: void
 */
function write_db_user_image(string $image ,int $id) :void {
    $pdo = new PDO("mysql:host=localhost;dbname=task2","mysql","mysql");
    $statment = $pdo->prepare("UPDATE users SET avatar=:image WHERE id=:idUser");

    $statment->execute([
        'image'=>$image,
        'idUser'=>$id
    ]);
}

/*
 * Parameter: string $social_link,string $telegram,string $instagram, int $id
 *
 * Description: Для записи социальных ссылок пользователя в бд.
 *
 * Return: void
 */
function write_db_user_social_link(string $social_link,string $telegram,string $instagram, int $id) : void {
    $pdo = new PDO("mysql:host=localhost;dbname=task2","mysql","mysql");
    $statment = $pdo->prepare("UPDATE users SET social_link=:social_link, instagram=:instagram, telegram=:telegram WHERE id=:idUser");

    $statment->execute([
        'social_link'=>$social_link,
        'instagram'=>$instagram,
        'telegram'=>$telegram,
        'idUser'=>$id
    ]);
}





/*
 * Parameter: int $id
 *
 * Description: Для получения данных о пользователи из базы
 *
 * Return: array $userData
 */
function get_user_by_id(int $id) :array {
    $pdo = new PDO("mysql:host=localhost;dbname=task2","mysql","mysql");
    $statment = $pdo->prepare("SELECT * FROM users WHERE id=:idUser;");
    $statment->execute(['idUser'=>$id]);

    $count = $statment->rowCount();

    if(!$count) {
        $user['error'] = true;
    } else {
        $user = $statment->fetch(PDO::FETCH_ASSOC);
        $user['error'] = false;
    }

    return $user;
}

/*
 * Parameter: int $id_logged, int $idUser
 *
 * Description: Проверяет равны ли идентификаторы? Не понял пока зачем эту функцию делать
 *
 * Return: boolean
 */
function is_author(int $id_logged,int $idUser) :bool {

    if($id_logged == $idUser) {
        return true;
    }

    return false;
}






