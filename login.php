<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: admin.php");
    exit;
}

$csrf_token = generateCSRFToken();

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $error_message = 'Güvenlik doğrulaması başarısız oldu. Lütfen tekrar deneyin.';
    } else {
        $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
        $result = loginUser($conn, $email, $password);
        
        if ($result['success']) {
            header("Location: admin.php?notification=login_success&type=success");
            exit;
        } else {
            $error_message = $result['message'];
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'reset' && isset($_GET['token'])) {
    $token = sanitizeInput($_GET['token']);
    
    $verify_result = verifyPasswordResetToken($conn, $token);
    
    if ($verify_result['success']) {
        $show_reset_form = true;
        $user_id = $verify_result['user_id'];
    } else {
        $error_message = $verify_result['message'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reset_password') {
    $token = sanitizeInput($_POST['token']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password !== $confirm_password) {
        $error_message = 'Şifreler eşleşmiyor.';
    } elseif (strlen($new_password) < 8) {
        $error_message = 'Şifre en az 8 karakter uzunluğunda olmalıdır.';
    } else {
        $reset_result = resetPassword($conn, $token, $new_password);
        
        if ($reset_result['success']) {
            $success_message = 'Şifreniz başarıyla sıfırlandı. Yeni şifrenizle giriş yapabilirsiniz.';
            $show_reset_form = false;
        } else {
            $error_message = $reset_result['message'];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'forgot_password') {
    $email = sanitizeInput($_POST['forgot_email']);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Geçerli bir e-posta adresi giriniz.';
    } else {
        $token_result = generatePasswordResetToken($conn, $email);
        
        if ($token_result['success']) {
            $success_message = 'Şifre sıfırlama bağlantısı e-posta adresinize gönderildi. Lütfen e-postanızı kontrol edin.';
        } else {
            $error_message = $token_result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap | Kırklareli Üniversitesi 360° Sanal Tur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            max-width: 450px;
            width: 100%;
            padding: 15px;
        }
        
        .login-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .login-header {
            background-color: var(--primary);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .login-logo {
            max-width: 150px;
            margin-bottom: 15px;
        }
        
        .login-body {
            padding: 30px;
            background-color: white;
        }
        
        .form-floating {
            margin-bottom: 20px;
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            font-weight: 600;
            margin-top: 10px;
        }
        
        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-to-home {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <?php if (isset($show_reset_form) && $show_reset_form): ?>
        
        <div class="login-card">
            <div class="login-header">
                <img src="assets/images/logo-white.png" alt="Kırklareli Üniversitesi Logo" class="login-logo">
                <h4>Şifre Sıfırlama</h4>
                <p class="mb-0">Yeni şifrenizi belirleyin</p>
            </div>
            
            <div class="login-body">
                <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($success_message)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success_message; ?>
                </div>
                <?php endif; ?>
                
                <?php if (empty($success_message)): ?>
                <form method="post" action="login.php">
                    <input type="hidden" name="action" value="reset_password">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="form-floating">
                        <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Yeni Şifre" required>
                        <label for="new_password">Yeni Şifre</label>
                    </div>
                    
                    <div class="form-floating">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Şifre Tekrar" required>
                        <label for="confirm_password">Şifre Tekrar</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-login">Şifremi Sıfırla</button>
                </form>
                <?php endif; ?>
                
                <div class="back-to-home">
                    <a href="login.php">Giriş sayfasına dön</a>
                </div>
            </div>
        </div>
        <?php else: ?>
        
        <div class="login-card">
            <div class="login-header">
                <img src="assets/images/logo-white.png" alt="Kırklareli Üniversitesi Logo" class="login-logo">
                <h4>Kırklareli Üniversitesi</h4>
                <p class="mb-0">360° Sanal Tur Yönetim Paneli</p>
            </div>
            
            <div class="login-body">
                <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($success_message)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success_message; ?>
                </div>
                <?php endif; ?>
                
                <form method="post" action="login.php" id="loginForm">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="form-floating">
                        <input type="email" class="form-control" id="email" name="email" placeholder="E-posta" required>
                        <label for="email">E-posta</label>
                    </div>
                    
                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Şifre" required>
                        <label for="password">Şifre</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-login">Giriş Yap</button>
                </form>
                
                <div class="forgot-password">
                    <a href="#" id="forgotPasswordLink">Şifremi unuttum</a>
                </div>
                
                <div class="back-to-home mt-3">
                    <a href="index.php">Ana Sayfaya Dön</a>
                </div>
            </div>
        </div>
        
        
        <div class="login-card mt-4" id="forgotPasswordForm" style="display: none;">
            <div class="login-header">
                <h4>Şifremi Unuttum</h4>
                <p class="mb-0">Şifre sıfırlama bağlantısı için e-posta adresinizi girin</p>
            </div>
            
            <div class="login-body">
                <form method="post" action="login.php">
                    <input type="hidden" name="action" value="forgot_password">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <div class="form-floating">
                        <input type="email" class="form-control" id="forgot_email" name="forgot_email" placeholder="E-posta" required>
                        <label for="forgot_email">E-posta</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-login">Şifre Sıfırlama Bağlantısı Gönder</button>
                </form>
                
                <div class="back-to-home">
                    <a href="#" id="backToLoginLink">Giriş formuna dön</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const forgotPasswordForm = document.getElementById('forgotPasswordForm');
            const forgotPasswordLink = document.getElementById('forgotPasswordLink');
            const backToLoginLink = document.getElementById('backToLoginLink');
            
            if (forgotPasswordLink) {
                forgotPasswordLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    loginForm.parentElement.parentElement.style.display = 'none';
                    forgotPasswordForm.style.display = 'block';
                });
            }
            
            if (backToLoginLink) {
                backToLoginLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    forgotPasswordForm.style.display = 'none';
                    loginForm.parentElement.parentElement.style.display = 'block';
                });
            }
        });
    </script>
</body>
</html>