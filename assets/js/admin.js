
document.addEventListener('DOMContentLoaded', function() {
    
    const sidebarToggleShow = document.getElementById('sidebarToggleShow');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminSidebar = document.querySelector('.admin-sidebar');
    
    if (sidebarToggleShow) {
        sidebarToggleShow.addEventListener('click', function() {
            adminSidebar.classList.add('show');
        });
    }
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            adminSidebar.classList.remove('show');
        });
    }
    
    
    if (document.getElementById('panoramaEditor')) {
        initPanoramaEditor();
    }
    
    
    if (document.getElementById('hotspotAdder')) {
        initHotspotAdder();
    }
    
    
    const dataTablesElements = document.querySelectorAll('.datatable, .data-table');
    if (dataTablesElements.length > 0) {
        dataTablesElements.forEach(table => {
            new DataTable(table, {
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/tr.json'
                }
            });
        });
    }
    
    
    if (document.getElementById('panoramaUploader')) {
        initFileUploader();
    }
    
    
    if (document.getElementById('visitsChart')) {
        initAnalyticsCharts();
    }
    
    
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    
    if (typeof initTableFilters === 'function') {
        initTableFilters();
    }
    
    if (typeof initExportButtons === 'function') {
        initExportButtons();
    }
    
    if (typeof initPrintButtons === 'function') {
        initPrintButtons();
    }
    
    
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});


function initPanoramaEditor() {
    const panoramaContainer = document.getElementById('panoramaPreview');
    
    
    const viewer = pannellum.viewer(panoramaContainer, {
        type: 'equirectangular',
        panorama: panoramaContainer.getAttribute('data-image'),
        autoLoad: true,
        showControls: false
    });
    
    
    const hotspotsData = document.getElementById('hotspotsData');
    if (hotspotsData) {
        const hotspots = JSON.parse(hotspotsData.value);
        hotspots.forEach(hotspot => {
            viewer.addHotSpot({
                id: hotspot.id,
                pitch: hotspot.pitch,
                yaw: hotspot.yaw,
                type: hotspot.type,
                text: hotspot.text,
                targetScene: hotspot.targetScene
            });
        });
    }
    
    
    let addingHotspot = false;
    
    const addHotspotBtn = document.getElementById('addHotspotBtn');
    if (addHotspotBtn) {
        addHotspotBtn.addEventListener('click', function() {
            addingHotspot = true;
            panoramaContainer.classList.add('adding-hotspot');
            alert('Panorama üzerinde bir noktaya tıklayarak hotspot ekleyebilirsiniz.');
        });
    }
    
    
    panoramaContainer.addEventListener('mousedown', function(e) {
        if (addingHotspot) {
            const coords = viewer.mouseEventToCoords(e);
            
            
            document.getElementById('hotspotPitch').value = coords.pitch;
            document.getElementById('hotspotYaw').value = coords.yaw;
            
            const hotspotModal = new bootstrap.Modal(document.getElementById('hotspotModal'));
            hotspotModal.show();
            
            addingHotspot = false;
            panoramaContainer.classList.remove('adding-hotspot');
        }
    });
    
    
    const hotspotForm = document.getElementById('hotspotForm');
    if (hotspotForm) {
        hotspotForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const hotspotData = {
                pitch: parseFloat(document.getElementById('hotspotPitch').value),
                yaw: parseFloat(document.getElementById('hotspotYaw').value),
                type: document.getElementById('hotspotType').value,
                text: document.getElementById('hotspotText').value,
                targetScene: document.getElementById('targetScene').value
            };
            
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'admin/save_hotspot.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const result = JSON.parse(xhr.responseText);
                    if (result.success) {
                        
                        hotspotData.id = result.hotspot_id;
                        viewer.addHotSpot(hotspotData);
                        
                        const hotspotModal = bootstrap.Modal.getInstance(document.getElementById('hotspotModal'));
                        hotspotModal.hide();
                        alert('Hotspot başarıyla eklendi!');
                    } else {
                        alert('Hata: ' + result.message);
                    }
                } else {
                    alert('Sunucu hatası oluştu!');
                }
            };
            
            xhr.onerror = function() {
                alert('Sunucu hatası oluştu!');
            };
            
            xhr.send(
                'scene_id=' + encodeURIComponent(document.getElementById('sceneId').value) + 
                '&hotspot=' + encodeURIComponent(JSON.stringify(hotspotData))
            );
        });
    }
}


function initHotspotAdder() {
    
    console.log('Hotspot ekleyici başlatıldı');
}


