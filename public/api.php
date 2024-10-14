<?php
require_once '../config/database.php';
require_once '../app/models/task.php';

session_start(); // Убедимся, что сессия стартует
$taskModel = new Task($pdo);
header('Content-Type: application/json');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        // Получение данных из тела запроса
        $input = json_decode(file_get_contents('php://input'), true);
        $userId = $_SESSION['user_id'] ?? null; // Убедимся, что пользователь залогинен
        $title = $input['title'] ?? '';

        if ($userId && !empty($title)) {
            // Попробуем создать задачу
            if ($taskModel->createTask($userId, $title, '')) {
                $taskId = $pdo->lastInsertId();
                echo json_encode([
                    'success' => true, 
                    'task' => [
                        'id' => $taskId,
                        'title' => $title,
                        'status' => 0
                    ]
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Не удалось создать задачу']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Недостаточно данных для создания задачи']);
        }
        break;

        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            if (isset($input['status'])) {
                $taskId = $input['id'];
                $status = $input['status'];
                $result = $taskModel->updateTask($taskId, '', '', $status);
            } else if (isset($input['title'])) {
                $taskId = $input['id'];
                $newTitle = $input['title'];
                $result = $taskModel->updateTitle($taskId, $newTitle);
            }
            echo json_encode(['success' => $result]);
            break;

            case 'DELETE':
                parse_str($_SERVER['QUERY_STRING'], $params);
                $taskId = $params['id'];
                $result = $taskModel->deleteTask($taskId);
                echo json_encode(['success' => $result]);
                break;

    default:
        echo json_encode(['success' => false, 'message' => 'Неверный метод запроса']);
        break;
}
error_log("Тестовая ошибка: " . print_r($input, true));