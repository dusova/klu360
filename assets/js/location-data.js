const panoramaVerileri = {
    'teknik-giris': {
        baslik: 'Teknik Bilimler Kampüsü - Ana Giriş',
        aciklama: 'Kırklareli Üniversitesi Teknik Bilimler Meslek Yüksekokulu ana giriş kapısı. Kampüsümüz birçok teknik programa ev sahipliği yapmaktadır.',
        detaylar: 'Bölümler: Bilgisayar Teknolojileri, Elektronik ve Otomasyon, İnşaat, Makine ve Metal Teknolojileri, Tekstil Giyim, Ayakkabı ve Deri, Elektrik ve Enerji.',
        resimUrl: '../assets/images/panoramas/teknik-giris.jpeg',
        ilerlemeIndeks: 0
    },
    'teknik-avlu': {
        baslik: 'Teknik Bilimler Kampüsü - Merkez Avlu',
        aciklama: 'Öğrencilerin dinlendiği ve sosyalleştiği merkez avlu alanı.',
        detaylar: 'Avluda öğrencilerimiz için oturma alanları, çeşitli etkinlikler için açık alan ve yeşil alanlar bulunmaktadır.',
        resimUrl: '../assets/images/panoramas/teknik-avlu.jpeg',
        ilerlemeIndeks: 1
    },
    'teknik-yemekhane': {
        baslik: 'Teknik Bilimler Kampüsü - Yemekhane',
        aciklama: 'Teknik alanlarla ilgili zengin kaynakları içeren Yemekhanemiz.',
        detaylar: 'Yemekhanemizde çeşitli teknik ve mesleki kitaplar, dergiler, elektronik kaynaklar ve çalışma alanları bulunmaktadır.',
        resimUrl: '../assets/images/panoramas/teknik-yemekhane.jpeg',
        ilerlemeIndeks: 2
    },
    'teknik-lab': {
        baslik: 'Teknik Bilimler Kampüsü - Laboratuvarlar',
        aciklama: 'Modern ekipmanlarla donatılmış uygulama laboratuvarlarımız.',
        detaylar: 'Bilgisayar, elektronik, mekanik ve diğer teknik alanlarda öğrencilerimize uygulamalı eğitim imkanı sağlayan laboratuvarlarımız bulunmaktadır.',
        resimUrl: '../assets/images/panoramas/teknik-lab.jpeg',
        ilerlemeIndeks: 3
    },
    'teknik-kantin': {
        baslik: 'Teknik Bilimler Kampüsü - Kantin',
        aciklama: 'Öğrencilerimizin yemek ve dinlenme ihtiyaçlarını karşılayan kantin alanı.',
        detaylar: 'Kantinde çeşitli yiyecek ve içecek seçenekleri, oturma alanları ve sosyalleşme imkanları bulunmaktadır.',
        resimUrl: '../assets/images/panoramas/teknik-kantin.jpeg',
        ilerlemeIndeks: 4
    }
};

function baglantilariAl(konumId) {
    const baglantiBilgileri = {
        'teknik-giris': [
            {
                pitch: 0,
                yaw: 30,
                text: "Merkez Avlu",
                type: "custom",
                cssClass: "ok-hotspot",
                createTooltipFunc: ozelIpucuOlustur,
                clickHandlerFunc: function() {
                    panoramaYukle('teknik-avlu');
                },
                okYonu: "right"
            }
        ],
        'teknik-avlu': [
            {
                pitch: -10,
                yaw: -30,
                text: "Ana Giriş",
                type: "custom",
                cssClass: "ok-hotspot",
                createTooltipFunc: ozelIpucuOlustur,
                clickHandlerFunc: function() {
                    panoramaYukle('teknik-giris');
                },
                okYonu: "left"
            },
            {
                pitch: 0,
                yaw: 90,
                text: "Yemekhane",
                type: "custom",
                cssClass: "ok-hotspot",
                createTooltipFunc: ozelIpucuOlustur,
                clickHandlerFunc: function() {
                    panoramaYukle('teknik-yemekhane');
                },
                okYonu: "right"
            },
            {
                pitch: 0,
                yaw: 180,
                text: "Laboratuvarlar",
                type: "custom",
                cssClass: "ok-hotspot",
                createTooltipFunc: ozelIpucuOlustur,
                clickHandlerFunc: function() {
                    panoramaYukle('teknik-lab');
                },
                okYonu: "down"
            },
            {
                pitch: 0,
                yaw: 270,
                text: "Kantin",
                type: "custom",
                cssClass: "ok-hotspot",
                createTooltipFunc: ozelIpucuOlustur,
                clickHandlerFunc: function() {
                    panoramaYukle('teknik-kantin');
                },
                okYonu: "left"
            }
        ],
        'teknik-yemekhane': [
            {
                pitch: 0,
                yaw: 270,
                text: "Merkez Avlu",
                type: "custom",
                cssClass: "ok-hotspot",
                createTooltipFunc: ozelIpucuOlustur,
                clickHandlerFunc: function() {
                    panoramaYukle('teknik-avlu');
                },
                okYonu: "left"
            }
        ],
        'teknik-lab': [
            {
                pitch: 0,
                yaw: 0,
                text: "Merkez Avlu",
                type: "custom",
                cssClass: "ok-hotspot",
                createTooltipFunc: ozelIpucuOlustur,
                clickHandlerFunc: function() {
                    panoramaYukle('teknik-avlu');
                },
                okYonu: "up"
            }
        ],
        'teknik-kantin': [
            {
                pitch: 0,
                yaw: 90,
                text: "Merkez Avlu",
                type: "custom",
                cssClass: "ok-hotspot",
                createTooltipFunc: ozelIpucuOlustur,
                clickHandlerFunc: function() {
                    panoramaYukle('teknik-avlu');
                },
                okYonu: "right"
            }
        ]
    };
    
    return baglantiBilgileri[konumId] || [];
}