


document.addEventListener('DOMContentLoaded', function() {
    
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });

    
    initSmoothScroll();
    
    
    setupScrollDown();
    
    
    setupBackToTop();
    
    
    setupCampusCards();
    
    
    setupFeatureCards();
    
    
    setupCreatorCards();
});


window.addEventListener('load', function() {
    
    hidePreloader();
});


function hidePreloader() {
    setTimeout(function() {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            preloader.style.opacity = '0';
            setTimeout(() => {
                preloader.style.display = 'none';
            }, 500);
        }
    }, 800); 
}


function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                
                if ('scrollBehavior' in document.documentElement.style) {
                    window.scrollTo({
                        top: targetElement.offsetTop,
                        behavior: 'smooth'
                    });
                } else {
                    
                    const startPosition = window.pageYOffset;
                    const targetPosition = targetElement.offsetTop;
                    const distance = targetPosition - startPosition;
                    const duration = 800;
                    let start = null;
                    
                    window.requestAnimationFrame(step);
                    
                    function step(timestamp) {
                        if (!start) start = timestamp;
                        const progress = timestamp - start;
                        const percentage = Math.min(progress / duration, 1);
                        const easeInOutQuad = percentage < 0.5
                            ? 2 * percentage * percentage
                            : 1 - Math.pow(-2 * percentage + 2, 2) / 2;
                            
                        window.scrollTo(0, startPosition + distance * easeInOutQuad);
                        
                        if (progress < duration) {
                            window.requestAnimationFrame(step);
                        }
                    }
                }
            }
        });
    });
}


function setupScrollDown() {
    const scrollDownBtn = document.getElementById('scroll-down');
    if (scrollDownBtn) {
        scrollDownBtn.addEventListener('click', function() {
            const campusSection = document.getElementById('campus-section');
            if (campusSection) {
                window.scrollTo({
                    top: campusSection.offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    }
}


function setupBackToTop() {
    const backToTopBtn = document.getElementById('backToTop');
    if (backToTopBtn) {
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopBtn.classList.add('visible');
            } else {
                backToTopBtn.classList.remove('visible');
            }
        });
        
        
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
}


function setupCampusCards() {
    const campusCards = document.querySelectorAll('.campus-card:not(.disabled)');
    
    campusCards.forEach(card => {
        
        card.addEventListener('mouseenter', function() {
            const button = this.querySelector('.tour-button');
            if (button) {
                button.classList.add('hover-effect');
            }
        });
        
        
        card.addEventListener('mouseleave', function() {
            const button = this.querySelector('.tour-button');
            if (button) {
                button.classList.remove('hover-effect');
            }
        });
    });
}


function setupFeatureCards() {
    const featureCards = document.querySelectorAll('.feature-card');
    
    featureCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            
            featureCards.forEach(otherCard => {
                if (otherCard !== card) {
                    otherCard.style.opacity = '0.7';
                    otherCard.style.transform = 'scale(0.98)';
                }
            });
        });
        
        card.addEventListener('mouseleave', function() {
            
            featureCards.forEach(otherCard => {
                otherCard.style.opacity = '1';
                otherCard.style.transform = 'translateY(0)';
            });
        });
    });
}


function setupCreatorCards() {
    const creatorCards = document.querySelectorAll('.creator-card');
    
    creatorCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            const avatar = this.querySelector('.creator-avatar');
            if (avatar) {
                avatar.classList.add('pulse');
            }
        });
        
        card.addEventListener('mouseleave', function() {
            const avatar = this.querySelector('.creator-avatar');
            if (avatar) {
                avatar.classList.remove('pulse');
            }
        });
    });
}


window.addEventListener('resize', function() {
    
    setTimeout(() => {
        AOS.refresh();
    }, 200);
});

