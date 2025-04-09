<?php

$stats = getVirtualTourStats($conn);
?>
<div class="container-fluid p-0">
    
    <div class="row g-3 mb-4 fade-in">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card primary">
                <i class="bi bi-eye"></i>
                <h3 class="stats-value"><?php echo number_format($stats['total_visits']); ?></h3>
                <p class="stats-title mb-0">Toplam Ziyaret</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card success">
                <i class="bi bi-person"></i>
                <h3 class="stats-value"><?php echo number_format($stats['unique_visitors']); ?></h3>
                <p class="stats-title mb-0">Tekil Ziyaretçi</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card warning">
                <i class="bi bi-building"></i>
                <h3 class="stats-value"><?php echo $stats['popular_campus']; ?></h3>
                <p class="stats-title mb-0">Popüler Kampüs</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card danger">
                <i class="bi bi-image"></i>
                <h3 class="stats-value"><?php echo $stats['popular_scene']; ?></h3>
                <p class="stats-title mb-0">Popüler Sahne</p>
            </div>
        </div>
    </div>
    
    <div class="row g-3">
        
        <div class="col-lg-8 slide-up">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Son 7 Gün Ziyaretleri</h5>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-secondary" id="view-weekly">Haftalık</button>
                        <button class="btn btn-sm btn-outline-secondary active" id="view-monthly">Aylık</button>
                        <button class="btn btn-sm btn-outline-secondary" id="view-yearly">Yıllık</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="visitsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="col-lg-4 slide-up">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Cihaz Dağılımı</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="devicesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-3 mt-3">
        
        <div class="col-12 slide-up" style="animation-delay: 0.2s;">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Son Aktiviteler</h5>
                    <a href="admin.php?page=analytics" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-graph-up"></i> Detaylı İstatistikler
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover data-table mb-0">
                            <thead>
                                <tr>
                                    <th>Tarih</th>
                                    <th>IP Adresi</th>
                                    <th>Kampüs</th>
                                    <th>Sahne</th>
                                    <th>Cihaz</th>
                                    <th>Tarayıcı</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT v.*, s.title as scene_title 
                                          FROM visits v
                                          LEFT JOIN scenes s ON v.scene_id = s.scene_id
                                          ORDER BY v.visit_time DESC LIMIT 10";
                                $visits = executeQuery($conn, $query);
                                
                                if (empty($visits)) {
                                    echo '<tr><td colspan="6" class="text-center py-4">Henüz ziyaret kaydı bulunmuyor.</td></tr>';
                                } else {
                                    foreach ($visits as $visit):
                                    ?>
                                    <tr>
                                        <td><?php echo date('d.m.Y H:i', strtotime($visit['visit_time'])); ?></td>
                                        <td><?php echo $visit['ip_address']; ?></td>
                                        <td><?php echo $visit['campus']; ?></td>
                                        <td><?php echo isset($visit['scene_title']) ? $visit['scene_title'] : '-'; ?></td>
                                        <td><span class="badge bg-light text-dark"><?php echo $visit['device_type']; ?></span></td>
                                        <td><?php echo $visit['browser']; ?></td>
                                    </tr>
                                    <?php 
                                    endforeach;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
    const visitsChartData = {
        labels: [
            <?php 
            foreach ($stats['last_week_visits'] as $day) {
                echo "'" . date('d.m.Y', strtotime($day['date'])) . "',";
            }
            ?>
        ],
        datasets: [{
            label: 'Ziyaret Sayısı',
            data: [
                <?php 
                foreach ($stats['last_week_visits'] as $day) {
                    echo $day['count'] . ",";
                }
                ?>
            ],
            backgroundColor: 'rgba(21, 101, 192, 0.1)',
            borderColor: 'rgba(21, 101, 192, 1)',
            borderWidth: 2,
            tension: 0.4
        }]
    };
    
    const visitsChart = new Chart(
        document.getElementById('visitsChart').getContext('2d'),
        {
            type: 'line',
            data: visitsChartData,
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
                }
            }
        }
    );
    
    
    const devicesChartData = {
        labels: [
            <?php 
            foreach ($stats['device_distribution'] as $device) {
                echo "'" . ucfirst($device['device_type']) . "',";
            }
            ?>
        ],
        datasets: [{
            data: [
                <?php 
                foreach ($stats['device_distribution'] as $device) {
                    echo $device['count'] . ",";
                }
                ?>
            ],
            backgroundColor: [
                'rgba(21, 101, 192, 0.7)',
                'rgba(76, 175, 80, 0.7)',
                'rgba(244, 67, 54, 0.7)'
            ],
            borderWidth: 0
        }]
    };
    
    const devicesChart = new Chart(
        document.getElementById('devicesChart').getContext('2d'),
        {
            type: 'doughnut',
            data: devicesChartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        }
    );
    
    
    document.getElementById('view-weekly').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('view-monthly').classList.remove('active');
        document.getElementById('view-yearly').classList.remove('active');
        
    });
    
    document.getElementById('view-monthly').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('view-weekly').classList.remove('active');
        document.getElementById('view-yearly').classList.remove('active');
        
    });
    
    document.getElementById('view-yearly').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('view-weekly').classList.remove('active');
        document.getElementById('view-monthly').classList.remove('active');
        
    });
</script>