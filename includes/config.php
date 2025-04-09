<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'kirklareli_virtual_tour');

define('SITE_URL', 'http://localhost/virtual-tour');
define('SITE_TITLE', 'Kırklareli Üniversitesi 360° Sanal Tur');

define('ROOT_PATH', dirname(__DIR__) . '/');
define('INCLUDES_PATH', ROOT_PATH . 'includes/');
define('UPLOADS_PATH', ROOT_PATH . 'uploads/');
define('PANORAMA_PATH', UPLOADS_PATH . 'panoramas/');
define('THUMBNAILS_PATH', UPLOADS_PATH . 'thumbnails/');
define('MAPS_PATH', UPLOADS_PATH . 'maps/');

define('MAX_FILE_SIZE', 20 * 1024 * 1024);

define('SESSION_LIFETIME', 7200);

define('CSRF_TOKEN_SECRET', 'klu_sanal_tur_2025');

define('GOOGLE_MAPS_API_KEY', '');  
define('ANALYTICS_API_KEY', '');    

define('DEBUG_MODE', true);

date_default_timezone_set('Europe/Istanbul');

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
?>
