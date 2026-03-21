<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Profile</title>
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
        <h2 style="text-align: center">
            Профиль
        </h2>

        <div>
            <div class="profile-field-wrapper">
                <div class="profile-subtitle">Логин</div>
                <?php if (isset($login)): ?>
                    <div>
                        <?= htmlspecialchars($login) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="profile-field-wrapper">
                <div class="profile-subtitle">Телефон</div>
                <?php if (isset($phone)): ?>
                    <div>
                        <?= htmlspecialchars($phone) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="profile-field-wrapper">
                <div class="profile-subtitle">Email</div>
                <?php if (isset($email)): ?>
                    <div>
                        <?= htmlspecialchars($email) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <h3>Редактирование</h3>

        <?php if ($error): ?>
            <div style="color: red;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="/profile">

            <div>
                <label for="login">Логин</label>
                <input
                        name="login"
                        type="text"
                        placeholder="Введите новый Логин"
                    <?php if (isset($oldInputData["login"])): ?>
                        value="<?= htmlspecialchars($oldInputData["login"]) ?>"
                    <?php endif; ?>
                >
            </div>

            <div>
                <label for="phone">Номер телефона</label>
                <input
                        name="phone"
                        type="text"
                        placeholder="Введите новый номер"
                    <?php if (isset($oldInputData["phone"])): ?>
                        value="<?= htmlspecialchars($oldInputData["phone"]) ?>"
                    <?php endif; ?>
                >
            </div>

            <div>
                <label for="email">Email</label>
                <input
                        name="email"
                        type="email"
                        placeholder="Введите новый email"
                    <?php if (isset($oldInputData["email"])): ?>
                        value="<?= htmlspecialchars($oldInputData["email"]) ?>"
                    <?php endif; ?>
                >
            </div>

            <div>
                <label for="password">Пароль</label>
                <input
                        name="password"
                        type="password"
                        placeholder="Введите новый пароль"
                    <?php if (isset($oldInputData["password"])): ?>
                        value="<?= htmlspecialchars($oldInputData["password"]) ?>"
                    <?php endif; ?>
                >
            </div>

            <button type="submit">Обновить</button>
        </form>

    </main>
</body>

</html>