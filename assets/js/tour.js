let viewer = null;
let currentSceneId = '';
let currentSceneIndex = 0;
let isAutorotate = false;
let isSceneInfoVisible = false;
let isCampusMapVisible = false;
let isFullscreen = false;
let isInterfaceHidden = false;
let interfaceTimeout = null;


function initTour(panoramaData, hotspotData, defaultScene) {
    console.log('Sanal tur başlatılıyor...');
    
    
    if (!panoramaData || Object.keys(panoramaData).length === 0) {
        console.error('Panorama verileri bulunamadı!');
        document.getElementById('loadingStatus').textContent = 'Panorama verileri bulunamadı. Lütfen sayfayı yenileyin.';
        return;
    }
    
    
    currentSceneId = defaultScene;
    
    
    window.panoramaData = panoramaData;
    window.hotspotData = hotspotData;
    
    
    preloadPanoramaImages(panoramaData, () => {
        initPanoramaViewer(panoramaData, hotspotData, defaultScene);
    });
    
    
    initTooltips();
}


function initTooltips() {
    if (typeof tippy === 'function') {
        tippy('[data-tippy-content]', {
            placement: 'right',
            arrow: true,
            theme: 'translucent',
            duration: [300, 200],
            animation: 'scale'
        });
    }
}


function preloadPanoramaImages(panoramaData, callback) {
    const images = [];
    let loadedCount = 0;
    const totalImages = Object.keys(panoramaData).length;
    
    
    document.getElementById('loadingProgress').style.width = '0%';
    document.getElementById('loadingStatus').textContent = `Panorama görüntüleri yükleniyor... (0/${totalImages})`;
    
    
    Object.keys(panoramaData).forEach((sceneId) => {
        const img = new Image();
        
        img.onload = () => {
            loadedCount++;
            updatePreloaderProgress(loadedCount, totalImages);
            
            if (loadedCount === totalImages) {
                setTimeout(() => {
                    callback();
                }, 500);
            }
        };
        
        img.onerror = () => {
            loadedCount++;
            updatePreloaderProgress(loadedCount, totalImages);
            console.error(`Görüntü yüklenemedi: ${sceneId}`);
        };
        
        img.src = panoramaData[sceneId].image;
        images.push(img);
    });
    
    
    if (totalImages === 0) {
        callback();
    }
}


function updatePreloaderProgress(loaded, total) {
    const progress = Math.round((loaded / total) * 100);
    document.getElementById('loadingProgress').style.width = `${progress}%`;
    document.getElementById('loadingStatus').textContent = `Panorama görüntüleri yükleniyor... (${loaded}/${total})`;
}


function hidePreloader() {
    document.getElementById('loadingStatus').textContent = "Sanal tur başlatılıyor...";
    setTimeout(() => {
        const preloader = document.getElementById('preloader');
        preloader.style.opacity = "0";
        setTimeout(() => {
            preloader.style.display = "none";
        }, 500);
    }, 500);
}


