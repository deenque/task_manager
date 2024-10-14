<?php 
require_once '../config/database.php'; // Подключаем файл конфигурации базы данных

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($username, $password) {
        // Проверка на существование имени пользователя
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->fetch()) {
            return 'Такой пользователь уже существует!'; // Сообщение, если пользователь найден
        } else {
            // Хэшируем пароль и регистрируем пользователя
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $insertStmt = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($insertStmt->execute([$username, $passwordHash])) {
                return 'Регистрация успешна! Вы можете войти.';
            } else {
                return 'Ошибка регистрации! Попробуйте снова.';
            }
        }
    }

    // Авторизация пользователя
    public function login($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) { // Успешная авторизация
            $_SESSION['user_id'] = $user['id']; // Сохранение ID пользователя в сессии
            return 'Успешно вошли в систему!';
        } else {
            return 'Неверное имя пользователя или пароль!'; // Сообщение об ошибке
        }
    }

    // Выход из системы
    public function logout() {
        session_unset(); // Удаляем все переменные сессии
        session_destroy(); // Уничтожаем сессию
        return 'Вы вышли из системы!';
    }
}

// Создание объекта User
$user = new User($pdo);
