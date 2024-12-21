<?php
require 'db.php';

function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

$vehicles = $pdo->query("SELECT vehicle_id, CONCAT(brand, ' ', model) AS vehicle FROM vehicle")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $service_date = sanitizeInput($_POST['service_date']);
        $vehicle_id = (int)$_POST['vehicle_id'];

        if (validateDate($service_date) && $vehicle_id > 0) {
            $stmt = $pdo->prepare("INSERT INTO service (service_date, vehicle_id) VALUES (?, ?)");
            $stmt->execute([$service_date, $vehicle_id]);
            echo "Запис про Т/О успішно додано.";
        } else {
            echo "Невірні дані для додавання.";
        }
    }

    if (isset($_POST['delete'])) {
        $service_id = (int)$_POST['service_id'];

        if ($service_id > 0) {
            $stmt = $pdo->prepare("DELETE FROM service WHERE service_id = ?");
            $stmt->execute([$service_id]);
            echo "Запис про Т/О видалено.";
        } else {
            echo "Невірні дані для видалення.";
        }
    }

    if (isset($_POST['update'])) {
        $service_id = (int)$_POST['service_id'];
        $service_date = sanitizeInput($_POST['service_date']);
        $vehicle_id = (int)$_POST['vehicle_id'];

        if ($service_id > 0 && validateDate($service_date) && $vehicle_id > 0) {
            $stmt = $pdo->prepare("UPDATE service SET service_date = ?, vehicle_id = ? WHERE service_id = ?");
            $stmt->execute([$service_date, $vehicle_id, $service_id]);
            echo "Запис про Т/О оновлено.";
        } else {
            echo "Невірні дані для оновлення.";
        }
    }
}

$services = $pdo->query("SELECT 
    service.service_id, service.service_date, 
    CONCAT(vehicle.brand, ' ', vehicle.model) AS vehicle 
FROM service 
JOIN vehicle ON service.vehicle_id = vehicle.vehicle_id")->fetchAll();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Технічне обслуговування</title>
</head>
<body>
    <h1>Список сервісів</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Дата обслуговування</th>
                <th>Транспорт</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $service): ?>
                <tr>
                    <td><?= htmlspecialchars($service['service_id']) ?></td>
                    <td><?= htmlspecialchars($service['service_date']) ?></td>
                    <td><?= htmlspecialchars($service['vehicle']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Додати сервіс</h2>
    <form method="POST">
        <input type="date" name="service_date" required>
        <select name="vehicle_id" required>
            <option value="">Оберіть транспорт</option>
            <?php foreach ($vehicles as $vehicle): ?>
                <option value="<?= htmlspecialchars($vehicle['vehicle_id']) ?>"><?= htmlspecialchars($vehicle['vehicle']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="add">Додати</button>
    </form>

    <h2>Видалити сервіс</h2>
    <form method="POST">
        <select name="service_id" required>
            <option value="">Оберіть ID запису про Т/О</option>
            <?php foreach ($services as $service): ?>
                <option value="<?= htmlspecialchars($service['service_id']) ?>">
                    <?= htmlspecialchars($service['service_id']) ?>: <?= htmlspecialchars($service['vehicle']) ?> (<?= htmlspecialchars($service['service_date']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="delete">Видалити</button>
    </form>

    <h2>Змінити дані про Т/О</h2>
    <form method="POST">
        <select name="service_id" required>
            <option value="">Оберіть ID Т/О</option>
            <?php foreach ($services as $service): ?>
                <option value="<?= htmlspecialchars($service['service_id']) ?>">
                    <?= htmlspecialchars($service['service_id']) ?>: <?= htmlspecialchars($service['vehicle']) ?> (<?= htmlspecialchars($service['service_date']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <input type="date" name="service_date" required>
        <select name="vehicle_id" required>
            <option value="">Оберіть транспорт</option>
            <?php foreach ($vehicles as $vehicle): ?>
                <option value="<?= htmlspecialchars($vehicle['vehicle_id']) ?>"><?= htmlspecialchars($vehicle['vehicle']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="update">Змінити</button>
    </form>
</body>
</html>