function initPanoramaViewer(panoramaData, hotspotData, defaultScene) {
    
    window.panoramaData = panoramaData;
    window.hotspotData = hotspotData;
    
    
    const hotspotRenderer = (hotSpotDiv, args) => {
        let hotspotClass = 'custom-hotspot';
        if (args.type === 'info') {
            hotspotClass += ' info-hotspot';
        } else if (args.URL) {
            hotspotClass += ' link-hotspot';
        }
        
        hotSpotDiv.classList.add(hotspotClass);
        
        
        if (args.text && typeof tippy === 'function') {
            tippy(hotSpotDiv, {
                content: args.text,
                placement: 'top',
                arrow: true,
                theme: 'translucent',
                duration: [300, 200],
                animation: 'scale'
            });
        }
    };
    
    
    const viewerConfig = {
        default: {
            firstScene: defaultScene,
            sceneFadeDuration: 1000,
            autoLoad: true,
            compass: false,
            hotSpotDebug: false,
            showControls: false,
            showFullscreenCtrl: false,
            hfov: 100,
            minHfov: 60,
            maxHfov: 120,
            autoRotate: 0,
            autoRotateInactivityDelay: 5000,
            hotSpotRenderer: hotspotRenderer
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
        viewer = pannellum.viewer('panorama', viewerConfig);
        window.viewer = viewer; 
        
        
        viewer.on('scenechange', onSceneChange);
        viewer.on('load', onViewerLoad);
        
        
        updateCurrentSceneIndex(defaultScene);
        updateSceneNavigation();
        setupInfoPanel(defaultScene);
        
    } catch (error) {
        console.error('Panorama başlatılamadı:', error);
        document.getElementById('loadingStatus').textContent = "Sanal tur yüklenirken bir hata oluştu. Lütfen sayfayı yenileyin.";
    }
}


function onViewerLoad() {
    hidePreloader();
    
    setTimeout(() => {
        setupInterface();
    }, 500);
}


function onSceneChange(sceneId) {
    currentSceneId = sceneId;
    
    
    document.querySelectorAll('.scene-item').forEach(item => {
        item.classList.remove('active');
    });
    
    const activeItem = document.querySelector(`.scene-item[data-scene-id="${sceneId}"]`);
    if (activeItem) {
        activeItem.classList.add('active');
        
        
        const carousel = document.getElementById('sceneCarousel');
        if (carousel) {
            carousel.scrollTo({
                left: activeItem.offsetLeft - carousel.offsetWidth / 2 + activeItem.offsetWidth / 2,
                behavior: 'smooth'
            });
        }
        
        
        updateCurrentSceneIndex(sceneId);
    }
    
    
    updateSceneNavigation();
    
    
    setupInfoPanel(sceneId);
    
    
    updateMapMarkers(sceneId);
    
    
    if (isAutorotate) {
        setTimeout(() => {
            viewer.startAutoRotate(1.5);
        }, 1000);
    }
    
    
    logSceneChange(sceneId);
    
    
    updateURL(sceneId);
}


function updateURL(sceneId) {
    if (history.pushState) {
        const url = new URL(window.location.href);
        url.searchParams.set('scene', sceneId);
        window.history.replaceState({}, '', url.toString());
    }
}


function updateCurrentSceneIndex(sceneId) {
    const sceneItems = document.querySelectorAll('.scene-item');
    for (let i = 0; i < sceneItems.length; i++) {
        if (sceneItems[i].dataset.sceneId === sceneId) {
            currentSceneIndex = i;
            break;
        }
    }
}


function updateSceneNavigation() {
    const sceneItems = document.querySelectorAll('.scene-item');
    const prevButton = document.getElementById('prevScene');
    const nextButton = document.getElementById('nextScene');
    
    if (prevButton) {
        prevButton.disabled = currentSceneIndex === 0;
    }
    
    if (nextButton) {
        nextButton.disabled = currentSceneIndex === sceneItems.length - 1;
    }
}


function setupInfoPanel(sceneId) {
    
    console.log("setupInfoPanel çağrıldı, sceneId:", sceneId);
    console.log("window.panoramaData:", window.panoramaData);
    
    if (window.panoramaData && window.panoramaData[sceneId]) {
        console.log("Sahne verileri bulundu:", window.panoramaData[sceneId]);
        
        
        const titleElement = document.getElementById('sceneTitle');
        const descElement = document.getElementById('sceneDescription');
        
        if (titleElement) {
            titleElement.textContent = window.panoramaData[sceneId].title || "Başlık Bulunamadı";
        }
        
        if (descElement) {
            
            const description = window.panoramaData[sceneId].description || "Bu mekan hakkında henüz detaylı bilgi bulunmamaktadır.";
            descElement.textContent = description;
            descElement.style.display = "block"; 
            console.log("Güncellenen açıklama:", description);
        }
        
        
        const locationNameElement = document.getElementById('locationName');
        if (locationNameElement) {
            locationNameElement.textContent = window.panoramaData[sceneId].title || "Bilinmeyen Konum";
        }
        
        
        const gallery = document.getElementById('sceneGallery');
        if (gallery) {
            gallery.innerHTML = '';
            
            
            
            const galleryImages = [
                'assets/images/gallery/placeholder1.jpg',
                'assets/images/gallery/placeholder2.jpg',
                'assets/images/gallery/placeholder3.jpg'
            ];
            
            galleryImages.forEach(src => {
                const img = document.createElement('img');
                img.src = src;
                img.classList.add('gallery-img');
                img.alt = window.panoramaData[sceneId].title + " Görüntüsü";
                img.addEventListener('click', () => {
                    openImageViewer(src);
                });
                gallery.appendChild(img);
            });
        }
    } else {
        console.warn("Sahne verisi bulunamadı:", sceneId);
    }
}


function updateMapMarkers(sceneId) {
    document.querySelectorAll('.map-marker').forEach(marker => {
        marker.classList.remove('active');
        if (marker.dataset.sceneId === sceneId) {
            marker.classList.add('active');
            
            
            
        }
    });
}


function logSceneChange(sceneId) {
    
    fetch('admin/log_activity.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=scene_change&scene_id=${sceneId}&campus=${window.campusId || ''}`
    }).catch(error => {
        console.warn('Ziyaret kaydedilemedi:', error);
        
    });
}


