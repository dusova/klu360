
<?php
require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';


function registerUser($conn, $name, $email, $password, $role = 'user') {
    $query = "SELECT id FROM users WHERE email = ?";
    $params = [$email];
    $result = executeQuery($conn, $query, $params);
    
    if (!empty($result)) {
        return [
            'success' => false,
            'message' => 'Bu e-posta adresi zaten kullanımda.'
        ];
    }
    
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    $query = "INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())";
    $params = [$name, $email, $passwordHash, $role];
    
    $result = executeQuery($conn, $query, $params);
    
    if ($result['affected_rows'] > 0) {
        return [
            'success' => true,
            'user_id' => $result['insert_id'],
            'message' => 'Kullanıcı başarıyla kaydedildi.'
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Kullanıcı kaydı sırasında bir hata oluştu.'
        ];
    }
}

function loginUser($conn, $email, $password) {
    $query = "SELECT * FROM users WHERE email = ?";
    $params = [$email];
    $result = executeQuery($conn, $query, $params);
    
    if (empty($result)) {
        return [
            'success' => false,
            'message' => 'Hatalı e-posta adresi veya şifre.'
        ];
    }
    
    $user = $result[0];
    
    if (!password_verify($password, $user['password'])) {
        return [
            'success' => false,
            'message' => 'Hatalı e-posta adresi veya şifre.'
        ];
    }
    
    if ($user['status'] != 'active') {
        return [
            'success' => false,
            'message' => 'Hesabınız aktif değil.'
        ];
    }
    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['last_activity'] = time();
    
    $query = "UPDATE users SET last_login = NOW() WHERE id = ?";
    $params = [$user['id']];
    executeQuery($conn, $query, $params);
    
    return [
        'success' => true,
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ],
        'message' => 'Giriş başarılı.'
    ];
}

function generatePasswordResetToken($conn, $email) {
    $query = "SELECT id FROM users WHERE email = ?";
    $params = [$email];
    $result = executeQuery($conn, $query, $params);
    
    if (empty($result)) {
        return [
            'success' => false,
            'message' => 'Bu e-posta adresiyle kayıtlı bir kullanıcı bulunamadı.'
        ];
    }
    
    $userId = $result[0]['id'];
    
    $token = bin2hex(random_bytes(32));
    $expiryTime = date('Y-m-d H:i:s', time() + 3600); 
    
    $query = "UPDATE password_resets SET used = 1 WHERE user_id = ?";
    $params = [$userId];
    executeQuery($conn, $query, $params);
    
    $query = "INSERT INTO password_resets (user_id, token, expiry_time, created_at) VALUES (?, ?, ?, NOW())";
    $params = [$userId, $token, $expiryTime];
    $result = executeQuery($conn, $query, $params);
    
    if ($result['affected_rows'] > 0) {
        return [
            'success' => true,
            'token' => $token,
            'message' => 'Şifre sıfırlama token\'ı oluşturuldu.'
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Token oluşturma sırasında bir hata oluştu.'
        ];
    }
}

function verifyPasswordResetToken($conn, $token) {
    $query = "SELECT * FROM password_resets WHERE token = ? AND used = 0 AND expiry_time > NOW()";
    $params = [$token];
    $result = executeQuery($conn, $query, $params);
    
    if (empty($result)) {
        return [
            'success' => false,
            'message' => 'Geçersiz veya süresi dolmuş token.'
        ];
    }
    
    return [
        'success' => true,
        'user_id' => $result[0]['user_id'],
        'message' => 'Token doğrulandı.'
    ];
}

function resetPassword($conn, $token, $newPassword) {
    $verifyResult = verifyPasswordResetToken($conn, $token);
    
    if (!$verifyResult['success']) {
        return $verifyResult;
    }
    
    $userId = $verifyResult['user_id'];
    
    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $query = "UPDATE users SET password = ? WHERE id = ?";
    $params = [$passwordHash, $userId];
    $result = executeQuery($conn, $query, $params);
    
    if ($result['affected_rows'] > 0) {
        $query = "UPDATE password_resets SET used = 1 WHERE token = ?";
        $params = [$token];
        executeQuery($conn, $query, $params);
        
        return [
            'success' => true,
            'message' => 'Şifre başarıyla sıfırlandı.'
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Şifre sıfırlama sırasında bir hata oluştu.'
        ];
    }
}

function logoutUser() {
    session_unset();
    session_destroy();
    
    header("Location: login.php");
    exit;
}
?>
