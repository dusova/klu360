<?php



$action = isset($_GET['action']) ? sanitizeInput($_GET['action']) : 'list';


$campuses = listCampuses($conn, 'active');


$selected_campus_id = isset($_GET['campus_id']) ? intval($_GET['campus_id']) : (count($campuses) > 0 ? $campuses[0]['id'] : 0);

if ($action == 'add' || $action == 'edit') {
    
    $scene_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $scene = null;
    
    if ($action == 'edit' && $scene_id > 0) {
        $query = "SELECT * FROM scenes WHERE id = ?";
        $params = [$scene_id];
        $result = executeQuery($conn, $query, $params);
        
        if (!empty($result)) {
            $scene = $result[0];
            $selected_campus_id = $scene['campus_id'];
        } else {
            
            header("Location: admin.php?page=scenes&notification=not_found&type=error");
            exit;
        }
    }
    
    
    $campus_map = '';
    foreach ($campuses as $campus) {
        if ($campus['id'] == $selected_campus_id) {
            $campus_map = $campus['map_image'];
            break;
        }
    }
    ?>
    <div class="content-box fade-in">
        <div class="content-header">
            <h5 class="content-title">
                <?php if ($action == 'add'): ?>
                    <i class="bi bi-plus-circle-fill text-primary me-2"></i>Yeni Sahne Ekle
                <?php else: ?>
                    <i class="bi bi-pencil-fill text-primary me-2"></i>Sahneyi Düzenle: <?php echo htmlspecialchars($scene['title']); ?>
                <?php endif; ?>
            </h5>
            <div class="content-actions">
                <a href="admin.php?page=scenes" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Listeye Dön
                </a>
            </div>
        </div>
        
        <form action="admin/process_scene.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="action" value="<?php echo $action; ?>">
            <input type="hidden" name="id" value="<?php echo $scene_id; ?>">
            
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0"><i class="bi bi-info-circle me-2"></i>Temel Bilgiler</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="campus_id" class="form-label fw-medium">Kampüs <span class="text-danger">*</span></label>
                                <select class="form-select" id="campus_id" name="campus_id" required>
                                    <?php foreach ($campuses as $campus): ?>
                                    <option value="<?php echo $campus['id']; ?>" <?php echo ($selected_campus_id == $campus['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($campus['name']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="title" class="form-label fw-medium">Sahne Başlığı <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" required
                                       value="<?php echo isset($scene) ? htmlspecialchars($scene['title']) : ''; ?>"
                                       placeholder="Sahne için başlık girin">
                                <div class="invalid-feedback">Lütfen sahne başlığını girin.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="scene_id" class="form-label fw-medium">Sahne ID</label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted"><i class="bi bi-hash"></i></span>
                                    <input type="text" class="form-control" id="scene_id" name="scene_id"
                                           value="<?php echo isset($scene) ? htmlspecialchars($scene['scene_id']) : ''; ?>"
                                           placeholder="Boş bırakırsanız başlıktan otomatik oluşturulur"
                                           <?php echo ($action == 'edit') ? 'readonly' : ''; ?>>
                                </div>
                                <div class="form-text">Sahne için benzersiz ID. Sistem içinde kullanılır.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label fw-medium">Açıklama</label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                          placeholder="Sahne hakkında açıklama girin"><?php echo isset($scene) ? htmlspecialchars($scene['description']) : ''; ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="display_order" class="form-label fw-medium">Görüntülenme Sırası</label>
                                        <input type="number" class="form-control" id="display_order" name="display_order" min="0"
                                               value="<?php echo isset($scene) ? (int)$scene['display_order'] : '0'; ?>">
                                        <div class="form-text">Küçük değerler önce gösterilir</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label fw-medium">Durum</label>
                                        <div class="form-check form-switch form-switch-lg">
                                            <input class="form-check-input" type="checkbox" id="statusSwitch" <?php echo (!isset($scene) || $scene['status'] == 'active') ? 'checked' : ''; ?>>
                                            <input type="hidden" name="status" id="statusValue" value="<?php echo (isset($scene)) ? $scene['status'] : 'active'; ?>">
                                            <label class="form-check-label" for="statusSwitch" id="statusLabel">
                                                <?php echo (!isset($scene) || $scene['status'] == 'active') ? 'Aktif' : 'Pasif'; ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0"><i class="bi bi-image me-2"></i>360° Panorama Görüntüsü</h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <?php if(isset($scene) && !empty($scene['image_path'])): ?>
                                    <div class="position-relative mb-3">
                                        <img src="<?php echo htmlspecialchars($scene['thumbnail_path']); ?>" alt="Panorama Önizleme" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                    </div>
                                <?php else: ?>
                                    <div class="image-placeholder mb-3">
                                        <i class="bi bi-image-fill text-muted"></i>
                                        <p class="text-muted mb-0">Henüz görsel yüklenmemiş</p>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="file-upload-wrapper">
                                    <div class="custom-file-upload" id="dropZone">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                        <h6 class="mt-2">360° Panorama Görseli</h6>
                                        <p class="text-muted mb-0">Equirectangular formatında</p>
                                        <button type="button" class="btn btn-primary mt-2" id="browseBtn">Dosya Seç</button>
                                        <input type="file" class="d-none" id="panorama_image" name="panorama_image" accept="image/*" <?php echo ($action == 'add') ? 'required' : ''; ?>>
                                        <small class="d-block mt-2 text-muted">JPG, JPEG formatında. Maks 10MB.</small>
                                    </div>
                                    <div id="filePreview" class="mt-3 d-none">
                                        <div class="d-flex align-items-center">
                                            <div class="file-icon me-2">
                                                <i class="bi bi-file-earmark-image"></i>
                                            </div>
                                            <div class="file-info flex-grow-1">
                                                <p class="file-name mb-0"></p>
                                                <small class="text-muted file-size"></small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger" id="removeFile">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0"><i class="bi bi-geo-alt me-2"></i>Haritadaki Konumu</h6>
                        </div>
                        <div class="card-body">
                            <?php if(!empty($campus_map)): ?>
                                <div class="position-relative mb-3 campus-map-container" style="height: 250px; overflow: hidden; border-radius: 8px; border: 1px solid #e0e0e0;">
                                    <img src="<?php echo htmlspecialchars($campus_map); ?>" alt="Kampüs Haritası" class="img-fluid w-100 campus-map">
                                    
                                    <?php if(isset($scene) && $scene['map_x'] !== null && $scene['map_y'] !== null): ?>
                                        <div class="position-absolute map-marker" id="mapMarker" style="left: <?php echo $scene['map_x']; ?>%; top: <?php echo $scene['map_y']; ?>%;">
                                            <i class="bi bi-geo-alt-fill text-danger"></i>
                                        </div>
                                    <?php else: ?>
                                        <div class="position-absolute map-marker d-none" id="mapMarker">
                                            <i class="bi bi-geo-alt-fill text-danger"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="alert alert-info d-flex align-items-center small mb-3" role="alert">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    <div>Harita üzerinde tıklayarak sahnenin konumunu belirleyin.</div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-1">
                                            <label for="map_x" class="form-label small fw-medium">X Koordinatı (%)</label>
                                            <input type="number" class="form-control form-control-sm" id="map_x" name="map_x" step="0.01" min="0" max="100"
                                                   value="<?php echo isset($scene) && $scene['map_x'] !== null ? $scene['map_x'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-1">
                                            <label for="map_y" class="form-label small fw-medium">Y Koordinatı (%)</label>
                                            <input type="number" class="form-control form-control-sm" id="map_y" name="map_y" step="0.01" min="0" max="100"
                                                   value="<?php echo isset($scene) && $scene['map_y'] !== null ? $scene['map_y'] : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <div>Seçilen kampüs için harita resmi bulunamadı. Önce kampüs ayarlarından harita ekleyin.</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='admin.php?page=scenes'">
                    <i class="bi bi-x-lg me-1"></i> İptal
                </button>
                <div>
                    <button type="reset" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Sıfırla
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> <?php echo $action == 'add' ? 'Sahne Ekle' : 'Değişiklikleri Kaydet'; ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            const statusSwitch = document.getElementById('statusSwitch');
            const statusValue = document.getElementById('statusValue');
            const statusLabel = document.getElementById('statusLabel');
            
            statusSwitch.addEventListener('change', function() {
                if (this.checked) {
                    statusValue.value = 'active';
                    statusLabel.textContent = 'Aktif';
                } else {
                    statusValue.value = 'inactive';
                    statusLabel.textContent = 'Pasif';
                }
            });
            
            
            const titleInput = document.getElementById('title');
            const sceneIdInput = document.getElementById('scene_id');
            
            <?php if ($action != 'edit'): ?>
            titleInput.addEventListener('blur', function() {
                if (sceneIdInput.value === '') {
                    
                    let sceneId = this.value.toLowerCase()
                        .replace(/ı/g, 'i')
                        .replace(/ğ/g, 'g')
                        .replace(/ü/g, 'u')
                        .replace(/ş/g, 's')
                        .replace(/ö/g, 'o')
                        .replace(/ç/g, 'c')
                        .replace(/İ/g, 'i')
                        .replace(/Ğ/g, 'g')
                        .replace(/Ü/g, 'u')
                        .replace(/Ş/g, 's')
                        .replace(/Ö/g, 'o')
                        .replace(/Ç/g, 'c');
                    
                    
                    sceneId = sceneId.replace(/[^a-z0-9\s-]/g, '');
                    
                    
                    sceneId = sceneId.replace(/[\s-]+/g, '-');
                    
                    
                    sceneId = sceneId.trim('-');
                    
                    
                    sceneId = sceneId + '-' + Math.floor(Date.now() / 1000);
                    
                    sceneIdInput.value = sceneId;
                }
            });
            <?php endif; ?>
            
            
            const mapContainer = document.querySelector('.campus-map-container');
            const mapMarker = document.getElementById('mapMarker');
            const mapXInput = document.getElementById('map_x');
            const mapYInput = document.getElementById('map_y');
            
            if (mapContainer && mapMarker) {
                mapContainer.addEventListener('click', function(e) {
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    
                    const xPercent = (x / this.offsetWidth) * 100;
                    const yPercent = (y / this.offsetHeight) * 100;
                    
                    
                    mapMarker.style.left = xPercent + '%';
                    mapMarker.style.top = yPercent + '%';
                    mapMarker.classList.remove('d-none');
                    
                    
                    mapXInput.value = xPercent.toFixed(2);
                    mapYInput.value = yPercent.toFixed(2);
                });
            }
            
            
            const campusSelect = document.getElementById('campus_id');
            campusSelect.addEventListener('change', function() {
                const campusId = this.value;
                window.location.href = 'admin.php?page=scenes&action=<?php echo $action; ?>&campus_id=' + campusId + '<?php echo ($scene_id > 0) ? '&id=' . $scene_id : ''; ?>';
            });
            
            
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('panorama_image');
            const browseBtn = document.getElementById('browseBtn');
            const filePreview = document.getElementById('filePreview');
            const fileName = document.querySelector('.file-name');
            const fileSize = document.querySelector('.file-size');
            const removeFileBtn = document.getElementById('removeFile');
            
            if (browseBtn && fileInput) {
                browseBtn.addEventListener('click', function() {
                    fileInput.click();
                });
            }
            
            
            if (dropZone) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, preventDefaults, false);
                });
                
                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                
                ['dragenter', 'dragover'].forEach(eventName => {
                    dropZone.addEventListener(eventName, highlight, false);
                });
                
                ['dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, unhighlight, false);
                });
                
                function highlight() {
                    dropZone.classList.add('highlight');
                }
                
                function unhighlight() {
                    dropZone.classList.remove('highlight');
                }
                
                
                dropZone.addEventListener('drop', function(e) {
                    const dt = e.dataTransfer;
                    fileInput.files = dt.files;
                    updateFilePreview();
                });
            }
            
            
            if (fileInput && filePreview) {
                fileInput.addEventListener('change', updateFilePreview);
                
                
                if (removeFileBtn) {
                    removeFileBtn.addEventListener('click', function() {
                        fileInput.value = '';
                        filePreview.classList.add('d-none');
                        if (dropZone) {
                            dropZone.classList.remove('d-none');
                        }
                    });
                }
                
                function updateFilePreview() {
                    if (fileInput.files && fileInput.files[0]) {
                        const file = fileInput.files[0];
                        
                        
                        if (fileName) fileName.textContent = file.name;
                        if (fileSize) fileSize.textContent = formatBytes(file.size);
                        
                        filePreview.classList.remove('d-none');
                        if (dropZone) {
                            dropZone.classList.add('d-none');
                        }
                    }
                }
                
                function formatBytes(bytes, decimals = 2) {
                    if (bytes === 0) return '0 Bytes';
                    
                    const k = 1024;
                    const dm = decimals < 0 ? 0 : decimals;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                    
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
                }
            }
            
            
            const form = document.querySelector('.needs-validation');
            if (form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            }
        });
    </script>
    <style>
        .form-switch-lg .form-check-input {
            width: 3em;
            height: 1.5em;
            margin-top: 0.1em;
        }
        
        .image-placeholder {
            border: 2px dashed #ced4da;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
        }
        
        .image-placeholder i {
            font-size: 3rem;
            color: #ced4da;
        }
        
        .custom-file-upload {
            border: 2px dashed #ced4da;
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .custom-file-upload.highlight {
            border-color: #1565c0;
            background-color: rgba(21, 101, 192, 0.05);
        }
        
        .custom-file-upload i {
            font-size: 2.5rem;
            color: #6c757d;
        }
        
        .file-icon i {
            font-size: 2rem;
            color: #1565c0;
        }
        
        .map-marker {
            transform: translate(-50%, -100%);
            z-index: 10;
        }
        
        .map-marker i {
            font-size: 1.5rem;
            filter: drop-shadow(0 0 2px rgba(0,0,0,0.5));
        }
    </style>
    <?php
} else {
    
    
    $scenes = [];
    if ($selected_campus_id > 0) {
        $scenes = listScenes($conn, $selected_campus_id);
    }
    ?>
    <div class="content-box fade-in">
        <div class="content-header mb-4">
            <h5 class="content-title">Sahne Yönetimi</h5>
            <div class="content-actions">
                <a href="admin.php?page=scenes&action=add&campus_id=<?php echo $selected_campus_id; ?>" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Yeni Sahne Ekle
                </a>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <label for="campusSelector" class="form-label fw-medium">Kampüs Seçin</label>
                <select class="form-select" id="campusSelector" name="campus_id" onchange="selectCampus(this.value)">
                    <?php foreach ($campuses as $campus): ?>
                    <option value="<?php echo $campus['id']; ?>" <?php echo $selected_campus_id == $campus['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($campus['name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0"><i class="bi bi-image me-2 text-primary"></i>Sahne Listesi</h5>
                    </div>
                    <div class="col-auto">
                    <div class="dropdown">
    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="sceneFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-funnel me-1"></i> Filtrele
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sceneFilterDropdown">
        <li><a class="dropdown-item active" href="#" data-filter-type="status" data-filter-value="all">Tümünü Göster</a></li>
        <li><a class="dropdown-item" href="#" data-filter-type="status" data-filter-value="Aktif">Sadece Aktif</a></li>
        <li><a class="dropdown-item" href="#" data-filter-type="status" data-filter-value="Pasif">Sadece Pasif</a></li>
        </ul>
</div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($scenes)): ?>
                    <div class="text-center py-5">
                        <img src="assets/images/empty-state.svg" alt="Veri Yok" class="img-fluid mb-3" style="max-width: 200px;">
                        <h5>Bu kampüs için henüz sahne eklenmemiş</h5>
                        <p class="text-muted">Yeni bir sahne eklemek için "Yeni Sahne Ekle" butonunu kullanabilirsiniz.</p>
                        <a href="admin.php?page=scenes&action=add&campus_id=<?php echo $selected_campus_id; ?>" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i> Sahne Ekle
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%" class="rounded-start">ID</th>
                                    <th width="15%">Önizleme</th>
                                    <th>Sahne Bilgileri</th>
                                    <th width="10%">Sıra</th>
                                    <th width="10%">Durum</th>
                                    <th width="15%" class="text-end rounded-end">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($scenes as $scene): ?>
                                <tr>
                                    <td class="align-middle"><?php echo $scene['id']; ?></td>
                                    <td class="align-middle">
                                        <?php if (!empty($scene['thumbnail_path'])): ?>
                                            <img src="<?php echo htmlspecialchars($scene['thumbnail_path']); ?>" alt="<?php echo htmlspecialchars($scene['title']); ?>" class="img-thumbnail" width="100">
                                        <?php else: ?>
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 100px; height: 60px;">
                                                <i class="bi bi-image text-secondary"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($scene['title']); ?></h6>
                                        <div>
                                            <small class="text-muted me-2">ID: <code><?php echo htmlspecialchars($scene['scene_id']); ?></code></small>
                                            <?php if (!empty($scene['description'])): ?>
                                                <br><small class="text-muted"><?php echo mb_substr(htmlspecialchars($scene['description']), 0, 60); ?><?php echo (mb_strlen($scene['description']) > 60) ? '...' : ''; ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge bg-secondary"><?php echo $scene['display_order']; ?></span>
                                    </td>
                                    <td class="align-middle">
                                        <?php if ($scene['status'] == 'active'): ?>
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                                <i class="bi bi-check-circle-fill me-1"></i>Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                                                <i class="bi bi-x-circle-fill me-1"></i>Pasif
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle text-end">
                                        <div class="d-flex justify-content-end">
                                            <a href="admin.php?page=scenes&action=edit&id=<?php echo $scene['id']; ?>" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="tooltip" title="Düzenle">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="admin.php?page=hotspots&scene_id=<?php echo $scene['id']; ?>" class="btn btn-sm btn-outline-success me-1" data-bs-toggle="tooltip" title="Hotspotları Düzenle">
                                                <i class="bi bi-cursor"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                                data-id="<?php echo $scene['id']; ?>" data-title="<?php echo htmlspecialchars($scene['title']); ?>" data-bs-toggle="tooltip" title="Sil">
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
            <?php if (!empty($scenes)): ?>
            <div class="card-footer bg-white border-top-0 p-3">
            <div class="d-flex justify-content-between align-items-center">
    <div class="text-muted small">
        <span class="fw-medium"><?php echo count($scenes); ?></span> sahne listeleniyor
    </div>
    <div>
        <button class="btn btn-sm btn-outline-primary me-2" data-export="excel" data-filename="sahneler">
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
    </div>
    
    
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Sahne Silme Onayı</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="avatar avatar-lg bg-danger-subtle text-danger rounded-circle mb-3">
                            <i class="bi bi-trash display-6"></i>
                        </div>
                        <h5 class="mb-1" id="sceneTitle"></h5>
                        <p class="text-muted mb-0">sahnesini silmek üzeresiniz.</p>
                    </div>
                    
                    <div class="alert alert-warning d-flex" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 flex-shrink-0"></i>
                        <div>
                            <strong>Uyarı:</strong> Bu işlem geri alınamaz ve sahneye ait tüm hotspotlar da silinecektir.
                        </div>
                    </div>
                    
                    <p class="text-center mb-0">Bu sahneyi silmek istediğinizden emin misiniz?</p>
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
        
        function selectCampus(campusId) {
            window.location.href = 'admin.php?page=scenes&campus_id=' + campusId;
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            
            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const title = button.getAttribute('data-title');
                    
                    const sceneTitleSpan = document.getElementById('sceneTitle');
                    const deleteLink = document.getElementById('deleteLink');
                    
                    sceneTitleSpan.textContent = title;
                    deleteLink.href = `admin/process_scene.php?action=delete&id=${id}&csrf_token=<?php echo $csrf_token; ?>`;
                });
            }
            
            
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            
            document.querySelectorAll('#sceneFilterDropdown .dropdown-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    
                    document.querySelectorAll('#sceneFilterDropdown .dropdown-item').forEach(el => {
                        el.classList.remove('active');
                    });
                    this.classList.add('active');
                    
                    
                    document.getElementById('sceneFilterDropdown').textContent = this.textContent;
                });
            });
        });
    </script>
    <style>
        .avatar {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
        
        .badge {
            font-weight: 500;
        }
        
        .bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.1);
        }
        
        .bg-secondary-subtle {
            background-color: rgba(108, 117, 125, 0.1);
        }
        
        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.1);
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
    <?php
}
?>