function setupInterface() {
    
    document.addEventListener('mousemove', () => {
        showInterface();
    });
    
    
    document.querySelectorAll('.scene-item').forEach(item => {
        item.addEventListener('click', () => {
            const sceneId = item.dataset.sceneId;
            if (viewer) {
                viewer.loadScene(sceneId);
            }
        });
    });
    
    
    document.querySelectorAll('.map-marker').forEach(marker => {
        marker.addEventListener('click', () => {
            const sceneId = marker.dataset.sceneId;
            if (viewer) {
                viewer.loadScene(sceneId);
                toggleCampusMap(false);
            }
        });
    });
    
    
    const prevButton = document.getElementById('prevScene');
    if (prevButton) {
        prevButton.addEventListener('click', () => {
            if (currentSceneIndex > 0) {
                const sceneItems = document.querySelectorAll('.scene-item');
                const prevSceneId = sceneItems[currentSceneIndex - 1].dataset.sceneId;
                if (viewer) {
                    viewer.loadScene(prevSceneId);
                }
            }
        });
    }
    
    
    const nextButton = document.getElementById('nextScene');
    if (nextButton) {
        nextButton.addEventListener('click', () => {
            const sceneItems = document.querySelectorAll('.scene-item');
            if (currentSceneIndex < sceneItems.length - 1) {
                const nextSceneId = sceneItems[currentSceneIndex + 1].dataset.sceneId;
                if (viewer) {
                    viewer.loadScene(nextSceneId);
                }
            }
        });
    }
    
    
    const infoButton = document.getElementById('infoButton');
    if (infoButton) {
        infoButton.addEventListener('click', () => {
            toggleSceneInfo();
        });
    }
    
    
    const closeInfoButton = document.getElementById('closeInfo');
    if (closeInfoButton) {
        closeInfoButton.addEventListener('click', () => {
            toggleSceneInfo(false);
        });
    }
    
    
    const mapButton = document.getElementById('mapButton');
    if (mapButton) {
        mapButton.addEventListener('click', () => {
            toggleCampusMap();
        });
    }
    
    
    const closeMapButton = document.getElementById('closeMap');
    if (closeMapButton) {
        closeMapButton.addEventListener('click', () => {
            toggleCampusMap(false);
        });
    }
    
    
    const fullscreenButton = document.getElementById('fullscreenButton');
    if (fullscreenButton) {
        fullscreenButton.addEventListener('click', () => {
            toggleFullscreen();
        });
    }
    
    
    const zoomInButton = document.getElementById('zoomInButton');
    if (zoomInButton) {
        zoomInButton.addEventListener('click', () => {
            if (viewer) {
                const currentHfov = viewer.getHfov();
                viewer.setHfov(Math.max(viewer.getConfig().minHfov, currentHfov - 10));
            }
        });
    }
    
    
    const zoomOutButton = document.getElementById('zoomOutButton');
    if (zoomOutButton) {
        zoomOutButton.addEventListener('click', () => {
            if (viewer) {
                const currentHfov = viewer.getHfov();
                viewer.setHfov(Math.min(viewer.getConfig().maxHfov, currentHfov + 10));
            }
        });
    }
    
    
    const autorotateButton = document.getElementById('toggleAutorotate');
    if (autorotateButton) {
        autorotateButton.addEventListener('click', () => {
            toggleAutoRotate();
        });
    }
    
    
    const shareButton = document.getElementById('shareButton');
    if (shareButton) {
        shareButton.addEventListener('click', () => {
            showShareDialog();
        });
    }
    
    
    const helpButton = document.getElementById('helpButton');
    if (helpButton) {
        helpButton.addEventListener('click', () => {
            showHelpDialog();
        });
    }
    
    
    const closeViewerButton = document.getElementById('closeViewer');
    if (closeViewerButton) {
        closeViewerButton.addEventListener('click', () => {
            closeImageViewer();
        });
    }
    
    
    document.addEventListener('keydown', (e) => {
        switch (e.key) {
            case 'ArrowLeft':
                if (prevButton && !prevButton.disabled) {
                    prevButton.click();
                }
                break;
            case 'ArrowRight':
                if (nextButton && !nextButton.disabled) {
                    nextButton.click();
                }
                break;
            case 'i':
            case 'I':
                if (infoButton) {
                    infoButton.click();
                }
                break;
            case 'm':
            case 'M':
                if (mapButton) {
                    mapButton.click();
                }
                break;
            case 'f':
            case 'F':
                if (fullscreenButton) {
                    fullscreenButton.click();
                }
                break;
            case 'r':
            case 'R':
                if (autorotateButton) {
                    autorotateButton.click();
                }
                break;
            case '+':
                if (zoomInButton) {
                    zoomInButton.click();
                }
                break;
            case '-':
                if (zoomOutButton) {
                    zoomOutButton.click();
                }
                break;
            case 'Escape':
                if (isSceneInfoVisible) toggleSceneInfo(false);
                if (isCampusMapVisible) toggleCampusMap(false);
                break;
        }
    });
}


