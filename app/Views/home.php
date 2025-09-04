<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Сокращатель ссылок</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
<div class="container">
    <h1>Сокращатель ссылок</h1>
    <?php if (!empty($error)): ?>
        <p class="error">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>
    <form method="post" action="/shorten" class="short-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
        <input type="url" name="url" required placeholder="Введите URL">
        <button type="submit">Сократить</button>
    </form>
    <?php if (!empty($short)): ?>
        <p class="result">Короткая ссылка: <a href="<?= htmlspecialchars($short) ?>"><?= htmlspecialchars($short) ?></a>
        </p>
    <?php endif; ?>
</div>
</body>
</html>
