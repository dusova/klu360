


class InteractiveMap {
    
    constructor(options = {}) {
        this.containerId = options.containerId || 'map-image-container';
        this.mapImageId = options.mapImageId || 'map-image';
        this.markerClass = options.markerClass || 'map-marker';
        this.markerActiveClass = options.markerActiveClass || 'active';
        this.onMarkerClick = options.onMarkerClick || function() {};
        
        this.container = document.getElementById(this.containerId);
        this.mapImage = document.getElementById(this.mapImageId);
        this.markers = [];
        this.scale = 1;
        this.translateX = 0;
        this.translateY = 0;
        this.isDragging = false;
        this.startX = 0;
        this.startY = 0;
        this.lastX = 0;
        this.lastY = 0;
        
        
        this.init();
    }
    
    
    init() {
        
        if (!this.container) {
            console.error(`Map container with ID "${this.containerId}" not found.`);
            return;
        }
        
        
        if (!this.mapImage) {
            console.error(`Map image with ID "${this.mapImageId}" not found.`);
            return;
        }
        
        
        this.markers = Array.from(this.container.querySelectorAll(`.${this.markerClass}`));
        
        
        this.markers.forEach(marker => {
            
            marker.dataset.originalLeft = marker.style.left;
            marker.dataset.originalTop = marker.style.top;
            
            
            marker.addEventListener('click', (e) => {
                const sceneId = marker.dataset.sceneId;
                this.onMarkerClick(sceneId, marker);
                e.stopPropagation();
            });
        });
        
        
        this.container.addEventListener('wheel', this.handleWheel.bind(this));
        
        
        this.container.addEventListener('mousedown', this.handleMouseDown.bind(this));
        document.addEventListener('mousemove', this.handleMouseMove.bind(this));
        document.addEventListener('mouseup', this.handleMouseUp.bind(this));
        
        
        this.container.addEventListener('touchstart', this.handleTouchStart.bind(this));
        this.container.addEventListener('touchmove', this.handleTouchMove.bind(this));
        this.container.addEventListener('touchend', this.handleTouchEnd.bind(this));
        
        
        this.container.addEventListener('dblclick', this.handleDoubleClick.bind(this));
        
        
        this.setupControls();
        
        
        this.container.style.cursor = 'grab';
        
        
        this.applyTransform();
    }
    
    
    setupControls() {
        
        const style = document.createElement('style');
        style.textContent = `
            .map-controls {
                position: absolute;
                top: 10px;
                right: 10px;
                display: flex;
                flex-direction: column;
                gap: 8px;
                z-index: 10;
            }
            
            .map-control-btn {
                width: 36px;
                height: 36px;
                border-radius: 50%;
                background-color: white;
                border: none;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                color: #0c4da2;
                transition: all 0.3s;
            }
            
            .map-control-btn:hover {
                background-color: #0c4da2;
                color: white;
                transform: scale(1.1);
            }
            
            .map-image-container {
                cursor: grab;
                overflow: hidden;
                position: relative;
            }
            
            .map-image-container.dragging {
                cursor: grabbing;
            }
            
            .map-transition {
                transition: transform 0.3s ease;
            }
        `;
        document.head.appendChild(style);
        
        
        const controlsDiv = document.createElement('div');
        controlsDiv.className = 'map-controls';
        
        
        const zoomInBtn = document.createElement('button');
        zoomInBtn.className = 'map-control-btn';
        zoomInBtn.innerHTML = '<i class="bi bi-plus-lg"></i>';
        zoomInBtn.title = 'Yakınlaştır';
        zoomInBtn.addEventListener('click', () => this.zoom(0.2));
        
        
        const zoomOutBtn = document.createElement('button');
        zoomOutBtn.className = 'map-control-btn';
        zoomOutBtn.innerHTML = '<i class="bi bi-dash-lg"></i>';
        zoomOutBtn.title = 'Uzaklaştır';
        zoomOutBtn.addEventListener('click', () => this.zoom(-0.2));
        
        
        const resetBtn = document.createElement('button');
        resetBtn.className = 'map-control-btn';
        resetBtn.innerHTML = '<i class="bi bi-arrows-fullscreen"></i>';
        resetBtn.title = 'Sıfırla';
        resetBtn.addEventListener('click', () => this.reset());
        
        
        controlsDiv.appendChild(zoomInBtn);
        controlsDiv.appendChild(zoomOutBtn);
        controlsDiv.appendChild(resetBtn);
        
        
        this.container.appendChild(controlsDiv);
    }
    
    
    setActiveMarker(sceneId) {
        
        this.markers.forEach(marker => {
            marker.classList.remove(this.markerActiveClass);
        });
        
        
        const activeMarker = this.markers.find(marker => marker.dataset.sceneId === sceneId);
        if (activeMarker) {
            activeMarker.classList.add(this.markerActiveClass);
            
            
            this.centerOnMarker(activeMarker);
        }
    }
    
    
    centerOnMarker(marker) {
        
        const originalLeft = parseFloat(marker.dataset.originalLeft || marker.style.left);
        const originalTop = parseFloat(marker.dataset.originalTop || marker.style.top);
        
        
        const markerX = originalLeft / 100 * this.mapImage.naturalWidth;
        const markerY = originalTop / 100 * this.mapImage.naturalHeight;
        
        
        const containerWidth = this.container.clientWidth;
        const containerHeight = this.container.clientHeight;
        
        
        this.translateX = containerWidth / 2 - markerX * this.scale;
        this.translateY = containerHeight / 2 - markerY * this.scale;
        
        
        this.checkBounds();
        
        
        this.applyTransform(true);
    }
    
    
    handleWheel(e) {
        e.preventDefault();
        
        
        const delta = -Math.sign(e.deltaY) * 0.1;
        
        
        const rect = this.container.getBoundingClientRect();
        const mouseX = e.clientX - rect.left;
        const mouseY = e.clientY - rect.top;
        
        
        this.zoomAtPoint(delta, mouseX, mouseY);
    }
    
    
    zoomAtPoint(delta, mouseX, mouseY) {
        
        const pointX = (mouseX - this.translateX) / this.scale;
        const pointY = (mouseY - this.translateY) / this.scale;
        
        
        const newScale = Math.max(0.5, Math.min(4, this.scale + delta));
        
        
        if (newScale !== this.scale) {
            this.scale = newScale;
            
            
            this.translateX = mouseX - pointX * this.scale;
            this.translateY = mouseY - pointY * this.scale;
            
            
            this.checkBounds();
            
            
            this.applyTransform();
        }
    }
    
    
    zoom(delta) {
        
        const centerX = this.container.clientWidth / 2;
        const centerY = this.container.clientHeight / 2;
        
        
        this.zoomAtPoint(delta, centerX, centerY);
    }
    
    
    reset() {
        this.scale = 1;
        this.translateX = 0;
        this.translateY = 0;
        
        
        this.applyTransform(true);
    }
    
    
    handleMouseDown(e) {
        if (e.button !== 0) return; 
        e.preventDefault();
        
        this.isDragging = true;
        this.container.classList.add('dragging');
        
        
        this.startX = e.clientX;
        this.startY = e.clientY;
        this.lastX = this.translateX;
        this.lastY = this.translateY;
    }
    
    
    handleMouseMove(e) {
        if (!this.isDragging) return;
        
        
        this.translateX = this.lastX + (e.clientX - this.startX);
        this.translateY = this.lastY + (e.clientY - this.startY);
        
        
        this.checkBounds();
        
        
        this.applyTransform();
    }
    
    
    handleMouseUp() {
        this.isDragging = false;
        this.container.classList.remove('dragging');
    }
    
    
    handleTouchStart(e) {
        if (e.touches.length === 1) {
            e.preventDefault();
            
            this.isDragging = true;
            
            
            this.startX = e.touches[0].clientX;
            this.startY = e.touches[0].clientY;
            this.lastX = this.translateX;
            this.lastY = this.translateY;
        }
    }
    
    
    handleTouchMove(e) {
        if (!this.isDragging || e.touches.length !== 1) return;
        
        e.preventDefault();
        
        
        this.translateX = this.lastX + (e.touches[0].clientX - this.startX);
        this.translateY = this.lastY + (e.touches[0].clientY - this.startY);
        
        
        this.checkBounds();
        
        
        this.applyTransform();
    }
    
    
    handleTouchEnd() {
        this.isDragging = false;
    }
    
    
    handleDoubleClick(e) {
        
        const rect = this.container.getBoundingClientRect();
        const mouseX = e.clientX - rect.left;
        const mouseY = e.clientY - rect.top;
        
        
        this.zoomAtPoint(0.5, mouseX, mouseY);
    }
    
    
    checkBounds() {
        
        const scaledWidth = this.mapImage.naturalWidth * this.scale;
        const scaledHeight = this.mapImage.naturalHeight * this.scale;
        
        
        const containerWidth = this.container.clientWidth;
        const containerHeight = this.container.clientHeight;
        
        
        if (scaledWidth <= containerWidth) {
            
            this.translateX = (containerWidth - scaledWidth) / 2;
        } else {
            
            const maxTranslateX = 0;
            const minTranslateX = containerWidth - scaledWidth;
            
            this.translateX = Math.min(maxTranslateX, Math.max(minTranslateX, this.translateX));
        }
        
        
        if (scaledHeight <= containerHeight) {
            
            this.translateY = (containerHeight - scaledHeight) / 2;
        } else {
            
            const maxTranslateY = 0;
            const minTranslateY = containerHeight - scaledHeight;
            
            this.translateY = Math.min(maxTranslateY, Math.max(minTranslateY, this.translateY));
        }
    }
    
    
    applyTransform(animate = false) {
        
        const transform = `translate(${this.translateX}px, ${this.translateY}px) scale(${this.scale})`;
        
        
        const inverseScale = 1 / this.scale;
        
        
        if (animate) {
            this.mapImage.classList.add('map-transition');
            this.markers.forEach(marker => {
                marker.classList.add('map-transition');
            });
            
            
            setTimeout(() => {
                this.mapImage.classList.remove('map-transition');
                this.markers.forEach(marker => {
                    marker.classList.remove('map-transition');
                });
            }, 300);
        } else {
            this.mapImage.classList.remove('map-transition');
            this.markers.forEach(marker => {
                marker.classList.remove('map-transition');
            });
        }
        
        
        this.mapImage.style.transform = transform;
        
        
        this.markers.forEach(marker => {
            
            const originalLeft = parseFloat(marker.dataset.originalLeft || marker.style.left) / 100;
            const originalTop = parseFloat(marker.dataset.originalTop || marker.style.top) / 100;
            
            
            const pixelX = originalLeft * this.mapImage.naturalWidth * this.scale + this.translateX;
            const pixelY = originalTop * this.mapImage.naturalHeight * this.scale + this.translateY;
            
            
            marker.style.left = `${pixelX}px`;
            marker.style.top = `${pixelY}px`;
            marker.style.transform = `translate(-50%, -100%) scale(${inverseScale})`;
        });
    }
    
    
    addMarker(options) {
        const { sceneId, x, y, tooltip, isActive = false } = options;
        
        
        const marker = document.createElement('div');
        marker.className = `${this.markerClass}${isActive ? ' ' + this.markerActiveClass : ''}`;
        marker.dataset.sceneId = sceneId;
        marker.style.left = `${x}%`;
        marker.style.top = `${y}%`;
        marker.dataset.originalLeft = `${x}`;
        marker.dataset.originalTop = `${y}`;
        
        
        marker.innerHTML = '<i class="bi bi-geo-alt-fill"></i>';
        
        
        if (tooltip && typeof tippy === 'function') {
            tippy(marker, {
                content: tooltip,
                placement: 'top',
                arrow: true,
                theme: 'translucent'
            });
        }
        
        
        marker.addEventListener('click', (e) => {
            this.onMarkerClick(sceneId, marker);
            e.stopPropagation();
        });
        
        
        this.container.appendChild(marker);
        
        
        this.markers.push(marker);
        
        
        this.applyTransform();
        
        return marker;
    }
    
    
    removeMarker(sceneId) {
        
        const markerIndex = this.markers.findIndex(marker => marker.dataset.sceneId === sceneId);
        
        if (markerIndex !== -1) {
            const marker = this.markers[markerIndex];
            
            
            marker.remove();
            
            
            this.markers.splice(markerIndex, 1);
        }
    }
}


window.InteractiveMap = InteractiveMap;