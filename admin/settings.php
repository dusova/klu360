<?php


if ($_SESSION['user_role'] != 'admin') {
    header("Location: admin.php?notification=permission_denied&type=error");
    exit;
}

$query = "SELECT * FROM settings";
$settings = executeQuery($conn, $query);


$settingsArray = [];
foreach ($settings as $setting) {
    $settingsArray[$setting['key']] = $setting['value'];
}


function getSetting($key, $default = '') {
    global $settingsArray;
    return isset($settingsArray[$key]) ? $settingsArray[$key] : $default;
}
?>
<div class="content-box fade-in">
    <div class="content-header">
        <h5 class="content-title">
            <i class="bi bi-gear-fill text-primary me-2"></i>Sistem Ayarları
        </h5>
        <div class="content-actions">
            <button type="button" class="btn btn-outline-primary" id="restoreDefaults">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Varsayılanlara Sıfırla
            </button>
        </div>
    </div>

    <form action="admin/process_settings.php" method="post" class="settings-form needs-validation" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

        <div class="row g-4">

            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <div class="d-flex align-items-center">
                            <span class="settings-icon bg-primary-subtle text-primary me-2">
                                <i class="bi bi-sliders"></i>
                            </span>
                            <h6 class="card-title mb-0">Genel Ayarlar</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="site_title" class="form-label fw-medium">Site Başlığı</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-type-h1"></i></span>
                                <input type="text" class="form-control" id="site_title" name="settings[site_title]"
                                      value="<?php echo htmlspecialchars(getSetting('site_title', 'Kırklareli Üniversitesi 360° Sanal Tur')); ?>"
                                      required>
                            </div>
                            <div class="form-text">Site başlığı sekme başlığında ve üst kısımda görünür.</div>
                        </div>

                        <div class="mb-4">
                            <label for="welcome_text" class="form-label fw-medium">Karşılama Metni</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-chat-left-text"></i></span>
                                <textarea class="form-control" id="welcome_text" name="settings[welcome_text]" rows="3"><?php echo htmlspecialchars(getSetting('welcome_text')); ?></textarea>
                            </div>
                            <div class="form-text">Ana sayfada ziyaretçileri karşılayan metin.</div>
                        </div>

                        <div class="mb-4">
                            <label for="footer_text" class="form-label fw-medium">Footer Metni</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-layout-text-window"></i></span>
                                <input type="text" class="form-control" id="footer_text" name="settings[footer_text]"
                                      value="<?php echo htmlspecialchars(getSetting('footer_text', '© ' . date('Y') . ' Kırklareli Üniversitesi. Tüm hakları saklıdır.')); ?>">
                            </div>
                            <div class="form-text">Sayfanın alt kısmında gösterilecek metin.</div>
                        </div>

                        <div class="mb-3">
                            <label for="default_campus" class="form-label fw-medium">Varsayılan Kampüs</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                <select class="form-select" id="default_campus" name="settings[default_campus]">
                                    <?php
                                    $campuses = listCampuses($conn, 'active');
                                    foreach ($campuses as $campus):
                                    ?>
                                    <option value="<?php echo htmlspecialchars($campus['slug']); ?>" <?php echo getSetting('default_campus') == $campus['slug'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($campus['name']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-text">Sanal tur başlatıldığında varsayılan olarak gösterilecek kampüs.</div>
                        </div>
                    </div>
                </div>


                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <div class="d-flex align-items-center">
                            <span class="settings-icon bg-success-subtle text-success me-2">
                                <i class="bi bi-tags"></i>
                            </span>
                            <h6 class="card-title mb-0">SEO ve Meta Bilgileri</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="meta_description" class="form-label fw-medium">Meta Açıklama</label>
                            <textarea class="form-control" id="meta_description" name="settings[meta_description]" rows="2"><?php echo htmlspecialchars(getSetting('meta_description')); ?></textarea>
                            <div class="form-text">Arama motorlarında görünecek site açıklaması. Max 160 karakter.</div>
                        </div>

                        <div class="mb-4">
                            <label for="meta_keywords" class="form-label fw-medium">Meta Anahtar Kelimeler</label>
                            <input type="text" class="form-control" id="meta_keywords" name="settings[meta_keywords]"
                                  value="<?php echo htmlspecialchars(getSetting('meta_keywords')); ?>"
                                  placeholder="sanal tur, 360 derece, kampüs, üniversite">
                            <div class="form-text">Anahtar kelimeleri virgülle ayırın. Örn: sanal tur, 360 derece, kampüs</div>
                        </div>

                        <div class="mb-0">
                            <label for="google_analytics" class="form-label fw-medium">Google Analytics Kodu</label>
                            <input type="text" class="form-control" id="google_analytics" name="settings[google_analytics]"
                                  value="<?php echo htmlspecialchars(getSetting('google_analytics')); ?>"
                                  placeholder="UA-XXXXXXXX-X veya G-XXXXXXXXXX">
                            <div class="form-text">Google Analytics izleme kodunuzu girin. Boş bırakırsanız Analytics kullanılmaz.</div>
                        </div>
                    </div>
                </div>


                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <div class="d-flex align-items-center">
                            <span class="settings-icon bg-info-subtle text-info me-2">
                                <i class="bi bi-people"></i>
                            </span>
                            <h6 class="card-title mb-0">Yapımcılar</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered creators-table" id="creatorsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ad Soyad</th>
                                        <th>Unvan/Görev</th>
                                        <th>Fotoğraf URL</th>
                                        <th width="80">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $creators = json_decode(getSetting('creators', '[]'), true);
                                    if (empty($creators)) {
                                        echo '<tr class="no-creators"><td colspan="4" class="text-center py-3 text-muted">Henüz yapımcı eklenmemiş</td></tr>';
                                    } else {
                                        foreach ($creators as $index => $creator):
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" name="creators[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($creator['name']); ?>" required>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="creators[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($creator['title']); ?>">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="creators[<?php echo $index; ?>][photo]" value="<?php echo htmlspecialchars($creator['photo']); ?>">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-creator" title="Sil">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                        endforeach;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-success" id="addCreator">
                            <i class="bi bi-plus-lg me-1"></i> Yapımcı Ekle
                        </button>
                    </div>
                </div>
            </div>


            <div class="col-lg-4">

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <div class="d-flex align-items-center">
                            <span class="settings-icon bg-danger-subtle text-danger me-2">
                                <i class="bi bi-shield-lock"></i>
                            </span>
                            <h6 class="card-title mb-0">Güvenlik Ayarları</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="setting-item">
                                <div class="form-check form-switch form-switch-lg">
                                    <input class="form-check-input" type="checkbox" id="maintenance_mode" name="settings[maintenance_mode]" value="1"
                                          <?php echo getSetting('maintenance_mode', '0') == '1' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="maintenance_mode">Bakım Modu</label>
                                </div>
                                <div class="form-text">Sayfayı bakım moduna alır. Sadece adminler erişebilir.</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="setting-item">
                                <div class="form-check form-switch form-switch-lg">
                                    <input class="form-check-input" type="checkbox" id="login_required" name="settings[login_required]" value="1"
                                          <?php echo getSetting('login_required', '0') == '1' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="login_required">Giriş Zorunluluğu</label>
                                </div>
                                <div class="form-text">Sanal turu görüntülemek için giriş yapılmasını zorunlu kılar.</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="max_login_attempts" class="form-label fw-medium">Maksimum Giriş Denemesi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-exclamation"></i></span>
                                <input type="number" class="form-control" id="max_login_attempts" name="settings[max_login_attempts]" min="1" max="10"
                                      value="<?php echo getSetting('max_login_attempts', '5'); ?>">
                            </div>
                            <div class="form-text">Başarısız giriş denemesinden sonra hesap 15 dakika kilitlenir.</div>
                        </div>

                        <div class="mb-4">
                            <label for="session_lifetime" class="form-label fw-medium">Oturum Süresi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-clock-history"></i></span>
                                <input type="number" class="form-control" id="session_lifetime" name="settings[session_lifetime]" min="300" step="300"
                                      value="<?php echo getSetting('session_lifetime', '7200'); ?>">
                                <span class="input-group-text">saniye</span>
                            </div>
                            <div class="form-text">Kullanıcı oturumunun aktif kalacağı süre. Varsayılan: 7200 (2 saat)</div>
                        </div>

                        <div class="mb-0 security-info">
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="bi bi-info-circle-fill me-2 flex-shrink-0"></i>
                                <div>
                                    <strong>Güvenlik İpucu:</strong> Hassas ayarları değiştirirken güvenlik etkileri düşünülmelidir.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <div class="d-flex align-items-center">
                            <span class="settings-icon bg-primary-subtle text-primary me-2">
                                <i class="bi bi-graph-up"></i>
                            </span>
                            <h6 class="card-title mb-0">İstatistik Ayarları</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="setting-item">
                                <div class="form-check form-switch form-switch-lg">
                                    <input class="form-check-input" type="checkbox" id="analytics_enabled" name="settings[analytics_enabled]" value="1"
                                          <?php echo getSetting('analytics_enabled', '1') == '1' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="analytics_enabled">İstatistik Toplama</label>
                                </div>
                                <div class="form-text">Ziyaretçi istatistiklerinin toplanmasını sağlar.</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="log_retention_days" class="form-label fw-medium">Log Saklama Süresi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                                <input type="number" class="form-control" id="log_retention_days" name="settings[log_retention_days]" min="1"
                                      value="<?php echo getSetting('log_retention_days', '30'); ?>">
                                <span class="input-group-text">gün</span>
                            </div>
                            <div class="form-text">Ziyaret ve aktivite kayıtlarının saklanacağı gün sayısı.</div>
                        </div>

                        <div class="mb-0">
                            <label for="visit_count_interval" class="form-label fw-medium">Ziyaret Sayma Aralığı</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-hourglass-split"></i></span>
                                <input type="number" class="form-control" id="visit_count_interval" name="settings[visit_count_interval]" min="1"
                                      value="<?php echo getSetting('visit_count_interval', '30'); ?>">
                                <span class="input-group-text">dakika</span>
                            </div>
                            <div class="form-text">Aynı IP'den yeni bir ziyaret sayılması için geçmesi gereken süre.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <div class="d-flex justify-content-between">
            <button type="reset" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Sıfırla
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Ayarları Kaydet
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        let creatorIndex = <?php echo max(count($creators), 0); ?>;

        document.getElementById('addCreator').addEventListener('click', function() {
            const tbody = document.querySelector('#creatorsTable tbody');
            const noCreatorsRow = document.querySelector('.no-creators');

            if (noCreatorsRow) {
                noCreatorsRow.remove();
            }

            const newRow = document.createElement('tr');

            newRow.innerHTML = `
                <td>
                    <input type="text" class="form-control" name="creators[${creatorIndex}][name]" required placeholder="Ad Soyad">
                </td>
                <td>
                    <input type="text" class="form-control" name="creators[${creatorIndex}][title]" placeholder="Unvan/Görev">
                </td>
                <td>
                    <input type="text" class="form-control" name="creators[${creatorIndex}][photo]" placeholder="Fotoğraf URL">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-creator" title="Sil">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;

            tbody.appendChild(newRow);
            creatorIndex++;


            newRow.classList.add('fade-in');
            setTimeout(() => newRow.classList.remove('fade-in'), 500);
        });


        document.querySelector('#creatorsTable').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-creator') || e.target.closest('.remove-creator')) {
                const button = e.target.classList.contains('remove-creator') ? e.target : e.target.closest('.remove-creator');
                const row = button.closest('tr');

                row.classList.add('fade-out');

                setTimeout(() => {
                    row.remove();


                    const remainingRows = document.querySelectorAll('#creatorsTable tbody tr').length;
                    if (remainingRows === 0) {
                        const tbody = document.querySelector('#creatorsTable tbody');
                        const noCreatorsRow = document.createElement('tr');
                        noCreatorsRow.classList.add('no-creators');
                        noCreatorsRow.innerHTML = '<td colspan="4" class="text-center py-3 text-muted">Henüz yapımcı eklenmemiş</td>';
                        tbody.appendChild(noCreatorsRow);
                    }
                }, 300);
            }
        });


        document.getElementById('restoreDefaults').addEventListener('click', function() {
            if (confirm('Tüm ayarları varsayılan değerlerine sıfırlamak istediğinizden emin misiniz?')) {

                document.getElementById('site_title').value = 'Kırklareli Üniversitesi 360° Sanal Tur';
                document.getElementById('welcome_text').value = 'Kırklareli Üniversitesi sanal tur uygulamasına hoş geldiniz. Kampüslerimizi 360° olarak keşfedin.';
                document.getElementById('footer_text').value = '© ' + new Date().getFullYear() + ' Kırklareli Üniversitesi. Tüm hakları saklıdır.';


                document.getElementById('maintenance_mode').checked = false;
                document.getElementById('login_required').checked = false;
                document.getElementById('max_login_attempts').value = '5';
                document.getElementById('session_lifetime').value = '7200';


                document.getElementById('analytics_enabled').checked = true;
                document.getElementById('log_retention_days').value = '30';
                document.getElementById('visit_count_interval').value = '30';


                document.getElementById('meta_description').value = 'Kırklareli Üniversitesi 360 derece sanal tur uygulaması ile kampüslerimizi keşfedin.';
                document.getElementById('meta_keywords').value = 'sanal tur, 360 derece, kampüs, üniversite, kırklareli';
                document.getElementById('google_analytics').value = '';

                alert('Ayarlar varsayılan değerlerine sıfırlandı. Değişiklikleri kaydetmek için "Ayarları Kaydet" butonuna tıklayın.');
            }
        });


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
    .settings-form .card {
        transition: all 0.3s ease;
    }

    .settings-form .card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08) !important;
    }

    .settings-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        border-radius: 6px;
    }

    .setting-item {
        padding: 10px;
        border-radius: 8px;
        background-color: #f8f9fa;
        transition: all 0.2s ease;
    }

    .setting-item:hover {
        background-color: #f0f0f0;
    }

    .form-switch-lg .form-check-input {
        width: 3em;
        height: 1.5em;
        margin-top: 0.1em;
    }

    .creators-table td {
        vertical-align: middle;
    }

    .fade-out {
        opacity: 0;
        transform: translateX(20px);
        transition: all 0.3s ease;
    }

    .fade-in {
        animation: fadeIn 0.5s;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }


    .bg-primary-subtle {
        background-color: rgba(21, 101, 192, 0.1);
    }

    .text-primary {
        color: #1565c0 !important;
    }

    .bg-success-subtle {
        background-color: rgba(76, 175, 80, 0.1);
    }

    .text-success {
        color: #4caf50 !important;
    }

    .bg-danger-subtle {
        background-color: rgba(244, 67, 54, 0.1);
    }

    .text-danger {
        color: #f44336 !important;
    }

    .bg-info-subtle {
        background-color: rgba(33, 150, 243, 0.1);
    }

    .text-info {
        color: #2196f3 !important;
    }
</style>
<?php
?>