    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <title>Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/public/style.css">
        <script src="https://smartcaptcha.cloud.yandex.ru/captcha.js" defer></script>
    </head>
    <body>
    <?php

    use util\SessionUtils;

    $oldInputData = SessionUtils::getOneTimeValue("input_data");
    $error = SessionUtils::getOneTimeValue("error");
    $smart_captcha_client = SessionUtils::getOneTimeValue("smart_captcha_client")
    ?>

    <main class="main-container">
        <h1>Авторизация</h1>

        <?php if ($error): ?>
            <div style="color: red;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/login">
            <input
                    type="text"
                    name="phone_or_email"
                    placeholder="Телефон или email"
                <?php if (isset($oldInputData["phone_or_email"])): ?>
                    value="<?= htmlspecialchars($oldInputData["phone_or_email"]) ?>"
                <?php endif; ?>
                    required
            >

            <input
                    type="password"
                    name="password"
                    placeholder="Пароль"
                <?php if (isset($oldInputData["password"])): ?>
                    value="<?= htmlspecialchars($oldInputData["password"]) ?>"
                <?php endif; ?>
                    required
            >

            <div
                    id="captcha-container"
                    class="smart-captcha"
                    data-sitekey="<?= $smart_captcha_client ?>"
            ></div>

            <button type="submit">Войти</button>
        </form>
    </main>
    </body>
    </html>