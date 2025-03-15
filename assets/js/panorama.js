let goruntule;
let otomatikDonuyor = false;
let mevcutKonum = 'teknik-giris';

function yukleniyorGoster() {
    document.getElementById('yukleniyor').style.display = 'flex';
}

function yukleniyorGizle() {
    const yukleniyorElementi = document.getElementById('yukleniyor');
    if (yukleniyorElementi) {
        yukleniyorElementi.style.display = 'none';
        console.log("Yükleniyor ekranı gizlendi");
    } else {
        console.error("Yükleniyor elementi bulunamadı");
    }
}

function ozelIpucuOlustur(hotSpotDiv, hotSpotData) {
    hotSpotDiv.classList.add('ok-hotspot');
    
    hotSpotDiv.style.backgroundColor = 'rgba(255,255,255,0.1)'; 
    
    const mevcutIkon = hotSpotDiv.querySelector('.ok-ikon');
    if (mevcutIkon) {
        hotSpotDiv.removeChild(mevcutIkon);
    }
    
    const okIkonuDiv = document.createElement('div');
    okIkonuDiv.classList.add('ok-ikon');
    
    let ikonClass = 'fa-arrow-right'; 
    
    if (hotSpotData && hotSpotData.okYonu) {
        switch(hotSpotData.okYonu) {
            case "up":
                ikonClass = 'fa-arrow-up';
                break;
            case "right":
                ikonClass = 'fa-arrow-right';
                break;
            case "down":
                ikonClass = 'fa-arrow-down';
                break;
            case "left":
                ikonClass = 'fa-arrow-left';
                break;
            default:
                ikonClass = 'fa-arrow-alt-circle-right';
        }
    }
    
    okIkonuDiv.innerHTML = `<i class="fas ${ikonClass}" style="font-size: 36px; color: white; text-shadow: 0 0 6px black, 0 0 10px black;"></i>`;
    hotSpotDiv.appendChild(okIkonuDiv);
    
    console.log("Ok ikonu oluşturuldu:", ikonClass); 
    
    hotSpotDiv.addEventListener('mouseover', function() {
        console.log("Hotspot üzerine gelindi"); 
        
        if (!hotSpotDiv.ipucu && hotSpotData && hotSpotData.text) {
            hotSpotDiv.ipucu = document.createElement('div');
            hotSpotDiv.ipucu.classList.add('pnlm-tooltip');
            hotSpotDiv.ipucu.classList.add('ok-tooltip');
            
            const ipucuSpan = document.createElement('span');
            ipucuSpan.innerHTML = hotSpotData.text;
            hotSpotDiv.ipucu.appendChild(ipucuSpan);
            
            hotSpotDiv.appendChild(hotSpotDiv.ipucu);
            hotSpotDiv.style.zIndex = "99";
        }
    });
    
    hotSpotDiv.addEventListener('mouseout', function() {
        if (hotSpotDiv.ipucu) {
            hotSpotDiv.removeChild(hotSpotDiv.ipucu);
            delete hotSpotDiv.ipucu;
            hotSpotDiv.style.zIndex = "auto";
        }
    });
}

function goruntuleyiciYuklendigindeOklariDuzelt() {
    setTimeout(function() {
        const hotspots = document.querySelectorAll('.pnlm-hotspot');
        hotspots.forEach(function(hotspot) {
            if (!hotspot.classList.contains('ok-hotspot')) {
                hotspot.classList.add('ok-hotspot');
            }
            hotspot.style.opacity = '1';
            hotspot.style.pointerEvents = 'auto';
        });
        
        console.log(hotspots.length + " hotspot bulundu ve düzeltildi");
    }, 500);
}

function goruntuleyiciBaslat(id, resimUrl) {
    const goruntuleyiciAyarlari = {
        type: 'equirectangular',
        panorama: resimUrl,
        autoLoad: true,
        compass: true,
        showFullscreenCtrl: false,
        showZoomCtrl: false,
        hotSpots: baglantilariAl(id),
        sceneFadeDuration: 1000,
        hfov: 100,
        minHfov: 50,
        maxHfov: 120,
        pitch: 0,
        yaw: 0,
        haov: 360,
        vaov: 180,
        minPitch: -90,
        maxPitch: 90,
        mouseZoom: true,
        friction: 0.15,
        onLoad: function() {
            yukleniyorGizle();
            console.log("Panorama başarıyla yüklendi:", id);
            goruntuleyiciYuklendigindeOklariDuzelt(); 
        },
        onError: function(hata) {
            console.error("Panorama başlatma hatası:", hata);
            yukleniyorGizle();
            alert("Panorama yüklenirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.");
        }
    };

    try {
        if (!goruntule) {
            goruntule = pannellum.viewer('panorama', goruntuleyiciAyarlari);
        } else {
            goruntule.destroy();
            goruntule = pannellum.viewer('panorama', goruntuleyiciAyarlari);
        }
        
        setTimeout(yukleniyorGizle, 3000);
        
    } catch (hata) {
        console.error("Panorama görüntüleyici oluşturma hatası:", hata);
        yukleniyorGizle();
        alert("Panorama görüntüleyici oluşturulurken bir hata oluştu.");
    }
}

