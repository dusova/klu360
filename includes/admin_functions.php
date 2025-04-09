
<?php

require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';


function saveCampus($conn, $data, $isUpdate = false) {
    
    if (empty($data['slug'])) {
        $data['slug'] = createSlug($data['name']);
    }
    
    if ($isUpdate) {
        $query = "UPDATE campuses SET 
                  name = ?, 
                  slug = ?, 
                  description = ?, 
                  map_image = ?, 
                  status = ?, 
                  updated_at = NOW() 
                  WHERE id = ?";
        
        $params = [
            $data['name'],
            $data['slug'],
            $data['description'],
            $data['map_image'],
            $data['status'],
            $data['id']
        ];
    } else {
        $query = "INSERT INTO campuses (name, slug, description, map_image, status, created_at, updated_at) 
                  VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
        
        $params = [
            $data['name'],
            $data['slug'],
            $data['description'],
            $data['map_image'],
            $data['status']
        ];
    }
    
    $result = executeQuery($conn, $query, $params);
    
    if (($isUpdate && $result['affected_rows'] >= 0) || (!$isUpdate && $result['affected_rows'] > 0)) {
        return [
            'success' => true,
            'id' => $isUpdate ? $data['id'] : $result['insert_id'],
            'message' => $isUpdate ? 'Kampüs başarıyla güncellendi.' : 'Kampüs başarıyla eklendi.'
        ];
    } else {
        return [
            'success' => false,
            'message' => 'İşlem sırasında bir hata oluştu.'
        ];
    }
}

function saveScene($conn, $data, $isUpdate = false) {
    
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        error_log("saveScene çağrıldı: " . ($isUpdate ? "Güncelleme" : "Ekleme"));
        error_log("Veri: " . print_r($data, true));
    }

    
    if (empty($data['scene_id'])) {
        $data['scene_id'] = createSlug($data['title']) . '-' . time();
    }
    
    if ($isUpdate) {
        $query = "UPDATE scenes SET 
                  campus_id = ?, 
                  scene_id = ?, 
                  title = ?, 
                  description = ?, 
                  image_path = ?, 
                  thumbnail_path = ?, 
                  map_x = ?, 
                  map_y = ?, 
                  display_order = ?, 
                  status = ?, 
                  updated_at = NOW() 
                  WHERE id = ?";
        
        $params = [
            $data['campus_id'],
            $data['scene_id'],
            $data['title'],
            $data['description'],
            $data['image_path'],
            $data['thumbnail_path'],
            $data['map_x'],
            $data['map_y'],
            $data['display_order'],
            $data['status'],
            $data['id']
        ];
    } else {
        $query = "INSERT INTO scenes (campus_id, scene_id, title, description, image_path, thumbnail_path, map_x, map_y, display_order, status, created_at, updated_at) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $params = [
            $data['campus_id'],
            $data['scene_id'],
            $data['title'],
            $data['description'],
            $data['image_path'],
            $data['thumbnail_path'],
            $data['map_x'],
            $data['map_y'],
            $data['display_order'],
            $data['status']
        ];
    }
    
    
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        error_log("SQL Sorgusu: " . $query);
        error_log("Parametreler: " . print_r($params, true));
    }
    
    $result = executeQuery($conn, $query, $params);
    
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        error_log("Sorgu sonucu: " . print_r($result, true));
    }
    
    if (($isUpdate && $result['affected_rows'] >= 0) || (!$isUpdate && $result['affected_rows'] > 0)) {
        return [
            'success' => true,
            'id' => $isUpdate ? $data['id'] : $result['insert_id'],
            'message' => $isUpdate ? 'Sahne başarıyla güncellendi.' : 'Sahne başarıyla eklendi.'
        ];
    } else {
        return [
            'success' => false,
            'message' => 'İşlem sırasında bir hata oluştu.'
        ];
    }
}


function saveHotspot($conn, $data, $isUpdate = false) {
    if ($isUpdate) {
        $query = "UPDATE hotspots SET 
                  scene_id = ?, 
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
            $data['scene_id'],
            $data['type'],
            $data['text'],
            $data['pitch'],
            $data['yaw'],
            $data['target_scene_id'],
            $data['target_pitch'],
            $data['target_yaw'],
            $data['id']
        ];
    } else {
        $query = "INSERT INTO hotspots (scene_id, type, text, pitch, yaw, target_scene_id, target_pitch, target_yaw, created_at, updated_at) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $params = [
            $data['scene_id'],
            $data['type'],
            $data['text'],
            $data['pitch'],
            $data['yaw'],
            $data['target_scene_id'],
            $data['target_pitch'],
            $data['target_yaw']
        ];
    }
    
    $result = executeQuery($conn, $query, $params);
    
    if (($isUpdate && $result['affected_rows'] >= 0) || (!$isUpdate && $result['affected_rows'] > 0)) {
        return [
            'success' => true,
            'id' => $isUpdate ? $data['id'] : $result['insert_id'],
            'message' => $isUpdate ? 'Hotspot başarıyla güncellendi.' : 'Hotspot başarıyla eklendi.'
        ];
    } else {
        return [
            'success' => false,
            'message' => 'İşlem sırasında bir hata oluştu.'
        ];
    }
}


function listCampuses($conn, $status = null) {
    $query = "SELECT * FROM campuses";
    $params = [];
    
    if ($status !== null) {
        $query .= " WHERE status = ?";
        $params[] = $status;
    }
    
    $query .= " ORDER BY name ASC";
    
    return executeQuery($conn, $query, $params);
}


