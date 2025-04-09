<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';
require_once 'includes/admin_functions.php';

session_start();

checkUserSession();

if ($_SESSION['user_role'] != 'admin' && $_SESSION['user_role'] != 'editor') {
    header("Location: index.php");
    exit;
}

$page = isset($_GET['page']) ? sanitizeInput($_GET['page']) : 'dashboard';

$csrf_token = generateCSRFToken();

$page_titles = [
    'dashboard' => 'Kontrol Paneli',
    'campuses' => 'Kampüsler',
    'scenes' => 'Sahneler',
    'hotspots' => 'Hotspotlar',
    'users' => 'Kullanıcılar',
    'settings' => 'Ayarlar',
    'analytics' => 'İstatistikler',
    'profile' => 'Profil'
];

$page_title = isset($page_titles[$page]) ? $page_titles[$page] : 'Admin Paneli';

$notification = isset($_GET['notification']) ? sanitizeInput($_GET['notification']) : '';
$notification_type = isset($_GET['type']) ? sanitizeInput($_GET['type']) : 'info';
$notification_message = '';

if ($notification == 'success') {
    $notification_message = 'İşlem başarıyla tamamlandı.';
} elseif ($notification == 'error') {
    $notification_message = 'İşlem sırasında bir hata oluştu.';
} elseif ($notification == 'delete_success') {
    $notification_message = 'Öğe başarıyla silindi.';
} elseif ($notification == 'login_success') {
    $notification_message = 'Giriş başarılı. Hoş geldiniz!';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> | Kırklareli Üniversitesi Sanal Tur Yönetimi</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">
    
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css">
    <script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">

    <script src="assets/js/export-print-filter.js"></script>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            
            <div class="col-lg-2 admin-sidebar">
                <div class="sidebar-logo">
                    <img src="assets/images/logo-white.png" alt="KLÜ Logo" class="img-fluid">
                    <h4>KLÜ Sanal Tur</h4>
                </div>
                
                <ul class="nav flex-column mt-3">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'dashboard' ? 'active' : ''; ?>" href="admin.php">
                            <i class="bi bi-speedometer2"></i> Kontrol Paneli
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'campuses' ? 'active' : ''; ?>" href="admin.php?page=campuses">
                            <i class="bi bi-building"></i> Kampüsler
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'scenes' ? 'active' : ''; ?>" href="admin.php?page=scenes">
                            <i class="bi bi-image"></i> Sahneler
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'hotspots' ? 'active' : ''; ?>" href="admin.php?page=hotspots">
                            <i class="bi bi-cursor"></i> Hotspotlar
                        </a>
                    </li>
                    <?php if ($_SESSION['user_role'] == 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'users' ? 'active' : ''; ?>" href="admin.php?page=users">
                            <i class="bi bi-people"></i> Kullanıcılar
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'analytics' ? 'active' : ''; ?>" href="admin.php?page=analytics">
                            <i class="bi bi-graph-up"></i> İstatistikler
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == 'settings' ? 'active' : ''; ?>" href="admin.php?page=settings">
                            <i class="bi bi-gear"></i> Ayarlar
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a class="nav-link" href="logout.php">
                            <i class="bi bi-box-arrow-right"></i> Çıkış Yap
                        </a>
                    </li>
                </ul>
            </div>
            
            
            <div class="col-lg-10 admin-content">
                
                <div class="admin-header d-flex justify-content-between align-items-center">
                    <div>
                        <button class="btn btn-sm btn-outline-secondary d-lg-none me-2" id="sidebarToggleShow">
                            <i class="bi bi-list"></i>
                        </button>
                        <h4 class="page-title d-inline-block mb-0"><?php echo $page_title; ?></h4>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <a href="index.php" class="btn btn-outline-primary me-3" target="_blank">
                            <i class="bi bi-eye"></i> Siteyi Görüntüle
                        </a>
                        <div class="dropdown profile-dropdown">
                            <button class="btn btn-link dropdown-toggle custom-dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="assets/images/avatar.png" alt="Profil" class="rounded-circle" width="40" height="40">
                                <span class="ms-2 d-none d-md-inline-block"><?php echo $_SESSION['user_name']; ?></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                                <li><a class="dropdown-item" href="admin.php?page=profile"><i class="bi bi-person me-2"></i> Profil</a></li>
                                <li><a class="dropdown-item" href="admin.php?page=settings"><i class="bi bi-gear me-2"></i> Ayarlar</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Çıkış Yap</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                
                <?php if (!empty($notification_message)): ?>
                <div class="alert alert-<?php echo $notification_type; ?> alert-dismissible fade show mt-3 mx-3" role="alert">
                    <i class="bi <?php echo $notification_type == 'success' ? 'bi-check-circle' : ($notification_type == 'warning' ? 'bi-exclamation-triangle' : 'bi-info-circle'); ?> me-2"></i>
                    <?php echo $notification_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                
                <div class="content-wrapper m-3">
                    <?php
                    $page_file = 'admin/' . $page . '.php';
                    if (file_exists($page_file)) {
                        include $page_file;
                    } else {
                        echo '<div class="content-box fade-in">
                                <div class="alert alert-warning mb-0">
                                    <i class="bi bi-exclamation-triangle me-2"></i> Sayfa bulunamadı.
                                </div>
                            </div>';
                    }
                    ?>
                </div>
                
                
                <footer class="admin-footer text-center py-3 mt-auto">
                    <div class="small text-muted">
                        &copy; <?php echo date('Y'); ?> Kırklareli Üniversitesi Sanal Tur Yönetimi | Tüm Hakları Saklıdır
                    </div>
                </footer>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    
    <script src="assets/js/admin.js"></script>
</body>
</html>