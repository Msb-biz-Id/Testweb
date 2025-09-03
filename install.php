<?php
/**
 * Installation Script for Testweb Jersey
 * Run this script to set up the database and initial configuration
 */

// Check if already installed
if (file_exists('.installed')) {
    die('Application is already installed. Delete .installed file to reinstall.');
}

// Database configuration
$dbConfig = [
    'host' => 'localhost',
    'name' => 'testweb_jersey',
    'user' => 'root',
    'pass' => ''
];

// Get database credentials from user input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbConfig['host'] = $_POST['db_host'] ?? 'localhost';
    $dbConfig['name'] = $_POST['db_name'] ?? 'testweb_jersey';
    $dbConfig['user'] = $_POST['db_user'] ?? 'root';
    $dbConfig['pass'] = $_POST['db_pass'] ?? '';
    
    $adminUsername = $_POST['admin_username'] ?? 'admin';
    $adminEmail = $_POST['admin_email'] ?? 'admin@testweb.com';
    $adminPassword = $_POST['admin_password'] ?? 'admin123';
    $adminName = $_POST['admin_name'] ?? 'Administrator';
    
    try {
        // Test database connection
        $pdo = new PDO(
            "mysql:host={$dbConfig['host']};charset=utf8mb4",
            $dbConfig['user'],
            $dbConfig['pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        // Create database if not exists
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbConfig['name']}`");
        $pdo->exec("USE `{$dbConfig['name']}`");
        
        // Read and execute schema
        $schema = file_get_contents('database/schema.sql');
        $statements = explode(';', $schema);
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                $pdo->exec($statement);
            }
        }
        
        // Update admin user
        $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ?, full_name = ? WHERE id = 1");
        $stmt->execute([$adminUsername, $adminEmail, $hashedPassword, $adminName]);
        
        // Create .env file
        $envContent = "DB_HOST={$dbConfig['host']}\n";
        $envContent .= "DB_NAME={$dbConfig['name']}\n";
        $envContent .= "DB_USER={$dbConfig['user']}\n";
        $envContent .= "DB_PASS={$dbConfig['pass']}\n";
        $envContent .= "APP_DEBUG=true\n";
        $envContent .= "APP_URL=http://localhost\n";
        
        file_put_contents('.env', $envContent);
        
        // Create .installed file
        file_put_contents('.installed', date('Y-m-d H:i:s'));
        
        $success = true;
        $message = 'Installation completed successfully!';
        
    } catch (Exception $e) {
        $error = 'Installation failed: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install Testweb Jersey</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow mt-5">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-tshirt me-2"></i>
                            Install Testweb Jersey
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($success) && $success): ?>
                            <div class="alert alert-success">
                                <h5>Installation Successful!</h5>
                                <p><?= htmlspecialchars($message) ?></p>
                                <hr>
                                <p><strong>Admin Login Details:</strong></p>
                                <ul>
                                    <li>Username: <?= htmlspecialchars($adminUsername) ?></li>
                                    <li>Email: <?= htmlspecialchars($adminEmail) ?></li>
                                    <li>Password: <?= htmlspecialchars($adminPassword) ?></li>
                                </ul>
                                <div class="mt-3">
                                    <a href="/admin/login" class="btn btn-primary">Go to Admin Panel</a>
                                    <a href="/" class="btn btn-outline-primary">View Website</a>
                                </div>
                            </div>
                        <?php elseif (isset($error)): ?>
                            <div class="alert alert-danger">
                                <h5>Installation Failed</h5>
                                <p><?= htmlspecialchars($error) ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!isset($success) || !$success): ?>
                            <form method="POST">
                                <h5>Database Configuration</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="db_host" class="form-label">Database Host</label>
                                            <input type="text" class="form-control" id="db_host" name="db_host" 
                                                   value="<?= htmlspecialchars($dbConfig['host']) ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="db_name" class="form-label">Database Name</label>
                                            <input type="text" class="form-control" id="db_name" name="db_name" 
                                                   value="<?= htmlspecialchars($dbConfig['name']) ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="db_user" class="form-label">Database User</label>
                                            <input type="text" class="form-control" id="db_user" name="db_user" 
                                                   value="<?= htmlspecialchars($dbConfig['user']) ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="db_pass" class="form-label">Database Password</label>
                                            <input type="password" class="form-control" id="db_pass" name="db_pass" 
                                                   value="<?= htmlspecialchars($dbConfig['pass']) ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <h5>Admin Account</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="admin_username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="admin_username" name="admin_username" 
                                                   value="admin" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="admin_email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="admin_email" name="admin_email" 
                                                   value="admin@testweb.com" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="admin_name" class="form-label">Full Name</label>
                                            <input type="text" class="form-control" id="admin_name" name="admin_name" 
                                                   value="Administrator" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="admin_password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="admin_password" name="admin_password" 
                                                   value="admin123" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-download me-2"></i>
                                        Install Application
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <small class="text-muted">
                        Testweb Jersey v1.0.0 - PHP MVC Framework
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>