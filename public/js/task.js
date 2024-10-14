document.getElementById('task-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const taskName = document.getElementById('task-name').value;

    // Убедитесь, что путь к файлу правильный
    fetch('api.php', { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ title: taskName }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            addTaskToList(data.task);
            document.getElementById('task-name').value = ''; // Очистка поля ввода
        } else {
            alert('Ошибка: ' + data.message);
        }
    })
    .catch((error) => {
        console.error('Ошибка:', error);
        alert('Произошла ошибка при добавлении задачи.');
    });
});

function addTaskToList(task) {
    const taskList = document.getElementById('task-list');
    const li = document.createElement('li');
    li.setAttribute('data-task-id', task.id);
    li.innerHTML = `
        ${task.title} 
        <span class="task-status">${task.status ? 'Выполнено' : 'Невыполнено'}</span>
        <button class="edit-button" onclick="editTask(${task.id}, '${task.title}')">Редактировать</button>
        <button class="delete-button" onclick="deleteTask(${task.id})">Удалить</button>
        <button class="status-button" onclick="updateTaskStatus(${task.id}, ${task.status ? '0' : '1'})">
            ${task.status ? 'Отменить' : 'Выполнить'}
        </button>
    `;
    taskList.appendChild(li);
}

function deleteTask(taskId) {
    fetch(`api.php?id=${taskId}`, {
        method: 'DELETE',
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const taskElement = document.querySelector(`li[data-task-id='${taskId}']`);
            taskElement.remove();
        } else {
            alert('Ошибка: ' + data.message);
        }
    })
    .catch((error) => {
        console.error('Ошибка:', error);
        alert('Произошла ошибка при удалении задачи.');
    });
}

function updateTaskStatus(taskId, currentStatus) {
    const newStatus = currentStatus === 1 ? 0 : 1;
    fetch(`api.php`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: taskId, status: newStatus }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const taskElement = document.querySelector(`li[data-task-id='${taskId}']`);
            const statusSpan = taskElement.querySelector('.task-status');
            statusSpan.textContent = newStatus ? 'Выполнено' : 'Невыполнено';

            const statusButton = taskElement.querySelector('.status-button');
            statusButton.textContent = newStatus ? 'Отменить' : 'Выполнить';
            statusButton.onclick = () => updateTaskStatus(taskId, newStatus);
        } else {
            alert('Ошибка: ' + data.message);
        }
    })
    .catch((error) => {
        console.error('Ошибка:', error);
        alert('Произошла ошибка при обновлении статуса задачи.');
    });
}

function editTask(taskId, currentTitle) {
    const newTitle = prompt("Введите новое название задачи:", currentTitle);
    if (newTitle !== null && newTitle.trim() !== "") {
        fetch(`api.php`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: taskId, title: newTitle }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const taskElement = document.querySelector(`li[data-task-id='${taskId}']`);
                taskElement.firstChild.textContent = newTitle;
            } else {
                alert('Ошибка: ' + data.message);
            }
        })
        .catch((error) => {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при редактировании задачи.');
        });
    }
}


function deleteTask(taskId) {
    fetch(`api.php?id=${taskId}`, {
        method: 'DELETE',
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const taskElement = document.querySelector(`li[data-task-id='${taskId}']`);
            taskElement.remove();
        } else {
            alert('Ошибка: ' + data.message);
        }
    })
    .catch((error) => {
        console.error('Ошибка:', error);
    });
}

function updateTaskStatus(taskId, currentStatus) {
    const newStatus = currentStatus === 1 ? 0 : 1;
    fetch(`api.php`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: taskId, status: newStatus }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const taskElement = document.querySelector(`li[data-task-id='${taskId}']`);
            const statusSpan = taskElement.querySelector('.task-status');
            statusSpan.textContent = newStatus ? 'Выполнено' : 'Невыполнено';

            const statusButton = taskElement.querySelector('.status-button');
            statusButton.textContent = newStatus ? 'Отменить' : 'Выполнить';
            statusButton.onclick = () => updateTaskStatus(taskId, newStatus);
        } else {
            alert('Ошибка: ' + data.message);
        }
    })
    .catch((error) => {
        console.error('Ошибка:', error);
    });
}

function filterTasks(status) {
    const taskElements = document.querySelectorAll('.task-list li');

    taskElements.forEach(taskElement => {
        const taskStatus = taskElement.querySelector('.task-status').textContent === 'Выполнено' ? 'completed' : 'pending';

        if (status === 'all' || taskStatus === status) {
            taskElement.style.display = ''; // Показываем элемент
        } else {
            taskElement.style.display = 'none'; // Скрываем элемент
        }
    });
}

function deleteTask(taskId) {
    // Находим элемент задачи в DOM
    const taskElement = document.querySelector(`li[data-task-id='${taskId}']`);
    
    // Удаляем задачу из DOM мгновенно
    taskElement.remove();

    // Теперь отправляем запрос на сервер, чтобы удалить задачу из базы данных
    fetch(`api.php?id=${taskId}`, {
        method: 'DELETE',
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert('Ошибка: ' + data.message);
           
        }
    })
    .catch((error) => {
        console.error('Ошибка:', error);
        // Если произошла ошибка, тоже можно вернуть элемент обратно
       
    });
}

document.getElementById('logout-button').addEventListener('click', function() {
    fetch('logout.php')
        .then(response => {
            if (response.ok) {
                window.location.href = 'login.php'; // Перенаправление на страницу входа
            } else {
                alert('Ошибка при выходе');
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
        });
});




