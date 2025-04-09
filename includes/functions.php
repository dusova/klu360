
<?php
require_once 'config.php';
require_once 'db.php';

function getVisitorIP() {
    $ip = '';
    
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    return $ip;
}

function logVisit($conn, $ip, $user_agent, $campus, $scene_id = null) {
    $device_type = getDeviceType($user_agent);
    $browser = getBrowser($user_agent);
    $operating_system = getOperatingSystem($user_agent);
    $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    
    $query = "INSERT INTO visits (ip_address, device_type, browser, operating_system, referrer, campus, scene_id, visit_time) 
              VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $params = [$ip, $device_type, $browser, $operating_system, $referrer, $campus, $scene_id];
    
    executeQuery($conn, $query, $params);
}


function logActivity($conn, $user_id, $activity_type, $details, $campus, $scene_id = null) {
    $ip = getVisitorIP();
    
    $query = "INSERT INTO user_activities (user_id, ip_address, activity_type, details, campus, scene_id, activity_time) 
              VALUES (?, ?, ?, ?, ?, ?, NOW())";
    
    $params = [$user_id, $ip, $activity_type, $details, $campus, $scene_id];
    
    executeQuery($conn, $query, $params);
}


function getDeviceType($user_agent) {
    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $user_agent)) {
        return 'tablet';
    }
    
    if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $user_agent)) {
        return 'mobile';
    }
    
    return 'desktop';
}


function getBrowser($user_agent) {
    if (preg_match('/MSIE/i', $user_agent) || preg_match('/Trident/i', $user_agent)) {
        return 'Internet Explorer';
    } elseif (preg_match('/Firefox/i', $user_agent)) {
        return 'Firefox';
    } elseif (preg_match('/Chrome/i', $user_agent)) {
        if (preg_match('/Edge/i', $user_agent)) {
            return 'Edge';
        }
        if (preg_match('/Edg/i', $user_agent)) {
            return 'Edge';
        }
        if (preg_match('/OPR/i', $user_agent)) {
            return 'Opera';
        }
        return 'Chrome';
    } elseif (preg_match('/Safari/i', $user_agent)) {
        return 'Safari';
    } elseif (preg_match('/Opera/i', $user_agent)) {
        return 'Opera';
    }
    
    return 'Unknown';
}


function getOperatingSystem($user_agent) {
    if (preg_match('/windows/i', $user_agent)) {
        return 'Windows';
    } elseif (preg_match('/mac/i', $user_agent)) {
        return 'Mac';
    } elseif (preg_match('/linux/i', $user_agent)) {
        return 'Linux';
    } elseif (preg_match('/android/i', $user_agent)) {
        return 'Android';
    } elseif (preg_match('/iphone/i', $user_agent)) {
        return 'iOS';
    }
    
    return 'Unknown';
}


function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}


function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    
    return true;
}


function sanitizeInput($input) {
    if (is_array($input)) {
        foreach ($input as $key => $value) {
            $input[$key] = sanitizeInput($value);
        }
    } else {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
    
    return $input;
}


function createSlug($text) {
    $text = str_replace(
        ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'],
        ['i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c'],
        $text
    );
    
    $text = strtolower($text);
    
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    
    $text = preg_replace('/[\s-]+/', '-', $text);
    
    $text = trim($text, '-');
    
    return $text;
}


function generateRandomPassword($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
    $password = '';
    
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[rand(0, strlen($chars) - 1)];
    }
    
    return $password;
}


function getCampusData($conn, $campus_slug) {
    $query = "SELECT * FROM campuses WHERE slug = ?";
    $params = [$campus_slug];
    
    $result = executeQuery($conn, $query, $params);
    
    return !empty($result) ? $result[0] : null;
}


function getPanoramaScenes($conn, $campus_id) {
    $query = "SELECT * FROM scenes WHERE campus_id = ? ORDER BY display_order ASC";
    $params = [$campus_id];
    
    return executeQuery($conn, $query, $params);
}


