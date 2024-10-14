
<link rel="stylesheet" href="css/dashboard.css">
<script src="js/task.js"></script>

<?php
class Task {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function updateTitle($taskId, $newTitle) {
        $stmt = $this->pdo->prepare("UPDATE tasks SET title = ? WHERE id = ?");
        return $stmt->execute([$newTitle, $taskId]);
    }
    
    public function createTask($userId, $title, $description) {
        $stmt = $this->pdo->prepare("INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $title, $description]);
    }

    public function getTasks($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateTask($taskId, $title, $description, $status) {
        $stmt = $this->pdo->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
        return $stmt->execute([$title, $description, $status, $taskId]);
    }

    public function deleteTask($taskId) {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$taskId]);
    }
}


?>