<?php
require_once '../config/database.php';
require_once '../app/models/user.php';

session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User($pdo);
    $message = $user->login($_POST['username'], $_POST['password']);
    if ($message === 'Успешно вошли в систему!') {
        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация пользователя</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>


<div class="background">
    <div class="shapes"></div>
    <div class="shapes"></div>
    <div class="form-container">
        <form action="" method="POST">
            <h3>Вход:</h3>
            <label for="user">Имя пользователя:</label>
            <input type="text" name="username" required placeholder="Имя пользователя" oninvalid="this.setCustomValidity('')" oninput="setCustomValidity('')">
            <div id="username-error" class="error-message" style="display: none;"></div>
            <label for="psw">Пароль:</label>
            <input type="password" name="password" required placeholder="Пароль" oninvalid="this.setCustomValidity('')" oninput="setCustomValidity('')">
            <div id="password-error" class="error-message" style="display: none;"></div>
            <button type="submit">Войти</button>
            <p><?php echo $message; ?></p>
            <div id="error-message" style="color: red; font-size: 14px; display: none;"></div> <!-- Контейнер для общего сообщения об ошибке -->
            <p>Нет аккаунта? <a href="register.php"> Создать</a></p>
        </form>
    </div>
</div>





    </div>
    <script src="js/app.js"></script>
</body>
</html>


