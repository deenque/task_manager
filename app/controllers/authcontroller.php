<?php
require_once '../config/database.php';

class AuthController {
    public function register($username, $password) {
        global $pdo;
        // Проверка существования пользователя
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            return "Пользователь уже существует";
        }

        // Хэширование пароля и регистрация
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $insert = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        if ($insert->execute([$username, $passwordHash])) {
            return "Регистрация успешна!";
        } else {
            return "Ошибка регистрации!";
        }
    }

    public function login($username, $password) {
        global $pdo;
        // Поиск пользователя
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            header('Location: dashboard.php');
            exit();
        } else {
            return "Неверное имя пользователя или пароль";
        }
    }
}


?>
