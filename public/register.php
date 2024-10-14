<?php
require_once '../config/database.php';
require_once '../app/models/user.php';

session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User($pdo);
    $message = $user->register($_POST['username'], $_POST['password']);
    if ($message === 'Регистрация успешна! Вы можете войти.') {
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="background">
    <div class="shapes"></div>
    <div class="shapes"></div>
    <div class="form-container">
    <form action="" method="POST">
    <h3>Регистрация:</h3>
    <label for="user">Имя пользователя:</label>
    <input type="text" name="username" id="user" required placeholder="Введите имя" autocomplete="name" />
    <div id="username-error" class="error-message" style="display: none;"></div>
    
    <label for="psw">Пароль:</label>
    <input type="password" name="password" id="psw" required placeholder="Введите пароль" autocomplete="password" />
    <div id="password-error" class="error-message" style="display: none;"></div>
    
    <button type="submit">Зарегистрироваться</button>
    <p><?php echo $message; ?></p>
    <p class="here">Уже есть аккаунт? <a href="login.php"> Войти</a></p>
    <div id="error-message" style="color: #a7a7a7; font-size: 10px; display: none;"></div> <!-- Контейнер для общего сообщения об ошибке -->
</form>

    </div>
</div>

    <script src="js/app.js"></script>
</body>
</html>


