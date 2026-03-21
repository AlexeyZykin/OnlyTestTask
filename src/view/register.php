<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/style.css">
</head>

<body>
<?php

use util\SessionUtils;

$oldInputData = SessionUtils::getOneTimeValue("input_data");
$error = SessionUtils::getOneTimeValue("error");
?>

<main class="main-container">
    <h1>Регистрация</h1>
    <?php if ($error): ?>
        <div style="color: red;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="/register">

        <div>
            <label for="login">Логин</label>
            <input name="login" type="text" placeholder="Введите логин"
                <?php if (isset($oldInputData["login"])): ?>
                    value="<?= htmlspecialchars($oldInputData["login"]) ?>"
                <?php endif; ?>
                   required
            >
        </div>

        <div>
            <label for="phone">Номер телефона</label>
            <input name="phone" type="text" placeholder="Номер телефона"
                <?php if (isset($oldInputData["phone"])): ?>
                    value="<?= htmlspecialchars($oldInputData["phone"]) ?>"
                <?php endif; ?>
                   required
            >
        </div>

        <div>
            <label for="email">Email</label>
            <input name="email" type="email" placeholder="Почта"
                <?php if (isset($oldInputData["email"])): ?>
                    value="<?= htmlspecialchars($oldInputData["email"]) ?>"
                <?php endif; ?>
                   required
            >
        </div>

        <div>
            <label for="password">Введите пароль</label>
            <input name="password" type="password" placeholder="Пароль"
                <?php if (isset($oldInputData["password"])): ?>
                    value="<?= htmlspecialchars($oldInputData["password"]) ?>"
                <?php endif; ?>
                   required
            >
        </div>

        <div>
            <label for="repeat_password">Повторите пароль</label>
            <input name="repeat_password" type="password" placeholder="Повторите пароль"
                <?php if (isset($oldInputData["repeat_password"])): ?>
                    value="<?= htmlspecialchars($oldInputData["repeat_password"]) ?>"
                <?php endif; ?>
                   required
            >
        </div>

        <button type="submit">Зарегистрироваться</button>
    </form>
</main>
</body>

</html>

