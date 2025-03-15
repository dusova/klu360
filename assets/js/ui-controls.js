function uiKontrolleriniBaslat() {
    document.getElementById('yanPanelDugme').addEventListener('click', function() {
        const yanPanel = document.getElementById('yanPanel');
        yanPanel.classList.toggle('yanPanelKapali');
        
        const ikon = this.querySelector('i');
        if (yanPanel.classList.contains('yanPanelKapali')) {
            ikon.classList.remove('fa-chevron-left');
            ikon.classList.add('fa-chevron-right');
        } else {
            ikon.classList.remove('fa-chevron-right');
            ikon.classList.add('fa-chevron-left');
        }
    });

    document.querySelectorAll('.kampusOge > a').forEach(oge => {
        oge.addEventListener('click', function(e) {
            e.preventDefault();
            const ebeveyn = this.parentElement;
            
            if (ebeveyn.classList.contains('yakinda')) {
                alert('Bu kampüs yakında eklenecektir.');
                return;
            }
            
            ebeveyn.classList.toggle('aktif');
            
            if (ebeveyn.classList.contains('aktif')) {
                document.querySelectorAll('.kampusOge').forEach(digerOge => {
                    if (digerOge !== ebeveyn) {
                        digerOge.classList.remove('aktif');
                    }
                });
            }
        });
    });

    document.querySelectorAll('.ilerlemeNokta').forEach((nokta, indeks) => {
        nokta.addEventListener('click', function() {
            const konumlar = ['teknik-giris', 'teknik-avlu', 'teknik-yemekhane', 'teknik-lab', 'teknik-kantin'];
            if (konumlar[indeks]) {
                panoramaYukle(konumlar[indeks]);
            }
        });
    });

    document.addEventListener('keydown', function(e) {
        const konumlar = ['teknik-giris', 'teknik-avlu', 'teknik-yemekhane', 'teknik-lab', 'teknik-kantin'];
        const mevcutIndeks = konumlar.indexOf(mevcutKonum);
        
        if (e.key === 'ArrowRight' && mevcutIndeks < konumlar.length - 1) {
            panoramaYukle(konumlar[mevcutIndeks + 1]);
        } else if (e.key === 'ArrowLeft' && mevcutIndeks > 0) {
            panoramaYukle(konumlar[mevcutIndeks - 1]);
        }
    });
}