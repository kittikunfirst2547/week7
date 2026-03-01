<?php
$host = 'kittikun-db';
$dbname = getenv('MYSQL_DATABASE') ?: 'eventdb';
$user = 'appuser';
$pass = file_get_contents('/run/secrets/db_pass') ?: '';
$pass = trim($pass);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("SELECT * FROM students");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $students = [];
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration System</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f4f8; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; }
        h1 { color: #2d3748; text-align: center; margin-bottom: 8px; }
        .subtitle { text-align: center; color: #718096; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        th { background: #4299e1; color: white; padding: 14px 16px; text-align: left; }
        td { padding: 12px 16px; border-bottom: 1px solid #e2e8f0; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #ebf8ff; }
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .Submitted { background: #c6f6d5; color: #276749; }
        .Pending { background: #fefcbf; color: #744210; }
        .In-Progress { background: #bee3f8; color: #2a4365; }
        .error { background: #fed7d7; color: #c53030; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="container">
    <h1>🎓 Event Registration System</h1>
    <p class="subtitle">IT464 - Lab07 & Assignment07 | kittikun</p>

    <?php if (isset($error)): ?>
        <div class="error">❌ Database Error: <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Student ID</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Submitted At</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($students)): ?>
                <tr><td colspan="7" style="text-align:center; color:#718096;">No data found</td></tr>
            <?php else: ?>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td><?= $s['id'] ?></td>
                    <td><?= htmlspecialchars($s['student_id']) ?></td>
                    <td><?= htmlspecialchars($s['full_name']) ?></td>
                    <td><?= htmlspecialchars($s['username']) ?></td>
                    <td><?= htmlspecialchars($s['email']) ?></td>
                    <td><span class="badge <?= str_replace(' ', '-', $s['status']) ?>"><?= $s['status'] ?></span></td>
                    <td><?= $s['submitted_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