function initFileUploader() {
    const dropZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    const browseBtn = document.getElementById('browseBtn');
    
    
    if (browseBtn) {
        browseBtn.addEventListener('click', function() {
            fileInput.click();
        });
    }
    
    
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files.length) {
                handleFiles(this.files);
            }
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
            const files = dt.files;
            handleFiles(files);
        });
    }
    
    
    function handleFiles(files) {
        const fileArray = Array.from(files);
        
        
        const imageFiles = fileArray.filter(file => file.type.match('image.*'));
        
        if (imageFiles.length === 0) {
            alert('Lütfen sadece resim dosyaları yükleyin.');
            return;
        }
        
        
        const uploadDetails = document.getElementById('uploadDetails');
        if (uploadDetails) {
            uploadDetails.style.display = 'block';
        }
        
        
        const fileList = document.getElementById('fileList');
        if (fileList) {
            fileList.innerHTML = '';
            
            imageFiles.forEach(file => {
                const item = document.createElement('div');
                item.className = 'file-item';
                item.textContent = file.name;
                fileList.appendChild(item);
            });
        }
        
        
        const uploadForm = document.getElementById('uploadForm');
        if (uploadForm) {
            uploadForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData();
                formData.append('campus_id', document.getElementById('campusId').value);
                formData.append('title', document.getElementById('sceneTitle').value);
                formData.append('description', document.getElementById('sceneDescription').value);
                
                imageFiles.forEach(file => {
                    formData.append('files[]', file);
                });
                
                
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'admin/upload_panorama.php', true);
                
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percent = (e.loaded / e.total) * 100;
                        document.getElementById('uploadProgress').style.width = percent + '%';
                    }
                }, false);
                
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const result = JSON.parse(xhr.responseText);
                        if (result.success) {
                            alert('Dosyalar başarıyla yüklendi!');
                            window.location.href = 'admin.php?page=scenes';
                        } else {
                            alert('Hata: ' + result.message);
                        }
                    } else {
                        alert('Sunucu hatası oluştu!');
                    }
                };
                
                xhr.onerror = function() {
                    alert('Sunucu hatası oluştu!');
                };
                
                xhr.send(formData);
            });
        }
    }
}


function initAnalyticsCharts() {
    
    const visitsChart = document.getElementById('visitsChart');
    if (visitsChart) {
        const visitsData = JSON.parse(document.getElementById('visitsData').value);
        
        new Chart(visitsChart.getContext('2d'), {
            type: 'line',
            data: {
                labels: visitsData.labels,
                datasets: [{
                    label: 'Ziyaretçi Sayısı',
                    data: visitsData.values,
                    backgroundColor: 'rgba(21, 101, 192, 0.1)',
                    borderColor: 'rgba(21, 101, 192, 1)',
                    borderWidth: 2,
                    tension: 0.4
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
                }
            }
        });
    }
    
    
    const scenesChart = document.getElementById('scenesChart');
    if (scenesChart) {
        const scenesData = JSON.parse(document.getElementById('scenesData').value);
        
        new Chart(scenesChart.getContext('2d'), {
            type: 'bar',
            data: {
                labels: scenesData.labels,
                datasets: [{
                    label: 'Ziyaret Sayısı',
                    data: scenesData.values,
                    backgroundColor: 'rgba(76, 175, 80, 0.7)',
                    borderWidth: 0
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
                }
            }
        });
    }
    
    
    const devicesChart = document.getElementById('devicesChart');
    if (devicesChart) {
        const devicesData = JSON.parse(document.getElementById('devicesData').value);
        
        new Chart(devicesChart.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: devicesData.labels,
                datasets: [{
                    data: devicesData.values,
                    backgroundColor: [
                        'rgba(21, 101, 192, 0.7)',
                        'rgba(76, 175, 80, 0.7)',
                        'rgba(244, 67, 54, 0.7)',
                        'rgba(255, 111, 0, 0.7)'
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
    }
}


function validateForm(form) {
    const csrfToken = form.querySelector('input[name="csrf_token"]').value;
    const storedToken = getCookie('csrf_token');
    
    if (csrfToken !== storedToken) {
        alert('Güvenlik doğrulaması başarısız. Lütfen sayfayı yenileyip tekrar deneyin.');
        return false;
    }
    
    return true;
}


function getCookie(name) {
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i].trim();
        if (cookie.startsWith(name + '=')) {
            return cookie.substring(name.length + 1);
        }
    }
    return '';
}


function confirmDelete(formId, itemName) {
    if (confirm(`${itemName} öğesini silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.`)) {
        document.getElementById(formId).submit();
    }
}