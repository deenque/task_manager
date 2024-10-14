<?php
session_start(); // Запускаем сессию

require_once '../config/database.php'; // Подключаем файл конфигурации базы данных
require_once '../app/models/user.php'; // Подключаем файл с классом User


// Проверяем, авторизован ли пользователь
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php"); // Если авторизован, перенаправляем на дашборд
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User($pdo); // Создаем объект User

    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'register') {
            $message = $user->register($_POST['username'], $_POST['password']);
            if ($message === 'Регистрация успешна! Вы можете войти.') {
                header("Location: login.php"); // Перенаправляем на страницу входа
                exit();
            } else {
                echo $message; // Выводим сообщение об ошибке
            }
        } elseif ($_POST['action'] === 'login') {
            $message = $user->login($_POST['username'], $_POST['password']);
            if ($message === 'Успешно вошли в систему!') {
                header("Location: dashboard.php"); // Перенаправляем к списку задач
                exit();
            } else {
                echo $message; // Выводим сообщение об ошибке
            }
        }
    }
}
?>
