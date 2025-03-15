document.addEventListener('DOMContentLoaded', function() {
    yukleniyorGoster();
    
    console.log("DOM yüklendi, panorama başlatılmaya hazırlanıyor");
    
    if (typeof pannellum === 'undefined') {
        console.error("Pannellum kütüphanesi yüklenemedi!");
        yukleniyorGizle();
        alert("360° görüntüleyici kütüphanesi yüklenemedi. Lütfen sayfayı yenileyin veya farklı bir tarayıcı kullanın.");
        return;
    }
    
    const panoramaKonteyner = document.getElementById('panorama');
    if (!panoramaKonteyner) {
        console.error("Panorama konteyneri bulunamadı!");
        yukleniyorGizle();
        alert("Panorama görüntüleyici için konteyner bulunamadı.");
        return;
    }

    const yukleniyorElementi = document.getElementById('yukleniyor');
    if (!yukleniyorElementi) {
        console.error("Yükleniyor elementi bulunamadı!");
        alert("Yükleme ekranı elementi bulunamadı.");
        return;
    }
    
    const asistanKonteyner = document.getElementById('asistanKonteyner');
    if (!asistanKonteyner) {
        console.error("Asistan konteyneri bulunamadı!");
        alert("Asistan konteyneri bulunamadı.");
        return;
    }
    
    const asistanTetikleyici = document.getElementById('asistanTetikleyici');
    if (asistanTetikleyici) {
        console.log("Asistan tetikleyici bulundu, görünür yapılıyor...");
        asistanTetikleyici.style.display = 'flex';
        asistanTetikleyici.style.pointerEvents = 'auto';
    } else {
        console.error("Asistan tetikleyici bulunamadı!");
    }
    
    const onYuklemeResmi = new Image();
    onYuklemeResmi.onload = function() {
        console.log("Test resmi başarıyla yüklendi, panorama başlatılıyor");
        
        if (asistanKonteyner) asistanKonteyner.style.display = 'flex';
        if (asistanTetikleyici) {
            asistanTetikleyici.style.display = 'flex';
            asistanTetikleyici.style.pointerEvents = 'auto';
            asistanTetikleyici.onclick = function() {
                console.log("Asistan tetikleyici doğrudan tıklandı!");
                const asistanPanel = document.getElementById('asistanPanel');
                if (asistanPanel) {
                    asistanPanel.style.display = 'flex';
                    asistanPanel.style.animation = 'slideIn 0.3s ease forwards';
                }
            };
        }
        
        setTimeout(function() {
            uiKontrolleriniBaslat();
            
            panoramaYukle('teknik-giris');
            
            asistanBaslat();
        }, 500);
    };
    
    onYuklemeResmi.onerror = function() {
        console.error("Test resmi yüklenemedi");
        yukleniyorGizle();
        alert("Test görüntüsü yüklenemedi. Lütfen internet bağlantınızı kontrol edin ve sayfayı yenileyin.");
    };
    
    onYuklemeResmi.src = panoramaVerileri['teknik-giris'].resimUrl;
    
    setTimeout(yukleniyorGizle, 10000);
});