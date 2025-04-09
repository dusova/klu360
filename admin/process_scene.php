<?php



require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
require_once '../includes/admin_functions.php';


ini_set('display_errors', 1);
error_reporting(E_ALL);


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


if (DEBUG_MODE) {
    echo "<pre>POST verileri:\n";
    print_r($_POST);
    echo "\nFILE verileri:\n";
    print_r($_FILES);
    echo "</pre>";
}


$action = isset($_POST['action']) ? sanitizeInput($_POST['action']) : (isset($_GET['action']) ? sanitizeInput($_GET['action']) : '');

if ($action == 'add' || $action == 'edit') {
    
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $campus_id = intval($_POST['campus_id']);
    $title = sanitizeInput($_POST['title']);
    $scene_id = sanitizeInput($_POST['scene_id']);
    $description = sanitizeInput($_POST['description']);
    $map_x = isset($_POST['map_x']) && $_POST['map_x'] !== '' ? floatval($_POST['map_x']) : null;
    $map_y = isset($_POST['map_y']) && $_POST['map_y'] !== '' ? floatval($_POST['map_y']) : null;
    $display_order = intval($_POST['display_order']);
    $status = sanitizeInput($_POST['status']);
    
    
    if ($campus_id <= 0) {
        header("Location: ../admin.php?page=scenes&notification=invalid_campus&type=error");
        exit;
    }
    
    
    if (empty($scene_id)) {
        $scene_id = createSlug($title) . '-' . time();
    }
    
    
    $image_path = '';
    $thumbnail_path = '';
    
    if ($action == 'edit' && $id > 0) {
        
        $query = "SELECT image_path, thumbnail_path FROM scenes WHERE id = ?";
        $params = [$id];
        $result = executeQuery($conn, $query, $params);
        
        if (!empty($result)) {
            $image_path = $result[0]['image_path'];
            $thumbnail_path = $result[0]['thumbnail_path'];
        }
    }
    
    
    if (DEBUG_MODE) {
        echo "<p>Panorama_image[error]: " . (isset($_FILES['panorama_image']) ? $_FILES['panorama_image']['error'] : 'Dosya yok') . "</p>";
    }
    
    
    if (isset($_FILES['panorama_image']) && $_FILES['panorama_image']['error'] == 0) {
        try {
            
            $panorama_dir = dirname(__DIR__) . '/uploads/panoramas/';
            $thumbnail_dir = dirname(__DIR__) . '/uploads/thumbnails/';
            
            
            if (!file_exists($panorama_dir)) {
                mkdir($panorama_dir, 0755, true);
            }
            if (!file_exists($thumbnail_dir)) {
                mkdir($thumbnail_dir, 0755, true);
            }
            
            
            $upload_result = uploadImage($_FILES['panorama_image'], $panorama_dir, 4000, 2000);
            
            if ($upload_result['success']) {
                $image_path = 'uploads/panoramas/' . $upload_result['file_name'];
                
                
                $thumbnail_result = createThumbnail($upload_result['file_path'], $thumbnail_dir, 300, 150);
                
                if ($thumbnail_result['success']) {
                    $thumbnail_path = 'uploads/thumbnails/' . $thumbnail_result['file_name'];
                } else {
                    
                    $thumbnail_path = $image_path;
                }
            } else {
                if (DEBUG_MODE) {
                    echo "<p>Yükleme hatası: " . $upload_result['message'] . "</p>";
                }
                header("Location: ../admin.php?page=scenes&notification=upload_error&type=error");
                exit;
            }
        } catch (Exception $e) {
            if (DEBUG_MODE) {
                echo "<p>Exception: " . $e->getMessage() . "</p>";
            }
            header("Location: ../admin.php?page=scenes&notification=exception&type=error");
            exit;
        }
    } elseif ($action == 'add' && empty($image_path)) {
        
        if (DEBUG_MODE) {
            echo "<p>Yeni sahne için panorama gerekli</p>";
        }
        header("Location: ../admin.php?page=scenes&notification=no_panorama&type=error");
        exit;
    }
    
    
    $scene_data = [
        'campus_id' => $campus_id,
        'scene_id' => $scene_id,
        'title' => $title,
        'description' => $description,
        'image_path' => $image_path,
        'thumbnail_path' => $thumbnail_path,
        'map_x' => $map_x,
        'map_y' => $map_y,
        'display_order' => $display_order,
        'status' => $status
    ];
    
    if ($action == 'edit') {
        $scene_data['id'] = $id;
    }
    
    
    if (DEBUG_MODE) {
        echo "<pre>Sahne verileri:\n";
        print_r($scene_data);
        echo "</pre>";
    }
    
    
    $result = saveScene($conn, $scene_data, $action == 'edit');
    
    if (DEBUG_MODE) {
        echo "<pre>Sorgu sonucu:\n";
        print_r($result);
        echo "</pre>";
    }
    
    if ($result['success']) {
        if (DEBUG_MODE) {
            echo "<p>İşlem başarılı, yönlendiriliyor...</p>";
            exit;
        }
        header("Location: ../admin.php?page=scenes&campus_id=$campus_id&notification=success&type=success");
    } else {
        if (DEBUG_MODE) {
            echo "<p>İşlem başarısız: " . $result['message'] . "</p>";
            exit;
        }
        header("Location: ../admin.php?page=scenes&campus_id=$campus_id&notification=error&type=error");
    }
    
    exit;
} elseif ($action == 'delete') {
    
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($id > 0) {
        
        $query = "SELECT campus_id FROM scenes WHERE id = ?";
        $params = [$id];
        $result = executeQuery($conn, $query, $params);
        
        $campus_id = !empty($result) ? $result[0]['campus_id'] : 0;
        
        
        $query = "DELETE FROM scenes WHERE id = ?";
        $params = [$id];
        $result = executeQuery($conn, $query, $params);
        
        if ($result['affected_rows'] > 0) {
            header("Location: ../admin.php?page=scenes&campus_id=$campus_id&notification=delete_success&type=success");
        } else {
            header("Location: ../admin.php?page=scenes&notification=error&type=error");
        }
    } else {
        header("Location: ../admin.php?page=scenes&notification=invalid_id&type=error");
    }
    
    exit;
} else {
    
    header("Location: ../admin.php?page=scenes&notification=invalid_action&type=error");
    exit;
}
?>