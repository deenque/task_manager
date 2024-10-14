<?php 
session_start(); // Запускаем сессию

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/database.php';
require_once '../app/models/task.php';

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    echo "Пользователь не найден.";
    exit();
}

$taskModel = new Task($pdo);
$tasks = $taskModel->getTasks($userId);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Управление задачами</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="js/task.js" defer></script> <!-- Добавил defer для отложенной загрузки -->
    
</head>
<body>
  <div class="dashboard-container">
        <h1>Добро пожаловать, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        <div class="filter-buttons">
            <button onclick="filterTasks('all')">Все</button>
            <button onclick="filterTasks('completed')">Выполненные</button>
            <button onclick="filterTasks('pending')">Невыполненные</button>
        </div>
        
        <form id="task-form">
            <input type="text" id="task-name" placeholder="Название задачи" required>
            <button type="submit">Добавить задачу</button>
        </form>

        <h2>Ваши задачи:</h2>
        <ul class="task-list" id="task-list">
            <?php foreach ($tasks as $task): ?>
                <li data-task-id="<?php echo $task['id']; ?>">
                    <?php echo htmlspecialchars($task['title']); ?>
                    <span class="task-status"><?php echo $task['status'] ? 'Выполнено' : 'Невыполнено'; ?></span>
                    <button class="edit-button" onclick="editTask(<?php echo $task['id']; ?>, '<?php echo addslashes(htmlspecialchars($task['title'])); ?>')">Редактировать</button>
                    <button class="delete-button" onclick="deleteTask(<?php echo $task['id']; ?>)">Удалить</button>
                    <button class="status-button" onclick="updateTaskStatus(<?php echo $task['id']; ?>, <?php echo $task['status'] ? '0' : '1'; ?>)">
                        <?php echo $task['status'] ? 'Отменить' : 'Выполнить'; ?>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="logout-container">
        <button id="logout-button" onclick="logout()">Выйти</button>
    </div>
</div>
</body>
</html>
