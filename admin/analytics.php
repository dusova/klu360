<?php



$endDate = date('Y-m-d');
$startDate = isset($_GET['start_date']) ? sanitizeInput($_GET['start_date']) : date('Y-m-d', strtotime('-30 days'));
$endDate = isset($_GET['end_date']) ? sanitizeInput($_GET['end_date']) : $endDate;


$timeRange = isset($_GET['time_range']) ? sanitizeInput($_GET['time_range']) : 'last30days';
switch ($timeRange) {
    case 'today':
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d');
        break;
    case 'yesterday':
        $startDate = date('Y-m-d', strtotime('-1 day'));
        $endDate = date('Y-m-d', strtotime('-1 day'));
        break;
    case 'last7days':
        $startDate = date('Y-m-d', strtotime('-6 days'));
        $endDate = date('Y-m-d');
        break;
    case 'last30days':
        $startDate = date('Y-m-d', strtotime('-29 days'));
        $endDate = date('Y-m-d');
        break;
    case 'thismonth':
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-d');
        break;
    case 'lastmonth':
        $startDate = date('Y-m-01', strtotime('first day of last month'));
        $endDate = date('Y-m-t', strtotime('last day of last month'));
        break;
    case 'custom':
        
        break;
}


if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate)) {
    $startDate = date('Y-m-d', strtotime('-30 days'));
}
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
    $endDate = date('Y-m-d');
}



$query = "SELECT 
          COUNT(*) as total_visits, 
          COUNT(DISTINCT ip_address) as unique_visitors 
          FROM visits 
          WHERE DATE(visit_time) BETWEEN ? AND ?";
$params = [$startDate, $endDate];
$visitsData = executeQuery($conn, $query, $params);

$totalVisits = !empty($visitsData) ? $visitsData[0]['total_visits'] : 0;
$uniqueVisitors = !empty($visitsData) ? $visitsData[0]['unique_visitors'] : 0;


$daysDiff = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24) + 1;
$avgDailyVisits = $daysDiff > 0 ? round($totalVisits / $daysDiff) : 0;


$query = "SELECT 
          DATE(visit_time) as date, 
          COUNT(*) as visits, 
          COUNT(DISTINCT ip_address) as unique_visitors 
          FROM visits 
          WHERE DATE(visit_time) BETWEEN ? AND ? 
          GROUP BY DATE(visit_time) 
          ORDER BY date ASC";
$params = [$startDate, $endDate];
$dailyVisits = executeQuery($conn, $query, $params);


$query = "SELECT 
          device_type, 
          COUNT(*) as count 
          FROM visits 
          WHERE DATE(visit_time) BETWEEN ? AND ? 
          GROUP BY device_type";
$params = [$startDate, $endDate];
$deviceDistribution = executeQuery($conn, $query, $params);


$query = "SELECT 
          browser, 
          COUNT(*) as count 
          FROM visits 
          WHERE DATE(visit_time) BETWEEN ? AND ? 
          GROUP BY browser 
          ORDER BY count DESC 
          LIMIT 5";
$params = [$startDate, $endDate];
$browserDistribution = executeQuery($conn, $query, $params);


$query = "SELECT 
          operating_system, 
          COUNT(*) as count 
          FROM visits 
          WHERE DATE(visit_time) BETWEEN ? AND ? 
          GROUP BY operating_system 
          ORDER BY count DESC 
          LIMIT 5";
$params = [$startDate, $endDate];
$osDistribution = executeQuery($conn, $query, $params);


$query = "SELECT 
          campus, 
          COUNT(*) as visits 
          FROM visits 
          WHERE DATE(visit_time) BETWEEN ? AND ? 
          GROUP BY campus 
          ORDER BY visits DESC";
$params = [$startDate, $endDate];
$popularCampuses = executeQuery($conn, $query, $params);


$query = "SELECT 
          v.scene_id, 
          s.title, 
          COUNT(*) as visits 
          FROM visits v 
          LEFT JOIN scenes s ON v.scene_id = s.scene_id 
          WHERE DATE(v.visit_time) BETWEEN ? AND ? AND v.scene_id IS NOT NULL 
          GROUP BY v.scene_id, s.title 
          ORDER BY visits DESC 
          LIMIT 10";
$params = [$startDate, $endDate];
$popularScenes = executeQuery($conn, $query, $params);


$query = "SELECT 
          CASE 
              WHEN referrer = '' THEN 'Doğrudan Giriş' 
              WHEN referrer LIKE '%google%' THEN 'Google' 
              WHEN referrer LIKE '%bing%' THEN 'Bing' 
              WHEN referrer LIKE '%yandex%' THEN 'Yandex' 
              WHEN referrer LIKE '%kirklareli.edu.tr%' THEN 'Kırklareli Üniversitesi' 
              ELSE 'Diğer' 
          END as referrer_source, 
          COUNT(*) as count 
          FROM visits 
          WHERE DATE(visit_time) BETWEEN ? AND ? 
          GROUP BY referrer_source 
          ORDER BY count DESC";
