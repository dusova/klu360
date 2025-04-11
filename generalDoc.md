# Kırklareli Üniversitesi 360° Sanal Tur - Genel Dökümantasyon

Bu dökümantasyon, Kırklareli Üniversitesi 360° Sanal Tur projesinin kapsamlı bir tanıtımını içermektedir. Projenin teknik altyapısı, özellikleri, geliştirme süreçleri ve yönetimi hakkında detaylı bilgi sunmaktadır.

**Versiyon:** 1.0  
**Son Güncelleme:** Nisan 2025  
**Hazırlayan:** Mustafa Arda DÜŞOVA

## İçindekiler

1. [Proje Özeti](#proje-özeti)
2. [Teknik Altyapı](#teknik-altyapı)
3. [Sistem Gereksinimleri](#sistem-gereksinimleri)
4. [Yazılım Mimarisi](#yazılım-mimarisi)
5. [Veritabanı Yapısı](#veritabanı-yapısı)
6. [API Referansları](#api-referansları)
7. [Güvenlik Protokolleri](#güvenlik-protokolleri)
8. [Performans Optimizasyonu](#performans-optimizasyonu)
9. [Kullanıcı Davranış Analizi](#kullanıcı-davranış-analizi)
10. [Ölçeklendirme Stratejisi](#ölçeklendirme-stratejisi)
11. [Test ve Kalite Kontrol](#test-ve-kalite-kontrol)
12. [Bakım ve Güncellemeler](#bakım-ve-güncellemeler)
13. [Proje Yapımcıları](#proje-yapımcıları)
14. [İletişim Bilgileri](#i̇letişim-bilgileri)

## Proje Özeti

Kırklareli Üniversitesi 360° Sanal Tur projesi, üniversitemizin kampüslerini ve tesislerini potansiyel öğrencilere, mevcut öğrencilere, akademik personele ve ziyaretçilere tanıtmak için geliştirilmiş interaktif bir web uygulamasıdır. Proje, üniversitemizin dijital dönüşüm stratejisi kapsamında hayata geçirilmiştir.

### Proje Vizyonu

Kırklareli Üniversitesi'nin fiziksel sınırlarını aşarak, eğitim tesislerini dünya genelinde erişilebilir kılmak ve üniversite seçim sürecinde öğrencilere yardımcı olmak.

### Proje Misyonu

Modern web teknolojilerini kullanarak, yüksek kaliteli ve kullanıcı dostu bir sanal deneyim sunmak, böylece kampüsümüzü uzaktan keşfetmeyi mümkün kılmak.

### Stratejik Hedefler

- Üniversite tanıtımında dijital dönüşümü tamamlamak
- Potansiyel öğrencilerin üniversite tercihinde bilinçli karar vermelerine yardımcı olmak
- Uzaktan eğitim öğrencilerine kampüs ortamını tanıtmak
- Uluslararası öğrencilerin üniversitemizi tercih etmelerini teşvik etmek
- Engelli bireyler için erişilebilirliği artırmak
- Üniversitenin dijital varlığını güçlendirmek

## Teknik Altyapı

### Frontend Teknolojileri

Sanal tur uygulaması, modern ve ölçeklenebilir bir frontend mimarisi üzerine inşa edilmiştir:

```
├── HTML5 (Semantic Markup)
├── CSS3
│   ├── Bootstrap 5.3
│   ├── Custom SCSS Framework
│   └── Responsive Design (Mobile-First Approach)
└── JavaScript (ES6+)
    ├── Pannellum.js (360° Panorama Viewer)
    ├── jQuery 3.6.0
    ├── Bootstrap JS Components
    └── Custom JavaScript Modules
        ├── Tour Navigator
        ├── Hotspot Manager
        ├── Accessibility Features
        └── Analytics Tracker
```

### Backend Teknolojileri

Uygulamanın sunucu tarafı, güvenli ve performanslı bir altyapı üzerine kurulmuştur:

```
├── PHP 7.4+
│   ├── Custom MVC Framework
│   ├── PSR-4 Compliant Autoloader
│   ├── Dependency Injection Container
│   └── Middleware Architecture
├── MySQL 5.7+ / MariaDB 10.3+
│   ├── InnoDB Engine
│   ├── UTF-8 Character Set
│   └── Optimized Query Design
└── Server Environment
    ├── Apache 2.4+ (mod_rewrite enabled)
    ├── PHP-FPM
    ├── OpCache
    └── Memory Management Optimizations
```

### Depolama ve CDN

Panoramik görüntülerin verimli sunumu için özel bir depolama ve içerik dağıtım stratejisi uygulanmıştır:

- **Görüntü Optimizasyonu:** Progressive JPEG formatı, farklı çözünürlük seviyeleri
- **Çoklu Çözünürlük:** Mobil, tablet ve masaüstü için optimize edilmiş görüntüler
- **Önbellek Stratejisi:** Browser caching, HTTP/2 push, service workers
- **Yük Dengeleme:** Ağır dosyalar için CDN entegrasyonu

## Sistem Gereksinimleri

### Sunucu Gereksinimleri

Sanal tur uygulaması için sunucu yapılandırması aşağıdaki minimum özelliklere sahip olmalıdır:

| Bileşen | Minimum Gereksinim | Önerilen |
| ------- | ------------------ | -------- |
| CPU | 2 Çekirdek, 2.0 GHz | 4+ Çekirdek, 2.5+ GHz |
| RAM | 4 GB | 8+ GB |
| Depolama | 50 GB SSD | 100+ GB SSD (RAID yapılandırması) |
| Bant Genişliği | 100 Mbps | 1 Gbps |
| İşletim Sistemi | Ubuntu 20.04 LTS | Ubuntu 22.04 LTS |
| Web Sunucusu | Apache 2.4.41+ | Apache 2.4.52+ with HTTP/2 |
| PHP Versiyonu | PHP 7.4 | PHP 8.1+ |
| MySQL Versiyonu | MySQL 5.7 | MySQL 8.0+ / MariaDB 10.6+ |

### İstemci Gereksinimleri

Kullanıcıların optimum deneyim yaşaması için önerilen minimum sistem gereksinimleri:

| Platform | Bileşen | Minimum Gereksinim |
| -------- | ------- | ------------------ |
| **Masaüstü/Dizüstü** | İşlemci | Çift çekirdekli, 2.0+ GHz |
|  | RAM | 4+ GB |
|  | Ekran | 1366x768+ çözünürlük |
|  | Grafik Kartı | WebGL destekli |
|  | Tarayıcı | Chrome 90+, Firefox 90+, Safari 14+, Edge 90+ |
|  | İnternet | 5+ Mbps indirme hızı |
| **Mobil Cihazlar** | İşletim Sistemi | Android 9.0+, iOS 13.0+ |
|  | RAM | 3+ GB |
|  | Ekran | 720x1280+ çözünürlük |
|  | İnternet | 4G/LTE veya Wi-Fi bağlantısı |
|  | Tarayıcı | Chrome (Android), Safari (iOS) |

### Web Tarayıcısı Gereksinimleri

Uygulamanın sorunsuz çalışması için gerekli web tarayıcısı özellikleri:

- JavaScript etkinleştirilmiş
- Çerezler etkinleştirilmiş
- WebGL desteği
- LocalStorage desteği
- Web Workers desteği

## Yazılım Mimarisi

### Uygulama Mimarisi

Sanal tur uygulaması, modüler ve sürdürülebilir bir yazılım mimarisi kullanılarak geliştirilmiştir:

```
klu-virtual-tour/
│   admin.php               # Admin paneli ana giriş noktası
│   create_admin.php        # İlk admin oluşturma (kurulumdan sonra silinmeli)
│   index.php               # Ana sayfa
│   login.php               # Kullanıcı girişi
│   logout.php              # Oturum kapatma
│   tour.php                # 360° Tur görüntüleyici
│
├───admin/                  # Admin paneli PHP dosyaları
│       analytics.php       # İstatistikler sayfası
│       campuses.php        # Kampüs yönetimi
│       dashboard.php       # Admin ana sayfası
│       hotspots.php        # Hotspot yönetimi
│       log_activity.php    # Aktivite kayıt API'si
│       process_campus.php  # Kampüs form işleme
│       process_hotspot.php # Hotspot form işleme
│       process_scene.php   # Sahne form işleme
│       process_settings.php # Ayarlar form işleme
│       process_user.php    # Kullanıcı form işleme
│       scenes.php          # Sahne yönetimi
│       settings.php        # Sistem ayarları
│       users.php           # Kullanıcı yönetimi
│
├───assets/                 # Statik dosyalar
│   ├───css/                # CSS dosyaları
│   │       admin.css       # Admin panel stilleri
│   │       responsive-mobile.css # Mobil uyumluluk
│   │       style.css       # Genel stiller
│   │       tour.css        # Tur görüntüleyici stilleri
│   │
│   ├───images/             # Genel resimler
│   │   │   avatar.png      # Varsayılan avatar
│   │   │   logo-white.png  # Beyaz logo
│   │   │   logo.png        # Ana logo
│   │   │
│   │   ├───campuses/       # Kampüs görselleri
│   │   │       kayali.jpg
│   │   │       luleburgaz.jpg
│   │   │       teknik-bilimler.jpg
│   │   │
│   │   ├───creators/       # Ekip üyesi görselleri
│   │   │       eylulkay.jpeg
│   │   │       fatihcbn.jpg
│   │   │       mdusova.webp
│   │   │       nadirsubasi.jpg
│   │   │
│   │   └───maps/           # Harita görselleri
│   │           teknik-bilimler.png
│   │
│   └───js/                 # JavaScript dosyaları
│           admin.js        # Admin panel işlevleri
│           animations.js   # Animasyonlar
│           export-print-filter.js # Dışa aktarma
│           hotspot-editor.js # Hotspot düzenleme
│           interactive-map.js # Etkileşimli harita
│           main.js         # Ana işlevler
│           tour.js         # Tur görüntüleyici
│
├───errors/                 # Hata sayfaları
│       403.php             # Yasak erişim
│       404.php             # Sayfa bulunamadı
│       500.php             # Sunucu hatası
│
├───includes/               # PHP sınıfları ve yardımcı dosyalar
│       admin_functions.php # Admin işlevleri
│       auth.php            # Kimlik doğrulama işlevleri
│       config.php          # Sistem yapılandırması
│       db.php              # Veritabanı bağlantısı
│       functions.php       # Genel işlevler
│
└───uploads/                # Yüklenen dosyalar
    ├───panoramas/          # 360° panorama görüntüleri
    │       67f4051519096.jpg
    │       67f4052822ee5.jpg
    │       67f4066a54263.jpg
    │       [... diğer panoramalar ...]
    │
    └───thumbnails/         # Küçük resimler
            67f4051519096_thumb.jpg
            67f4052822ee5_thumb.jpg
            67f4066a54263_thumb.jpg
            [... diğer thumbnails ...]
```

### Veri Akış Diyagramı

Sanal tur uygulamasındaki temel veri akışları aşağıdaki şekilde işlemektedir:

1. **Kullanıcı İsteği**: Kullanıcı, tarayıcı aracılığıyla tour.php?campus=X&scene=Y şeklinde bir istek gönderir.
2. **Veri Hazırlama**: Sistem, ilgili kampüs ve sahne verilerini veritabanından çeker.
3. **Panorama Yükleme**: Seçilen sahnenin panoramik görüntüsü, optimizasyon katmanı üzerinden kullanıcıya sunulur.
4. **Hotspot Verileri**: İlgili sahneye ait hotspot'lar JSON formatında JavaScript'e aktarılır.
5. **Kullanıcı Etkileşimi**: Kullanıcı, panoramayla etkileşime girer (gezinme, hotspot tıklama vb.).
6. **Analitik İzleme**: Kullanıcı davranışları, anonim olarak analitik veritabanına kaydedilir.

### Tasarım Desenleri

Uygulamada kullanılan başlıca yazılım tasarım desenleri:

- **MVC (Model-View-Controller)**: Uygulama katmanlarının ayrılması
- **Singleton**: Veritabanı bağlantısı için
- **Factory**: Veri nesnelerinin oluşturulması
- **Observer**: Kullanıcı etkileşimlerinin izlenmesi
- **Repository**: Veritabanı işlemlerinin soyutlanması
- **Strategy**: Farklı görüntüleme modları için

## Veritabanı Yapısı

### ER (Entity-Relationship) Diyagramı

Sanal tur uygulamasının veritabanı yapısı, uygun indeksleme ve ilişkisel bütünlük kuralları ile optimize edilmiştir:

```
+----------------+       +----------------+       +----------------+
|    campuses    |       |     scenes     |       |    hotspots    |
+----------------+       +----------------+       +----------------+
| PK id          |<----->| PK id          |<----->| PK id          |
|    name        |       |    campus_id   |       |    scene_id    |
|    slug        |       |    scene_id    |       |    type        |
|    description |       |    title       |       |    text        |
|    map_image   |       |    description |       |    pitch       |
|    status      |       |    image_path  |       |    yaw         |
|    created_at  |       |    thumbnail   |       |    target_scene|
|    updated_at  |       |    map_x       |       |    created_at  |
+----------------+       |    map_y       |       |    updated_at  |
                         |    status      |       +----------------+
                         |    created_at  |
                         |    updated_at  |
                         +----------------+

+----------------+       +----------------+       +----------------+
|     users      |       |     visits     |       |    settings    |
+----------------+       +----------------+       +----------------+
| PK id          |       | PK id          |       | PK id          |
|    name        |       |    ip_address  |       |    key         |
|    email       |       |    device_type |       |    value       |
|    password    |       |    browser     |       |    created_at  |
|    role        |       |    os          |       |    updated_at  |
|    status      |       |    referrer    |       +----------------+
|    last_login  |       |    campus      |
|    created_at  |       |    scene_id    |
|    updated_at  |       |    visit_time  |
+----------------+       +----------------+
```

### Veritabanı Optimizasyonu

Veritabanı performansını artırmak için uygulanan optimizasyon teknikleri:

- **İndeksleme Stratejisi**: Sık sorgulanan sütunlar için uygun indeksler
- **İlişki Tasarımı**: Verimli ilişkisel yapı ve yabancı anahtar kısıtlamaları
- **Sorgu Optimizasyonu**: Karmaşık sorgular için hazır ifadeler ve önbelleğe alma
- **Veritabanı Partition**: Ziyaret kayıtları için tarih bazlı bölümleme
- **Denormalizasyon**: Belirli durumlarda performans için kontrollü denormalizasyon

### Veri Sözlüğü Örneği

**Tablo: campuses**

| Sütun | Veri Tipi | Açıklama | Kısıtlamalar |
| ----- | --------- | -------- | ------------ |
| id | INT | Benzersiz kimlik | PK, AUTO_INCREMENT |
| name | VARCHAR(255) | Kampüs adı | NOT NULL |
| slug | VARCHAR(255) | SEO dostu URL | UNIQUE, NOT NULL |
| description | TEXT | Kampüs açıklaması | NULL olabilir |
| map_image | VARCHAR(255) | Harita görseli dosya yolu | NULL olabilir |
| status | ENUM | Kampüsün görünürlük durumu | 'active', 'inactive', DEFAULT 'active' |
| created_at | DATETIME | Oluşturulma tarihi | NOT NULL |
| updated_at | DATETIME | Son güncellenme tarihi | NOT NULL |

## API Referansları

### Dahili API Endpoints

Sanal tur uygulaması, frontend ile backend arasındaki iletişim için aşağıdaki API endpoints'lerini kullanmaktadır:

#### Kampüs Endpoints

```
GET /api.php?action=list_campuses
    Tüm aktif kampüsleri listeler

GET /api.php?action=get_campus&id={campus_id}
    Belirli bir kampüsün detaylarını döndürür

POST /api.php?action=log_campus_visit
    Bir kampüs ziyaretini kaydeder
    Parametreler: campus_id, user_agent, referer
```

#### Sahne Endpoints

```
GET /api.php?action=list_scenes&campus_id={campus_id}
    Belirli bir kampüsteki tüm sahneleri listeler

GET /api.php?action=get_scene&id={scene_id}
    Belirli bir sahnenin tüm detaylarını ve hotspot'larını döndürür

POST /api.php?action=log_scene_view
    Bir sahne görüntülenmesini kaydeder
    Parametreler: scene_id, campus_id, time_spent
```

#### Kullanıcı Endpoints

```
POST /api.php?action=login
    Kullanıcı girişi için kullanılır
    Parametreler: email, password

POST /api.php?action=logout
    Kullanıcı oturumunu sonlandırır

GET /api.php?action=check_auth
    Oturum durumunu kontrol eder
```

### API Yanıt Formatı

Tüm API istekleri, JSON formatında standart bir yanıt yapısı döndürür:

```json
{
    "status": "success", // veya "error"
    "data": {
        // İstek başarılıysa döndürülen veriler
    },
    "error": {
        "code": 400, // Hata kodu
        "message": "Error message" // Hata mesajı
    },
    "timestamp": "2025-04-10T14:30:45Z"
}
```

### API Güvenliği

- **Kimlik Doğrulama**: Admin API istekleri için JWT (JSON Web Token) tabanlı kimlik doğrulama
- **CSRF Koruması**: Tüm POST istekleri için CSRF token doğrulaması
- **Hız Sınırlama**: IP başına istek sınırlaması (rate limiting)
- **Veri Doğrulama**: Tüm istek parametreleri için kapsamlı doğrulama

## Güvenlik Protokolleri

### Uygulanan Güvenlik Önlemleri

Sanal tur uygulaması, modern web güvenlik standartlarına uygun olarak tasarlanmıştır:

#### Veri Güvenliği

- **HTTPS Zorunluluğu**: Tüm trafik SSL/TLS ile şifrelenmektedir
- **Şifre Güvenliği**: Şifreler bcrypt ile hashlenerek saklanmaktadır
- **SQL Enjeksiyon Koruması**: Parametrize sorgular ve prepared statements
- **XSS Koruması**: Çıktı kodlaması ve Content Security Policy
- **CSRF Koruması**: Tüm formlar için token doğrulaması

#### Sunucu Güvenliği

- **Güvenlik Başlıkları**: HTTP Security Headers (HSTS, X-Content-Type-Options, X-Frame-Options)
- **Dosya Yükleme Güvenliği**: Dosya türü doğrulama, boyut sınırlaması, yeniden adlandırma
- **İzin Yönetimi**: Minimum gerekli dosya ve dizin izinleri
- **Güncel Bileşenler**: Tüm yazılım bileşenleri ve kütüphaneler güncel tutulmaktadır

#### Oturum Güvenliği

- **Güvenli Çerez Ayarları**: HttpOnly, Secure, SameSite
- **Oturum Zaman Aşımı**: İnaktif oturumlar için otomatik sonlandırma
- **Rol Tabanlı Erişim Kontrolü**: Kullanıcı yetkilerine göre kısıtlı erişim

### Güvenlik Denetimi ve Testleri

Uygulamaya düzenli olarak güvenlik denetimleri ve testleri uygulanmaktadır:

- **OWASP Top 10** tehditler için güvenlik denetimleri
- **Penetrasyon Testleri**: Yılda iki kez üçüncü taraf güvenlik uzmanları tarafından
- **Kod Güvenlik Analizi**: Statik kod analizi ve güvenlik açığı taraması
- **Güvenlik Açıkları İzleme**: Kullanılan kütüphaneler için CVE takibi

## Performans Optimizasyonu

### Sayfa Yükleme Hızı

Sanal tur uygulaması, optimum kullanıcı deneyimi için aşağıdaki performans optimizasyonlarını içermektedir:

#### Frontend Optimizasyonları

- **Kod Minimizasyonu**: JavaScript ve CSS dosyalarının sıkıştırılması
- **Asset Birleştirme**: HTTP isteklerini azaltmak için dosyaların birleştirilmesi
- **Lazy Loading**: Görüntülerin ve panoramaların gerektiğinde yüklenmesi
- **Browser Caching**: Statik içerik için uygun önbellek başlıkları
- **Görüntü Optimizasyonu**: WebP formatı ve uyarlanabilir görüntüler

#### Backend Optimizasyonları

- **Veritabanı Sorgu Optimizasyonu**: İndeksleme ve sorgu iyileştirmeleri
- **Önbelleğe Alma**: Sık kullanılan veriler için sunucu taraflı önbellek
- **Asenkron İşlemler**: Ziyaret logları gibi kritik olmayan işlemler için queue kullanımı
- **Sıkıştırma**: Gzip/Brotli sıkıştırma ile ağ trafiğinin azaltılması

### Yükleme Stratejisi

Sanal tur uygulaması, büyük panoramik görüntülerin verimli yüklenmesi için özel bir strateji kullanmaktadır:

1. **Aşamalı Yükleme**: İlk olarak düşük çözünürlüklü versiyon, ardından tam görüntü
2. **Ön Yükleme**: Kullanıcının geçiş yapabileceği sahnelerin önceden yüklenmesi
3. **Görüntü Piramitleri**: Farklı zoom seviyeleri için optimize edilmiş görüntüler
4. **Kod Bölme**: JavaScript kodunun ihtiyaç duyulduğunda yüklenmesi

### Performans Ölçütleri

Uygulama performansı düzenli olarak aşağıdaki ölçütlere göre değerlendirilmektedir:

| Ölçüt | Hedef Değer | Mevcut Ortalama |
| ----- | ----------- | --------------- |
| İlk Içerik Gösterimi (FCP) | < 1 saniye | 0.8 saniye |
| En Büyük İçerikli Boya (LCP) | < 2.5 saniye | 2.2 saniye |
| İlk Giriş Gecikmesi (FID) | < 100 ms | 75 ms |
| Kümülatif Düzen Değişimi (CLS) | < 0.1 | 0.05 |
| Tam Yükleme Süresi | < 5 saniye | 4.2 saniye |
| TTFB (Time to First Byte) | < 200 ms | 180 ms |

## Kullanıcı Davranış Analizi

### Toplanan Analitik Veriler

Kullanıcı deneyimini iyileştirmek için `visits` ve `user_activities` tablolarında aşağıdaki anonim kullanıcı verileri toplanmaktadır:

- **Sayfa Görüntülemeleri**: Hangi kampüs ve sahnelerin görüntülendiği
- **Oturum Süresi**: Kullanıcıların sanal turda geçirdiği toplam süre
- **Cihaz Bilgileri**: Kullanılan cihaz türü (masaüstü, mobil, tablet), işletim sistemi ve tarayıcı
- **Ziyaret Kaynağı**: Referrer bilgisi (kullanıcının hangi siteden geldiği)
- **Hotspot Etkileşimleri**: Kullanıcıların tıkladığı hotspot'lar ve geçiş yaptığı sahneler

### Analitik Gösterge Paneli

Admin panelinde yer alan analitik gösterge paneli (`analytics.php`), aşağıdaki bilgileri sağlamaktadır:

- **Ziyaretçi İstatistikleri**: Toplam ve benzersiz ziyaretçi sayıları
- **Cihaz Dağılımı**: Masaüstü, mobil ve tablet kullanım oranları
- **Popüler Sahneler**: En çok ziyaret edilen sahneler ve ziyaret sayıları
- **Zaman Bazlı Analiz**: Günlük, haftalık ve aylık ziyaret grafikleri
- **Tarayıcı ve İşletim Sistemi Dağılımı**: Kullanıcıların kullandığı tarayıcı ve işletim sistemleri

### Davranış Analizi Sonuçları

Kullanıcı davranış analizinden elde edilen bazı önemli bulgular:

- Kullanıcıların %65'i masaüstü cihazlardan, %30'u mobil cihazlardan ve %5'i tabletlerden erişim sağlamaktadır
- Ortalama ziyaret süresi 5 dakika 12 saniyedir
- En popüler sahneler: Ana Giriş, Kantin ve Laboratuvar
- Chrome ve Firefox en çok kullanılan tarayıcılardır (%70 ve %15)
- Ziyaretçilerin çoğu 14:00-16:00 saatleri arasında siteyi ziyaret etmektedir

## Ölçeklendirme Stratejisi

### Mevcut Ölçek

Sanal tur uygulaması şu anda aşağıdaki ölçekte işletilmektedir:

- **Kampüs Sayısı**: 1 (aktif) + 2 (geliştirme aşamasında)
- **Toplam Sahne Sayısı**: 28 (Teknik Bilimler MYO)
- **Ortalama Günlük Ziyaret**: 250-300 tekil kullanıcı
- **Aylık Bant Genişliği Kullanımı**: 120 GB
- **Veritabanı Boyutu**: 89,6 KB
- **Toplam Depolama**: 500 MB (panoramalar dahil)

### Yatay Ölçeklendirme Planı

Artan kullanıcı sayısı ve içerik miktarını karşılamak için planlanan ölçeklendirme adımları:

1. **Yük Dengeleyici**: Trafiği birden fazla uygulama sunucusuna dağıtma
2. **Veritabanı Replikasyonu**: Okuma işlemlerini replika sunuculara yönlendirme
3. **CDN Entegrasyonu**: Statik içeriği küresel ağ üzerinden dağıtma
4. **Konteynerleştirme**: Docker ve Kubernetes ile esnek ölçeklendirme
5. **Mikro Hizmet Mimarisi**: Uygulamanın bağımsız mikro hizmetlere ayrılması

### Büyüme Projeksiyonu

Projenin önümüzdeki yıllarda aşağıdaki şekilde büyümesi öngörülmektedir:

| Zaman Çerçevesi | Kampüs Sayısı | Sahne Sayısı | Günlük Ziyaret | Depolama |
| --------------- | ------------- | ------------ | -------------- | -------- |
| Şu anda | 1 | 28 | 300 | 500 MB |
| 6 Ay | 3 | 90 | 700 | 2 GB |
| 12 Ay | 5 | 150 | 1,200 | 4 GB |
| 2 Yıl | 7 | 250 | 2,500 | 8 GB |

## Test ve Kalite Kontrol

### Test Metodolojisi

Sanal tur uygulaması, aşağıdaki kapsamlı test metodolojisine tabi tutulmuştur:

#### Birim Testleri

- PHP sınıfları için PHPUnit ile birim testleri
- JavaScript modülleri için Jest ile birim testleri
- Veritabanı işlemleri için izole test ortamı

#### Entegrasyon Testleri

- API endpoint'leri için uçtan uca testler
- Frontend-backend entegrasyonu için testler
- Veritabanı etkileşimleri için entegrasyon testleri

#### Kullanıcı Arayüzü Testleri

- Selenium ile otomatize UI testleri
- Farklı tarayıcılarda çapraz tarayıcı testleri
- Mobil uyumluluk testleri

#### Yük Testleri

- Apache JMeter ile performans ve yük testleri
- Eş zamanlı kullanıcı senaryoları
- Darboğaz tespiti ve optimizasyon

### Test Senaryoları

Uygulamanın test edilmesi için kullanılan temel test senaryoları:

1. **Kampüs Navigasyonu**: Kullanıcının tüm kampüsler arasında geçiş yapabilmesi
2. **Sahne Yükleme**: Panoramik görüntülerin doğru ve hızlı yüklenmesi
3. **Hotspot Etkileşimi**: Hotspot'ların doğru çalışması ve hedeflere yönlendirmesi
4. **Mobil Kullanım**: Farklı mobil cihazlarda doğru görüntülenme ve işlevsellik
5. **Admin İşlevleri**: İçerik ekleme, düzenleme ve silme işlemlerinin doğru çalışması

### Kalite Güvence Prosedürleri

Her yeni sürüm yayınlanmadan önce uygulanan kalite güvence adımları:

1. **Kod İncelemesi**: En az iki geliştirici tarafından kod incelemesi
2. **Statik Kod Analizi**: PHP_CodeSniffer ve ESLint ile kod kalitesi kontrolü
3. **Regresyon Testleri**: Önceki işlevselliğin bozulmadığından emin olmak için
4. **Kullanıcı Kabul Testleri**: Seçilmiş test kullanıcıları ile gerçek ortamda test
5. **Belgelendirme Güncellemesi**: Tüm değişikliklerin dokümantasyona yansıtılması

## Bakım ve Güncellemeler

### Güncelleme Planı

Sanal tur uygulaması için planlanan güncelleme döngüsü:

- **Haftalık**: Güvenlik yamaları ve kritik hata düzeltmeleri
- **Aylık**: Küçük özellik iyileştirmeleri ve optimizasyonlar
- **Üç Aylık**: Yeni özellikler ve büyük güncellemeler
- **Yıllık**: Büyük sürüm yükseltmeleri ve teknoloji yenilemeleri

### Sürüm Takip Sistemi

Uygulama, Semantik Sürümleme (Semantic Versioning) ilkelerini takip etmektedir:

- **X.0.0**: Büyük sürüm, geriye dönük uyumlu olmayan değişiklikler
- **0.X.0**: Küçük sürüm, geriye dönük uyumlu yeni özellikler
- **0.0.X**: Yama, geriye dönük uyumlu hata düzeltmeleri

### Yedekleme Stratejisi

Veri güvenliği için uygulanan yedekleme stratejisi:

- **Günlük**: Veritabanının tam yedeği
- **Haftalık**: Tüm sistem dosyalarının yedeği
- **Aylık**: Soğuk depolama (off-site) tam sistem yedeği
- **Otomasyon**: Otomatikleştirilmiş yedekleme ve doğrulama prosedürleri
- **Yedek Testi**: Düzenli olarak yedeklerden geri yükleme testleri

## Proje Yapımcıları

### Proje Ekibi

- **Mustafa Arda Düşova:** Proje Yöneticisi, Ön-Arka Yüz Geliştirici
  - Proje mimarisi ve teknik altyapı tasarımı
  - Backend sistem geliştirme ve veritabanı tasarımı
  - Frontend uygulama geliştirme ve API entegrasyonu
  - Performans optimizasyonu ve güvenlik önlemleri

- **Fatih Çoban:** Panoramik Fotoğrafçı, UI/UX Tasarımcı
  - 360° panoramik fotoğrafların çekilmesi ve işlenmesi
  - Kullanıcı arayüzü tasarımı ve kullanıcı deneyimi planlaması
  - Responsive tasarım implementasyonu
  - Görsel varlıkların hazırlanması ve optimizasyonu

- **Eylül Kay:** Proje Sözcüsü, Sosyal Medya Yönetimi, Görsel Tasarım
  - Proje iletişimi ve paydaş yönetimi
  - Sosyal medya stratejisi ve içerik planlaması
  - Marka kimliği ve görsel tasarım elementleri
  - Kullanıcı dokümantasyonu ve yardım içerikleri

- **Dr. Öğr. Üyesi Nadir Subaşı:** Proje Danışmanı
  - Akademik danışmanlık ve metodoloji desteği
  - Proje gereksinimleri ve kullanıcı ihtiyaçları analizi
  - Kalite kontrol süreçlerinin denetimi
  - Kurumsal entegrasyon ve kaynak yönetimi

### Teknik Uzmanlık Alanları

| Ekip Üyesi | Uzmanlık Alanları |
| ---------- | ----------------- |
| Mustafa Arda Düşova | PHP, MySQL, JavaScript, WebGL, API Tasarımı, Ölçeklendirme, Güvenlik |
| Fatih Çoban | 360° Fotoğrafçılık, Photoshop, UI/UX, Responsive Design, Bootstrap, CSS3 |
| Eylül Kay | Kurumsal İletişim, SEO, Sosyal Medya Stratejisi, İçerik Yönetimi, Figma |
| Dr. Öğr. Üyesi Nadir Subaşı | Dijital Dönüşüm, Kullanıcı Deneyimi Araştırmaları, Proje Yönetimi |

### Katkıda Bulunanlar

Projeye değerli katkılarda bulunan diğer kişi ve birimler:

- **Bilgi İşlem Daire Başkanlığı**: Sunucu altyapısı ve teknik destek
- **Kurumsal İletişim Koordinatörlüğü**: İçerik desteği ve tasarım onayları
- **Bilgisayar Mühendisliği Bölümü Öğrencileri**: Test ve kodlama desteği
- **Teknik Bilimler MYO Personeli**: İçerik sağlama ve koordinasyon

## İletişim Bilgileri

### Proje İletişim

- **E-posta:** info@mdusova.com
- **Telefon:** +90 530 743 22 39
- **Web:** https://mdusova.com/
- **Adres:** Kırklareli Üniversitesi Teknik Bilimler Meslek Yüksekokulu, Karahıdır Mahallesi Harmanlık Mevkii 39100 Kırklareli

### Teknik Destek

- **E-posta:** bibd@klu.edu.tr
- **Telefon:** +90 288 212 96 81
- **Çalışma Saatleri:** Pazartesi-Cuma, 09:00-17:00
- **Destek Talebi:** [https://github.com/dusova/klu360/issues](https://github.com/dusova/klu360/issues)

### Sosyal Medya Hesapları

- **Instagram:** @kirklareliuni
- **Twitter:** @kirklareliuni
- **Facebook:** /kirklareliuniversitesi
- **YouTube:** /kirklareliuniversitesi

### Güncellemeler ve Duyurular

Projeyle ilgili güncellemeler ve duyurular için:
- [https://github.com/dusova/klu360/releases](https://github.com/dusova/klu360/releases)
- Üniversite ana sayfası: [https://klu.edu.tr/](https://klu.edu.tr/)
