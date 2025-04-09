<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

session_start();

$visitor_ip = getVisitorIP();
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$campus = isset($_GET['campus']) ? sanitizeInput($_GET['campus']) : 'teknik-bilimler';
$scene_id = isset($_GET['scene']) ? sanitizeInput($_GET['scene']) : null;

$default_campus_data = [
    'id' => 1,
    'name' => 'Teknik Bilimler MYO',
    'map_image' => 'assets/images/maps/teknik-bilimler-map.jpg'
];

$default_scenes = [
    [
        'id' => 1,
        'scene_id' => 'scene1',
        'title' => 'Ana Giriş',
        'description' => 'Ana giriş binası',
        'image_path' => 'assets/images/panoramas/scene1.jpg',
        'thumbnail_path' => 'assets/images/thumbnails/scene1.jpg',
        'map_x' => 50,
        'map_y' => 50,
        'display_order' => 1
    ]
];

try {
    logVisit($conn, $visitor_ip, $user_agent, $campus, $scene_id);
} catch (Exception $e) {
    if (DEBUG_MODE) {
        echo '<div class="debug-error">Ziyaret kaydedilemedi: ' . $e->getMessage() . '</div>';
    }
}

try {
    $campus_data = getCampusData($conn, $campus);
    if (!$campus_data) {
        if (DEBUG_MODE) {
            echo '<div class="debug-error">Kampüs verisi bulunamadı, varsayılan değerler kullanılıyor</div>';
        }
        $campus_data = $default_campus_data;
    }
} catch (Exception $e) {
    if (DEBUG_MODE) {
        echo '<div class="debug-error">Kampüs verileri alınamadı: ' . $e->getMessage() . '</div>';
    }
    $campus_data = $default_campus_data;
}

try {
    $query = "SELECT * FROM scenes WHERE campus_id = ? AND status = 'active' ORDER BY display_order ASC";
    $params = [$campus_data['id']];
    $scenes = executeQuery($conn, $query, $params);

    if (empty($scenes)) {
        if (DEBUG_MODE) {
            echo '<div class="debug-error">Sahneler bulunamadı, varsayılan değerler kullanılıyor</div>';
        }
        $scenes = $default_scenes;
    }
} catch (Exception $e) {
    if (DEBUG_MODE) {
        echo '<div class="debug-error">Sahneler alınamadı: ' . $e->getMessage() . '</div>';
    }
    $scenes = $default_scenes;
}

$default_scene = null;
if ($scene_id) {
    foreach ($scenes as $scene) {
        if ($scene['scene_id'] == $scene_id) {
            $default_scene = $scene['scene_id'];
            break;
        }
    }
}

if (!$default_scene && !empty($scenes)) {
    $default_scene = $scenes[0]['scene_id'];
} else if (!$default_scene) {
    $default_scene = 'scene1'; 
}

$panoramaData = [];
$hotspotData = [];

foreach ($scenes as $scene) {
    $panoramaData[$scene['scene_id']] = [
        'title' => $scene['title'],
        'image' => $scene['image_path'],
        'thumb' => $scene['thumbnail_path'],
        'description' => $scene['description']
    ];
    
    try {
        $query = "SELECT * FROM hotspots WHERE scene_id = ?";
        $params = [$scene['id']]; 
        $hotspots_db = executeQuery($conn, $query, $params);
        
        $scene_hotspots = [];
        foreach ($hotspots_db as $hotspot) {
            $hotspot_data = [
                'id' => 'hotspot-' . $hotspot['id'],
                'pitch' => (float)$hotspot['pitch'],
                'yaw' => (float)$hotspot['yaw'],
                'text' => $hotspot['text']
            ];
            
            if ($hotspot['type'] == 'scene') {
                $hotspot_data['type'] = 'scene';
                $hotspot_data['sceneId'] = $hotspot['target_scene_id'];
                
                if ($hotspot['target_pitch'] !== null && $hotspot['target_yaw'] !== null) {
                    $hotspot_data['targetPitch'] = (float)$hotspot['target_pitch'];
                    $hotspot_data['targetYaw'] = (float)$hotspot['target_yaw'];
                }
            } elseif ($hotspot['type'] == 'info') {
                $hotspot_data['type'] = 'info';
            } elseif ($hotspot['type'] == 'link') {
                $hotspot_data['type'] = 'info'; 
                $hotspot_data['URL'] = $hotspot['target_scene_id']; 
                $hotspot_data['cssClass'] = 'link-hotspot'; 
            }
            
            $scene_hotspots[] = $hotspot_data;
        }
        
        $hotspotData[$scene['scene_id']] = $scene_hotspots;
    } catch (Exception $e) {
        if (DEBUG_MODE) {
            echo '<div class="debug-error">Hotspotlar alınamadı: ' . $e->getMessage() . '</div>';
        }
        $hotspotData[$scene['scene_id']] = [];
    }
}

