<?php
require 'db.php';

function sanitize_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $name = sanitize_input($_POST['name']);
        $phone = sanitize_input($_POST['phone']);
        $email = filter_var(sanitize_input($_POST['email']), FILTER_VALIDATE_EMAIL);

        if ($email) {
            $stmt = $pdo->prepare("INSERT INTO customer (name, phone, email) VALUES (?, ?, ?)");
            $stmt->execute([$name, $phone, $email]);
            echo "Клієнта успішно додано.";
        } else {
            echo "Невірний формат email.";
        }
    }

    if (isset($_POST['delete'])) {
        $id = filter_var(sanitize_input($_POST['customer_id']), FILTER_VALIDATE_INT);

        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM customer WHERE customer_id = ?");
            $stmt->execute([$id]);
            echo "Клієнта успішно видалено.";
        } else {
            echo "Невірний ID.";
        }
    }

    if (isset($_POST['update'])) {
        $id = filter_var(sanitize_input($_POST['customer_id']), FILTER_VALIDATE_INT);
        $name = sanitize_input($_POST['name']);
        $phone = sanitize_input($_POST['phone']);
        $email = filter_var(sanitize_input($_POST['email']), FILTER_VALIDATE_EMAIL);

        if ($id && $email) {
            $stmt = $pdo->prepare("UPDATE customer SET name = ?, phone = ?, email = ? WHERE customer_id = ?");
            $stmt->execute([$name, $phone, $email, $id]);
            echo "Дані клієнта успішно змінено.";
        } else {
            echo "Помилка в ID або email.";
        }
    }
}

$customers = $pdo->query("SELECT * FROM customer")->fetchAll();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Клієнти</title>
</head>
<body>
    <h1>Список клієнтів</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ім'я</th>
                <th>Телефон</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?= htmlspecialchars($customer['customer_id']) ?></td>
                    <td><?= htmlspecialchars($customer['name']) ?></td>
                    <td><?= htmlspecialchars($customer['phone']) ?></td>
                    <td><?= htmlspecialchars($customer['email']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Додати клієнта</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Ім'я" required>
        <input type="text" name="phone" placeholder="Телефон" required>
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit" name="add">Додати</button>
    </form>

    <h2>Видалити клієнта</h2>
    <form method="POST">
        <select name="customer_id" required>
            <option value="">Оберіть ID</option>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= htmlspecialchars($customer['customer_id']) ?>">
                    <?= htmlspecialchars($customer['customer_id']) ?>: <?= htmlspecialchars($customer['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="delete">Видалити</button>
    </form>

    <h2>Змінити дані клієнта</h2>
    <form method="POST">
        <select name="customer_id" required>
            <option value="">Оберіть ID</option>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= htmlspecialchars($customer['customer_id']) ?>">
                    <?= htmlspecialchars($customer['customer_id']) ?>: <?= htmlspecialchars($customer['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="name" placeholder="Нове ім'я" required>
        <input type="text" name="phone" placeholder="Новий телефон" required>
        <input type="email" name="email" placeholder="Новий Email" required>
        <button type="submit" name="update">Змінити</button>
    </form>
</body>
</html>