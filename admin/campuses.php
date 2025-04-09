<?php



$action = isset($_GET['action']) ? sanitizeInput($_GET['action']) : 'list';


$campuses = listCampuses($conn);

if ($action == 'add' || $action == 'edit') {
    
    $campus_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $campus = null;
    
    if ($action == 'edit' && $campus_id > 0) {
        $query = "SELECT * FROM campuses WHERE id = ?";
        $params = [$campus_id];
        $result = executeQuery($conn, $query, $params);
        
        if (!empty($result)) {
            $campus = $result[0];
        } else {
            
            header("Location: admin.php?page=campuses&notification=not_found&type=error");
            exit;
        }
    }
    ?>
    <div class="content-box fade-in">
        <div class="content-header mb-4">
            <h5 class="content-title">
                <?php if ($action == 'add'): ?>
                    <i class="bi bi-plus-circle-fill text-primary me-2"></i>Yeni Kampüs Ekle
                <?php else: ?>
                    <i class="bi bi-pencil-fill text-primary me-2"></i>Kampüs Düzenle: <?php echo htmlspecialchars($campus['name']); ?>
                <?php endif; ?>
            </h5>
            <div class="content-actions">
                <a href="admin.php?page=campuses" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Listeye Dön
                </a>
            </div>
        </div>
        
        <form action="admin/process_campus.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="action" value="<?php echo $action; ?>">
            <input type="hidden" name="id" value="<?php echo $campus_id; ?>">
            
            <div class="row g-4">
                
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0"><i class="bi bi-info-circle me-2"></i>Temel Bilgiler</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="name" class="form-label fw-medium">Kampüs Adı <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" id="name" name="name" required
                                       value="<?php echo isset($campus) ? htmlspecialchars($campus['name']) : ''; ?>"
                                       placeholder="Kampüs adını girin">
                                <div class="invalid-feedback">Lütfen kampüs adını girin.</div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="slug" class="form-label fw-medium">Slug (URL)</label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted"><i class="bi bi-link-45deg"></i></span>
                                    <input type="text" class="form-control" id="slug" name="slug"
                                           value="<?php echo isset($campus) ? htmlspecialchars($campus['slug']) : ''; ?>"
                                           placeholder="Boş bırakırsanız otomatik oluşturulur">
                                </div>
                                <div class="form-text">URL'de kullanılacak kısa ad. Örn: kayali-kampusu</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label fw-medium">Açıklama</label>
                                <textarea class="form-control" id="description" name="description" rows="4" 
                                          placeholder="Kampüs hakkında kısa bir açıklama girin"><?php echo isset($campus) ? htmlspecialchars($campus['description']) : ''; ?></textarea>
                            </div>
                            
                            <div class="mb-0">
                                <label for="status" class="form-label fw-medium">Durum</label>
                                <div class="d-flex">
                                    <div class="form-check form-switch form-switch-lg me-3">
                                        <input class="form-check-input" type="checkbox" id="statusSwitch" <?php echo (!isset($campus) || $campus['status'] == 'active') ? 'checked' : ''; ?>>
                                        <input type="hidden" name="status" id="statusValue" value="<?php echo (isset($campus)) ? $campus['status'] : 'active'; ?>">
                                        <label class="form-check-label" for="statusSwitch" id="statusLabel">
                                            <?php echo (!isset($campus) || $campus['status'] == 'active') ? 'Aktif' : 'Pasif'; ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0"><i class="bi bi-image me-2"></i>Kampüs Görseli</h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <?php if(isset($campus) && !empty($campus['map_image'])): ?>
                                    <div class="position-relative mb-3 image-preview-container">
                                        <img src="<?php echo htmlspecialchars($campus['map_image']); ?>" alt="Kampüs Haritası" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                        <div class="image-actions">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="removeImage" name="remove_image" value="1">
                                                <label class="form-check-label text-white" for="removeImage">
                                                    Görseli kaldır
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="image-placeholder mb-3">
                                        <i class="bi bi-image text-muted"></i>
                                        <p class="text-muted mb-0">Henüz görsel yüklenmemiş</p>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="file-upload-wrapper">
                                    <div class="custom-file-upload" id="dropZone">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                        <h6 class="mt-2">Görseli buraya sürükleyin</h6>
                                        <p class="text-muted mb-0">veya</p>
                                        <button type="button" class="btn btn-primary mt-2" id="browseBtn">Dosya Seç</button>
                                        <input type="file" class="d-none" id="map_image" name="map_image" accept="image/*" <?php echo ($action == 'add') ? 'required' : ''; ?>>
                                        <small class="d-block mt-2 text-muted">PNG, JPG veya JPEG. Maks 2MB.</small>
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
                            
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <div>
                                    Kampüs haritası, sanal tur içinde yönlendirme ve navigasyon için kullanılacaktır. 
                                    Mümkünse kampüsün üstten görünümünü içeren bir görsel yükleyin.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='admin.php?page=campuses'">
                    <i class="bi bi-x-lg me-1"></i> İptal
                </button>
                <div>
                    <button type="reset" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Sıfırla
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> <?php echo $action == 'add' ? 'Kampüs Ekle' : 'Değişiklikleri Kaydet'; ?>
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
            
            
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');
            
            nameInput.addEventListener('blur', function() {
                if (slugInput.value === '') {
                    
                    let slug = this.value.toLowerCase()
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
                    
                    
                    slug = slug.replace(/[^a-z0-9\s-]/g, '');
                    
                    
                    slug = slug.replace(/[\s-]+/g, '-');
                    
                    
                    slug = slug.trim('-');
                    
                    slugInput.value = slug;
                }
            });
            
            
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('map_image');
            const browseBtn = document.getElementById('browseBtn');
            const filePreview = document.getElementById('filePreview');
            const fileName = document.querySelector('.file-name');
            const fileSize = document.querySelector('.file-size');
            const removeFileBtn = document.getElementById('removeFile');
            
            browseBtn.addEventListener('click', function() {
                fileInput.click();
            });
            
            
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
            
            
            fileInput.addEventListener('change', updateFilePreview);
            
            
            removeFileBtn.addEventListener('click', function() {
                fileInput.value = '';
                filePreview.classList.add('d-none');
                dropZone.classList.remove('d-none');
            });
            
            function updateFilePreview() {
                if (fileInput.files && fileInput.files[0]) {
                    const file = fileInput.files[0];
                    
                    
                    fileName.textContent = file.name;
                    fileSize.textContent = formatBytes(file.size);
                    
                    filePreview.classList.remove('d-none');
                    dropZone.classList.add('d-none');
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
        .form-switch-lg .form-check-input {
            width: 3em;
            height: 1.5em;
            margin-top: 0.1em;
        }
        
        .image-preview-container {
            overflow: hidden;
            border-radius: 8px;
        }
        
        .image-actions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.6);
            padding: 8px;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }
        
        .image-preview-container:hover .image-actions {
            transform: translateY(0);
        }
        
        .image-placeholder {
            border: 2px dashed #ced4da;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
        }
        
        .image-placeholder i {
            font-size: 3rem;
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
        
        .form-control-lg {
            height: calc(1.5em + 1.25rem + 2px);
        }
    </style>
    <?php
} else {
    
    ?>
    <div class="content-box fade-in">
        <div class="content-header mb-4">
            <h5 class="content-title">Kampüs Yönetimi</h5>
            <div class="content-actions">
                <a href="admin.php?page=campuses&action=add" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Yeni Kampüs Ekle
                </a>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0"><i class="bi bi-building me-2 text-primary"></i>Kampüs Listesi</h5>
                    </div>
                    <div class="col-auto">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="campusFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-funnel me-1"></i> Filtrele
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="campusFilterDropdown">
                                <li><a class="dropdown-item active" href="#">Tümünü Göster</a></li>
                                <li><a class="dropdown-item" href="#">Sadece Aktif</a></li>
                                <li><a class="dropdown-item" href="#">Sadece Pasif</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Sıfırla</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($campuses)): ?>
                    <div class="text-center py-5">
                        <img src="assets/images/empty-state.svg" alt="Veri Yok" class="img-fluid mb-3" style="max-width: 200px;">
                        <h5>Henüz kampüs eklenmemiş</h5>
                        <p class="text-muted">Yeni bir kampüs eklemek için yukarıdaki "Yeni Kampüs Ekle" butonunu kullanabilirsiniz.</p>
                        <a href="admin.php?page=campuses&action=add" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i> Kampüs Ekle
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover data-table mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%" class="rounded-start">ID</th>
                                    <th>Kampüs Adı</th>
                                    <th width="15%">Slug</th>
                                    <th width="10%">Durum</th>
                                    <th width="15%">Oluşturma Tarihi</th>
                                    <th width="15%" class="text-end rounded-end">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($campuses as $campus): ?>
                                <tr>
                                    <td class="align-middle"><?php echo $campus['id']; ?></td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($campus['map_image'])): ?>
                                                <img src="<?php echo htmlspecialchars($campus['map_image']); ?>" alt="<?php echo htmlspecialchars($campus['name']); ?>" class="rounded me-3" width="50" height="35">
                                            <?php else: ?>
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 35px;">
                                                    <i class="bi bi-building text-secondary"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-0"><?php echo htmlspecialchars($campus['name']); ?></h6>
                                                <?php if (!empty($campus['description'])): ?>
                                                    <small class="text-muted"><?php echo mb_substr(htmlspecialchars($campus['description']), 0, 60); ?><?php echo (mb_strlen($campus['description']) > 60) ? '...' : ''; ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle"><code><?php echo htmlspecialchars($campus['slug']); ?></code></td>
                                    <td class="align-middle">
                                        <?php if ($campus['status'] == 'active'): ?>
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                                <i class="bi bi-check-circle-fill me-1"></i>Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                                                <i class="bi bi-x-circle-fill me-1"></i>Pasif
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle">
                                        <i class="bi bi-calendar3 text-muted me-1"></i>
                                        <?php echo date('d.m.Y', strtotime($campus['created_at'])); ?>
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-clock text-muted me-1"></i>
                                            <?php echo date('H:i', strtotime($campus['created_at'])); ?>
                                        </small>
                                    </td>
                                    <td class="align-middle text-end">
                                        <div class="d-flex justify-content-end">
                                            <a href="admin.php?page=scenes&campus_id=<?php echo $campus['id']; ?>" class="btn btn-sm btn-outline-info me-1" data-bs-toggle="tooltip" title="Sahneleri Görüntüle">
                                                <i class="bi bi-image"></i>
                                            </a>
                                            <a href="admin.php?page=campuses&action=edit&id=<?php echo $campus['id']; ?>" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="tooltip" title="Düzenle">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                                data-id="<?php echo $campus['id']; ?>" data-title="<?php echo htmlspecialchars($campus['name']); ?>" data-bs-toggle="tooltip" title="Sil">
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
            <?php if (!empty($campuses)): ?>
            <div class="card-footer bg-white border-top-0 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <span class="fw-medium"><?php echo count($campuses); ?></span> kampüs listeleniyor
                    </div>
                    <div>
                        <button class="btn btn-sm btn-outline-primary me-2">
                            <i class="bi bi-file-earmark-excel me-1"></i> Excel'e Aktar
                        </button>
                        <button class="btn btn-sm btn-outline-primary">
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
                    <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Kampüs Silme Onayı</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="avatar avatar-lg bg-danger-subtle text-danger rounded-circle mb-3">
                            <i class="bi bi-trash display-6"></i>
                        </div>
                        <h5 class="mb-1" id="campusTitle"></h5>
                        <p class="text-muted mb-0">kampüsünü silmek üzeresiniz.</p>
                    </div>
                    
                    <div class="alert alert-warning d-flex" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 flex-shrink-0"></i>
                        <div>
                            <strong>Uyarı:</strong> Bu işlem geri alınamaz ve kampüse ait tüm sahneler ve hotspotlar da silinecektir.
                        </div>
                    </div>
                    
                    <p class="text-center mb-0">Bu kampüsü silmek istediğinizden emin misiniz?</p>
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
            
            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const title = button.getAttribute('data-title');
                    
                    const campusTitleSpan = document.getElementById('campusTitle');
                    const deleteLink = document.getElementById('deleteLink');
                    
                    campusTitleSpan.textContent = title;
                    deleteLink.href = `admin/process_campus.php?action=delete&id=${id}&csrf_token=<?php echo $csrf_token; ?>`;
                });
            }
            
            
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            
            document.querySelectorAll('#campusFilterDropdown .dropdown-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    
                    document.querySelectorAll('#campusFilterDropdown .dropdown-item').forEach(el => {
                        el.classList.remove('active');
                    });
                    this.classList.add('active');
                    
                    
                    document.getElementById('campusFilterDropdown').textContent = this.textContent;
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