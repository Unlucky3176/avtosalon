<?php
require 'db.php';

function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function validatePositiveNumber($number) {
    return filter_var($number, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
}

$vehicles = $pdo->query("SELECT * FROM vehicle")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $brand = sanitizeInput($_POST['brand']);
        $model = sanitizeInput($_POST['model']);
        $engine_type = sanitizeInput($_POST['engine_type']);
        $engine_capacity = (int)$_POST['engine_capacity'];

        if (!empty($brand) && !empty($model) && !empty($engine_type) && validatePositiveNumber($engine_capacity)) {
            $stmt = $pdo->prepare("INSERT INTO vehicle (brand, model, engine_type, engine_capacity) VALUES (?, ?, ?, ?)");
            $stmt->execute([$brand, $model, $engine_type, $engine_capacity]);
            echo "Транспортний засіб успішно додано.";
        } else {
            echo "Невірні дані для додавання.";
        }
    }

    if (isset($_POST['delete'])) {
        $id = (int)$_POST['vehicle_id'];

        if (validatePositiveNumber($id)) {
            $stmt = $pdo->prepare("DELETE FROM vehicle WHERE vehicle_id = ?");
            $stmt->execute([$id]);
            echo "Транспортний засіб успішно видалено.";
        } else {
            echo "Невірні дані для видалення.";
        }
    }

    if (isset($_POST['update'])) {
        $id = (int)$_POST['vehicle_id'];
        $brand = sanitizeInput($_POST['brand']);
        $model = sanitizeInput($_POST['model']);
        $engine_type = sanitizeInput($_POST['engine_type']);
        $engine_capacity = (int)$_POST['engine_capacity'];

        if (validatePositiveNumber($id) && !empty($brand) && !empty($model) && !empty($engine_type) && validatePositiveNumber($engine_capacity)) {
            $stmt = $pdo->prepare("UPDATE vehicle SET brand = ?, model = ?, engine_type = ?, engine_capacity = ? WHERE vehicle_id = ?");
            $stmt->execute([$brand, $model, $engine_type, $engine_capacity, $id]);
            echo "Дані транспортного засобу успішно змінено.";
        } else {
            echo "Невірні дані для оновлення.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Транспортні засоби</title>
</head>
<body>
    <h1>Список транспортних засобів</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Бренд</th>
                <th>Модель</th>
                <th>Тип двигуна</th>
                <th>Об'єм двигуна (куб. см)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vehicles as $vehicle): ?>
                <tr>
                    <td><?= htmlspecialchars($vehicle['vehicle_id']) ?></td>
                    <td><?= htmlspecialchars($vehicle['brand']) ?></td>
                    <td><?= htmlspecialchars($vehicle['model']) ?></td>
                    <td><?= htmlspecialchars($vehicle['engine_type']) ?></td>
                    <td><?= htmlspecialchars($vehicle['engine_capacity']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Додати транспортний засіб</h2>
    <form method="POST">
        <input type="text" name="brand" placeholder="Бренд" required>
        <input type="text" name="model" placeholder="Модель" required>
        <input type="text" name="engine_type" placeholder="Тип двигуна" required>
        <input type="number" name="engine_capacity" placeholder="Об'єм двигуна" required>
        <button type="submit" name="add">Додати</button>
    </form>

    <h2>Видалити транспортний засіб</h2>
    <form method="POST">
        <select name="vehicle_id" required>
            <option value="">Оберіть ID</option>
            <?php foreach ($vehicles as $vehicle): ?>
                <option value="<?= htmlspecialchars($vehicle['vehicle_id']) ?>">
                    <?= htmlspecialchars($vehicle['vehicle_id']) ?>: <?= htmlspecialchars($vehicle['brand']) ?> <?= htmlspecialchars($vehicle['model']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="delete">Видалити</button>
    </form>

    <h2>Змінити дані транспортного засобу</h2>
    <form method="POST">
        <select name="vehicle_id" required>
            <option value="">Оберіть ID</option>
            <?php foreach ($vehicles as $vehicle): ?>
                <option value="<?= htmlspecialchars($vehicle['vehicle_id']) ?>">
                    <?= htmlspecialchars($vehicle['vehicle_id']) ?>: <?= htmlspecialchars($vehicle['brand']) ?> <?= htmlspecialchars($vehicle['model']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="brand" placeholder="Новий бренд" required>
        <input type="text" name="model" placeholder="Нова модель" required>
        <input type="text" name="engine_type" placeholder="Новий тип двигуна" required>
        <input type="number" name="engine_capacity" placeholder="Новий об'єм двигуна" required>
        <button type="submit" name="update">Змінити</button>
    </form>
</body>
</html>