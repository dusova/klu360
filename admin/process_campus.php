<?php



require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
require_once '../includes/admin_functions.php';


session_start();


checkUserSession();


if ($_SESSION['user_role'] != 'admin' && $_SESSION['user_role'] != 'editor') {
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
    $slug = sanitizeInput($_POST['slug']);
    $description = sanitizeInput($_POST['description']);
    $status = sanitizeInput($_POST['status']);
    
    
    if (empty($slug)) {
        $slug = createSlug($name);
    }
    
    
    $map_image = '';
    if ($action == 'edit' && $id > 0) {
        
        $query = "SELECT map_image FROM campuses WHERE id = ?";
        $params = [$id];
        $result = executeQuery($conn, $query, $params);
        
        if (!empty($result)) {
            $map_image = $result[0]['map_image'];
        }
    }
    
    
    if (isset($_FILES['map_image']) && $_FILES['map_image']['error'] == 0) {
        $upload_result = uploadImage($_FILES['map_image'], MAPS_PATH, 1920, 1080);
        
        if ($upload_result['success']) {
            $map_image = 'uploads/maps/' . $upload_result['file_name'];
        } else {
            header("Location: ../admin.php?page=campuses&notification=upload_error&type=error");
            exit;
        }
    } elseif ($action == 'add' && empty($map_image)) {
        
        header("Location: ../admin.php?page=campuses&notification=no_map_image&type=error");
        exit;
    }
    
    
    $campus_data = [
        'name' => $name,
        'slug' => $slug,
        'description' => $description,
        'map_image' => $map_image,
        'status' => $status
    ];
    
    if ($action == 'edit') {
        $campus_data['id'] = $id;
    }
    
    
    $result = saveCampus($conn, $campus_data, $action == 'edit');
    
    if ($result['success']) {
        header("Location: ../admin.php?page=campuses&notification=success&type=success");
    } else {
        header("Location: ../admin.php?page=campuses&notification=error&type=error");
    }
    
    exit;
} elseif ($action == 'delete') {
    
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($id > 0) {
        
        $query = "DELETE FROM campuses WHERE id = ?";
        $params = [$id];
        $result = executeQuery($conn, $query, $params);
        
        if ($result['affected_rows'] > 0) {
            header("Location: ../admin.php?page=campuses&notification=delete_success&type=success");
        } else {
            header("Location: ../admin.php?page=campuses&notification=error&type=error");
        }
    } else {
        header("Location: ../admin.php?page=campuses&notification=invalid_id&type=error");
    }
    
    exit;
} else {
    
    header("Location: ../admin.php?page=campuses&notification=invalid_action&type=error");
    exit;
}
?>
