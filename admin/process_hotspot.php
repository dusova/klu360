<?php



require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
require_once '../includes/admin_functions.php';


define('DEBUG_MODE', true);


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
    echo "\nGET verileri:\n";
    print_r($_GET);
    echo "</pre>";
}


$action = isset($_POST['action']) ? sanitizeInput($_POST['action']) : (isset($_GET['action']) ? sanitizeInput($_GET['action']) : '');

if ($action == 'add' || $action == 'edit') {
    
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $scene_id = intval($_POST['scene_id']);
    $type = sanitizeInput($_POST['type']);
    $text = sanitizeInput($_POST['text']);
    $pitch = floatval($_POST['pitch']);
    $yaw = floatval($_POST['yaw']);
    $target_pitch = isset($_POST['target_pitch']) && $_POST['target_pitch'] !== '' ? floatval($_POST['target_pitch']) : null;
    $target_yaw = isset($_POST['target_yaw']) && $_POST['target_yaw'] !== '' ? floatval($_POST['target_yaw']) : null;
    
    
    $target_scene_id = '';
    if ($type == 'scene') {
        $target_scene_id = sanitizeInput($_POST['target_scene_id']);
    } elseif ($type == 'link') {
        $target_scene_id = sanitizeInput($_POST['link_url']);
    }
    
    
    if ($scene_id <= 0) {
        if (DEBUG_MODE) {
            echo "<p>Geçersiz sahne ID: $scene_id</p>";
        }
        header("Location: ../admin.php?page=hotspots&notification=invalid_scene&type=error");
        exit;
    }
    
    
    $hotspot_data = [
        'scene_id' => $scene_id,
        'type' => $type,
        'text' => $text,
        'pitch' => $pitch,
        'yaw' => $yaw,
        'target_scene_id' => $target_scene_id,
        'target_pitch' => $target_pitch,
        'target_yaw' => $target_yaw
    ];
    
    if ($action == 'edit') {
        $hotspot_data['id'] = $id;
    }
    
    
    if (DEBUG_MODE) {
        echo "<pre>Hotspot verileri:\n";
        print_r($hotspot_data);
        echo "</pre>";
    }
    
    
    if ($action == 'edit') {
        
        $query = "UPDATE hotspots SET 
                  type = ?, 
                  text = ?, 
                  pitch = ?, 
                  yaw = ?, 
                  target_scene_id = ?, 
                  target_pitch = ?, 
                  target_yaw = ?, 
                  updated_at = NOW() 
                  WHERE id = ?";
        
        $params = [
            $type,
            $text,
            $pitch,
            $yaw,
            $target_scene_id,
            $target_pitch,
            $target_yaw,
            $id
        ];
        
        $result = executeQuery($conn, $query, $params);
        
        if (DEBUG_MODE) {
            echo "<pre>Güncelleme sorgu sonucu:\n";
            print_r($result);
            echo "</pre>";
        }
        
        if ($result['affected_rows'] >= 0) {
            if (DEBUG_MODE) {
                echo "<p>Hotspot başarıyla güncellendi.</p>";
            } else {
                header("Location: ../admin.php?page=hotspots&scene_id=$scene_id&notification=success&type=success");
            }
        } else {
            if (DEBUG_MODE) {
                echo "<p>Hotspot güncellenirken bir hata oluştu.</p>";
            } else {
                header("Location: ../admin.php?page=hotspots&scene_id=$scene_id&notification=error&type=error");
            }
        }
    } else {
        
        $query = "INSERT INTO hotspots (scene_id, type, text, pitch, yaw, target_scene_id, target_pitch, target_yaw, created_at, updated_at) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $params = [
            $scene_id,
            $type,
            $text,
            $pitch,
            $yaw,
            $target_scene_id,
            $target_pitch,
            $target_yaw
        ];
        
        $result = executeQuery($conn, $query, $params);
        
        if (DEBUG_MODE) {
            echo "<pre>Ekleme sorgu sonucu:\n";
            print_r($result);
            echo "</pre>";
        }
        
        if ($result['affected_rows'] > 0) {
            if (DEBUG_MODE) {
                echo "<p>Hotspot başarıyla eklendi.</p>";
            } else {
                header("Location: ../admin.php?page=hotspots&scene_id=$scene_id&notification=success&type=success");
            }
        } else {
            if (DEBUG_MODE) {
                echo "<p>Hotspot eklenirken bir hata oluştu.</p>";
            } else {
                header("Location: ../admin.php?page=hotspots&scene_id=$scene_id&notification=error&type=error");
            }
        }
    }
    
    if (!DEBUG_MODE) {
        exit;
    }
} elseif ($action == 'delete') {
    
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($id > 0) {
        
        $query = "SELECT scene_id FROM hotspots WHERE id = ?";
        $params = [$id];
        $result = executeQuery($conn, $query, $params);
        
        $scene_id = !empty($result) ? $result[0]['scene_id'] : 0;
        
        
        $query = "DELETE FROM hotspots WHERE id = ?";
        $params = [$id];
        $result = executeQuery($conn, $query, $params);
        
        if ($result['affected_rows'] > 0) {
            header("Location: ../admin.php?page=hotspots&scene_id=$scene_id&notification=delete_success&type=success");
        } else {
            header("Location: ../admin.php?page=hotspots&notification=error&type=error");
        }
    } else {
        header("Location: ../admin.php?page=hotspots&notification=invalid_id&type=error");
    }
    
    exit;
} else {
    
    header("Location: ../admin.php?page=hotspots&notification=invalid_action&type=error");
    exit;
}
?>