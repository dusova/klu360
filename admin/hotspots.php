<?php



$action = isset($_GET['action']) ? sanitizeInput($_GET['action']) : 'list';


$campuses = listCampuses($conn, 'active');


$selected_campus_id = isset($_GET['campus_id']) ? intval($_GET['campus_id']) : (count($campuses) > 0 ? $campuses[0]['id'] : 0);


$scenes = [];
if ($selected_campus_id > 0) {
    $scenes = listScenes($conn, $selected_campus_id, 'active');
}

$selected_scene_id = isset($_GET['scene_id']) ? intval($_GET['scene_id']) : (count($scenes) > 0 ? $scenes[0]['id'] : 0);


$scene_info = null;
$hotspots = [];

if ($selected_scene_id > 0) {
    $query = "SELECT * FROM scenes WHERE id = ?";
    $params = [$selected_scene_id];
    $result = executeQuery($conn, $query, $params);
    
    if (!empty($result)) {
        $scene_info = $result[0];
        
        
        $query = "SELECT h.*, s.title as target_scene_title, s.scene_id as target_scene_id 
                  FROM hotspots h
                  LEFT JOIN scenes s ON h.target_scene_id = s.scene_id
                  WHERE h.scene_id = ?";
        $params = [$scene_info['id']];
        $hotspots = executeQuery($conn, $query, $params);
    }
}