function toggleSceneInfo(show = null) {
    isSceneInfoVisible = show !== null ? show : !isSceneInfoVisible;
    
    const infoPanel = document.getElementById('sceneInfo');
    const infoButton = document.getElementById('infoButton');
    
    if (infoPanel && infoButton) {
        if (isSceneInfoVisible) {
            infoPanel.classList.add('visible');
            infoButton.classList.add('active');
            
            
            if (isCampusMapVisible) {
                toggleCampusMap(false);
            }
        } else {
            infoPanel.classList.remove('visible');
            infoButton.classList.remove('active');
        }
    }
}


function toggleCampusMap(show = null) {
    isCampusMapVisible = show !== null ? show : !isCampusMapVisible;
    
    const mapPanel = document.getElementById('campusMap');
    const mapButton = document.getElementById('mapButton');
    
    if (mapPanel && mapButton) {
        if (isCampusMapVisible) {
            mapPanel.classList.add('visible');
            mapButton.classList.add('active');
            
            
            if (isSceneInfoVisible) {
                toggleSceneInfo(false);
            }
        } else {
            mapPanel.classList.remove('visible');
            mapButton.classList.remove('active');
        }
    }
}


function toggleFullscreen() {
    if (viewer) {
        try {
            
            const panoramaElement = document.getElementById('panorama');
            
            if (!document.fullscreenElement && 
                !document.mozFullScreenElement &&
                !document.webkitFullscreenElement && 
                !document.msFullscreenElement) {
                
                
                if (panoramaElement.requestFullscreen) {
                    panoramaElement.requestFullscreen();
                } else if (panoramaElement.msRequestFullscreen) {
                    panoramaElement.msRequestFullscreen();
                } else if (panoramaElement.mozRequestFullScreen) {
                    panoramaElement.mozRequestFullScreen();
                } else if (panoramaElement.webkitRequestFullscreen) {
                    panoramaElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                } else {
                    
                    console.log("Native fullscreen API bulunamadı, Pannellum'un kendi yöntemi kullanılıyor");
                    viewer.toggleFullscreen();
                }
                
                isFullscreen = true;
            } else {
                
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else {
                    
                    viewer.toggleFullscreen();
                }
                
                isFullscreen = false;
            }
            
            const fullscreenButton = document.getElementById('fullscreenButton');
            if (fullscreenButton) {
                fullscreenButton.classList.toggle('active', isFullscreen);
            }
        } catch (error) {
            console.warn('Tam ekran modu açılamadı:', error);
            
        }
    }
}