$params = [$startDate, $endDate];
$referrerDistribution = executeQuery($conn, $query, $params);


$dailyVisitsLabels = [];
$dailyVisitsData = [];
$dailyUniqueVisitorsData = [];

foreach ($dailyVisits as $day) {
    $dailyVisitsLabels[] = date('d.m.Y', strtotime($day['date']));
    $dailyVisitsData[] = $day['visits'];
    $dailyUniqueVisitorsData[] = $day['unique_visitors'];
}

$deviceLabels = [];
$deviceData = [];
foreach ($deviceDistribution as $device) {
    $deviceLabels[] = ucfirst($device['device_type']);
    $deviceData[] = $device['count'];
}

$browserLabels = [];
$browserData = [];
foreach ($browserDistribution as $browser) {
    $browserLabels[] = $browser['browser'];
    $browserData[] = $browser['count'];
}

$osLabels = [];
$osData = [];
foreach ($osDistribution as $os) {
    $osLabels[] = $os['operating_system'];
    $osData[] = $os['count'];
}

$referrerLabels = [];
$referrerData = [];
foreach ($referrerDistribution as $referrer) {
    $referrerLabels[] = $referrer['referrer_source'];
    $referrerData[] = $referrer['count'];
}

$campusLabels = [];
$campusData = [];
foreach ($popularCampuses as $campus) {
    $campusLabels[] = $campus['campus'];
    $campusData[] = $campus['visits'];
}
?>

<div class="content-box fade-in">
    <div class="content-header mb-4">
        <h5 class="content-title">İstatistikler ve Analizler</h5>
        <div class="content-actions">
        <div class="btn-group">
    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-download me-1"></i> Dışa Aktar
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="#" data-export="excel" data-filename="istatistikler"><i class="bi bi-file-earmark-excel me-2"></i>Excel İndir</a></li>
        <li><a class="dropdown-item" href="#" data-export="pdf" data-filename="istatistikler"><i class="bi bi-file-earmark-pdf me-2"></i>PDF İndir</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#" data-print="table"><i class="bi bi-printer me-2"></i>Yazdır</a></li>
    </ul>
