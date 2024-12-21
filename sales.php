<?php
require 'db.php';

function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function validate_number($data) {
    return is_numeric($data) ? $data : null;
}

$customers = $pdo->query("SELECT customer_id, name FROM customer")->fetchAll();
$vehicles = $pdo->query("SELECT vehicle_id, brand, model FROM vehicle")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $sale_date = sanitize_input($_POST['sale_date']);
        $customer_id = validate_number($_POST['customer_id']);
        $vehicle_id = validate_number($_POST['vehicle_id']);
        $amount = validate_number($_POST['amount']);

        if ($sale_date && $customer_id && $vehicle_id && $amount) {
            $stmt = $pdo->prepare("INSERT INTO sale (sale_date, customer_id, vehicle_id, amount) VALUES (?, ?, ?, ?)");
            $stmt->execute([$sale_date, $customer_id, $vehicle_id, $amount]);
            echo "Продаж успішно додано.";
        } else {
            echo "Помилка: некоректні дані.";
        }
    }

    if (isset($_POST['delete'])) {
        $sale_id = validate_number($_POST['sale_id']);

        if ($sale_id) {
            $stmt = $pdo->prepare("DELETE FROM sale WHERE sale_id = ?");
            $stmt->execute([$sale_id]);
            echo "Продаж успішно видалено.";
        } else {
            echo "Помилка: некоректний ID.";
        }
    }

    if (isset($_POST['update'])) {
        $sale_id = validate_number($_POST['sale_id']);
        $sale_date = sanitize_input($_POST['sale_date']);
        $customer_id = validate_number($_POST['customer_id']);
        $vehicle_id = validate_number($_POST['vehicle_id']);
        $amount = validate_number($_POST['amount']);

        if ($sale_id && $sale_date && $customer_id && $vehicle_id && $amount) {
            $stmt = $pdo->prepare("UPDATE sale SET sale_date = ?, customer_id = ?, vehicle_id = ?, amount = ? WHERE sale_id = ?");
            $stmt->execute([$sale_date, $customer_id, $vehicle_id, $amount, $sale_id]);
            echo "Дані про продаж успішно змінено.";
        } else {
            echo "Помилка: некоректні дані.";
        }
    }
}

$sales = $pdo->query("SELECT 
    sale.sale_id, sale.sale_date, sale.amount, 
    customer.name AS customer_name, 
    CONCAT(vehicle.brand, ' ', vehicle.model) AS vehicle 
FROM sale 
JOIN customer ON sale.customer_id = customer.customer_id 
JOIN vehicle ON sale.vehicle_id = vehicle.vehicle_id")->fetchAll();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Продажі</title>
</head>
<body>
    <h1>Список продажів</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Дата продажу</th>
                <th>Клієнт</th>
                <th>Транспорт</th>
                <th>Сума ($)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sales as $sale): ?>
                <tr>
                    <td><?= $sale['sale_id'] ?></td>
                    <td><?= $sale['sale_date'] ?></td>
                    <td><?= $sale['customer_name'] ?></td>
                    <td><?= $sale['vehicle'] ?></td>
                    <td><?= $sale['amount'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Додати продаж</h2>
    <form method="POST">
        <input type="date" name="sale_date" required>
        <select name="customer_id" required>
            <option value="">Оберіть клієнта</option>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= $customer['customer_id'] ?>"><?= $customer['name'] ?></option>
            <?php endforeach; ?>
        </select>
        <select name="vehicle_id" required>
            <option value="">Оберіть транспорт</option>
            <?php foreach ($vehicles as $vehicle): ?>
                <option value="<?= $vehicle['vehicle_id'] ?>">
                    <?= $vehicle['brand'] ?> <?= $vehicle['model'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="amount" placeholder="Сума ($)" required>
        <button type="submit" name="add">Додати</button>
    </form>

    <h2>Видалити продаж</h2>
    <form method="POST">
        <select name="sale_id" required>
            <option value="">Оберіть ID продажу</option>
            <?php foreach ($sales as $sale): ?>
                <option value="<?= $sale['sale_id'] ?>">
                    <?= $sale['sale_id'] ?>: <?= $sale['customer_name'] ?> — <?= $sale['vehicle'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="delete">Видалити</button>
    </form>

    <h2>Змінити дані про продаж</h2>
    <form method="POST">
        <select name="sale_id" required>
            <option value="">Оберіть ID продажу</option>
            <?php foreach ($sales as $sale): ?>
                <option value="<?= $sale['sale_id'] ?>">
                    <?= $sale['sale_id'] ?>: <?= $sale['customer_name'] ?> — <?= $sale['vehicle'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="date" name="sale_date" required>
        <select name="customer_id" required>
            <option value="">Оберіть клієнта</option>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= $customer['customer_id'] ?>"><?= $customer['name'] ?></option>
            <?php endforeach; ?>
        </select>
        <select name="vehicle_id" required>
            <option value="">Оберіть транспорт</option>
            <?php foreach ($vehicles as $vehicle): ?>
                <option value="<?= $vehicle['vehicle_id'] ?>">
                    <?= $vehicle['brand'] ?> <?= $vehicle['model'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="amount" placeholder="Сума ($)" required>
        <button type="submit" name="update">Змінити</button>
    </form>
</body>
</html>