function toggleAutoRotate() {
    if (viewer) {
        isAutorotate = !isAutorotate;
        
        const autorotateButton = document.getElementById('toggleAutorotate');
        if (autorotateButton) {
            if (isAutorotate) {
                viewer.startAutoRotate(1.5);
                autorotateButton.classList.add('active');
            } else {
                viewer.stopAutoRotate();
                autorotateButton.classList.remove('active');
            }
        }
    }
}


function showInterface() {
    if (isInterfaceHidden) {
        isInterfaceHidden = false;
        
        const header = document.getElementById('tourHeader');
        if (header) {
            header.classList.remove('hidden');
        }
    }
    
    
    if (interfaceTimeout) {
        clearTimeout(interfaceTimeout);
    }
    
    
    interfaceTimeout = setTimeout(() => {
        if (!isSceneInfoVisible && !isCampusMapVisible) {
            const header = document.getElementById('tourHeader');
            if (header) {
                header.classList.add('hidden');
                isInterfaceHidden = true;
            }
        }
    }, 5000);
}


function openImageViewer(src) {
    const imageViewer = document.getElementById('imageViewer');
    const viewerImage = document.getElementById('viewerImage');
    
    if (imageViewer && viewerImage) {
        viewerImage.src = src;
        imageViewer.style.display = 'flex';
    }
}


function closeImageViewer() {
    const imageViewer = document.getElementById('imageViewer');
    if (imageViewer) {
        imageViewer.style.display = 'none';
    }
}


