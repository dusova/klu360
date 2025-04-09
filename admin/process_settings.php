
<?php



require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
require_once '../includes/admin_functions.php';


session_start();


checkUserSession();


if ($_SESSION['user_role'] != 'admin') {
    header("Location: ../admin.php?notification=permission_denied&type=error");
    exit;
}


if (!isset($_POST['csrf_token'])) {
    header("Location: ../admin.php?notification=invalid_request&type=error");
    exit;
}

if (!validateCSRFToken($_POST['csrf_token'])) {
    header("Location: ../admin.php?notification=invalid_token&type=error");
    exit;
}


if (isset($_POST['settings']) && is_array($_POST['settings'])) {
    $settings = $_POST['settings'];
    
    
    foreach ($settings as $key => $value) {
        $key = sanitizeInput($key);
        $value = sanitizeInput($value);
        
        
        $query = "SELECT id FROM settings WHERE `key` = ?";
        $params = [$key];
        $result = executeQuery($conn, $query, $params);
        
        if (!empty($result)) {
            
            $query = "UPDATE settings SET `value` = ?, updated_at = NOW() WHERE `key` = ?";
            $params = [$value, $key];
        } else {
            
            $query = "INSERT INTO settings (`key`, `value`, created_at, updated_at) VALUES (?, ?, NOW(), NOW())";
            $params = [$key, $value];
        }
        
        executeQuery($conn, $query, $params);
    }
    
    
    if (isset($_POST['creators']) && is_array($_POST['creators'])) {
        $creators = [];
        
        foreach ($_POST['creators'] as $creator) {
            if (!empty($creator['name'])) {
                $creators[] = [
                    'name' => sanitizeInput($creator['name']),
                    'title' => sanitizeInput($creator['title']),
                    'photo' => sanitizeInput($creator['photo'])
                ];
            }
        }
        
        $creators_json = json_encode($creators);
        
        
        $query = "SELECT id FROM settings WHERE `key` = 'creators'";
        $result = executeQuery($conn, $query);
        
        if (!empty($result)) {
            $query = "UPDATE settings SET `value` = ?, updated_at = NOW() WHERE `key` = 'creators'";
            $params = [$creators_json];
        } else {
            $query = "INSERT INTO settings (`key`, `value`, created_at, updated_at) VALUES ('creators', ?, NOW(), NOW())";
            $params = [$creators_json];
        }
        
        executeQuery($conn, $query, $params);
    }
    
    header("Location: ../admin.php?page=settings&notification=success&type=success");
} else {
    header("Location: ../admin.php?page=settings&notification=error&type=error");
}

exit;
?>