if (empty($panoramaData)) {
    $panoramaData['scene1'] = [
        'title' => 'Varsayılan Sahne',
        'image' => 'assets/images/panoramas/default.jpg',
        'thumb' => 'assets/images/thumbnails/default.jpg',
        'description' => 'Veritabanından sahne verisi alınamadı.'
    ];
    $hotspotData['scene1'] = [];
    $default_scene = 'scene1';
}

$panoramaDataJSON = json_encode($panoramaData);
$hotspotDataJSON = json_encode($hotspotData);


?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($campus_data['name']); ?> - 360° Sanal Tur | Kırklareli Üniversitesi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css">
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/animations/scale.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/tour.css">
    <link rel="stylesheet" href="assets/css/responsive-mobile.css">
    
    <style>
        .debug-error {
            background-color: #ffdddd;
            border-left: 5px solid #f44336;
            padding: 10px;
            margin: 10px 0;
            color: #333;
            display: none; 
        }
        
        .custom-hotspot {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: rgba(12, 77, 162, 0.9);
            border: 3px solid rgba(255, 255, 255, 0.7);
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.4);
            transform: translate(-25px, -25px);
        }
        
        .custom-hotspot::after {
            content: "";
            width: 18px;
            height: 18px;
            border-top: 3px solid white;
            border-right: 3px solid white;
            transform: rotate(45deg);
        }
        
        .custom-hotspot:hover {
            transform: translate(-25px, -25px) scale(1.15);
            background-color: rgba(12, 77, 162, 1);
            border-color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
        }
        
        .info-hotspot {
            background-color: rgba(40, 167, 69, 0.9);
        }
        
        .info-hotspot:hover {
            background-color: rgba(40, 167, 69, 1);
        }
        
        .info-hotspot::after {
            content: "i";
            font-family: 'Poppins', sans-serif;
            font-style: italic;
            font-weight: bold;
            font-size: 24px;
            color: white;
            border: none;
            transform: none;
        }
        
        .link-hotspot {
            background-color: rgba(233, 64, 87, 0.9);
        }
        
        .link-hotspot:hover {
            background-color: rgba(233, 64, 87, 1);
        }
        
        .link-hotspot::after {
            content: "\2197";
            font-family: 'Poppins', sans-serif;
            font-size: 20px;
            color: white;
            border: none;
            transform: none;
        }
    </style>
