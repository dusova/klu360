# Kırklareli Üniversitesi 360° Sanal Tur

<p align="center">
  <img src="logo.png" alt="Kırklareli Üniversitesi 360° Sanal Tur Logo" width="400"/>
</p>

<p align="center">
  <a href="#hakkında">Hakkında</a> •
  <a href="#dökümantasyon">Dökümantasyon</a> •
  <a href="#kurulum">Kurulum</a> •
  <a href="#kullanım">Kullanım</a> •
  <a href="#proje-ekibi">Proje Ekibi</a> •
  <a href="#lisans">Lisans</a>
</p>

## Hakkında

Kırklareli Üniversitesi 360° Sanal Tur projesi, üniversitemizin kampüslerini ve tesislerini potansiyel öğrencilere, mevcut öğrencilere, akademik personele ve ziyaretçilere tanıtmak için geliştirilmiş interaktif bir web uygulamasıdır. 

Proje, modern web teknolojileri kullanılarak, masaüstü bilgisayarlar, tabletler ve mobil cihazlar gibi farklı platformlarda çalışabilir şekilde tasarlanmıştır.

### Özellikler

- 360° panoramik görüntülerle kampüs turu
- İnteraktif hotspot'lar ile detaylı bilgiler
- Kampüs haritası üzerinde konum görüntüleme
- Çoklu cihaz desteği (masaüstü, tablet, mobil)
- Admin paneli ile içerik yönetimi
- Ziyaretçi istatistikleri ve analitikler

## Dökümantasyon

Proje ile ilgili detaylı bilgi için aşağıdaki dökümantasyon dosyalarını inceleyebilirsiniz:

1. [Teknik Dökümantasyon](technicDoc.md) - Kurulum, yapılandırma ve teknik detaylar
2. [Admin Panel Dökümantasyonu](adminPanelDoc.md) - Admin panel kullanım kılavuzu
3. [Kullanım Kılavuzu](userGuide.md) - Son kullanıcılar için kullanım talimatları
4. [Genel Dökümantasyon](generalDoc.md) - Proje detayları, teknik altyapı ve mimari bilgiler

## Kurulum

### Sistem Gereksinimleri

- PHP 7.4 veya daha yeni
- MySQL 5.7 veya daha yeni (veya MariaDB 10.3+)
- Apache 2.4+ (mod_rewrite etkinleştirilmiş) veya Nginx
- SSL sertifikası (HTTPS için)

### Kurulum Adımları

1. Repoyu klonlayın:
```bash
git clone https://github.com/dusova/klu360.git
```

2. Gerekli PHP bağımlılıklarını yükleyin:
```bash
composer install
```

3. Veritabanını oluşturun:
```bash
mysql -u kullanici_adi -p veritabani_adi < database.sql
```

4. `includes/config.php` dosyasını düzenleyin:
```php
// Veritabanı bağlantı bilgileri
define('DB_HOST', 'localhost');
define('DB_USER', 'veritabani_kullanici');
define('DB_PASS', 'veritabani_sifre');
define('DB_NAME', 'veritabani_adi');

// Site yapılandırması
define('SITE_URL', 'https://siteadresi.com');
define('SITE_TITLE', 'Kırklareli Üniversitesi 360° Sanal Tur');
```

5. Admin kullanıcısı oluşturun (tarayıcıdan `https://siteadresi.com/create_admin.php` adresine giderek) ve ardından `create_admin.php` dosyasını silin.

6. Gerekli dizinlere yazma izinleri verin:
```bash
chmod 775 uploads/
chmod 775 uploads/panoramas/
chmod 775 uploads/thumbnails/
```

Detaylı kurulum talimatları için [Teknik Dökümantasyon](technicDoc.md) belgesini inceleyebilirsiniz.

## Kullanım

### Son Kullanıcılar İçin

Sanal tur uygulamasına aşağıdaki adresten erişebilirsiniz:
```
https://siteadresi.com/
```

Ana sayfada mevcut kampüslerin listesini görecek ve istediğiniz kampüsü seçerek sanal tura başlayabileceksiniz. Detaylı kullanım bilgileri için [Kullanım Kılavuzu](userGuide.md) belgesini inceleyebilirsiniz.

### Yöneticiler İçin

Admin paneline aşağıdaki adresten erişebilirsiniz:
```
https://siteadresi.com/admin
```

Admin paneli üzerinden kampüs, sahne ve hotspot yönetimi yapabilir, kullanıcıları düzenleyebilir ve ziyaretçi istatistiklerini görüntüleyebilirsiniz. Detaylı bilgi için [Admin Panel Dökümantasyonu](adminPanelDoc.md) belgesini inceleyebilirsiniz.

## Proje Ekibi

- **Mustafa Arda Düşova** - Proje Yöneticisi, Ön-Arka Yüz Geliştirici
- **Fatih Çoban** - Panoramik Fotoğrafçı, UI/UX Tasarımcı
- **Eylül Kay** - Proje Sözcüsü, Sosyal Medya Yönetimi, Görsel Tasarım
- **Dr. Öğr. Üyesi Nadir Subaşı** - Proje Danışmanı

## Lisans

Bu proje [MIT Lisansı](LICENSE) altında lisanslanmıştır.

## İletişim

- **E-posta:** [info@mdusova.com](mailto:info@mdusova.com)
- **Web:** [mdusova.com](https://mdusova.com/)
- **Adres:** Kırklareli Üniversitesi Teknik Bilimler Meslek Yüksekokulu, Karahıdır Mahallesi Harmanlık Mevkii 39100 Kırklareli
