<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

define('DEBUG_MODE', true);

ob_start();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Kullanıcısı Oluştur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Admin Kullanıcısı Oluştur</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $name = isset($_POST['name']) ? sanitizeInput($_POST['name']) : '';
                            $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
                            $password = isset($_POST['password']) ? $_POST['password'] : '';
                            $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
                            
                            $errors = [];
                            
                            if (empty($name)) {
                                $errors[] = "Ad Soyad alanı gereklidir.";
                            }
                            
                            if (empty($email)) {
                                $errors[] = "E-posta alanı gereklidir.";
                            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $errors[] = "Geçerli bir e-posta adresi giriniz.";
                            }
                            
                            if (empty($password)) {
                                $errors[] = "Şifre alanı gereklidir.";
                            } elseif (strlen($password) < 8) {
                                $errors[] = "Şifre en az 8 karakter uzunluğunda olmalıdır.";
                            }
                            
                            if ($password !== $confirm_password) {
                                $errors[] = "Şifreler eşleşmiyor.";
                            }
                            
                            if (empty($errors)) {
                                try {
                                    $conn = connectDatabase();
                                    
                                    $query = "SELECT id FROM users WHERE email = ?";
                                    $params = [$email];
                                    $result = executeQuery($conn, $query, $params);
                                    
                                    if (!empty($result)) {
                                        $user_id = $result[0]['id'];
                                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                                        
                                        $query = "UPDATE users SET 
                                                  name = ?, 
                                                  password = ?, 
                                                  role = 'admin', 
                                                  status = 'active', 
                                                  updated_at = NOW() 
                                                  WHERE id = ?";
                                        $params = [$name, $passwordHash, $user_id];
                                        
                                        $result = executeQuery($conn, $query, $params);
                                        
                                        echo '<div class="alert alert-success">
                                              <strong>Başarılı!</strong> Kullanıcı bilgileri güncellendi.
                                              <br>Bu dosyayı sunucunuzdan silmeyi unutmayın!
                                              <br><a href="login.php" class="btn btn-primary mt-3">Giriş Yap</a>
                                              </div>';
                                    } else {
                                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                                        
                                        $query = "INSERT INTO users (name, email, password, role, status, created_at, updated_at) 
                                                 VALUES (?, ?, ?, 'admin', 'active', NOW(), NOW())";
                                        $params = [$name, $email, $passwordHash];
                                        
                                        $result = executeQuery($conn, $query, $params);
                                        
                                        echo '<div class="alert alert-success">
                                              <strong>Başarılı!</strong> Admin kullanıcısı oluşturuldu.
                                              <br>Bu dosyayı sunucunuzdan silmeyi unutmayın!
                                              <br><a href="login.php" class="btn btn-primary mt-3">Giriş Yap</a>
                                              </div>';
                                    }
                                    
                                    closeDatabase($conn);
                                } catch (Exception $e) {
                                    echo '<div class="alert alert-danger">
                                          <strong>Hata!</strong> ' . $e->getMessage() . '
                                          </div>';
                                }
                            } else {
                                echo '<div class="alert alert-danger">';
                                echo '<strong>Hata!</strong> Lütfen aşağıdaki hataları düzeltin:';
                                echo '<ul class="mb-0 mt-2">';
                                foreach ($errors as $error) {
                                    echo '<li>' . $error . '</li>';
                                }
                                echo '</ul>';
                                echo '</div>';
                            }
                        }
                        ?>
                        
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="name" class="form-label">Ad Soyad</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($name) ? $name : 'Admin'; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">E-posta</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? $email : 'admin@kirklareli.edu.tr'; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Şifre</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">Şifre en az 8 karakter uzunluğunda olmalıdır.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Şifre Tekrar</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Admin Kullanıcısı Oluştur</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="alert alert-warning mb-0">
                            <strong>Uyarı!</strong> Bu dosya, sadece ilk kurulum için kullanılmalıdır. Kullanımdan sonra güvenlik nedeniyle sunucunuzdan siliniz.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
ob_end_flush();
?>