function getHotspots($conn, $scene_id) {
    $query = "SELECT * FROM hotspots WHERE scene_id = ?";
    $params = [$scene_id];
    
    $result = executeQuery($conn, $query, $params);
    $hotspots = [];
    
    foreach ($result as $row) {
        $hotspot = [
            'id' => 'hotspot-' . $row['id'],
            'pitch' => (float)$row['pitch'],
            'yaw' => (float)$row['yaw'],
            'type' => $row['type'],
            'text' => $row['text'],
            'cssClass' => 'custom-hotspot'
        ];
        
        if ($row['type'] == 'scene') {
            $hotspot['sceneId'] = $row['target_scene_id'];
            
            if ($row['target_pitch'] !== null && $row['target_yaw'] !== null) {
                $hotspot['targetPitch'] = (float)$row['target_pitch'];
                $hotspot['targetYaw'] = (float)$row['target_yaw'];
            }
        } elseif ($row['type'] == 'info') {
        } elseif ($row['type'] == 'link') {
            $hotspot['URL'] = $row['target_scene_id']; 
        }
        
        $hotspots[] = $hotspot;
    }
    
    return $hotspots;
}


function checkUserSession() {
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
    
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_LIFETIME)) {
        session_unset();
        session_destroy();
        header("Location: login.php?timeout=1");
        exit;
    }
    
    $_SESSION['last_activity'] = time();
}


function checkUserRole($requiredRole) {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $requiredRole) {
        header("Location: dashboard.php?error=permission");
        exit;
    }
}


function getVirtualTourStats($conn) {
    $query = "SELECT COUNT(*) as total_visits FROM visits";
    $totalVisits = executeQuery($conn, $query)[0]['total_visits'];
    
    $query = "SELECT COUNT(DISTINCT ip_address) as unique_visitors FROM visits";
    $uniqueVisitors = executeQuery($conn, $query)[0]['unique_visitors'];
    
    $query = "SELECT campus, COUNT(*) as visit_count 
              FROM visits 
              GROUP BY campus 
              ORDER BY visit_count DESC 
              LIMIT 1";
    $popularCampus = executeQuery($conn, $query);
    $popularCampus = !empty($popularCampus) ? $popularCampus[0]['campus'] : '';
    
    $query = "SELECT scene_id, COUNT(*) as visit_count 
              FROM visits 
              WHERE scene_id IS NOT NULL 
              GROUP BY scene_id 
              ORDER BY visit_count DESC 
              LIMIT 1";
    $popularScene = executeQuery($conn, $query);
    
    if (!empty($popularScene)) {
        $sceneId = $popularScene[0]['scene_id'];
        $query = "SELECT title FROM scenes WHERE scene_id = ?";
        $params = [$sceneId];
        $sceneInfo = executeQuery($conn, $query, $params);
        $popularScene = !empty($sceneInfo) ? $sceneInfo[0]['title'] : '';
    } else {
        $popularScene = '';
    }
    
    $query = "SELECT device_type, COUNT(*) as count 
              FROM visits 
              GROUP BY device_type";
    $deviceDistribution = executeQuery($conn, $query);
    
    $query = "SELECT DATE(visit_time) as date, COUNT(*) as count 
              FROM visits 
              WHERE visit_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
              GROUP BY DATE(visit_time) 
              ORDER BY date ASC";
    $lastWeekVisits = executeQuery($conn, $query);
    
    return [
        'total_visits' => $totalVisits,
        'unique_visitors' => $uniqueVisitors,
        'popular_campus' => $popularCampus,
        'popular_scene' => $popularScene,
        'device_distribution' => $deviceDistribution,
        'last_week_visits' => $lastWeekVisits
    ];
}

function uploadImage($file, $targetDir, $maxWidth = 1920, $maxHeight = 1080) {
    $fileName = basename($file['name']);
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    $newFileName = uniqid() . '.' . $fileType;
    $targetFilePath = $targetDir . $newFileName;
    
    $allowedTypes = ['jpg', 'jpeg', 'png'];
    if (!in_array($fileType, $allowedTypes)) {
        return [
            'success' => false,
            'message' => 'Sadece JPG, JPEG ve PNG dosyaları kabul edilir.'
        ];
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return [
            'success' => false,
            'message' => 'Dosya boyutu çok büyük. Maksimum ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB.'
        ];
    }
    
    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        return [
            'success' => true,
            'file_name' => $newFileName,
            'file_path' => $targetFilePath
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Dosya yükleme sırasında bir hata oluştu.'
        ];
    }
}


function createThumbnail($sourcePath, $targetDir, $width = 300, $height = 200) {
    $pathInfo = pathinfo($sourcePath);
    $fileName = $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
    $targetPath = $targetDir . $fileName;
    
    if (copy($sourcePath, $targetPath)) {
        return [
            'success' => true,
            'file_name' => $fileName,
            'file_path' => $targetPath
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Küçük resim oluşturulurken bir hata oluştu.'
        ];
    }
}
?>