</div>
        </div>
    </div>
    
    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="get" class="row g-3 align-items-end">
                <input type="hidden" name="page" value="analytics">
                
                
                <div class="col-lg-5">
                    <label class="form-label fw-medium">Zaman Dilimi</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="time_range" id="today" value="today" <?php echo $timeRange == 'today' ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-secondary" for="today">Bugün</label>
                        
                        <input type="radio" class="btn-check" name="time_range" id="yesterday" value="yesterday" <?php echo $timeRange == 'yesterday' ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-secondary" for="yesterday">Dün</label>
                        
                        <input type="radio" class="btn-check" name="time_range" id="last7days" value="last7days" <?php echo $timeRange == 'last7days' ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-secondary" for="last7days">Son 7 Gün</label>
                        
                        <input type="radio" class="btn-check" name="time_range" id="last30days" value="last30days" <?php echo $timeRange == 'last30days' ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-secondary" for="last30days">Son 30 Gün</label>
                        
                        <input type="radio" class="btn-check" name="time_range" id="custom" value="custom" <?php echo $timeRange == 'custom' ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-secondary" for="custom">Özel</label>
                    </div>
                </div>
                
                
                <div class="col-lg-5" id="customDateContainer" <?php echo $timeRange != 'custom' ? 'style="display:none"' : ''; ?>>
                    <div class="row g-2">
                        <div class="col-6">
                            <label for="start_date" class="form-label fw-medium">Başlangıç Tarihi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $startDate; ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="end_date" class="form-label fw-medium">Bitiş Tarihi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $endDate; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Uygula
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stats-card primary">
                <div class="stats-card-body">
                    <div class="stats-icon">
                        <i class="bi bi-eye"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-value"><?php echo number_format($totalVisits); ?></h3>
                        <p class="stats-title mb-0">Toplam Ziyaret</p>
                    </div>
                </div>
                <div class="stats-footer">
                    <small class="text-muted">
                        <i class="bi bi-calendar3"></i> <?php echo date('d.m.Y', strtotime($startDate)); ?> - <?php echo date('d.m.Y', strtotime($endDate)); ?>
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stats-card success">
                <div class="stats-card-body">
                    <div class="stats-icon">
                        <i class="bi bi-person"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-value"><?php echo number_format($uniqueVisitors); ?></h3>
                        <p class="stats-title mb-0">Tekil Ziyaretçi</p>
                    </div>
                </div>
                <div class="stats-footer">
                    <small class="text-muted">
                        <i class="bi bi-fingerprint"></i> IP bazlı hesaplanmıştır
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stats-card info">
                <div class="stats-card-body">
                    <div class="stats-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div class="stats-content">
                        <h3 class="stats-value"><?php echo number_format($avgDailyVisits); ?></h3>
                        <p class="stats-title mb-0">Günlük Ortalama</p>
                    </div>
                </div>
                <div class="stats-footer">
                    <small class="text-muted">
                        <i class="bi bi-arrow-repeat"></i> <?php echo $daysDiff; ?> günlük ortalama
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Ziyaret Analizi</h5>
                <div class="chart-actions">
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary active" id="showAll">Tümü</button>
                        <button type="button" class="btn btn-outline-secondary" id="showVisits">Ziyaretler</button>
                        <button type="button" class="btn btn-outline-secondary" id="showUniques">Tekil Ziyaretçiler</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-container" style="position: relative; height: 400px;">
                <canvas id="visitsChart"></canvas>
            </div>
        </div>
    </div>
    
    
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 card-title"><i class="bi bi-phone text-primary me-2"></i>Cihaz Dağılımı</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 260px;">
                        <canvas id="deviceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 card-title"><i class="bi bi-globe text-primary me-2"></i>Tarayıcı Dağılımı</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 260px;">
                        <canvas id="browserChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 card-title"><i class="bi bi-cpu text-primary me-2"></i>İşletim Sistemi</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 260px;">
                        <canvas id="osChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 card-title"><i class="bi bi-share text-primary me-2"></i>Trafik Kaynakları</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="referrerChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 card-title"><i class="bi bi-building text-primary me-2"></i>Popüler Kampüsler</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="campusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 card-title"><i class="bi bi-camera-video text-primary me-2"></i>En Popüler Sahneler</h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($popularScenes)): ?>
                <div class="text-center py-5">
                    <img src="assets/images/empty-data.svg" alt="Veri Yok" class="img-fluid mb-3" style="max-width: 200px;">
                    <h5>Bu tarih aralığında veri bulunamadı</h5>
                    <p class="text-muted">Seçilen tarih aralığında henüz sahne ziyareti kaydedilmemiş.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%" class="rounded-start">Sıra</th>
                                <th width="15%">Sahne ID</th>
                                <th>Sahne Adı</th>
                                <th width="15%">Ziyaret Sayısı</th>
                                <th width="25%" class="rounded-end">Oran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach ($popularScenes as $index => $scene): 
                                $percentage = ($totalVisits > 0) ? ($scene['visits'] / $totalVisits) * 100 : 0;
                            ?>
                            <tr>
                                <td class="text-center align-middle fw-medium"><?php echo $index + 1; ?></td>
                                <td class="align-middle"><code><?php echo htmlspecialchars($scene['scene_id']); ?></code></td>
                                <td class="align-middle fw-medium"><?php echo htmlspecialchars($scene['title'] ?? $scene['scene_id']); ?></td>
                                <td class="align-middle text-center">
                                    <span class="badge bg-primary rounded-pill px-3 py-2">
                                        <?php echo number_format($scene['visits']); ?>
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $percentage; ?>%;" 
                                                 aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="text-muted small"><?php echo number_format($percentage, 1); ?>%</span>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const timeRangeInputs = document.querySelectorAll('input[name="time_range"]');
        const customDateContainer = document.getElementById('customDateContainer');
        
        timeRangeInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.value === 'custom') {
                    customDateContainer.style.display = 'block';
                } else {
                    customDateContainer.style.display = 'none';
                }
            });
        });
        
        
        const visitsCtx = document.getElementById('visitsChart').getContext('2d');
        const visitsChart = new Chart(visitsCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($dailyVisitsLabels); ?>,
                datasets: [
                    {
                        label: 'Toplam Ziyaret',
                        data: <?php echo json_encode($dailyVisitsData); ?>,
                        borderColor: 'rgba(21, 101, 192, 1)',
                        backgroundColor: 'rgba(21, 101, 192, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Tekil Ziyaretçi',
                        data: <?php echo json_encode($dailyUniqueVisitorsData); ?>,
                        borderColor: 'rgba(76, 175, 80, 1)',
                        backgroundColor: 'rgba(76, 175, 80, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#333',
                        bodyColor: '#666',
                        borderColor: '#ddd',
                        borderWidth: 1,
                        padding: 10,
                        boxPadding: 5,
                        usePointStyle: true,
                        callbacks: {
                            labelPointStyle: function(context) {
                                return {
                                    pointStyle: 'circle',
                                    rotation: 0
                                };
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                }
            }
        });
        
        
        document.getElementById('showAll').addEventListener('click', function() {
            visitsChart.data.datasets[0].hidden = false;
            visitsChart.data.datasets[1].hidden = false;
            visitsChart.update();
            
            
            this.classList.add('active');
            document.getElementById('showVisits').classList.remove('active');
            document.getElementById('showUniques').classList.remove('active');
        });
        
        document.getElementById('showVisits').addEventListener('click', function() {
            visitsChart.data.datasets[0].hidden = false;
            visitsChart.data.datasets[1].hidden = true;
            visitsChart.update();
            
            
            this.classList.add('active');
            document.getElementById('showAll').classList.remove('active');
            document.getElementById('showUniques').classList.remove('active');
        });
        
        document.getElementById('showUniques').addEventListener('click', function() {
            visitsChart.data.datasets[0].hidden = true;
            visitsChart.data.datasets[1].hidden = false;
            visitsChart.update();
            
            
            this.classList.add('active');
            document.getElementById('showAll').classList.remove('active');
            document.getElementById('showVisits').classList.remove('active');
        });
        
        
        const deviceCtx = document.getElementById('deviceChart').getContext('2d');
        new Chart(deviceCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($deviceLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($deviceData); ?>,
                    backgroundColor: [
                        'rgba(21, 101, 192, 0.8)',
                        'rgba(76, 175, 80, 0.8)',
                        'rgba(255, 111, 0, 0.8)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        
        
        const browserCtx = document.getElementById('browserChart').getContext('2d');
        new Chart(browserCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($browserLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($browserData); ?>,
                    backgroundColor: [
                        'rgba(21, 101, 192, 0.8)',
                        'rgba(76, 175, 80, 0.8)',
                        'rgba(244, 67, 54, 0.8)',
                        'rgba(255, 111, 0, 0.8)',
                        'rgba(33, 150, 243, 0.8)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        
        
        const osCtx = document.getElementById('osChart').getContext('2d');
        new Chart(osCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($osLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($osData); ?>,
                    backgroundColor: [
                        'rgba(21, 101, 192, 0.8)',
                        'rgba(76, 175, 80, 0.8)',
                        'rgba(244, 67, 54, 0.8)',
                        'rgba(255, 111, 0, 0.8)',
                        'rgba(33, 150, 243, 0.8)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        
        
        const referrerCtx = document.getElementById('referrerChart').getContext('2d');
        new Chart(referrerCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($referrerLabels); ?>,
                datasets: [{
                    label: 'Ziyaret Sayısı',
                    data: <?php echo json_encode($referrerData); ?>,
                    backgroundColor: 'rgba(21, 101, 192, 0.8)',
                    borderWidth: 0,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
        
        
        const campusCtx = document.getElementById('campusChart').getContext('2d');
        new Chart(campusCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($campusLabels); ?>,
                datasets: [{
                    label: 'Ziyaret Sayısı',
                    data: <?php echo json_encode($campusData); ?>,
                    backgroundColor: 'rgba(76, 175, 80, 0.8)',
                    borderWidth: 0,
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
<style>
    
    .stats-card {
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        height: 100%;
    }
    
    .stats-card.primary {
        border-left: 4px solid var(--primary-color);
    }
    
    .stats-card.success {
        border-left: 4px solid var(--success-color);
    }
    
    .stats-card.info {
        border-left: 4px solid var(--info-color);
    }
    
    .stats-card-body {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        background-color: white;
    }
    
    .stats-icon {
        font-size: 2rem;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-right: 1rem;
    }
    
    .stats-card.primary .stats-icon {
        color: var(--primary-color);
        background-color: rgba(21, 101, 192, 0.1);
    }
    
    .stats-card.success .stats-icon {
        color: var(--success-color);
        background-color: rgba(76, 175, 80, 0.1);
    }
    
    .stats-card.info .stats-icon {
        color: var(--info-color);
        background-color: rgba(33, 150, 243, 0.1);
    }
    
    .stats-content {
        flex: 1;
    }
    
    .stats-value {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.2rem;
    }
    
    .stats-card.primary .stats-value {
        color: var(--primary-color);
    }
    
    .stats-card.success .stats-value {
        color: var(--success-color);
    }
    
    .stats-card.info .stats-value {
        color: var(--info-color);
    }
    
    .stats-title {
        color: var(--text-dark);
        font-weight: 500;
        opacity: 0.8;
    }
    
    .stats-footer {
        padding: 0.75rem 1.5rem;
        background-color: #f8f9fa;
        border-top: 1px solid #eee;
    }
    
    
    .chart-container {
        position: relative;
        margin: 0 auto;
    }
    
    .btn-check:checked + .btn-outline-secondary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    
    .card-title {
        font-weight: 600;
    }
    
    th.rounded-start {
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }
    
    th.rounded-end {
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }
    
    @media (max-width: 767.98px) {
        .stats-value {
            font-size: 1.5rem;
        }
        
        .stats-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }
    }
</style>