document.addEventListener('DOMContentLoaded', () => {
    // Обработчик для формы регистрации
    const registerForm = document.querySelector('form[action*="register"]');
    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(registerForm);

            try {
                const response = await fetch('../app/routes.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.text();
                alert(result);
            } catch (error) {
                console.error('Ошибка при регистрации:', error);
                alert('Не удалось выполнить регистрацию. Попробуйте снова.');
            }
        });
    }

    // Обработчик для формы входа
    const loginForm = document.querySelector('form[action*="login"]');
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(loginForm);

            try {
                const response = await fetch('../app/routes.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.text();
                if (response.ok) {
                    // Редирект на tasks.php при успешном входе
                    window.location.href = 'tasks.php';
                } else {
                    alert(result);
                }
            } catch (error) {
                console.error('Ошибка при входе:', error);
                alert('Не удалось выполнить вход. Попробуйте снова.');
            }
        });
    }
});
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const usernameInput = form.querySelector('input[name="username"]');
    const passwordInput = form.querySelector('input[name="password"]');
    const usernameError = document.getElementById('username-error');
    const passwordError = document.getElementById('password-error');
    const errorMessage = document.getElementById('error-message');

    form.addEventListener('submit', function (event) {
        let valid = true;
        usernameError.style.display = 'none'; // Скрываем ошибки
        passwordError.style.display = 'none';
        errorMessage.style.display = 'none'; // Скрываем общее сообщение об ошибке

        // Валидация логина
        const username = usernameInput.value.trim();
        const usernamePattern = /^[a-zA-Z0-9]+$/; // Только буквы и цифры

        if (!usernamePattern.test(username)) {
            valid = false;
            usernameError.textContent = 'Некорректный формат. Используйте только буквы и цифры.';
            usernameError.style.display = 'block'; // Показываем сообщение об ошибке
        }

        // Валидация пароля
        const password = passwordInput.value.trim();
        if (password.length < 6) { // Минимальная длина пароля
            valid = false;
            passwordError.textContent = 'Пароль должен содержать минимум 6 символов.';
            passwordError.style.display = 'block'; // Показываем сообщение об ошибке
        }

        // Если форма не валидна, предотвратим отправку
        if (!valid) {
            event.preventDefault(); // Предотвращаем отправку формы
        }
    });
});