if ($action == 'add' || $action == 'edit') {
    $hotspot_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $hotspot = null;
    
    if ($action == 'edit' && $hotspot_id > 0) {
        $query = "SELECT * FROM hotspots WHERE id = ?";
        $params = [$hotspot_id];
        $result = executeQuery($conn, $query, $params);
        
        if (!empty($result)) {
            $hotspot = $result[0];
            
            
            if ($hotspot['scene_id'] != $selected_scene_id) {
                $query = "SELECT * FROM scenes WHERE id = ?";
                $params = [$hotspot['scene_id']];
                $result = executeQuery($conn, $query, $params);
                
                if (!empty($result)) {
                    $scene_info = $result[0];
                    $selected_scene_id = $scene_info['id'];
                    
                    
                    $selected_campus_id = $scene_info['campus_id'];
                    
                    
                    $scenes = listScenes($conn, $selected_campus_id, 'active');
                }
            }
        }
    }
    
    
    $target_scenes = listScenes($conn, null, 'active');
    
    ?>
    <div class="content-box fade-in">
        <div class="content-header">
            <h5 class="content-title">
                <?php if ($action == 'add'): ?>
                    <i class="bi bi-plus-circle-fill text-primary me-2"></i>Yeni Hotspot Ekle
                <?php else: ?>
                    <i class="bi bi-pencil-fill text-primary me-2"></i>Hotspot Düzenle: <?php echo htmlspecialchars($hotspot['text'] ?? ''); ?>
                <?php endif; ?>
            </h5>
            <div class="content-actions">
                <a href="admin.php?page=hotspots&scene_id=<?php echo $selected_scene_id; ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Listeye Dön
                </a>
            </div>
        </div>
        
        <?php if (!$scene_info): ?>
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 flex-shrink-0"></i>
                <div>Seçilen sahne bulunamadı. Lütfen geçerli bir sahne seçin.</div>
            </div>
        <?php else: ?>
            <form id="hotspotForm" action="admin/process_hotspot.php" method="post" class="needs-validation" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="action" value="<?php echo $action; ?>">
                <input type="hidden" name="id" value="<?php echo $hotspot_id; ?>">
                <input type="hidden" name="scene_id" value="<?php echo $selected_scene_id; ?>">
                
                <div class="row g-4">
                    
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0"><i class="bi bi-geo-alt me-2"></i>Hotspot Konumu</h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
                                    <i class="bi bi-info-circle-fill me-2 flex-shrink-0"></i>
                                    <div>
                                        <strong>Konum Seçme:</strong> Görüntüde istediğiniz yere odaklandıktan sonra görüntünün merkezindeki kırmızı işaretçinin konumunu kullanabilirsiniz. "Bu Konumu Kullan" butonuna tıklayarak merkezdeki konumu seçin.
                                    </div>
                                </div>
                                
                                <div id="panoramaPreview" class="panorama-container mb-3 position-relative" style="height: 450px; border-radius: 8px; overflow: hidden;"></div>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="pitch" class="form-label fw-medium">Pitch (Yukarı/Aşağı Açı)</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-arrow-up-down"></i></span>
                                            <input type="number" class="form-control" id="pitch" name="pitch" step="0.01" required
                                                   value="<?php echo isset($hotspot) ? $hotspot['pitch'] : '0'; ?>">
                                            <div class="invalid-feedback">Lütfen pitch değeri girin.</div>
                                        </div>
                                        <div class="form-text">Dikey konum: Yukarı (+) / Aşağı (-)</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="yaw" class="form-label fw-medium">Yaw (Sağ/Sol Açı)</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-arrow-left-right"></i></span>
                                            <input type="number" class="form-control" id="yaw" name="yaw" step="0.01" required
                                                   value="<?php echo isset($hotspot) ? $hotspot['yaw'] : '0'; ?>">
                                            <div class="invalid-feedback">Lütfen yaw değeri girin.</div>
                                        </div>
                                        <div class="form-text">Yatay konum: Sağ (+) / Sol (-)</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0"><i class="bi bi-info-circle me-2"></i>Hotspot Bilgileri</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <label for="type" class="form-label fw-medium">Hotspot Tipi <span class="text-danger">*</span></label>
                                    <div class="hotspot-type-selector d-flex mb-2">
                                        <div class="form-check form-check-inline me-4">
                                            <input class="form-check-input" type="radio" name="type" id="typeScene" value="scene" <?php echo (!isset($hotspot) || $hotspot['type'] == 'scene') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="typeScene">
                                                <i class="bi bi-arrow-right-circle me-1"></i>Sahne Geçişi
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline me-4">
                                            <input class="form-check-input" type="radio" name="type" id="typeInfo" value="info" <?php echo (isset($hotspot) && $hotspot['type'] == 'info') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="typeInfo">
                                                <i class="bi bi-info-circle me-1"></i>Bilgi
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type" id="typeLink" value="link" <?php echo (isset($hotspot) && $hotspot['type'] == 'link') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="typeLink">
                                                <i class="bi bi-link-45deg me-1"></i>Dış Bağlantı
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="text" class="form-label fw-medium">Hotspot Metni <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="text" name="text" required
                                           value="<?php echo isset($hotspot) ? htmlspecialchars($hotspot['text']) : ''; ?>"
                                           placeholder="Burada görünecek metin">
                                    <div class="form-text">Hotspot üzerine gelindiğinde görünecek açıklama metni.</div>
                                </div>
                                
                                <div class="scene-target-group mb-4">
                                    <label for="target_scene_id" class="form-label fw-medium">Hedef Sahne</label>
                                    <select class="form-select" id="target_scene_id" name="target_scene_id">
                                        <option value="">Sahne Seçin</option>
                                        <?php foreach ($target_scenes as $scene): ?>
                                        <option value="<?php echo $scene['scene_id']; ?>" <?php echo (isset($hotspot) && $hotspot['target_scene_id'] == $scene['scene_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($scene['title']); ?> (<?php echo $scene['id']; ?>)
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="form-text">Hotspot'a tıklandığında gidilecek sahne.</div>
                                </div>
                                
                                <div class="link-target-group mb-4" style="display: none;">
                                    <label for="link_url" class="form-label fw-medium">Hedef URL</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                                        <input type="url" class="form-control" id="link_url" name="link_url"
                                               value="<?php echo isset($hotspot) && $hotspot['type'] == 'link' ? htmlspecialchars($hotspot['target_scene_id']) : ''; ?>"
                                               placeholder="https:
                                    </div>
                                    <div class="form-text">Hotspot'a tıklandığında açılacak URL.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0"><i class="bi bi-target me-2"></i>Hedef Görünüm (Opsiyonel)</h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info d-flex mb-4" role="alert">
                                    <i class="bi bi-info-circle-fill me-2 flex-shrink-0"></i>
                                    <div>
                                        Hedef sahnede görünümün hangi açıyla başlayacağını belirleyebilirsiniz. Boş bırakırsanız varsayılan görünüm kullanılır.
                                    </div>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="target_pitch" class="form-label fw-medium">Hedef Pitch</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-arrow-up-down"></i></span>
                                            <input type="number" class="form-control" id="target_pitch" name="target_pitch" step="0.01"
                                                   value="<?php echo isset($hotspot) && $hotspot['target_pitch'] !== null ? $hotspot['target_pitch'] : ''; ?>"
                                                   placeholder="Opsiyonel">
                                        </div>
                                        <div class="form-text">Hedef sahnede bakış açısı (dikey).</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="target_yaw" class="form-label fw-medium">Hedef Yaw</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-arrow-left-right"></i></span>
                                            <input type="number" class="form-control" id="target_yaw" name="target_yaw" step="0.01"
                                                   value="<?php echo isset($hotspot) && $hotspot['target_yaw'] !== null ? $hotspot['target_yaw'] : ''; ?>"
                                                   placeholder="Opsiyonel">
                                        </div>
                                        <div class="form-text">Hedef sahnede bakış açısı (yatay).</div>
                                    </div>
                                </div>
                                
                                <div class="text-center my-4 py-3">
                                    <div class="hotspot-preview">
                                        <div class="hotspot-icon mb-2">
                                            <i class="bi bi-cursor-fill display-4 text-primary"></i>
                                        </div>
                                        <h6 class="mb-1" id="previewText">Hotspot Önizleme</h6>
                                        <small class="d-block text-muted" id="previewType">Sahne Geçişi</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='admin.php?page=hotspots&scene_id=<?php echo $selected_scene_id; ?>'">
                        <i class="bi bi-x-lg me-1"></i> İptal
                    </button>
                    <div>
                        <button type="reset" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Sıfırla
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> <?php echo $action == 'add' ? 'Hotspot Ekle' : 'Değişiklikleri Kaydet'; ?>
                        </button>
                    </div>
                </div>
            </form>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    
                    document.getElementById('panoramaPreview').insertAdjacentHTML('beforeend', `
                        <button id="capturePosition" class="position-btn capture-btn">
                            <i class="bi bi-geo-alt-fill me-1"></i> Bu Konumu Kullan
                        </button>
                        <div id="positionInfo" class="position-info">
                            <i class="bi bi-crosshair me-1"></i> Pitch: 0.00, Yaw: 0.00
                        </div>
                        <div class="crosshair">
                            <div class="crosshair-h"></div>
                            <div class="crosshair-v"></div>
                        </div>
                    `);
                    
                    
                    try {
                        const imageUrl = "<?php echo $scene_info ? htmlspecialchars($scene_info['image_path']) : ''; ?>";
                        console.log("Panorama resmi: ", imageUrl);

                        const viewer = pannellum.viewer('panoramaPreview', {
                            type: 'equirectangular',
                            panorama: imageUrl,
                            autoLoad: true,
                            showControls: true,
                            showFullscreenCtrl: false,
                            hotSpotDebug: true,
                            hotSpots: [] 
                        });

                        <?php foreach ($hotspots as $hotspot): ?>
                        viewer.addHotSpot({
                            id: '<?php echo $hotspot['id']; ?>',
                            pitch: <?php echo $hotspot['pitch']; ?>,
                            yaw: <?php echo $hotspot['yaw']; ?>,
                            type: '<?php echo $hotspot['type']; ?>',
                            text: '<?php echo addslashes($hotspot['text']); ?>'
                        });
                        <?php endforeach; ?>
                    } catch (error) {
                        console.error("Pannellum başlatma hatası: ", error);
                        document.getElementById('panoramaPreview').innerHTML = 
                            '<div class="alert alert-danger d-flex align-items-center" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i><div>Panorama görüntüleyici başlatılamadı: ' + error.message + '</div></div>';
                    }
                    
                    
                    document.getElementById('capturePosition').addEventListener('click', function() {
                        
                        const position = viewer.getPosition();
                        const pitch = position.pitch;
                        const yaw = position.yaw;
                        
                        
                        document.getElementById('pitch').value = pitch.toFixed(2);
                        document.getElementById('yaw').value = yaw.toFixed(2);
                        
                        
                        viewer.removeHotSpot('current-hotspot');
                        viewer.addHotSpot({
                            id: 'current-hotspot',
                            pitch: pitch,
                            yaw: yaw,
                            type: 'info',
                            text: document.getElementById('text').value || 'Yeni Hotspot'
                        });
                        
                        
                        Toastify({
                            text: `Konum başarıyla kaydedildi: Pitch ${pitch.toFixed(2)}, Yaw ${yaw.toFixed(2)}`,
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#4caf50",
                            stopOnFocus: true,
                        }).showToast();
                    });
                    
                    
                    const typeScene = document.getElementById('typeScene');
                    const typeInfo = document.getElementById('typeInfo');
                    const typeLink = document.getElementById('typeLink');
                    const sceneTargetGroup = document.querySelector('.scene-target-group');
                    const linkTargetGroup = document.querySelector('.link-target-group');
                    const previewText = document.getElementById('previewText');
                    const previewType = document.getElementById('previewType');
                    const textInput = document.getElementById('text');
                    
                    function updateHotspotType() {
                        if (typeScene.checked) {
                            sceneTargetGroup.style.display = 'block';
                            linkTargetGroup.style.display = 'none';
                            previewType.textContent = 'Sahne Geçişi';
                            document.querySelector('.hotspot-icon i').className = 'bi bi-arrow-right-circle-fill display-4 text-primary';
                        } else if (typeLink.checked) {
                            sceneTargetGroup.style.display = 'none';
                            linkTargetGroup.style.display = 'block';
                            previewType.textContent = 'Dış Bağlantı';
                            document.querySelector('.hotspot-icon i').className = 'bi bi-link-45deg display-4 text-success';
                        } else {
                            sceneTargetGroup.style.display = 'none';
                            linkTargetGroup.style.display = 'none';
                            previewType.textContent = 'Bilgi';
                            document.querySelector('.hotspot-icon i').className = 'bi bi-info-circle-fill display-4 text-info';
                        }
                    }
                    
                    [typeScene, typeInfo, typeLink].forEach(el => {
                        el.addEventListener('change', updateHotspotType);
                    });
                    
                    
                    textInput.addEventListener('input', function() {
                        previewText.textContent = this.value || 'Hotspot Önizleme';
                    });
                    
                    
                    updateHotspotType();
                    
                    
                    previewText.textContent = textInput.value || 'Hotspot Önizleme';
                    
                    
                    const form = document.querySelector('.needs-validation');
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            </script>
            <style>
                .position-btn {
                    position: absolute;
                    bottom: 15px;
                    right: 15px;
                    z-index: 100;
                    background: var(--primary-color);
                    color: white;
                    border: none;
                    padding: 8px 15px;
                    border-radius: var(--border-radius);
                    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                    font-weight: 500;
                    transition: all 0.3s ease;
                }
                
                .position-btn:hover {
                    background: var(--primary-dark);
                    transform: translateY(-2px);
                    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
                }
                
                .position-info {
                    position: absolute;
                    bottom: 15px;
                    left: 15px;
                    z-index: 100;
                    background: rgba(0, 0, 0, 0.7);
                    color: white;
                    padding: 8px 12px;
                    border-radius: var(--border-radius);
                    font-size: 0.875rem;
                }
                
                .crosshair {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    z-index: 90;
                    pointer-events: none;
                }
                
                .crosshair-h, .crosshair-v {
                    background: rgba(255, 0, 0, 0.7);
                    position: absolute;
                }
                
                .crosshair-h {
                    width: 20px;
                    height: 2px;
                    top: 0;
                    left: -10px;
                }
                
                .crosshair-v {
                    width: 2px;
                    height: 20px;
                    top: -10px;
                    left: 0;
                }
                
                .hotspot-preview {
                    padding: 20px;
                    border: 1px dashed #dee2e6;
                    border-radius: var(--border-radius);
                    background-color: #f8f9fa;
                }
                
                .form-check-inline {
                    margin-right: 1.5rem;
                }
                
                .form-check-input:checked {
                    background-color: var(--primary-color);
                    border-color: var(--primary-color);
                }
            </style>
        <?php endif; ?>
    </div>
    <?php
} else {
    
    ?>
    <div class="content-box fade-in">
        <div class="content-header mb-4">
            <h5 class="content-title">Hotspot Yönetimi</h5>
            <div class="content-actions">
                <?php if ($selected_scene_id > 0): ?>
                    <a href="admin.php?page=hotspots&action=add&scene_id=<?php echo $selected_scene_id; ?>" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Yeni Hotspot Ekle
                    </a>
                <?php else: ?>
                    <button type="button" class="btn btn-primary" disabled>
                        <i class="bi bi-plus-lg me-1"></i> Yeni Hotspot Ekle
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="bi bi-filter me-2"></i>Sahne Seçimi</h6>
            </div>
            <div class="card-body">
                <form action="admin.php" method="get" class="row g-3">
                    <input type="hidden" name="page" value="hotspots">
                    
                    <div class="col-md-5">
                        <label for="campus_id" class="form-label fw-medium">Kampüs</label>
                        <select class="form-select" id="campus_id" name="campus_id" onchange="this.form.submit()">
                            <?php foreach ($campuses as $campus): ?>
                                <option value="<?php echo $campus['id']; ?>" <?php echo $selected_campus_id == $campus['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($campus['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-7">
                        <label for="scene_id" class="form-label fw-medium">Sahne</label>
                        <select class="form-select <?php echo empty($scenes) ? 'is-invalid' : ''; ?>" id="scene_id" name="scene_id" onchange="this.form.submit()" <?php echo empty($scenes) ? 'disabled' : ''; ?>>
                            <?php if (empty($scenes)): ?>
                                <option value="">Önce kampüs seçin veya kampüse sahne ekleyin</option>
                            <?php else: ?>
                                <?php foreach ($scenes as $scene): ?>
                                    <option value="<?php echo $scene['id']; ?>" <?php echo $selected_scene_id == $scene['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($scene['title']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (empty($scenes)): ?>
                            <div class="invalid-feedback">Bu kampüs için henüz sahne bulunmuyor. Önce bir sahne eklemelisiniz.</div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
        
        <?php if ($scene_info): ?>
            
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-image text-primary me-2"></i>
                            Sahne Önizleme: <span class="fw-light"><?php echo htmlspecialchars($scene_info['title']); ?></span>
                        </h5>
                        <a href="admin.php?page=scenes&action=edit&id=<?php echo $scene_info['id']; ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i>Sahneyi Düzenle
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="panoramaPreview" class="panorama-container" style="height: 450px;"></div>
                </div>
                <div class="card-footer bg-white border-top-0 p-3">
                    <div class="d-flex align-items-center text-muted">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Panoramada hareket etmek için fare veya dokunmatik ekranınızı kullanın. Tüm hotspotlar panoramada işaretlenmiştir.</small>
                    </div>
                </div>
            </div>
            
            
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-cursor text-primary me-2"></i>Hotspot Listesi</h5>
                        <?php if ($selected_scene_id > 0): ?>
                            <a href="admin.php?page=hotspots&action=add&scene_id=<?php echo $selected_scene_id; ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Yeni Hotspot Ekle
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($hotspots)): ?>
                        <div class="text-center py-5">
                            <img src="assets/images/empty-state.svg" alt="Veri Yok" class="img-fluid mb-3" style="max-width: 200px;">
                            <h5>Bu sahne için henüz hotspot eklenmemiş</h5>
                            <p class="text-muted">Sahneyi 360° olarak zenginleştirmek için hotspotlar ekleyebilirsiniz.</p>
                            <a href="admin.php?page=hotspots&action=add&scene_id=<?php echo $selected_scene_id; ?>" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Yeni Hotspot Ekle
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%" class="rounded-start">ID</th>
                                        <th width="10%">Tip</th>
                                        <th width="20%">Metin</th>
                                        <th width="15%">Konum</th>
                                        <th>Hedef</th>
                                        <th width="15%" class="text-end rounded-end">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($hotspots as $hotspot): ?>
                                        <tr>
                                            <td class="align-middle"><?php echo $hotspot['id']; ?></td>
                                            <td class="align-middle">
                                                <?php
                                                switch ($hotspot['type']) {
                                                    case 'scene':
                                                        echo '<span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                                                <i class="bi bi-arrow-right-circle-fill me-1"></i>Sahne
                                                              </span>';
                                                        break;
                                                    case 'info':
                                                        echo '<span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">
                                                                <i class="bi bi-info-circle-fill me-1"></i>Bilgi
                                                              </span>';
                                                        break;
                                                    case 'link':
                                                        echo '<span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                                                <i class="bi bi-link-45deg me-1"></i>Link
                                                              </span>';
                                                        break;
                                                    default:
                                                        echo '<span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                                                                <i class="bi bi-question-circle-fill me-1"></i>Diğer
                                                              </span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="align-middle"><?php echo htmlspecialchars($hotspot['text']); ?></td>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-light text-dark me-1">P: <?php echo $hotspot['pitch']; ?></span>
                                                    <span class="badge bg-light text-dark">Y: <?php echo $hotspot['yaw']; ?></span>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <?php
                                                if ($hotspot['type'] == 'scene') {
                                                    echo isset($hotspot['target_scene_title']) 
                                                        ? '<span class="d-inline-block text-truncate" style="max-width: 250px;">' . htmlspecialchars($hotspot['target_scene_title']) . '</span>' 
                                                        : '<span class="text-muted">'.htmlspecialchars($hotspot['target_scene_id']).'</span>';
                                                } elseif ($hotspot['type'] == 'link') {
                                                    echo '<a href="' . htmlspecialchars($hotspot['target_scene_id']) . '" target="_blank" class="d-inline-block text-truncate" style="max-width: 250px;">' 
                                                        . htmlspecialchars($hotspot['target_scene_id']) . '</a>';
                                                } else {
                                                    echo '<span class="text-muted">-</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="align-middle text-end">
                                                <div class="d-flex justify-content-end">
                                                    <a href="admin.php?page=hotspots&action=edit&id=<?php echo $hotspot['id']; ?>" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="tooltip" title="Düzenle">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                                        data-id="<?php echo $hotspot['id']; ?>" data-title="<?php echo htmlspecialchars($hotspot['text']); ?>" data-bs-toggle="tooltip" title="Sil">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($hotspots)): ?>
                    <div class="card-footer bg-white border-top-0 p-3">
                    <div class="d-flex justify-content-between align-items-center">
    <div class="text-muted small">
        <span class="fw-medium"><?php echo count($hotspots); ?></span> hotspot listeleniyor
    </div>
    <div>
        <button class="btn btn-sm btn-outline-primary me-2" data-export="excel" data-filename="hotspotlar">
            <i class="bi bi-file-earmark-excel me-1"></i> Excel'e Aktar
        </button>
        <button class="btn btn-sm btn-outline-primary" data-print="table">
            <i class="bi bi-printer me-1"></i> Yazdır
        </button>
    </div>
</div>
                    </div>
                <?php endif; ?>
            </div>
            
            
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Hotspot Silme Onayı</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="text-center mb-4">
                                <div class="avatar avatar-lg bg-danger-subtle text-danger rounded-circle mb-3">
                                    <i class="bi bi-trash display-6"></i>
                                </div>
                                <h5 class="mb-1" id="hotspotTitle"></h5>
                                <p class="text-muted mb-0">hotspotunu silmek üzeresiniz.</p>
                            </div>
                            
                            <div class="alert alert-warning d-flex" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2 flex-shrink-0"></i>
                                <div>
                                    <strong>Uyarı:</strong> Bu işlem geri alınamaz ve hotspot kalıcı olarak silinecektir.
                                </div>
                            </div>
                            
                            <p class="text-center mb-0">Bu hotspotu silmek istediğinizden emin misiniz?</p>
                        </div>
                        <div class="modal-footer border-top-0">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg me-1"></i> İptal
                            </button>
                            <a href="#" id="deleteLink" class="btn btn-danger">
                                <i class="bi bi-trash me-1"></i> Evet, Sil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    
                    const panoramaContainer = document.getElementById('panoramaPreview');
                    
                    try {
                        const imageUrl = "<?php echo htmlspecialchars($scene_info['image_path']); ?>";
                        console.log("Panorama resmi: ", imageUrl);
                        
                        
                        const viewer = pannellum.viewer(panoramaContainer, {
                            type: 'equirectangular',
                            panorama: imageUrl,
                            autoLoad: true,
                            compass: false,
                            showFullscreenCtrl: true,
                            hotSpotDebug: false,
                            hotSpots: [] 
                        });
                        
                        
                        <?php foreach ($hotspots as $hotspot): ?>
                        viewer.addHotSpot({
                            id: '<?php echo $hotspot['id']; ?>',
                            pitch: <?php echo $hotspot['pitch']; ?>,
                            yaw: <?php echo $hotspot['yaw']; ?>,
                            type: '<?php echo $hotspot['type']; ?>',
                            text: '<?php echo addslashes($hotspot['text']); ?>'
                        });
                        <?php endforeach; ?>
                        
                        console.log("Pannellum başarıyla başlatıldı");
                    } catch (error) {
                        console.error("Pannellum başlatma hatası: ", error);
                        panoramaContainer.innerHTML = '<div class="alert alert-danger d-flex align-items-center m-3" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i><div>Panorama görüntüleyici başlatılamadı: ' + error.message + '</div></div>';
                    }
                    
                    
                    const deleteModal = document.getElementById('deleteModal');
                    if (deleteModal) {
                        deleteModal.addEventListener('show.bs.modal', function(event) {
                            const button = event.relatedTarget;
                            const id = button.getAttribute('data-id');
                            const title = button.getAttribute('data-title');
                            
                            const hotspotTitleSpan = document.getElementById('hotspotTitle');
                            const deleteLink = document.getElementById('deleteLink');
                            
                            hotspotTitleSpan.textContent = title;
                            deleteLink.href = `admin/process_hotspot.php?action=delete&id=${id}&csrf_token=<?php echo $csrf_token; ?>`;
                        });
                    }
                    
                    
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                });
            </script>
            <style>
                .bg-primary-subtle {
                    background-color: rgba(21, 101, 192, 0.1);
                }
                
                .bg-info-subtle {
                    background-color: rgba(3, 169, 244, 0.1);
                }
                
                .bg-success-subtle {
                    background-color: rgba(76, 175, 80, 0.1);
                }
                
                .bg-secondary-subtle {
                    background-color: rgba(108, 117, 125, 0.1);
                }
                
                .bg-danger-subtle {
                    background-color: rgba(220, 53, 69, 0.1);
                }
                
                .avatar {
                    width: 80px;
                    height: 80px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto;
                }
                
                .panorama-container {
                    border-radius: var(--border-radius);
                    overflow: hidden;
                }
                
                th.rounded-start {
                    border-top-left-radius: 8px;
                    border-bottom-left-radius: 8px;
                }
                
                th.rounded-end {
                    border-top-right-radius: 8px;
                    border-bottom-right-radius: 8px;
                }
            </style>
        <?php else: ?>
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill me-2 flex-shrink-0"></i>
                <div>
                    Lütfen önce bir kampüs ve sahne seçin veya henüz yoksa yeni bir sahne ekleyin.
                </div>
            </div>
            
            <div class="text-center py-5">
                <img src="assets/images/select-scene.svg" alt="Sahne Seçin" class="img-fluid mb-3" style="max-width: 300px;">
                <h5>Hotspot Eklemek İçin Sahne Seçin</h5>
                <p class="text-muted">Yukarıdaki dropdown menüden bir kampüs ve sahne seçin.</p>
                <a href="admin.php?page=scenes&action=add" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Yeni Sahne Ekle
                </a>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
?>