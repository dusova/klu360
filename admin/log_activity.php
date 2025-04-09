
<?php



require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';


header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}


if (!isset($_POST['action']) || empty($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Missing action parameter']);
    exit;
}


$action = sanitizeInput($_POST['action']);
$scene_id = isset($_POST['scene_id']) ? sanitizeInput($_POST['scene_id']) : null;
$campus = isset($_POST['campus']) ? sanitizeInput($_POST['campus']) : null;
$details = isset($_POST['details']) ? $_POST['details'] : '{}';


$ip = getVisitorIP();
$user_agent = $_SERVER['HTTP_USER_AGENT'];

if ($action == 'scene_change') {
    
    if (!$scene_id || !$campus) {
        echo json_encode(['success' => false, 'message' => 'Missing parameters']);
        exit;
    }
    
    
    logVisit($conn, $ip, $user_agent, $campus, $scene_id);
    
    echo json_encode(['success' => true]);
} elseif ($action == 'user_action') {
    
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    
    
    logActivity($conn, $user_id, $action, $details, $campus, $scene_id);
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

exit;
?>