function listScenes($conn, $campusId = null, $status = null) {
    $query = "SELECT * FROM scenes";
    $params = [];
    $whereAdded = false;
    
    if ($campusId !== null) {
        $query .= " WHERE campus_id = ?";
        $params[] = $campusId;
        $whereAdded = true;
    }
    
    if ($status !== null) {
        $query .= $whereAdded ? " AND status = ?" : " WHERE status = ?";
        $params[] = $status;
    }
    
    $query .= " ORDER BY display_order ASC";
    
    return executeQuery($conn, $query, $params);
}


function getVisitorStats($conn, $startDate = null, $endDate = null) {
    $query = "SELECT 
              DATE(visit_time) as date, 
              COUNT(*) as total_visits, 
              COUNT(DISTINCT ip_address) as unique_visitors 
              FROM visits";
    
    $params = [];
    $whereAdded = false;
    
    if ($startDate !== null) {
        $query .= " WHERE visit_time >= ?";
        $params[] = $startDate;
        $whereAdded = true;
    }
    
    if ($endDate !== null) {
        $query .= $whereAdded ? " AND visit_time <= ?" : " WHERE visit_time <= ?";
        $params[] = $endDate;
    }
    
    $query .= " GROUP BY DATE(visit_time) ORDER BY date ASC";
    
    return executeQuery($conn, $query, $params);
}


function getPopularScenes($conn, $limit = 10, $startDate = null, $endDate = null) {
    $query = "SELECT 
              v.scene_id, 
              s.title, 
              COUNT(*) as visit_count 
              FROM visits v 
              JOIN scenes s ON v.scene_id = s.scene_id 
              WHERE v.scene_id IS NOT NULL";
    
    $params = [];
    
    if ($startDate !== null) {
        $query .= " AND v.visit_time >= ?";
        $params[] = $startDate;
    }
    
    if ($endDate !== null) {
        $query .= " AND v.visit_time <= ?";
        $params[] = $endDate;
    }
    
    $query .= " GROUP BY v.scene_id, s.title 
               ORDER BY visit_count DESC 
               LIMIT ?";
    
    $params[] = $limit;
    
    return executeQuery($conn, $query, $params);
}


function getDeviceDistribution($conn, $startDate = null, $endDate = null) {
    $query = "SELECT 
              device_type, 
              COUNT(*) as count, 
              (COUNT(*) * 100.0 / (SELECT COUNT(*) FROM visits)) as percentage 
              FROM visits";
    
    $params = [];
    $whereAdded = false;
    
    if ($startDate !== null) {
        $query .= " WHERE visit_time >= ?";
        $params[] = $startDate;
        $whereAdded = true;
    }
    
    if ($endDate !== null) {
        $query .= $whereAdded ? " AND visit_time <= ?" : " WHERE visit_time <= ?";
        $params[] = $endDate;
    }
    
    $query .= " GROUP BY device_type ORDER BY count DESC";
    
    return executeQuery($conn, $query, $params);
}


function getUserActivities($conn, $userId = null, $limit = 100) {
    $query = "SELECT 
              a.*, 
              u.name as user_name, 
              u.email as user_email 
              FROM user_activities a 
              LEFT JOIN users u ON a.user_id = u.id";
    
    $params = [];
    
    if ($userId !== null) {
        $query .= " WHERE a.user_id = ?";
        $params[] = $userId;
    }
    
    $query .= " ORDER BY a.activity_time DESC LIMIT ?";
    $params[] = $limit;
    
    return executeQuery($conn, $query, $params);
}


function listUsers($conn, $role = null, $status = null) {
    $query = "SELECT * FROM users";
    $params = [];
    $whereAdded = false;
    
    if ($role !== null) {
        $query .= " WHERE role = ?";
        $params[] = $role;
        $whereAdded = true;
    }
    
    if ($status !== null) {
        $query .= $whereAdded ? " AND status = ?" : " WHERE status = ?";
        $params[] = $status;
    }
    
    $query .= " ORDER BY name ASC";
    
    return executeQuery($conn, $query, $params);
}


function saveUser($conn, $data, $isUpdate = false) {
    if ($isUpdate) {
        
        if (!empty($data['password'])) {
            $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $query = "UPDATE users SET 
                      name = ?, 
                      email = ?, 
                      password = ?, 
                      role = ?, 
                      status = ?, 
                      updated_at = NOW() 
                      WHERE id = ?";
            
            $params = [
                $data['name'],
                $data['email'],
                $passwordHash,
                $data['role'],
                $data['status'],
                $data['id']
            ];
        } else {
            $query = "UPDATE users SET 
                      name = ?, 
                      email = ?, 
                      role = ?, 
                      status = ?, 
                      updated_at = NOW() 
                      WHERE id = ?";
            
            $params = [
                $data['name'],
                $data['email'],
                $data['role'],
                $data['status'],
                $data['id']
            ];
        }
    } else {
        
        $password = isset($data['password']) && !empty($data['password']) ? 
                    $data['password'] : generateRandomPassword();
        
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO users (name, email, password, role, status, created_at, updated_at) 
                  VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
        
        $params = [
            $data['name'],
            $data['email'],
            $passwordHash,
            $data['role'],
            $data['status']
        ];
    }
    
    $result = executeQuery($conn, $query, $params);
    
    if (($isUpdate && $result['affected_rows'] >= 0) || (!$isUpdate && $result['affected_rows'] > 0)) {
        return [
            'success' => true,
            'id' => $isUpdate ? $data['id'] : $result['insert_id'],
            'password' => isset($password) ? $password : null,
            'message' => $isUpdate ? 'Kullanıcı başarıyla güncellendi.' : 'Kullanıcı başarıyla eklendi.'
        ];
    } else {
        return [
            'success' => false,
            'message' => 'İşlem sırasında bir hata oluştu.'
        ];
    }
}
?>