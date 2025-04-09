<?php




if ($_SESSION['user_role'] != 'admin') {
    header("Location: admin.php?notification=permission_denied&type=error");
    exit;
}


$action = isset($_GET['action']) ? sanitizeInput($_GET['action']) : 'list';

if ($action == 'add' || $action == 'edit') {
    
    $user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $user = null;
    
    if ($action == 'edit' && $user_id > 0) {
        $query = "SELECT * FROM users WHERE id = ?";
        $params = [$user_id];
        $result = executeQuery($conn, $query, $params);
        
        if (!empty($result)) {
            $user = $result[0];
        } else {
            
            header("Location: admin.php?page=users&notification=not_found&type=error");
            exit;
        }
    }
    ?>
    <div class="content-box fade-in">
        <div class="content-header">
            <h5 class="content-title">
                <?php if ($action == 'add'): ?>
                    <i class="bi bi-person-plus-fill text-primary me-2"></i>Yeni Kullanıcı Ekle
                <?php else: ?>
                    <i class="bi bi-person-gear text-primary me-2"></i>Kullanıcıyı Düzenle: <?php echo htmlspecialchars($user['name']); ?>
                <?php endif; ?>
            </h5>
            <div class="content-actions">
                <a href="admin.php?page=users" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Listeye Dön
                </a>
            </div>
        </div>
        
        <form action="admin/process_user.php" method="post" class="needs-validation" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="action" value="<?php echo $action; ?>">
            <input type="hidden" name="id" value="<?php echo $user_id; ?>">
            
            <div class="row g-4">
                
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0"><i class="bi bi-person me-2"></i>Kişisel Bilgiler</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="name" class="form-label fw-medium">Ad Soyad <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                    <input type="text" class="form-control" id="name" name="name" required
                                           value="<?php echo isset($user) ? htmlspecialchars($user['name']) : ''; ?>"
                                           placeholder="Kullanıcı adı ve soyadı">
                                    <div class="invalid-feedback">Lütfen ad soyad girin.</div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="form-label fw-medium">E-posta <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" required
                                           value="<?php echo isset($user) ? htmlspecialchars($user['email']) : ''; ?>"
                                           placeholder="kullanici@example.com">
                                    <div class="invalid-feedback">Lütfen geçerli bir e-posta adresi girin.</div>
                                </div>
                            </div>
                            
                            <div class="mb-0">
                                <label for="password" class="form-label fw-medium">
                                    <?php echo $action == 'add' ? 'Şifre <span class="text-danger">*</span>' : 'Yeni Şifre <small class="fw-normal text-muted">(Opsiyonel)</small>'; ?>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           <?php echo $action == 'add' ? 'required' : ''; ?>
                                           placeholder="<?php echo $action == 'add' ? 'Şifre girin' : 'Değiştirmek için yeni şifre girin'; ?>">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <div class="invalid-feedback">
                                        <?php echo $action == 'add' ? 'Lütfen şifre girin.' : ''; ?>
                                    </div>
                                </div>
                                <div class="form-text mt-2">
                                    <?php if ($action == 'add'): ?>
                                        <i class="bi bi-info-circle me-1"></i> Şifre en az 8 karakter uzunluğunda olmalı ve büyük harf, küçük harf, rakam içermelidir.
                                    <?php else: ?>
                                        <i class="bi bi-info-circle me-1"></i> Şifreyi değiştirmek istemiyorsanız bu alanı boş bırakın.
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0"><i class="bi bi-gear me-2"></i>Kullanıcı Ayarları</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="role" class="form-label fw-medium">Kullanıcı Rolü <span class="text-danger">*</span></label>
                                <div class="role-selector mb-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role" id="roleAdmin" value="admin" 
                                               <?php echo (isset($user) && $user['role'] == 'admin') || (!isset($user)) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="roleAdmin">
                                            <span class="badge bg-danger me-1"><i class="bi bi-shield-lock"></i></span>Admin
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role" id="roleEditor" value="editor" 
                                               <?php echo (isset($user) && $user['role'] == 'editor') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="roleEditor">
                                            <span class="badge bg-warning text-dark me-1"><i class="bi bi-pencil-square"></i></span>Editör
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="role" id="roleUser" value="user" 
                                               <?php echo (isset($user) && $user['role'] == 'user') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="roleUser">
                                            <span class="badge bg-info text-dark me-1"><i class="bi bi-person"></i></span>Kullanıcı
                                        </label>
                                    </div>
                                </div>
                                <div class="role-descriptions p-3 bg-light rounded">
                                    <div id="adminRoleDesc" <?php echo ((isset($user) && $user['role'] != 'admin') && isset($user)) ? 'style="display:none"' : ''; ?>>
                                        <h6 class="mb-2 text-danger"><i class="bi bi-shield-lock me-1"></i>Admin</h6>
                                        <p class="mb-0 text-muted">Tüm sisteme tam erişim, kullanıcı yönetimi, ayarlar ve diğer tüm özellikleri kullanabilir.</p>
                                    </div>
                                    <div id="editorRoleDesc" <?php echo ((isset($user) && $user['role'] != 'editor')) ? 'style="display:none"' : ''; ?>>
                                        <h6 class="mb-2 text-warning"><i class="bi bi-pencil-square me-1"></i>Editör</h6>
                                        <p class="mb-0 text-muted">İçerik ekleme ve düzenleme, sahneleri ve hotspotları yönetebilir ama kullanıcı yönetimine erişemez.</p>
                                    </div>
                                    <div id="userRoleDesc" <?php echo ((isset($user) && $user['role'] != 'user')) ? 'style="display:none"' : ''; ?>>
                                        <h6 class="mb-2 text-info"><i class="bi bi-person me-1"></i>Kullanıcı</h6>
                                        <p class="mb-0 text-muted">Sadece görüntüleme yapabilir, sisteme içerik ekleyemez veya düzenleyemez.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="status" class="form-label fw-medium">Hesap Durumu</label>
                                <div class="form-check form-switch form-switch-lg">
                                    <input class="form-check-input" type="checkbox" id="statusSwitch" <?php echo (!isset($user) || $user['status'] == 'active') ? 'checked' : ''; ?>>
                                    <input type="hidden" name="status" id="statusValue" value="<?php echo (isset($user)) ? $user['status'] : 'active'; ?>">
                                    <label class="form-check-label" for="statusSwitch" id="statusLabel">
                                        <?php echo (!isset($user) || $user['status'] == 'active') ? 'Aktif' : 'Pasif'; ?>
                                    </label>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i> Pasif durumdaki kullanıcılar sisteme giriş yapamazlar.
                                </div>
                            </div>
                            
                            <?php if (isset($user) && $user['last_login']): ?>
                                <div class="user-info mt-4">
                                    <h6 class="fw-medium"><i class="bi bi-clock-history me-2"></i>Kullanıcı Bilgileri</h6>
                                    <div class="row g-3 text-muted">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar3 me-2"></i>
                                                <div>
                                                    <small>Kayıt Tarihi</small>
                                                    <div><?php echo date('d.m.Y', strtotime($user['created_at'])); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                                <div>
                                                    <small>Son Giriş</small>
                                                    <div><?php echo date('d.m.Y H:i', strtotime($user['last_login'])); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='admin.php?page=users'">
                    <i class="bi bi-x-lg me-1"></i> İptal
                </button>
                <div>
                    <button type="reset" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Sıfırla
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> <?php echo $action == 'add' ? 'Kullanıcı Ekle' : 'Değişiklikleri Kaydet'; ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').className = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
            });
            
            
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
            
            
            const roleAdmin = document.getElementById('roleAdmin');
            const roleEditor = document.getElementById('roleEditor');
            const roleUser = document.getElementById('roleUser');
            
            const adminRoleDesc = document.getElementById('adminRoleDesc');
            const editorRoleDesc = document.getElementById('editorRoleDesc');
            const userRoleDesc = document.getElementById('userRoleDesc');
            
            function updateRoleDescription() {
                adminRoleDesc.style.display = roleAdmin.checked ? 'block' : 'none';
                editorRoleDesc.style.display = roleEditor.checked ? 'block' : 'none';
                userRoleDesc.style.display = roleUser.checked ? 'block' : 'none';
            }
            
            roleAdmin.addEventListener('change', updateRoleDescription);
            roleEditor.addEventListener('change', updateRoleDescription);
            roleUser.addEventListener('change', updateRoleDescription);
            
            
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
        
        .form-check-inline {
            margin-right: 1.5rem;
        }
        
        .role-descriptions {
            border-left: 3px solid #dee2e6;
        }
        
        .role-descriptions h6 {
            font-weight: 600;
        }
    </style>
    <?php
} else {
    
    $users = listUsers($conn);
    ?>
    <div class="content-box fade-in">
        <div class="content-header mb-4">
            <h5 class="content-title">Kullanıcı Yönetimi</h5>
            <div class="content-actions">
                <a href="admin.php?page=users&action=add" class="btn btn-primary">
                    <i class="bi bi-person-plus me-1"></i> Yeni Kullanıcı Ekle
                </a>
            </div>
        </div>
        
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0"><i class="bi bi-people text-primary me-2"></i>Kullanıcı Listesi</h5>
                    </div>
                    <div class="col-auto">
                    <div class="dropdown">
    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="userFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-funnel me-1"></i> Filtrele
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userFilterDropdown">
        <li><a class="dropdown-item active" href="#" data-filter-type="role" data-filter-value="all">Tümünü Göster</a></li>
        <li><a class="dropdown-item" href="#" data-filter-type="role" data-filter-value="Admin">Adminler</a></li>
        <li><a class="dropdown-item" href="#" data-filter-type="role" data-filter-value="Editör">Editörler</a></li>
        <li><a class="dropdown-item" href="#" data-filter-type="role" data-filter-value="Kullanıcı">Kullanıcılar</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#" data-filter-type="status" data-filter-value="Aktif">Aktif Kullanıcılar</a></li>
        <li><a class="dropdown-item" href="#" data-filter-type="status" data-filter-value="Pasif">Pasif Kullanıcılar</a></li>
    </ul>
</div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($users)): ?>
                    <div class="text-center py-5">
                        <img src="assets/images/empty-state.svg" alt="Veri Yok" class="img-fluid mb-3" style="max-width: 200px;">
                        <h5>Henüz kullanıcı eklenmemiş</h5>
                        <p class="text-muted">Yeni kullanıcı eklemek için "Yeni Kullanıcı Ekle" butonunu kullanabilirsiniz.</p>
                        <a href="admin.php?page=users&action=add" class="btn btn-primary">
                            <i class="bi bi-person-plus me-1"></i> Kullanıcı Ekle
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%" class="rounded-start">ID</th>
                                    <th width="20%">Kullanıcı</th>
                                    <th width="15%">E-posta</th>
                                    <th width="10%">Rol</th>
                                    <th width="10%">Durum</th>
                                    <th width="20%">Son Aktivite</th>
                                    <th width="15%" class="text-end rounded-end">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td class="align-middle"><?php echo $user['id']; ?></td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-3 rounded-circle bg-<?php echo getRoleColor($user['role']); ?>-subtle text-<?php echo getRoleColor($user['role']); ?> d-flex align-items-center justify-content-center">
                                                <i class="bi bi-person"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?php echo htmlspecialchars($user['name']); ?></h6>
                                                <small class="text-muted">ID: <?php echo $user['id']; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="align-middle">
                                        <?php
                                        switch ($user['role']) {
                                            case 'admin':
                                                echo '<span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                                                    <i class="bi bi-shield-lock me-1"></i>Admin
                                                </span>';
                                                break;
                                            case 'editor':
                                                echo '<span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                                                    <i class="bi bi-pencil-square me-1"></i>Editör
                                                </span>';
                                                break;
                                            default:
                                                echo '<span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">
                                                    <i class="bi bi-person me-1"></i>Kullanıcı
                                                </span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="align-middle">
                                        <?php
                                        switch ($user['status']) {
                                            case 'active':
                                                echo '<span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                                    <i class="bi bi-check-circle-fill me-1"></i>Aktif
                                                </span>';
                                                break;
                                            case 'inactive':
                                                echo '<span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">
                                                    <i class="bi bi-x-circle-fill me-1"></i>Pasif
                                                </span>';
                                                break;
                                            case 'pending':
                                                echo '<span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                                                    <i class="bi bi-clock-fill me-1"></i>Beklemede
                                                </span>';
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td class="align-middle">
                                        <?php if ($user['last_login']): ?>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-clock-history text-muted me-2"></i>
                                                <div>
                                                    <div class="small"><?php echo date('d.m.Y', strtotime($user['last_login'])); ?></div>
                                                    <small class="text-muted"><?php echo date('H:i', strtotime($user['last_login'])); ?></small>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted">Henüz giriş yapılmadı</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle text-end">
                                        <div class="d-flex justify-content-end">
                                            <a href="admin.php?page=users&action=edit&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="tooltip" title="Düzenle">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <?php if ($user['id'] != $_SESSION['user_id']): 
                                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                                    data-id="<?php echo $user['id']; ?>" data-name="<?php echo htmlspecialchars($user['name']); ?>" data-bs-toggle="tooltip" title="Sil">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled data-bs-toggle="tooltip" title="Aktif kullanıcı silinemez">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($users)): ?>
                <div class="card-footer bg-white border-top-0 p-3">
                <div class="d-flex justify-content-between align-items-center">
    <div class="text-muted small">
        <span class="fw-medium"><?php echo count($users); ?></span> kullanıcı listeleniyor
    </div>
    <div>
        <button class="btn btn-sm btn-outline-primary me-2" data-export="excel" data-filename="kullanicilar">
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
                    <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Kullanıcı Silme Onayı</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="avatar avatar-lg bg-danger-subtle text-danger rounded-circle mb-3">
                            <i class="bi bi-person-x display-6"></i>
                        </div>
                        <h5 class="mb-1" id="userName"></h5>
                        <p class="text-muted mb-0">kullanıcısını silmek üzeresiniz.</p>
                    </div>
                    
                    <div class="alert alert-warning d-flex" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 flex-shrink-0"></i>
                        <div>
                            <strong>Uyarı:</strong> Bu işlem geri alınamaz ve kullanıcıya ait tüm veriler silinecektir.
                        </div>
                    </div>
                    
                    <p class="text-center mb-0">Bu kullanıcıyı silmek istediğinizden emin misiniz?</p>
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
                    const name = button.getAttribute('data-name');
                    
                    const userNameSpan = document.getElementById('userName');
                    const deleteLink = document.getElementById('deleteLink');
                    
                    userNameSpan.textContent = name;
                    deleteLink.href = `admin/process_user.php?action=delete&id=${id}&csrf_token=<?php echo $csrf_token; ?>`;
                });
            }
            
            
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            
            document.querySelectorAll('#userFilterDropdown .dropdown-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    
                    document.querySelectorAll('#userFilterDropdown .dropdown-item').forEach(el => {
                        el.classList.remove('active');
                    });
                    this.classList.add('active');
                    
                    
                    document.getElementById('userFilterDropdown').textContent = this.textContent;
                });
            });
        });
    </script>
    <style>
        .user-avatar {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }
        
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
        
        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.1);
        }
        
        .bg-warning-subtle {
            background-color: rgba(255, 193, 7, 0.1);
        }
        
        .bg-info-subtle {
            background-color: rgba(13, 202, 240, 0.1);
        }
        
        .bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.1);
        }
        
        .bg-secondary-subtle {
            background-color: rgba(108, 117, 125, 0.1);
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


function getRoleColor($role) {
    switch ($role) {
        case 'admin':
            return 'danger';
        case 'editor':
            return 'warning';
        default:
            return 'info';
    }
}
?>