function panoramaYukle(id) {
    yukleniyorGoster();
    
    const panorama = panoramaVerileri[id];
    if (!panorama) {
        yukleniyorGizle();
        console.error("Panorama verisi bulunamadı:", id);
        return;
    }

    mevcutKonum = id;

    document.querySelectorAll('.mekanOge').forEach(oge => {
        oge.classList.remove('aktif');
    });
    
    const konumElementi = document.querySelector(`.mekanOge a[onclick*="${id}"]`);
    if (konumElementi) {
        konumElementi.parentElement.classList.add('aktif');
    }

    document.querySelectorAll('.ilerlemeNokta').forEach((nokta, indeks) => {
        nokta.classList.remove('aktif');
        if (indeks === panorama.ilerlemeIndeks) {
            nokta.classList.add('aktif');
        }
    });

    document.querySelector('.bilgiKutusu h3').textContent = panorama.baslik;
    document.querySelector('.bilgiKutusu p:first-of-type').textContent = panorama.aciklama;
    document.querySelector('.bilgiKutusu p:last-of-type').innerHTML = `<strong>Detaylar:</strong> ${panorama.detaylar}`;

    const img = new Image();
    img.onload = function() {
        goruntuleyiciBaslat(id, panorama.resimUrl);
    };
    
    img.onerror = function() {
        console.error("Panorama resmi yüklenemedi:", panorama.resimUrl);
        yukleniyorGizle();
        alert("Panorama görüntüsü yüklenemedi. Lütfen daha sonra tekrar deneyin.");
    };
    
    img.src = panorama.resimUrl;
    
    setTimeout(yukleniyorGizle, 8000);
}

function tamEkranDegistir() {
    const konteyner = document.querySelector('.panoramaKonteyner');
    
    if (!document.fullscreenElement) {
        if (konteyner.requestFullscreen) {
            konteyner.requestFullscreen();
        } else if (konteyner.mozRequestFullScreen) {
            konteyner.mozRequestFullScreen();
        } else if (konteyner.webkitRequestFullscreen) {
            konteyner.webkitRequestFullscreen();
        } else if (konteyner.msRequestFullscreen) {
            konteyner.msRequestFullscreen();
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozExitFullScreen) {
            document.mozExitFullScreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }
}

function otomatikDondurmeDegistir() {
    if (!goruntule) return;
    
    otomatikDonuyor = !otomatikDonuyor;
    
    try {
        if (otomatikDonuyor) {
            if (typeof goruntule.startAutoRotate === 'function') {
                goruntule.startAutoRotate(3);
            } else if (typeof goruntule.autoRotate === 'function') {
                goruntule.autoRotate(3);
            } else {
                console.error("Otomatik döndürme metodu bulunamadı");
            }
        } else {
            if (typeof goruntule.stopAutoRotate === 'function') {
                goruntule.stopAutoRotate();
            } else if (typeof goruntule.autoRotate === 'function') {
                goruntule.autoRotate(0);
            } else {
                console.error("Otomatik döndürme durdurma metodu bulunamadı");
            }
        }
    } catch (hata) {
        console.error("Otomatik döndürme hatası:", hata);
    }
    
    const dondurDugmesi = document.getElementById('dondurme-dugme');
    if (dondurDugmesi) {
        if (otomatikDonuyor) {
            dondurDugmesi.classList.add('aktif');
        } else {
            dondurDugmesi.classList.remove('aktif');
        }
    }
}

function zum(deger) {
    if (!goruntule) return;
    
    try {
        let mevcutHfov = goruntule.getHfov();
        goruntule.setHfov(mevcutHfov + deger);
    } catch (hata) {
        console.error("Yakınlaştırma/uzaklaştırma hatası:", hata);
    }
}

function sifirla() {
    if (!goruntule) return;
    
    try {
        goruntule.setPitch(0); 
        goruntule.setYaw(0);  
        goruntule.setHfov(100); 
    } catch (hata) {
        console.error("Görüntü sıfırlama hatası:", hata);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    try {
        uiKontrolleriniBaslat();
        console.log("UI kontrolleri başlatıldı");
    } catch (hata) {
        console.error("UI kontrollerini başlatma hatası:", hata);
    }
});