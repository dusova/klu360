
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


if (!isset($_POST['csrf_token']) && !isset($_GET['csrf_token'])) {
    header("Location: ../admin.php?notification=invalid_request&type=error");
    exit;
}

$token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : $_GET['csrf_token'];
if (!validateCSRFToken($token)) {
    header("Location: ../admin.php?notification=invalid_token&type=error");
    exit;
}


$action = isset($_POST['action']) ? sanitizeInput($_POST['action']) : (isset($_GET['action']) ? sanitizeInput($_GET['action']) : '');

if ($action == 'add' || $action == 'edit') {
    
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $role = sanitizeInput($_POST['role']);
    $status = sanitizeInput($_POST['status']);
    
    
    $user_data = [
        'name' => $name,
        'email' => $email,
        'role' => $role,
        'status' => $status
    ];
    
    
    if (!empty($password)) {
        $user_data['password'] = $password;
    }
    
    if ($action == 'edit') {
        $user_data['id'] = $id;
    }
    
    
    $result = saveUser($conn, $user_data, $action == 'edit');
    
    if ($result['success']) {
        header("Location: ../admin.php?page=users&notification=success&type=success");
    } else {
        header("Location: ../admin.php?page=users&notification=error&type=error");
    }
    
    exit;
} elseif ($action == 'delete') {
    
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    
    if ($id == $_SESSION['user_id']) {
        header("Location: ../admin.php?page=users&notification=cannot_delete_self&type=error");
        exit;
    }
    
    if ($id > 0) {
        
        $query = "DELETE FROM users WHERE id = ?";
        $params = [$id];
        $result = executeQuery($conn, $query, $params);
        
        if ($result['affected_rows'] > 0) {
            header("Location: ../admin.php?page=users&notification=delete_success&type=success");
        } else {
            header("Location: ../admin.php?page=users&notification=error&type=error");
        }
    } else {
        header("Location: ../admin.php?page=users&notification=invalid_id&type=error");
    }
    
    exit;
} else {
    
    header("Location: ../admin.php?page=users&notification=invalid_action&type=error");
    exit;
}
?>