</head>
<body>
    <div id="preloader">
        <div class="loader-container">
            <div class="loader-circle"></div>
            <div class="loader-circle"></div>
            <div class="loader-circle"></div>
            <div class="loader-text">360°</div>
        </div>
        <div class="loading-progress">
            <div class="progress-bar" id="loadingProgress"></div>
        </div>
        <div class="loading-status" id="loadingStatus">Panorama görüntüleri yükleniyor...</div>
    </div>

    <div id="panorama"></div>

    <div class="tour-interface">
        <div class="tour-header" id="tourHeader">
            <div class="campus-logo">
                <div class="logo-img">
                    <img src="assets/images/logo.png" alt="Kırklareli Üniversitesi" width="30" height="30">
                </div>
                <div class="campus-name"><?php echo htmlspecialchars($campus_data['name']); ?> Sanal Turu</div>
            </div>
            <div class="tour-actions">
                <button class="action-btn" id="toggleAutorotate" data-tippy-content="Otomatik Döndür">
                    <i class="bi bi-arrow-repeat"></i>
                </button>
                <button class="action-btn" id="shareButton" data-tippy-content="Paylaş">
                    <i class="bi bi-share"></i>
                </button>
                <button class="action-btn" id="helpButton" data-tippy-content="Yardım">
                    <i class="bi bi-question-lg"></i>
                </button>
                <a href="index.php" class="action-btn" data-tippy-content="Ana Sayfa">
                    <i class="bi bi-house"></i>
                </a>
            </div>
        </div>

        <div class="side-controls">
            <button class="control-btn" id="infoButton" data-tippy-content="Mekan Bilgisi">
                <i class="bi bi-info-lg"></i>
            </button>
            <button class="control-btn" id="mapButton" data-tippy-content="Kampüs Haritası">
                <i class="bi bi-map"></i>
            </button>
            <button class="control-btn" id="fullscreenButton" data-tippy-content="Tam Ekran">
                <i class="bi bi-fullscreen"></i>
            </button>
            <button class="control-btn" id="zoomInButton" data-tippy-content="Yakınlaştır">
                <i class="bi bi-zoom-in"></i>
            </button>
            <button class="control-btn" id="zoomOutButton" data-tippy-content="Uzaklaştır">
                <i class="bi bi-zoom-out"></i>
            </button>
        </div>

        <div class="scene-info" id="sceneInfo">
            <div class="info-card">
                <div class="info-header">
                    <div>
                        <h3 class="info-title" id="sceneTitle">Yükleniyor...</h3>
                        <div class="info-subtitle" id="sceneSubtitle"><?php echo htmlspecialchars($campus_data['name']); ?></div>
                    </div>
                    <button class="info-close" id="closeInfo">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="info-body">
                    <div class="location-badge">
                        <i class="bi bi-geo-alt"></i> <span id="locationName"><?php echo htmlspecialchars($campus_data['name']); ?></span>
                    </div>
                    <div class="info-description" id="sceneDescription">
                    <?php echo htmlspecialchars($campus_data['description']); ?>
                    </div>
                    <h5>Görseller</h5>
                    <div class="info-gallery" id="sceneGallery">
                        <img src="assets/images/gallery/placeholder1.jpg" class="gallery-img" alt="Yükleniyor...">
                        <img src="assets/images/gallery/placeholder2.jpg" class="gallery-img" alt="Yükleniyor...">
                    </div>
                </div>
            </div>
        </div>

        <div class="campus-map" id="campusMap">
            <div class="map-card">
                <div class="map-header">
                    <div class="map-title">
                        <i class="bi bi-map me-2"></i> <?php echo htmlspecialchars($campus_data['name']); ?> Haritası
                    </div>
                    <button class="map-close" id="closeMap">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="map-content">
                    <div class="map-image-container" id="map-image-container">
                        <img src="<?php echo htmlspecialchars($campus_data['map_image']); ?>" alt="Kampüs Haritası" class="map-image" id="map-image">
                        
                        <?php if (isset($scenes) && is_array($scenes)): ?>
                            <?php foreach ($scenes as $scene): ?>
                                <?php if (isset($scene['map_x']) && isset($scene['map_y']) && $scene['map_x'] !== null && $scene['map_y'] !== null): ?>
                                <div class="map-marker" 
                                    data-scene-id="<?php echo htmlspecialchars($scene['scene_id']); ?>"
                                    style="left: <?php echo htmlspecialchars($scene['map_x']); ?>%; top: <?php echo htmlspecialchars($scene['map_y']); ?>%;"
                                    data-tippy-content="<?php echo htmlspecialchars($scene['title']); ?>">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="scene-navigation">
            <div class="navigation-container">
                <button class="nav-button" id="prevScene" disabled>
                    <i class="bi bi-chevron-left"></i>
                </button>
                
                <div class="scene-carousel" id="sceneCarousel">
                    <?php if (isset($scenes) && is_array($scenes)): ?>
                        <?php foreach ($scenes as $index => $scene): ?>
                        <div class="scene-item <?php echo ($scene['scene_id'] == $default_scene) ? 'active' : ''; ?>" 
                            data-scene-id="<?php echo htmlspecialchars($scene['scene_id']); ?>"
                            data-index="<?php echo $index; ?>">
                            <img src="<?php echo htmlspecialchars($scene['thumbnail_path']); ?>" alt="<?php echo htmlspecialchars($scene['title']); ?>" class="scene-thumbnail">
                            <div class="scene-label"><?php echo htmlspecialchars($scene['title']); ?></div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="scene-item active" data-scene-id="scene1" data-index="0">
                            <img src="assets/images/thumbnails/default.jpg" alt="Varsayılan Sahne" class="scene-thumbnail">
                            <div class="scene-label">Varsayılan Sahne</div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <button class="nav-button" id="nextScene" <?php echo (isset($scenes) && count($scenes) <= 1) ? 'disabled' : ''; ?>>
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <div id="imageViewer">
        <button id="closeViewer"><i class="bi bi-x-lg"></i></button>
        <img src="" id="viewerImage" alt="Büyütülmüş Görüntü">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="assets/js/tour.js"></script>
    <script src="assets/js/interactive-map.js"></script>
    <script src="assets/js/animations.js"></script>

    <script>
        const panoramaData = <?php echo $panoramaDataJSON; ?>;
        const hotspotData = <?php echo $hotspotDataJSON; ?>;
        const defaultScene = "<?php echo $default_scene; ?>";
        
        window.campusId = "<?php echo $campus; ?>";
        
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof initTour === 'function') {
                initTour(panoramaData, hotspotData, defaultScene);
            } else {
                console.error('tour.js dosyası bulunamadı veya yüklenemedi!');
                initBasicTour();
            }
            
            if (typeof InteractiveMap === 'function') {
                document.getElementById('map-image').onload = function() {
                    const map = new InteractiveMap({
                        containerId: 'map-image-container',
                        mapImageId: 'map-image',
                        onMarkerClick: function(sceneId) {
                            if (window.viewer) {
                                window.viewer.loadScene(sceneId);
                            }
                            const mapPanel = document.getElementById('campusMap');
                            mapPanel.classList.remove('visible');
                        }
                    });
                };
            }
        });
        
        function initBasicTour() {
            console.log('Temel tur başlatılıyor...');
            
            window.addEventListener('load', function() {
                setTimeout(function() {
                    document.getElementById('preloader').style.opacity = '0';
                    setTimeout(function() {
                        document.getElementById('preloader').style.display = 'none';
                    }, 500);
                }, 1000);
            });
            
            const viewerConfig = {
                default: {
                    firstScene: defaultScene,
                    sceneFadeDuration: 1000,
                    autoLoad: true,
                    compass: false
                },
                scenes: {}
            };
            
            Object.keys(panoramaData).forEach(sceneId => {
                viewerConfig.scenes[sceneId] = {
                    title: panoramaData[sceneId].title,
                    panorama: panoramaData[sceneId].image,
                    hotSpots: hotspotData[sceneId] || []
                };
            });
            
            try {
                window.viewer = pannellum.viewer('panorama', viewerConfig);
                
                setupBasicControls();
            } catch (error) {
                console.error('Pannellum başlatılamadı:', error);
                document.getElementById('loadingStatus').textContent = 'Panorama görüntüleyici yüklenemedi. Lütfen sayfayı yenileyin.';
            }
        }
        
        function setupBasicControls() {
            document.getElementById('infoButton').addEventListener('click', function() {
                document.getElementById('sceneInfo').classList.toggle('visible');
            });
            
            document.getElementById('closeInfo').addEventListener('click', function() {
                document.getElementById('sceneInfo').classList.remove('visible');
            });
            
            document.getElementById('mapButton').addEventListener('click', function() {
                document.getElementById('campusMap').classList.toggle('visible');
            });
            
            document.getElementById('closeMap').addEventListener('click', function() {
                document.getElementById('campusMap').classList.remove('visible');
            });
            
            document.getElementById('fullscreenButton').addEventListener('click', function() {
                if (window.viewer) {
                    window.viewer.toggleFullscreen();
                }
            });
            
            document.getElementById('zoomInButton').addEventListener('click', function() {
                if (window.viewer) {
                    window.viewer.setHfov(window.viewer.getHfov() - 10);
                }
            });
            
            document.getElementById('zoomOutButton').addEventListener('click', function() {
                if (window.viewer) {
                    window.viewer.setHfov(window.viewer.getHfov() + 10);
                }
            });
            
            document.querySelectorAll('.scene-item').forEach(function(item) {
                item.addEventListener('click', function() {
                    const sceneId = this.dataset.sceneId;
                    if (window.viewer) {
                        window.viewer.loadScene(sceneId);
                    }
                });
            });
            
            document.getElementById('prevScene').addEventListener('click', function() {
                if (window.viewer) {
                    const currentScene = window.viewer.getScene();
                    const scenes = document.querySelectorAll('.scene-item');
                    for (let i = 0; i < scenes.length; i++) {
                        if (scenes[i].dataset.sceneId === currentScene && i > 0) {
                            window.viewer.loadScene(scenes[i-1].dataset.sceneId);
                            break;
                        }
                    }
                }
            });
            
            document.getElementById('nextScene').addEventListener('click', function() {
                if (window.viewer) {
                    const currentScene = window.viewer.getScene();
                    const scenes = document.querySelectorAll('.scene-item');
                    for (let i = 0; i < scenes.length; i++) {
                        if (scenes[i].dataset.sceneId === currentScene && i < scenes.length - 1) {
                            window.viewer.loadScene(scenes[i+1].dataset.sceneId);
                            break;
                        }
                    }
                }
            });
            
            if (window.viewer) {
                window.viewer.on('scenechange', function(sceneId) {
                    document.querySelectorAll('.scene-item').forEach(function(item) {
                        if (item.dataset.sceneId === sceneId) {
                            item.classList.add('active');
                        } else {
                            item.classList.remove('active');
                        }
                    });
                    
                    if (panoramaData[sceneId]) {
                        document.getElementById('sceneTitle').textContent = panoramaData[sceneId].title;
                        document.getElementById('sceneDescription').textContent = panoramaData[sceneId].description;
                    }
                    
                    const url = new URL(window.location.href);
                    url.searchParams.set('scene', sceneId);
                    window.history.replaceState({}, '', url.toString());
                });
            }
        }
    </script>
</body>
</html>