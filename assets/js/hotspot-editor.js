

class HotspotEditor {
    constructor(options = {}) {
        
        this.viewerId = options.viewerId || 'panorama';
        this.viewer = null;
        this.hotspots = [];
        this.selectedHotspot = null;
        this.newHotspotType = 'scene';
        this.scenes = options.scenes || [];
        this.onSave = options.onSave || function() {};
        this.isEditorMode = false;
        
        
        this.init();
    }
    
    init() {
        
        if (!window.pannellum) {
            console.error('Pannellum kütüphanesi bulunamadı!');
            return;
        }
        
        
        this.createEditorPanel();
        
        
        document.getElementById('hotspot-type').addEventListener('change', (e) => {
            this.newHotspotType = e.target.value;
            this.updateHotspotForm();
        });
        
        
        document.getElementById('hotspot-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.saveHotspot();
        });
        
        
        document.getElementById('delete-hotspot').addEventListener('click', (e) => {
            e.preventDefault();
            this.deleteHotspot();
        });
        
        
        document.getElementById('toggle-editor').addEventListener('click', () => {
            this.toggleEditorMode();
        });
        
        
        document.getElementById('save-changes').addEventListener('click', () => {
            this.saveChanges();
        });
    }
    
    setupViewer(viewer) {
        this.viewer = viewer;
        
        
        if (this.viewer) {
            
            document.getElementById(this.viewerId).addEventListener('click', (e) => {
                if (this.isEditorMode) {
                    this.handleViewerClick(e);
                }
            });
            
            
            this.viewer.on('click', (hotspot) => {
                if (this.isEditorMode && hotspot) {
                    this.selectHotspot(hotspot);
                }
            });
            
            
            this.loadHotspots();
        }
    }
    
    createEditorPanel() {
        
        const editorHTML = `
        <div id="hotspot-editor-panel" class="hotspot-editor-panel">
            <div class="editor-header">
                <h3>Hotspot Düzenleyici</h3>
                <button id="toggle-editor" class="btn-toggle">Düzenleme Modunu Aç</button>
            </div>
            <div class="editor-body" style="display: none;">
                <form id="hotspot-form">
                    <div class="form-row">
                        <label for="hotspot-type">Hotspot Tipi:</label>
                        <select id="hotspot-type" required>
                            <option value="scene">Sahne Geçişi</option>
                            <option value="info">Bilgi Noktası</option>
                            <option value="link">Bağlantı</option>
                        </select>
                    </div>
                    
                    <div class="form-row">
                        <label for="hotspot-title">Başlık:</label>
                        <input type="text" id="hotspot-title" required>
                    </div>
                    
                    <div id="scene-specific-fields">
                        <div class="form-row">
                            <label for="target-scene">Hedef Sahne:</label>
                            <select id="target-scene">
                                <option value="">Seçiniz...</option>
                                ${this.scenes.map(scene => `<option value="${scene.id}">${scene.title}</option>`).join('')}
                            </select>
                        </div>
                    </div>
                    
                    <div id="link-specific-fields" style="display: none;">
                        <div class="form-row">
                            <label for="link-url">URL:</label>
                            <input type="url" id="link-url">
                        </div>
                    </div>
                    
                    <div id="info-specific-fields" style="display: none;">
                        <div class="form-row">
                            <label for="info-text">Bilgi Metni:</label>
                            <textarea id="info-text" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-row coordinates">
                        <div>
                            <label for="hotspot-pitch">Pitch:</label>
                            <input type="number" id="hotspot-pitch" step="0.1" required>
                        </div>
                        <div>
                            <label for="hotspot-yaw">Yaw:</label>
                            <input type="number" id="hotspot-yaw" step="0.1" required>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-save-hotspot">Kaydet</button>
                        <button type="button" id="delete-hotspot" class="btn-delete-hotspot" disabled>Sil</button>
                    </div>
                </form>
                
                <div class="hotspot-list-container">
                    <h4>Mevcut Hotspotlar</h4>
                    <ul id="hotspot-list" class="hotspot-list"></ul>
                </div>
                
                <div class="editor-footer">
                    <button id="save-changes" class="btn-save-changes">Tüm Değişiklikleri Kaydet</button>
                </div>
            </div>
        </div>
        `;
        
        
        const styles = `
        <style>
            .hotspot-editor-panel {
                position: fixed;
                top: 20px;
                right: 20px;
                width: 350px;
                background-color: rgba(255, 255, 255, 0.95);
                border-radius: 10px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
                z-index: 1000;
                backdrop-filter: blur(10px);
                overflow: hidden;
                font-family: 'Poppins', Arial, sans-serif;
            }
            
            .editor-header {
                background-color: #0c4da2;
                color: white;
                padding: 15px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .editor-header h3 {
                margin: 0;
                font-size: 16px;
                font-weight: 600;
            }
            
            .editor-body {
                padding: 15px;
                max-height: 70vh;
                overflow-y: auto;
            }
            
            .form-row {
                margin-bottom: 15px;
            }
            
            .form-row label {
                display: block;
                margin-bottom: 5px;
                font-weight: 500;
                font-size: 14px;
            }
            
            .form-row input,
            .form-row select,
            .form-row textarea {
                width: 100%;
                padding: 8px 12px;
                border: 1px solid #ced4da;
                border-radius: 6px;
                font-size: 14px;
            }
            
            .form-row.coordinates {
                display: flex;
                gap: 10px;
            }
            
            .form-row.coordinates > div {
                flex: 1;
            }
            
            .form-actions {
                display: flex;
                gap: 10px;
                margin-bottom: 20px;
            }
            
            .btn-toggle {
                background-color: #6c757d;
                color: white;
                border: none;
                padding: 6px 12px;
                border-radius: 4px;
                font-size: 12px;
                cursor: pointer;
                transition: all 0.2s;
            }
            
            .btn-toggle.active {
                background-color: #e94057;
            }
            
            .btn-save-hotspot {
                background-color: #28a745;
                color: white;
                border: none;
                padding: 8px 15px;
                border-radius: 6px;
                cursor: pointer;
                flex: 1;
            }
            
            .btn-delete-hotspot {
                background-color: #dc3545;
                color: white;
                border: none;
                padding: 8px 15px;
                border-radius: 6px;
                cursor: pointer;
            }
            
            .btn-delete-hotspot:disabled {
                background-color: #6c757d;
                cursor: not-allowed;
                opacity: 0.6;
            }
            
            .btn-save-changes {
                background-color: #0c4da2;
                color: white;
                border: none;
                padding: 10px 15px;
                border-radius: 6px;
                cursor: pointer;
                width: 100%;
                font-weight: 600;
                margin-top: 10px;
            }
            
            .hotspot-list-container {
                margin-top: 20px;
            }
            
            .hotspot-list-container h4 {
                font-size: 16px;
                margin-bottom: 10px;
                font-weight: 600;
            }
            
            .hotspot-list {
                list-style: none;
                padding: 0;
                margin: 0;
                max-height: 200px;
                overflow-y: auto;
                border: 1px solid #ced4da;
                border-radius: 6px;
            }
            
            .hotspot-list li {
                padding: 8px 12px;
                border-bottom: 1px solid #eeeeee;
                cursor: pointer;
                transition: all 0.2s;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .hotspot-list li:last-child {
                border-bottom: none;
            }
            
            .hotspot-list li:hover {
                background-color: #f8f9fa;
            }
            
            .hotspot-list li.selected {
                background-color: #e0eaff;
            }
            
            .hotspot-badge {
                font-size: 12px;
                padding: 2px 8px;
                border-radius: 12px;
                font-weight: 600;
            }
            
            .hotspot-badge.scene {
                background-color: #0c4da2;
                color: white;
            }
            
            .hotspot-badge.info {
                background-color: #28a745;
                color: white;
            }
            
            .hotspot-badge.link {
                background-color: #e94057;
                color: white;
            }
            
            .editor-instructions {
                background-color: #f8f9fa;
                border-radius: 6px;
                padding: 10px;
                margin-bottom: 15px;
                font-size: 13px;
                color: #495057;
            }
            
            .editor-footer {
                margin-top: 15px;
                text-align: center;
            }
            
            
            .editor-mode .pnlm-container {
                cursor: crosshair !important;
            }
            
            .custom-hotspot.editing {
                border: 3px solid yellow !important;
                box-shadow: 0 0 20px rgba(255, 255, 0, 0.5) !important;
            }
        </style>
        `;
        
        
        const editorContainer = document.createElement('div');
        editorContainer.innerHTML = editorHTML + styles;
        document.body.appendChild(editorContainer);
    }
    
    updateHotspotForm() {
        const hotspotType = this.newHotspotType;
        
        
        document.getElementById('scene-specific-fields').style.display = 'none';
        document.getElementById('info-specific-fields').style.display = 'none';
        document.getElementById('link-specific-fields').style.display = 'none';
        
        
        switch (hotspotType) {
            case 'scene':
                document.getElementById('scene-specific-fields').style.display = 'block';
                break;
            case 'info':
                document.getElementById('info-specific-fields').style.display = 'block';
                break;
            case 'link':
                document.getElementById('link-specific-fields').style.display = 'block';
                break;
        }
    }
    
    toggleEditorMode() {
        this.isEditorMode = !this.isEditorMode;
        
        const editorBody = document.querySelector('.editor-body');
        const toggleButton = document.getElementById('toggle-editor');
        
        if (this.isEditorMode) {
            editorBody.style.display = 'block';
            toggleButton.textContent = 'Düzenleme Modunu Kapat';
            toggleButton.classList.add('active');
            document.body.classList.add('editor-mode');
            
            
            const instructionsDiv = document.createElement('div');
            instructionsDiv.className = 'editor-instructions';
            instructionsDiv.innerHTML = `
                <strong>Düzenleme Modu Aktif</strong>
                <p>Yeni hotspot eklemek için panoramaya tıklayın. Mevcut bir hotspotu düzenlemek için üzerine tıklayın.</p>
            `;
            editorBody.insertBefore(instructionsDiv, document.getElementById('hotspot-form'));
            
        } else {
            editorBody.style.display = 'none';
            toggleButton.textContent = 'Düzenleme Modunu Aç';
            toggleButton.classList.remove('active');
            document.body.classList.remove('editor-mode');
            
            
            const instructions = document.querySelector('.editor-instructions');
            if (instructions) {
                instructions.remove();
            }
            
            
            this.clearSelection();
        }
    }
    
    handleViewerClick(e) {
        
        if (!e.target.classList.contains('pnlm-hotspot')) {
            const coords = this.getClickedCoordinates(e);
            if (coords) {
                this.clearSelection();
                this.fillNewHotspotForm(coords);
            }
        }
    }
    
    getClickedCoordinates(event) {
        
        try {
            
            const rect = document.getElementById(this.viewerId).getBoundingClientRect();
            const viewerX = event.clientX - rect.left;
            const viewerY = event.clientY - rect.top;
            
            
            const coords = this.viewer.mouseEventToCoords(event);
            
            return {
                pitch: coords[0],
                yaw: coords[1]
            };
        } catch (error) {
            console.error('Koordinat hesaplanamadı:', error);
            return null;
        }
    }
    
    loadHotspots() {
        
        try {
            const currentScene = this.viewer.getScene();
            const sceneConfig = this.viewer.getConfig().scenes[currentScene];
            
            if (sceneConfig && sceneConfig.hotSpots) {
                this.hotspots = [...sceneConfig.hotSpots];
                this.updateHotspotList();
            }
        } catch (error) {
            console.error('Hotspotlar yüklenemedi:', error);
        }
    }
    
    updateHotspotList() {
        const listElement = document.getElementById('hotspot-list');
        
        
        listElement.innerHTML = '';
        
        
        this.hotspots.forEach((hotspot, index) => {
            const li = document.createElement('li');
            li.dataset.index = index;
            
            if (this.selectedHotspot && this.selectedHotspot.id === hotspot.id) {
                li.classList.add('selected');
            }
            
            
            let type = 'info';
            if (hotspot.sceneId) type = 'scene';
            else if (hotspot.URL) type = 'link';
            
            li.innerHTML = `
                <span>${hotspot.text || 'İsimsiz Hotspot'}</span>
                <span class="hotspot-badge ${type}">${this.getTypeName(type)}</span>
            `;
            
            li.addEventListener('click', () => {
                this.selectHotspotFromList(index);
            });
            
            listElement.appendChild(li);
        });
    }
    
    getTypeName(type) {
        switch (type) {
            case 'scene': return 'Sahne';
            case 'info': return 'Bilgi';
            case 'link': return 'Bağlantı';
            default: return type;
        }
    }
    
    selectHotspotFromList(index) {
        const hotspot = this.hotspots[index];
        if (hotspot) {
            this.selectHotspot(hotspot.id);
        }
    }
    
    selectHotspot(hotspotId) {
        
        const id = typeof hotspotId === 'object' ? hotspotId.id : hotspotId;
        
        
        const hotspot = this.hotspots.find(h => h.id === id);
        
        if (hotspot) {
            this.selectedHotspot = hotspot;
            
            
            this.fillHotspotForm(hotspot);
            
            
            document.getElementById('delete-hotspot').disabled = false;
            
            
            this.updateHotspotList();
            
            
            this.updateHotspotStyles();
        }
    }
    
    fillHotspotForm(hotspot) {
        
        let type = 'info';
        if (hotspot.sceneId) type = 'scene';
        else if (hotspot.URL) type = 'link';
        
        
        document.getElementById('hotspot-type').value = type;
        this.newHotspotType = type;
        this.updateHotspotForm();
        
        
        document.getElementById('hotspot-title').value = hotspot.text || '';
        document.getElementById('hotspot-pitch').value = hotspot.pitch || 0;
        document.getElementById('hotspot-yaw').value = hotspot.yaw || 0;
        
        
        switch (type) {
            case 'scene':
                document.getElementById('target-scene').value = hotspot.sceneId || '';
                break;
            case 'info':
                document.getElementById('info-text').value = hotspot.info || '';
                break;
            case 'link':
                document.getElementById('link-url').value = hotspot.URL || '';
                break;
        }
    }
    
    fillNewHotspotForm(coords) {
        
        this.selectedHotspot = null;
        document.getElementById('delete-hotspot').disabled = true;
        
        
        document.getElementById('hotspot-pitch').value = coords.pitch.toFixed(2);
        document.getElementById('hotspot-yaw').value = coords.yaw.toFixed(2);
        
        
        document.getElementById('hotspot-title').value = '';
        document.getElementById('target-scene').value = '';
        document.getElementById('info-text').value = '';
        document.getElementById('link-url').value = '';
        
        
        document.getElementById('hotspot-type').value = 'scene';
        this.newHotspotType = 'scene';
        this.updateHotspotForm();
    }
    
    clearSelection() {
        this.selectedHotspot = null;
        document.getElementById('delete-hotspot').disabled = true;
        
        
        const selectedItems = document.querySelectorAll('#hotspot-list li.selected');
        selectedItems.forEach(item => {
            item.classList.remove('selected');
        });
        
        
        this.updateHotspotStyles();
    }
    
    updateHotspotStyles() {
        
        const hotspotElements = document.querySelectorAll('.pnlm-hotspot');
        
        
        hotspotElements.forEach(element => {
            element.classList.remove('editing');
        });
        
        
        if (this.selectedHotspot) {
            const selectedElement = Array.from(hotspotElements).find(
                element => element.id === this.selectedHotspot.id
            );
            
            if (selectedElement) {
                selectedElement.classList.add('editing');
            }
        }
    }
    
    saveHotspot() {
        const form = document.getElementById('hotspot-form');
        
        
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        
        const title = document.getElementById('hotspot-title').value;
        const pitch = parseFloat(document.getElementById('hotspot-pitch').value);
        const yaw = parseFloat(document.getElementById('hotspot-yaw').value);
        const type = document.getElementById('hotspot-type').value;
        
        
        const hotspot = {
            id: this.selectedHotspot ? this.selectedHotspot.id : 'hotspot-' + Date.now(),
            pitch: pitch,
            yaw: yaw,
            text: title,
            type: type
        };
        
        
        switch (type) {
            case 'scene':
                const targetScene = document.getElementById('target-scene').value;
                if (!targetScene) {
                    alert('Lütfen hedef sahne seçin.');
                    return;
                }
                hotspot.sceneId = targetScene;
                break;
                
            case 'info':
                hotspot.info = document.getElementById('info-text').value;
                break;
                
            case 'link':
                const url = document.getElementById('link-url').value;
                if (!url) {
                    alert('Lütfen URL girin.');
                    return;
                }
                hotspot.URL = url;
                break;
        }
        
        
        if (this.selectedHotspot) {
            
            const index = this.hotspots.findIndex(h => h.id === this.selectedHotspot.id);
            if (index !== -1) {
                this.hotspots[index] = hotspot;
            }
        } else {
            
            this.hotspots.push(hotspot);
        }
        
        
        this.updateViewerHotspots();
        
        
        this.updateHotspotList();
        this.selectHotspot(hotspot.id);
        
        
        alert(this.selectedHotspot ? 'Hotspot güncellendi!' : 'Yeni hotspot eklendi!');
    }
    
    deleteHotspot() {
        if (!this.selectedHotspot) return;
        
        
        if (confirm('Bu hotspot\'u silmek istediğinizden emin misiniz?')) {
            
            const index = this.hotspots.findIndex(h => h.id === this.selectedHotspot.id);
            if (index !== -1) {
                this.hotspots.splice(index, 1);
            }
            
            
            this.updateViewerHotspots();
            
            
            this.clearSelection();
            this.updateHotspotList();
            
            
            alert('Hotspot silindi!');
        }
    }
    
    updateViewerHotspots() {
        try {
            if (!this.viewer) return;
            
            const currentScene = this.viewer.getScene();
            
            
            const viewerHotspots = this.hotspots.map(hotspot => {
                
                return {
                    ...hotspot
                };
            });
            
            
            this.viewer.removeHotSpots();
            
            
            viewerHotspots.forEach(hotspot => {
                this.viewer.addHotSpot(hotspot, currentScene);
            });
        } catch (error) {
            console.error('Viewer hotspotları güncellenirken hata:', error);
        }
    }
    
    saveChanges() {
        
        try {
            
            const currentScene = this.viewer.getScene();
            
            
            this.onSave({
                sceneId: currentScene,
                hotspots: this.hotspots
            });
            
            
            alert('Tüm değişiklikler kaydedildi!');
        } catch (error) {
            console.error('Değişiklikler kaydedilirken hata:', error);
            alert('Hata: Değişiklikler kaydedilemedi. Lütfen tekrar deneyin.');
        }
    }
}