function showShareDialog() {
    const url = window.location.href;
    
    if (typeof Swal === 'function') {
        Swal.fire({
            title: 'Sanal Turu Paylaş',
            html: `
                <div style="margin-bottom: 20px;">
                    <p style="margin-bottom: 15px;">Bu sanal tur görünümünü paylaşmak için aşağıdaki bağlantıyı kullanabilirsiniz:</p>
                    <div style="display: flex; margin-bottom: 20px;">
                        <input type="text" id="shareUrl" value="${url}" readonly style="flex: 1; padding: 10px; border-radius: 4px 0 0 4px; border: 1px solid #ced4da; border-right: none;">
                        <button id="copyButton" style="padding: 10px 15px; background-color: #0c4da2; color: white; border: none; border-radius: 0 4px 4px 0; cursor: pointer;">Kopyala</button>
                    </div>
                    <p style="margin-bottom: 15px;">Veya sosyal medyada paylaş:</p>
                    <div style="display: flex; justify-content: center; gap: 15px;">
                        <a href="https:
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <a href="https:
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https:
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="mailto:?subject=Kırklareli Üniversitesi Sanal Tur&body=${encodeURIComponent(url)}" target="_blank" style="background-color: #6c757d; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                            <i class="bi bi-envelope"></i>
                        </a>
                    </div>
                </div>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            focusConfirm: false,
            didOpen: () => {
                const copyButton = document.getElementById('copyButton');
                const shareUrl = document.getElementById('shareUrl');
                
                if (copyButton && shareUrl) {
                    copyButton.addEventListener('click', () => {
                        shareUrl.select();
                        document.execCommand('copy');
                        
                        copyButton.textContent = 'Kopyalandı!';
                        copyButton.style.backgroundColor = '#28a745';
                        
                        setTimeout(() => {
                            copyButton.textContent = 'Kopyala';
                            copyButton.style.backgroundColor = '#0c4da2';
                        }, 2000);
                    });
                }
            }
        });
    }
}


function showHelpDialog() {
    if (typeof Swal === 'function') {
        Swal.fire({
            title: 'Sanal Tur Kullanımı',
            html: `
                <div style="text-align: left; max-height: 400px; overflow-y: auto; padding: 10px;">
                    <h5>Sanal Turda Gezinme</h5>
                    <p>Panoramik görüntüyü keşfetmek için parmağınızı veya farenizi sürükleyin. Çift tıklama yaparak belirli bir noktaya odaklanabilirsiniz.</p>
                    
                    <h5>Kontrol Düğmeleri</h5>
                    <ul style="padding-left: 20px; margin-bottom: 15px;">
                        <li><i class="bi bi-info-lg"></i> - Mekan hakkında bilgi görüntüler</li>
                        <li><i class="bi bi-map"></i> - Kampüs haritasını gösterir</li>
                        <li><i class="bi bi-fullscreen"></i> - Tam ekran modunu açar/kapatır</li>
                        <li><i class="bi bi-zoom-in"></i> / <i class="bi bi-zoom-out"></i> - Yakınlaştırma/Uzaklaştırma</li>
                    </ul>
                    
                    <h5>Üst Kontroller</h5>
                    <ul style="padding-left: 20px; margin-bottom: 15px;">
                        <li><i class="bi bi-arrow-repeat"></i> - Otomatik döndürme modunu açar/kapatır</li>
                        <li><i class="bi bi-share"></i> - Mevcut görünümü paylaşmanızı sağlar</li>
                        <li><i class="bi bi-question-lg"></i> - Yardım menüsünü açar</li>
                        <li><i class="bi bi-house"></i> - Ana sayfaya döner</li>
                    </ul>
                    
                    <h5>Sahneler Arası Geçiş</h5>
                    <p>Sahneler arasında geçiş yapmak için:</p>
                    <ul style="padding-left: 20px; margin-bottom: 15px;">
                        <li>Alttaki sahne küçük resimlerine tıklayın</li>
                        <li>Ok işaretlerini kullanın (<i class="bi bi-chevron-left"></i> / <i class="bi bi-chevron-right"></i>)</li>
                        <li>Panorama içindeki gezinme noktalarına tıklayın</li>
                        <li>Haritadaki konum işaretlerine tıklayın</li>
                    </ul>
                    
                    <h5>Klavye Kısayolları</h5>
                    <ul style="padding-left: 20px; margin-bottom: 15px;">
                        <li><strong>Sol/Sağ Ok</strong> - Önceki/Sonraki sahne</li>
                        <li><strong>I</strong> - Mekan bilgisi panelini açar/kapatır</li>
                        <li><strong>M</strong> - Harita panelini açar/kapatır</li>
                        <li><strong>F</strong> - Tam ekran modunu açar/kapatır</li>
                        <li><strong>R</strong> - Otomatik döndürmeyi açar/kapatır</li>
                        <li><strong>+/-</strong> - Yakınlaştırma/Uzaklaştırma</li>
                        <li><strong>ESC</strong> - Açık panelleri kapatır</li>
                    </ul>
                </div>
            `,
            showConfirmButton: true,
            confirmButtonText: 'Anladım',
            confirmButtonColor: '#0c4da2',
            focusConfirm: false
        });
    }
}


window.initTour = initTour;
window.toggleSceneInfo = toggleSceneInfo;
window.toggleCampusMap = toggleCampusMap;
window.toggleFullscreen = toggleFullscreen;
window.toggleAutoRotate = toggleAutoRotate;
window.openImageViewer = openImageViewer;
window.closeImageViewer = closeImageViewer;
window.showShareDialog = showShareDialog;
window.showHelpDialog = showHelpDialog;