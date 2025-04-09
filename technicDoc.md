# Kırklareli Üniversitesi 360° Sanal Tur - Teknik Dökümantasyon

Bu dökümantasyon, Kırklareli Üniversitesi 360° Sanal Tur uygulamasının kurulum ve yapılandırma süreçlerini içermektedir.

**Versiyon:** 1.0  
**Son Güncelleme:** Nisan 2025

## İçindekiler

1. [Sistem Gereksinimleri](#sistem-gereksinimleri)
2. [Kurulum Adımları](#kurulum-adımları)
3. [Veritabanı Yapılandırması](#veritabanı-yapılandırması)
4. [Dosya Yapısı](#dosya-yapısı)
5. [Güvenlik Önlemleri](#güvenlik-önlemleri)
6. [Yedekleme Prosedürleri](#yedekleme-prosedürleri)
7. [Sorun Giderme](#sorun-giderme)
8. [Teknik Referanslar](#teknik-referanslar)

## Sistem Gereksinimleri

### Sunucu Gereksinimleri

- PHP 7.4 veya daha yeni
- MySQL 5.7 veya daha yeni (veya MariaDB 10.3+)
- Apache 2.4+ (mod_rewrite etkinleştirilmiş) veya Nginx
- SSL sertifikası (HTTPS için)

### PHP Gereksinimleri

- GD veya ImageMagick uzantısı (resim işleme için)
- JSON uzantısı
- PDO ve PDO_MySQL uzantıları
- mbstring uzantısı
- CURL uzantısı
- ZIP uzantısı
- Açılmış fonksiyonlar: `file_get_contents`, `file_put_contents`, `readfile`

### Tavsiye Edilen Sunucu Yapılandırması

- CPU: Çift çekirdekli işlemci veya daha iyisi
- RAM: En az 4GB
- Depolama: En az 10GB (panorama görüntüleri için daha fazla alan gerekebilir)
- Bant genişliği: Aylık en az 500GB

## Kurulum Adımları

### 1. Dosyaların Transferi

1. Proje dosyalarını sunucunuza FTP veya SSH kullanarak yükleyin
2. Dosyaların web kök dizinine (`public_html`, `www` veya `htdocs`) yüklenmesi önerilir
3. Dosya izinlerini aşağıdaki şekilde ayarlayın:
   - Dizinler için: `755` (chmod 755)
   - Dosyalar için: `644` (chmod 644)
   - `uploads` dizini için: `775` (chmod 775)

### 2. Veritabanı Kurulumu

1. MySQL veritabanı oluşturun
2. `database.sql` dosyasını içe aktarın:
   ```bash
   mysql -u kullanici_adi -p veritabani_adi < database.sql
   ```
   veya phpMyAdmin kullanarak SQL dosyasını içe aktarın

### 3. Yapılandırma Dosyası

1. `includes/config.php` dosyasını düzenleyin:
   ```php
   // Veritabanı bağlantı bilgileri
   define('DB_HOST', 'localhost');
   define('DB_USER', 'veritabani_kullanici');
   define('DB_PASS', 'veritabani_sifre');
   define('DB_NAME', 'veritabani_adi');

   // Site yapılandırması
   define('SITE_URL', 'https://siteadresi.com'); // SSL kullanılması önerilir
   define('SITE_TITLE', 'Kırklareli Üniversitesi 360° Sanal Tur');

   // Debug modu (canlı sunucuda kapalı olmalı)
   define('DEBUG_MODE', false);
   ```

### 4. Admin Kullanıcısı Oluşturma

1. Tarayıcıda `https://siteadresi.com/create_admin.php` adresine gidin
2. Admin kullanıcısını oluşturun
3. Oluşturma işlemi tamamlandıktan sonra güvenlik nedeniyle `create_admin.php` dosyasını sunucudan silin

### 5. .htaccess Dosyası

Apache sunucuları için `.htaccess` dosyasının düzgün yapılandırıldığından emin olun. Dosya içeriği aşağıdakine benzer olmalıdır:

```apache
# PHP sürümünü belirt (sunucu tarafından destekleniyorsa)
<IfModule mod_php.c>
    php_flag display_errors off
    php_value max_execution_time 300
    php_value memory_limit 256M
    php_value post_max_size 20M
    php_value upload_max_filesize 20M
    php_flag session.cookie_httponly on
    php_flag session.use_only_cookies on
    php_flag session.cookie_secure on
</IfModule>

# Mod Rewrite etkinleştir
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /

    # www'suz URL'ye yönlendir
    RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
    RewriteRule ^(.*)$ https://%1/$1 [R=301,L]

    # HTTPS yönlendirmesi (canlı sunucuda kullanılmalıdır)
    RewriteCond %{HTTPS} !=on
    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    # SEO dostu URL yapısı
    RewriteRule ^tour/([a-zA-Z0-9-]+)/?$ tour.php?campus=$1 [QSA,L]
    RewriteRule ^tour/([a-zA-Z0-9-]+)/([a-zA-Z0-9-]+)/?$ tour.php?campus=$1&scene=$2 [QSA,L]

    # Admin paneli
    RewriteRule ^admin/?$ admin.php [QSA,L]
    RewriteRule ^admin/([a-zA-Z0-9-]+)/?$ admin.php?page=$1 [QSA,L]
    
    # Login sayfası
    RewriteRule ^login/?$ login.php [QSA,L]
    RewriteRule ^logout/?$ logout.php [QSA,L]
    
    # 404 - sayfa bulunamadı
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ 404.php [L]
</IfModule>

# Dizin listelemeyi devre dışı bırak
Options -Indexes
```

### 6. Dizin İzinleri

Aşağıdaki dizinlerin yazılabilir olduğundan emin olun:

```bash
chmod 775 uploads/
chmod 775 uploads/panoramas/
chmod 775 uploads/thumbnails/
chmod 775 uploads/maps/
chmod 775 tmp/
```

### 7. Güvenlik Kontrolleri

1. `create_admin.php` dosyasının silindiğinden emin olun
2. `includes/` dizinine doğrudan erişimin kısıtlandığını doğrulayın
3. `uploads/` dizininde PHP dosyalarının çalıştırılmasının engellendiğini kontrol edin

### 8. İlk Giriş

1. Tarayıcıda `https://siteadresi.com/login` adresine gidin
2. Admin kullanıcı bilgileriyle giriş yapın
3. Sistem yapılandırmasını kontrol edin

## Veritabanı Yapılandırması

### Veritabanı Şeması

Sistem aşağıdaki tablolardan oluşmaktadır:

1. **users** - Kullanıcı hesapları
2. **password_resets** - Şifre sıfırlama token'ları
3. **campuses** - Kampüs bilgileri
4. **scenes** - 360° sahneler
5. **hotspots** - Sahnelerdeki hotspotlar
6. **visits** - Ziyaretçi istatistikleri
7. **user_activities** - Kullanıcı aktivite logları
8. **settings** - Sistem ayarları

### Veritabanı Oluşturma SQL'i:

```sql
-- Veritabanı oluştur
CREATE DATABASE IF NOT EXISTS `kirklareli_virtual_tour` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `kirklareli_virtual_tour`;

-- Kullanıcılar
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','editor','user') NOT NULL DEFAULT 'user',
  `status` enum('active','inactive','pending') NOT NULL DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Şifre Sıfırlama
CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `expiry_time` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `token` (`token`),
  CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Kampüsler
CREATE TABLE `campuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `map_image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sahneler
CREATE TABLE `scenes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campus_id` int(11) NOT NULL,
  `scene_id` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `thumbnail_path` varchar(255) NOT NULL,
  `map_x` decimal(5,2) DEFAULT NULL,
  `map_y` decimal(5,2) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `scene_id` (`scene_id`),
  KEY `campus_id` (`campus_id`),
  CONSTRAINT `scenes_ibfk_1` FOREIGN KEY (`campus_id`) REFERENCES `campuses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Hotspotlar
CREATE TABLE `hotspots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scene_id` int(11) NOT NULL,
  `type` enum('scene','info','link') NOT NULL DEFAULT 'scene',
  `text` varchar(255) NOT NULL,
  `pitch` decimal(8,4) NOT NULL,
  `yaw` decimal(8,4) NOT NULL,
  `target_scene_id` varchar(255) DEFAULT NULL,
  `target_pitch` decimal(8,4) DEFAULT NULL,
  `target_yaw` decimal(8,4) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `scene_id` (`scene_id`),
  CONSTRAINT `hotspots_ibfk_1` FOREIGN KEY (`scene_id`) REFERENCES `scenes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ziyaretler
CREATE TABLE `visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `device_type` enum('desktop','mobile','tablet','unknown') NOT NULL DEFAULT 'unknown',
  `browser` varchar(100) DEFAULT NULL,
  `operating_system` varchar(100) DEFAULT NULL,
  `referrer` varchar(255) DEFAULT NULL,
  `campus` varchar(255) NOT NULL,
  `scene_id` varchar(255) DEFAULT NULL,
  `visit_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `campus` (`campus`),
  KEY `scene_id` (`scene_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Kullanıcı Aktiviteleri
CREATE TABLE `user_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `activity_type` varchar(50) NOT NULL,
  `details` text DEFAULT NULL,
  `campus` varchar(255) DEFAULT NULL,
  `scene_id` varchar(255) DEFAULT NULL,
  `activity_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `campus` (`campus`),
  KEY `scene_id` (`scene_id`),
  CONSTRAINT `user_activities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ayarlar
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Dosya Yapısı

```
.
├── admin/                  # Admin paneli PHP dosyaları
│   ├── analytics.php       # İstatistikler sayfası
│   ├── campuses.php        # Kampüs yönetimi 
│   ├── dashboard.php       # Admin ana sayfası
│   ├── hotspots.php        # Hotspot yönetimi
│   ├── log_activity.php    # Aktivite kayıt API'si
│   ├── process_*.php       # Form işleme dosyaları
│   ├── scenes.php          # Sahne yönetimi
│   ├── settings.php        # Sistem ayarları
│   └── users.php           # Kullanıcı yönetimi
├── assets/                 # Statik dosyalar
│   ├── css/                # CSS dosyaları
│   ├── images/             # Genel resimler 
│   └── js/                 # JavaScript dosyaları
├── includes/               # PHP sınıfları ve yardımcı dosyalar
│   ├── admin_functions.php # Admin işlevleri
│   ├── auth.php            # Kimlik doğrulama işlevleri
│   ├── config.php          # Sistem yapılandırması
│   ├── db.php              # Veritabanı bağlantısı
│   └── functions.php       # Genel işlevler
├── uploads/                # Yüklenen dosyalar
│   ├── maps/               # Kampüs haritaları
│   ├── panoramas/          # 360° panorama görüntüleri
│   └── thumbnails/         # Küçük resimler
├── admin.php               # Admin paneli ana giriş noktası
├── create_admin.php        # İlk admin oluşturma (kurulumdan sonra silinmeli)
├── index.php               # Ana sayfa
├── login.php               # Kullanıcı girişi
├── logout.php              # Oturum kapatma
├── tour.php                # 360° Tur görüntüleyici
└── .htaccess               # Apache yapılandırması
```

## Güvenlik Önlemleri

### Uygulamada Alınan Güvenlik Önlemleri

1. **SQL Enjeksiyon Koruması**
   - Tüm veritabanı sorgularında PDO prepared statements kullanılmıştır
   - Kullanıcı girdileri doğrudan SQL sorgularına dahil edilmemiştir

2. **XSS (Cross-Site Scripting) Koruması**
   - Tüm kullanıcı girdileri `htmlspecialchars()` ile temizlenmektedir
   - `sanitizeInput()` fonksiyonu potansiyel zararlı içeriği temizler

3. **CSRF (Cross-Site Request Forgery) Koruması**
   - Tüm formlar CSRF token içermektedir
   - Token doğrulama her form gönderiminde yapılmaktadır

4. **Şifre Güvenliği**
   - Şifreler PHP'nin `password_hash()` fonksiyonu ile güvenli bir şekilde hashlenmektedir
   - Şifre karşılaştırma için `password_verify()` kullanılmaktadır

5. **Dosya Yükleme Güvenliği**
   - Dosya türü ve boyutu doğrulanmaktadır
   - Dosya adları benzersiz ID'ler ile değiştirilmektedir
   - Yüklenen dosyaların doğrudan çalıştırılması engellenmektedir

6. **Oturum Güvenliği**
   - Otomatik oturum sonlandırma (SESSION_LIFETIME ile kontrol edilir)
   - Sadece çerez tabanlı oturumlar (HttpOnly)
   - Güvenli çerezler (HTTPS bağlantıları için)

### Önerilen Ek Güvenlik Önlemleri

1. **Düzenli Güvenlik Güncellemeleri**
   - PHP ve MySQL/MariaDB güncel tutulmalıdır
   - Kullanılan tüm kütüphaneler güncel olmalıdır

2. **IP Kısıtlamaları**
   - Admin paneline erişim için IP kısıtlamaları düşünülebilir
   - Güvenilir IP adresleri için `.htaccess` üzerinden kısıtlama yapılabilir

3. **İki Faktörlü Kimlik Doğrulama (2FA)**
   - Admin kullanıcıları için 2FA uygulanması düşünülebilir

4. **SSL Sertifikası**
   - HTTPS kullanımı zorunlu tutulmalıdır
   - Let's Encrypt gibi ücretsiz bir SSL sağlayıcısı kullanılabilir

5. **Web Application Firewall (WAF)**
   - Cloudflare veya ModSecurity gibi bir WAF kullanılması önerilir

6. **Düzenli Güvenlik Taraması**
   - Site düzenli olarak güvenlik açıkları için taranmalıdır
   - OWASP ZAP gibi açık kaynaklı güvenlik tarama araçları kullanılabilir

## Yedekleme Prosedürleri

### Düzenli Yedekleme Planı

Aşağıdaki yedekleme planını uygulamanız önerilir:

1. **Günlük Yedekleme**
   - Veritabanının tam yedeği
   - Son 7 günlük yedeklerin saklanması

2. **Haftalık Yedekleme**
   - Veritabanı ve tüm dosyaların (özellikle uploads/ dizini) yedeği
   - Son 4 haftalık yedeklerin saklanması

3. **Aylık Yedekleme**
   - Tam sistem yedeği (veritabanı + tüm dosyalar)
   - Son 12 aylık yedeklerin saklanması

### Veritabanı Yedekleme Komutu

MySQL veritabanını yedeklemek için:

```bash
mysqldump -u kullanici_adi -p veritabani_adi > yedek_$(date +%Y%m%d).sql
```

### Dosya Yedekleme Komutu

Dosyaları sıkıştırarak yedeklemek için:

```bash
tar -czf yedek_dosyalar_$(date +%Y%m%d).tar.gz /path/to/site/files
```

### Otomatik Yedekleme Scripti

Bir cron job ile otomatik yedekleme için aşağıdaki bash scripti kullanılabilir:

```bash
#!/bin/bash
# backup.sh - Sanal Tur Otomatik Yedekleme Scripti

# Değişkenler
BACKUP_DIR="/path/to/backups"
SITE_DIR="/path/to/site/files"
DB_USER="veritabani_kullanici"
DB_PASS="veritabani_sifre"
DB_NAME="veritabani_adi"
DATE=$(date +%Y%m%d)

# Yedekleme dizinini oluştur
mkdir -p $BACKUP_DIR

# Veritabanı yedeği
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_backup_$DATE.sql

# Dosya yedeği
tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz $SITE_DIR

# Eski yedekleri temizle (7 günden eski günlük yedekler)
find $BACKUP_DIR -name "db_backup_*.sql" -type f -mtime +7 -delete
find $BACKUP_DIR -name "files_backup_*.tar.gz" -type f -mtime +7 -delete

# Yedeklemeyi bildir
echo "Yedekleme tamamlandı: $DATE" | mail -s "Sanal Tur Yedekleme" admin@example.com
```

Bu scripti çalıştırmak için:

```bash
chmod +x backup.sh
crontab -e
```

Sonra crontab'a aşağıdaki satırı ekleyin (her gece 3'te çalıştırır):

```
0 3 * * * /path/to/backup.sh
```

## Sorun Giderme

### Genel Sorunlar ve Çözümleri

#### 1. Veritabanı Bağlantı Hatası

**Belirtiler:** "Veritabanı bağlantı hatası" mesajı veya boş sayfa.

**Çözüm:**
- `includes/config.php` dosyasındaki veritabanı bilgilerini kontrol edin
- MySQL/MariaDB servisinin çalıştığından emin olun
- Veritabanı kullanıcısının doğru yetkilere sahip olduğunu doğrulayın

#### 2. 500 Internal Server Error

**Belirtiler:** Tarayıcıda "500 Internal Server Error" mesajı.

**Çözüm:**
- PHP hata loglarını kontrol edin (genellikle `/var/log/apache2/error.log` veya `/var/log/nginx/error.log`)
- `.htaccess` dosyasında yazım hatası olup olmadığını kontrol edin
- `includes/config.php` dosyasında yazım hatası kontrolü yapın
- Dosya izinlerini kontrol edin

#### 3. 404 Not Found Hataları

**Belirtiler:** SEO dostu URL'ler çalışmıyor veya "404 Not Found" hatası alınıyor.

**Çözüm:**
- `mod_rewrite` modülünün Apache'de etkin olduğundan emin olun
- `.htaccess` dosyasını kontrol edin
- Apache yapılandırmasında `AllowOverride All` ayarının doğru yerde olduğunu doğrulayın

#### 4. Dosya Yükleme Sorunları

**Belirtiler:** Panorama görüntüleri veya haritalar yüklenemiyor.

**Çözüm:**
- `php.ini` dosyasındaki `upload_max_filesize` ve `post_max_size` değerlerini kontrol edin
- `uploads/` dizininin yazılabilir olduğunu kontrol edin
- Dosya izinlerini 755 (dizinler) ve 644 (dosyalar) olarak ayarlayın

#### 5. Oturum Sorunları

**Belirtiler:** Sık sık oturumdan çıkılıyor veya "Lütfen tekrar giriş yapın" mesajı alınıyor.

**Çözüm:**
- `php.ini` dosyasındaki `session.gc_maxlifetime` değerini kontrol edin
- `includes/config.php` dosyasındaki `SESSION_LIFETIME` değerini kontrol edin
- Çerez ayarlarını kontrol edin ve SSL bağlantılarında sorun olmadığından emin olun

### Hata Loglarının Kontrolü

Apache hata loglarını kontrol etmek için:

```bash
tail -f /var/log/apache2/error.log
```

Nginx hata loglarını kontrol etmek için:

```bash
tail -f /var/log/nginx/error.log
```

PHP hata loglarını kontrol etmek için (yapılandırmaya bağlı olarak):

```bash
tail -f /var/log/php-errors.log
```

### Debug Modunu Etkinleştirme

Detaylı hata mesajları için `includes/config.php` dosyasında debug modunu geçici olarak etkinleştirebilirsiniz:

```php
// Debug modu (canlı sunucuda kapalı olmalı)
define('DEBUG_MODE', true);
```

> **Not:** Debug modu yalnızca geliştirme ortamında veya sorun giderme sırasında açık olmalıdır. Canlı sunucuda her zaman kapalı tutun.

## Teknik Referanslar

### Kullanılan Kütüphaneler

- [Pannellum](https://pannellum.org/) - 360° panorama görüntüleyici (JavaScript)
- [Bootstrap 5](https://getbootstrap.com/) - Duyarlı web tasarım çerçevesi
- [Chart.js](https://www.chartjs.org/) - İstatistik grafikleri için
- [DataTables](https://datatables.net/) - Gelişmiş tablo görüntüleme ve filtreleme

### API Referansları

#### AJAX İstek Örneği

```javascript
// Sahne değişimini kaydetmek için AJAX isteği
fetch('admin/log_activity.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `action=scene_change&scene_id=${sceneId}&campus=${campusId}`
}).catch(error => {
    console.warn('Ziyaret kaydedilemedi:', error);
});
```

#### Veritabanı Erişim Örneği

```php
// Veritabanı sorgulama örneği
$query = "SELECT * FROM scenes WHERE campus_id = ?";
$params = [$campusId];
$scenes = executeQuery($conn, $query, $params);
```

#### Tüm Sahneleri JSON Formatında Alma Örneği

```php
$scenes = listScenes($conn, $campus_id, 'active');
$scenes_json = json_encode($scenes);
```

### Önemli Fonksiyonlar

- `connectDatabase()` - Veritabanı bağlantısı oluşturur
- `executeQuery()` - Güvenli SQL sorgularını çalıştırır
- `sanitizeInput()` - Kullanıcı girdilerini temizler
- `generateCSRFToken()` - CSRF koruması için token oluşturur
- `validateCSRFToken()` - CSRF token'ını doğrular
- `logVisit()` - Ziyaretçi istatistiklerini kaydeder
