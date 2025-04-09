


class AnimationManager {
    constructor() {
        this.isReducedMotion = this.checkReducedMotion();
        this.touchSupport = ('ontouchstart' in window);
        this.isHighPerformance = !this.isMobileDevice() && !this.isLowEndDevice();
        
        
        this.setupEventListeners();
    }
    
    
    checkReducedMotion() {
        return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }
    
    
    isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }
    
    
    isLowEndDevice() {
        
        const cpuCores = navigator.hardwareConcurrency || 1;
        return cpuCores <= 2;
    }
    
    
    setupEventListeners() {
        
        window.matchMedia('(prefers-reduced-motion: reduce)').addEventListener('change', (e) => {
            this.isReducedMotion = e.matches;
        });
        
        
        document.addEventListener('DOMContentLoaded', () => {
            this.initializeAnimations();
        });
    }
    
    
    initializeAnimations() {
        
        if (document.querySelector('.hero-section')) {
            this.setupHomeAnimations();
        }
        
        
        if (document.querySelector('.tour-container')) {
            this.setupTourAnimations();
        }
    }
    
    
    setupHomeAnimations() {
        
        if (this.isReducedMotion) {
            this.setupMinimalAnimations();
            return;
        }
        
        
        this.animateHeroEntrance();
        
        
        this.setupParallaxEffect();
        
        
        this.setupScrollAnimations();
        
        
        this.setup3DCardEffect();
        
        
        this.setupShineEffect();
    }
    
    
    setupTourAnimations() {
        
        if (this.isReducedMotion) {
            return;
        }
        
        
        this.setupSceneTransitions();
        
        
        this.setupHotspotAnimations();
        
        
        this.setupMapAnimations();
        
        
        this.setupInterfaceAnimations();
    }
    
    
    setupMinimalAnimations() {
        
        document.querySelectorAll('.hero-content > *').forEach((el, index) => {
            el.style.opacity = "0";
            setTimeout(() => {
                el.style.opacity = "1";
            }, 300 + (index * 150));
        });
    }
    
    
    animateHeroEntrance() {
        const heroElements = document.querySelectorAll('.hero-content > *');
        
        heroElements.forEach((el, index) => {
            el.style.opacity = "0";
            el.style.transform = "translateY(30px)";
            el.style.transition = "opacity 0.8s ease, transform 0.8s ease";
            
            setTimeout(() => {
                el.style.opacity = "1";
                el.style.transform = "translateY(0)";
            }, 500 + (index * 200));
        });
        
        
        const scrollIndicator = document.querySelector('.scroll-indicator');
        if (scrollIndicator) {
            scrollIndicator.style.opacity = "0";
            
            setTimeout(() => {
                scrollIndicator.style.transition = "opacity 1s ease";
                scrollIndicator.style.opacity = "1";
            }, 1800);
        }
    }
    
    
    setupParallaxEffect() {
        if (!this.isHighPerformance) return;
        
        const heroSection = document.querySelector('.hero-section');
        const heroContent = document.querySelector('.hero-content');
        
        if (heroSection && heroContent) {
            window.addEventListener('scroll', () => {
                const scrollPosition = window.scrollY;
                if (scrollPosition < window.innerHeight) {
                    const translateY = scrollPosition * 0.4;
                    heroContent.style.transform = `translateY(${translateY}px)`;
                    heroSection.style.backgroundPositionY = `${scrollPosition * 0.1}px`;
                }
            });
        }
    }
    
    
    setupScrollAnimations() {
        const animateElements = document.querySelectorAll('.campus-card, .feature-card, .creator-card');
        
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -10% 0px'
        });
        
        
        const style = document.createElement('style');
        style.textContent = `
            .campus-card, .feature-card, .creator-card {
                opacity: 0;
                transform: translateY(30px);
                transition: opacity 0.6s ease-out, transform 0.6s ease-out;
            }
            
            .animate-in {
                opacity: 1 !important;
                transform: translateY(0) !important;
            }
        `;
        document.head.appendChild(style);
        
        
        animateElements.forEach(element => {
            observer.observe(element);
        });
    }
    
    
    setup3DCardEffect() {
        if (!this.isHighPerformance || this.touchSupport) return;
        
        const cards = document.querySelectorAll('.campus-card:not(.disabled)');
        
        cards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const cardRect = card.getBoundingClientRect();
                const cardCenterX = cardRect.left + cardRect.width / 2;
                const cardCenterY = cardRect.top + cardRect.height / 2;
                const mouseX = e.clientX - cardCenterX;
                const mouseY = e.clientY - cardCenterY;
                
                
                const rotateY = Math.min(Math.max((mouseX / cardRect.width) * 10, -10), 10);
                const rotateX = Math.min(Math.max((mouseY / cardRect.height) * -10, -10), 10);
                
                
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
            });
            
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1)';
            });
        });
    }
    
    
    setupShineEffect() {
        if (!this.isHighPerformance || this.touchSupport) return;
        
        const style = document.createElement('style');
        style.textContent = `
            .shine-effect {
                position: relative;
                overflow: hidden;
            }
            
            .shine-effect::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 50%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transform: skewX(-25deg);
                animation: shine 8s infinite;
            }
            
            @keyframes shine {
                0%, 100% {
                    left: -100%;
                }
                20%, 80% {
                    left: 150%;
                }
            }
        `;
        document.head.appendChild(style);
        
        
        const exploreBtn = document.querySelector('.explore-btn');
        if (exploreBtn) {
            exploreBtn.classList.add('shine-effect');
        }
        
        
        const tourButtons = document.querySelectorAll('.tour-button:not(.disabled)');
        tourButtons.forEach(button => {
            button.classList.add('shine-effect');
        });
    }
    
    
    setupSceneTransitions() {
        
        
        const style = document.createElement('style');
        style.textContent = `
            .scene-notification {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: rgba(0, 0, 0, 0.7);
                color: white;
                padding: 15px 30px;
                border-radius: 50px;
                font-weight: 600;
                opacity: 0;
                z-index: 200;
                pointer-events: none;
                transition: opacity 0.3s;
            }
            
            .scene-notification.show {
                opacity: 1;
                animation: notificationFade 2s forwards;
            }
            
            @keyframes notificationFade {
                0% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
                20% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
                80% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
                100% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
            }
        `;
        document.head.appendChild(style);
        
        
        const notification = document.createElement('div');
        notification.className = 'scene-notification';
        document.body.appendChild(notification);
        
        
        const checkViewerAndSetup = () => {
            if (window.viewer) {
                window.viewer.on('scenechange', (sceneId) => {
                    
                    if (window.panoramaData && window.panoramaData[sceneId]) {
                        notification.textContent = window.panoramaData[sceneId].title;
                        notification.classList.add('show');
                        
                        
                        setTimeout(() => {
                            notification.classList.remove('show');
                        }, 2000);
                    }
                });
                
                return true;
            }
            return false;
        };
        
        
        if (!checkViewerAndSetup()) {
            const interval = setInterval(() => {
                if (checkViewerAndSetup()) {
                    clearInterval(interval);
                }
            }, 500);
            
            
            setTimeout(() => {
                clearInterval(interval);
            }, 10000);
        }
    }
    
    
    setupHotspotAnimations() {
        const style = document.createElement('style');
        style.textContent = `
            .pnlm-hotspot {
                transition: transform 0.3s !important;
            }
            
            .pnlm-hotspot:hover {
                z-index: 10 !important;
            }
            
            @keyframes pulse {
                0% {
                    box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.6);
                }
                70% {
                    box-shadow: 0 0 0 10px rgba(255, 255, 255, 0);
                }
                100% {
                    box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
                }
            }
            
            @keyframes bounce {
                0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
                40% {transform: translateY(-5px);}
                60% {transform: translateY(-3px);}
            }
            
            .pulsate {
                animation: pulse 2s infinite;
            }
            
            .bounce {
                animation: bounce 2s infinite;
            }
        `;
        document.head.appendChild(style);
        
        
        const addHotspotAnimations = () => {
            const hotspots = document.querySelectorAll('.pnlm-hotspot');
            
            hotspots.forEach((hotspot, index) => {
                
                if (hotspot.querySelector('.custom-hotspot')) {
                    const customHotspot = hotspot.querySelector('.custom-hotspot');
                    
                    if (customHotspot.classList.contains('info-hotspot')) {
                        customHotspot.classList.add('pulsate');
                    } else if (customHotspot.classList.contains('link-hotspot')) {
                        customHotspot.classList.add('bounce');
                    }
                }
            });
        };
        
        
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.addedNodes.length > 0) {
                    
                    addHotspotAnimations();
                }
            });
        });
        
        
        const panoramaContainer = document.getElementById('panorama');
        if (panoramaContainer) {
            observer.observe(panoramaContainer, {
                childList: true,
                subtree: true
            });
            
            
            addHotspotAnimations();
        }
    }
    
    
    setupMapAnimations() {
        
        const style = document.createElement('style');
        style.textContent = `
            @keyframes bounce {
                0%, 20%, 50%, 80%, 100% {transform: translate(-50%, -100%);}
                40% {transform: translate(-50%, -110%);}
                60% {transform: translate(-50%, -105%);}
            }
            
            .map-marker.active {
                animation: bounce 2s infinite;
            }
            
            .map-marker i {
                transition: transform 0.3s, color 0.3s;
            }
            
            .map-marker:hover i {
                transform: scale(1.2);
            }
            
            .map-transition {
                transition: transform 0.3s ease-out !important;
            }
        `;
        document.head.appendChild(style);
    }
    
    
    setupInterfaceAnimations() {
        
        const sideControls = document.querySelector('.side-controls');
        if (sideControls) {
            sideControls.style.opacity = '0';
            sideControls.style.transform = 'translateX(-20px) translateY(-50%)';
            sideControls.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            
            setTimeout(() => {
                sideControls.style.opacity = '1';
                sideControls.style.transform = 'translateX(0) translateY(-50%)';
            }, 800);
        }
        
        
        const sceneNavigation = document.querySelector('.scene-navigation');
        if (sceneNavigation) {
            sceneNavigation.style.opacity = '0';
            sceneNavigation.style.transform = 'translateY(20px)';
            sceneNavigation.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            
            setTimeout(() => {
                sceneNavigation.style.opacity = '1';
                sceneNavigation.style.transform = 'translateY(0)';
            }, 600);
        }
        
        
        const addPanelAnimation = (selector, showClass) => {
            const style = document.createElement('style');
            style.textContent = `
                ${selector} {
                    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.4s ease !important;
                    opacity: 0;
                }
                
                ${selector}.${showClass} {
                    opacity: 1;
                }
            `;
            document.head.appendChild(style);
        };
        
        addPanelAnimation('.scene-info', 'visible');
        addPanelAnimation('.campus-map', 'visible');
        
        
        const style = document.createElement('style');
        style.textContent = `
            .info-close, .map-close {
                transition: transform 0.3s ease !important;
            }
            
            .info-close:hover, .map-close:hover {
                transform: rotate(90deg) !important;
            }
        `;
        document.head.appendChild(style);
    }
    
    
    animatePage(page) {
        switch (page) {
            case 'about':
                
                break;
                
            case 'contact':
                
                break;
                
            case 'gallery':
                
                this.animateGalleryItems();
                break;
        }
    }
    
    
    animateGalleryItems() {
        const items = document.querySelectorAll('.gallery-item');
        
        items.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'scale(0.9)';
            
            setTimeout(() => {
                item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                item.style.opacity = '1';
                item.style.transform = 'scale(1)';
            }, 100 + (index * 50));
        });
    }
}


document.addEventListener('DOMContentLoaded', () => {
    window.animationManager = new AnimationManager();
});