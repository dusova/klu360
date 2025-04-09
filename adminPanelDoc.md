# Kırklareli Üniversitesi 360° Sanal Tur - Admin Panel Dökümantasyonu

Bu dökümantasyon, Kırklareli Üniversitesi 360° Sanal Tur uygulamasının admin panelini kullanma kılavuzudur. Admin ve editör rolüne sahip kullanıcıların sistemi nasıl yöneteceklerine dair detaylı bilgiler içerir.

**Versiyon:** 1.0  
**Son Güncelleme:** Nisan 2025

## İçindekiler

1. [Giriş](#giriş)
2. [Admin Paneline Erişim](#admin-paneline-erişim)
3. [Kontrol Paneli](#kontrol-paneli)
4. [Kampüs Yönetimi](#kampüs-yönetimi)
5. [Sahne Yönetimi](#sahne-yönetimi)
6. [Hotspot Yönetimi](#hotspot-yönetimi)
7. [Kullanıcı Yönetimi](#kullanıcı-yönetimi)
8. [İstatistikler](#i̇statistikler)
9. [Sistem Ayarları](#sistem-ayarları)
10. [Güvenlik](#güvenlik)

## Giriş

Kırklareli Üniversitesi 360° Sanal Tur uygulaması, aşağıdaki temel bileşenlerden oluşur:

- **Kampüsler:** Üniversitenin farklı kampüslerini temsil eder
- **Sahneler:** Her bir kampüse ait 360° panoramik görüntülerdir
- **Hotspotlar:** Sahneler üzerindeki etkileşimli noktalar (bilgi, bağlantı veya sahne geçişi)
- **Kullanıcılar:** Sistemi yöneten kullanıcılardır (admin, editör veya kullanıcı rolleri)

Admin paneli, bu bileşenlerin yönetimini ve sistem ayarlarını yapılandırmayı sağlar.

## Admin Paneline Erişim

### Giriş Yapma

1. Web tarayıcınızda `https://[site-adresi]/login` adresine gidin
2. E-posta adresinizi ve şifrenizi girin
3. "Giriş Yap" butonuna tıklayın

> **Not:** Admin paneline erişim için "admin" veya "editör" rolüne sahip olmanız gerekir. Normal kullanıcıların admin paneline erişim izni yoktur.

### Şifremi Unuttum

Şifrenizi unuttuysanız:

1. Giriş sayfasında "Şifremi unuttum" bağlantısına tıklayın
2. E-posta adresinizi girin
3. Gelen e-postadaki bağlantıya tıklayarak şifrenizi sıfırlayın

> **Not:** Şifre sıfırlama bağlantısı 1 saat süreyle geçerlidir.

### Çıkış Yapma

Admin panelinden güvenli bir şekilde çıkış yapmak için sağ üst köşedeki profil menüsünden "Çıkış Yap" seçeneğini kullanın.

## Kontrol Paneli

Kontrol paneli, sistemin genel durumunu gösteren bir özet ekranıdır. Bu ekranda aşağıdaki bilgileri görebilirsiniz:

- **Toplam Ziyaret:** Sanal tura yapılan toplam ziyaret sayısı
- **Tekil Ziyaretçi:** Benzersiz ziyaretçi sayısı
- **Popüler Kampüs:** En çok ziyaret edilen kampüs
- **Popüler Sahne:** En çok görüntülenen sahne

Ayrıca kontrol panelinde şu bilgiler de yer alır:
- Son 7 günlük ziyaret grafiği
- Cihaz dağılımı (mobil, tablet, masaüstü)
- Son aktiviteler listesi

## Kampüs Yönetimi

Kampüs yönetimi, üniversitenin kampüslerini tanımlamak ve yönetmek için kullanılır.

### Kampüs Listesi

Kampüs listesi ekranında mevcut tüm kampüsleri görebilir, sıralayabilir ve filtreleyebilirsiniz. Her kampüs için aşağıdaki işlemleri yapabilirsiniz:

- Düzenleme
- Silme
- Sahne görüntüleme

### Yeni Kampüs Ekleme

1. "Yeni Kampüs Ekle" butonuna tıklayın
2. Gerekli bilgileri doldurun:
   - **Kampüs Adı:** Kampüsün tam adı (örn. "Teknik Bilimler MYO")
   - **Slug:** URL'de kullanılacak kısa ad (örn. "teknik-bilimler")
   - **Açıklama:** Kampüs hakkında kısa açıklama
   - **Kampüs Haritası:** Kampüsün üstten görünümünü gösteren harita resmi
   - **Durum:** Aktif veya pasif
3. "Kampüs Ekle" butonuna tıklayın

> **İpucu:** Slug alanını boş bırakırsanız, sistem kampüs adından otomatik olarak bir slug oluşturacaktır.

### Kampüs Düzenleme

1. Düzenlemek istediğiniz kampüsün yanındaki "Düzenle" butonuna tıklayın
2. Gerekli değişiklikleri yapın
3. "Değişiklikleri Kaydet" butonuna tıklayın

### Kampüs Silme

1. Silmek istediğiniz kampüsün yanındaki "Sil" butonuna tıklayın
2. Onay penceresinde "Evet, Sil" butonuna tıklayarak işlemi onaylayın

> **Uyarı:** Bir kampüsü sildiğinizde, o kampüse ait tüm sahneler ve hotspotlar da silinecektir. Bu işlem geri alınamaz.

## Sahne Yönetimi

Sahne yönetimi, 360° panoramik görüntüleri yönetmek için kullanılır. Her sahne bir kampüse aittir.

### Sahne Listesi

Sahne listesi ekranında öncelikle bir kampüs seçmeniz gerekir. Seçilen kampüse ait tüm sahneleri görebilir, sıralayabilir ve filtreleyebilirsiniz. Her sahne için aşağıdaki işlemleri yapabilirsiniz:

- Düzenleme
- Silme
- Hotspot yönetimi

### Yeni Sahne Ekleme

1. "Yeni Sahne Ekle" butonuna tıklayın
2. Gerekli bilgileri doldurun:
   - **Kampüs:** Sahnenin ait olduğu kampüs
   - **Sahne Başlığı:** Sahnenin adı (örn. "Ana Giriş")
   - **Sahne ID:** URL'de ve sistemde kullanılacak benzersiz kimlik
   - **Açıklama:** Sahne hakkında açıklama
   - **360° Panorama Görüntüsü:** Equirectangular formatında panoramik görüntü
   - **Haritadaki Konum:** Kampüs haritası üzerindeki konumu
   - **Görüntülenme Sırası:** Sahnelerin listelenme sırası
   - **Durum:** Aktif veya pasif
3. "Sahne Ekle" butonuna tıklayın

> **Not:** 360° panorama görüntüleri equirectangular (eş dikdörtgensel) formatta olmalıdır. Bu format, küresel panoramaların düz bir dikdörtgen üzerinde gösterilmesini sağlar.

### Sahne Düzenleme

1. Düzenlemek istediğiniz sahnenin yanındaki "Düzenle" butonuna tıklayın
2. Gerekli değişiklikleri yapın
3. "Değişiklikleri Kaydet" butonuna tıklayın

### Sahne Silme

1. Silmek istediğiniz sahnenin yanındaki "Sil" butonuna tıklayın
2. Onay penceresinde "Evet, Sil" butonuna tıklayarak işlemi onaylayın

> **Uyarı:** Bir sahneyi sildiğinizde, o sahneye ait tüm hotspotlar da silinecektir. Bu işlem geri alınamaz.

## Hotspot Yönetimi

Hotspotlar, panoramik sahneler üzerindeki etkileşimli noktalardır. Üç farklı hotspot türü vardır:

1. **Sahne Geçişi:** Başka bir sahneye geçiş sağlar
2. **Bilgi:** Tıklandığında bilgi gösterir
3. **Bağlantı:** Harici bir bağlantıya yönlendirir

### Hotspot Listesi

Hotspot listesi ekranında öncelikle bir kampüs ve sahne seçmeniz gerekir. Seçilen sahneye ait tüm hotspotları görebilir ve yönetebilirsiniz.

### Yeni Hotspot Ekleme

1. "Yeni Hotspot Ekle" butonuna tıklayın
2. Hotspot konumunu belirlemek için:
   - Panorama görüntüsü üzerinde istediğiniz noktaya odaklanın
   - "Bu Konumu Kullan" butonuna tıklayın
3. Hotspot türünü seçin (Sahne Geçişi, Bilgi veya Bağlantı)
4. Gerekli bilgileri doldurun:
   - **Hotspot Metni:** Fare imleci üzerine geldiğinde gösterilecek metin
   - **Hedef Sahne:** (Sahne Geçişi için) Tıklandığında gidilecek sahne
   - **Hedef URL:** (Bağlantı için) Tıklandığında açılacak URL
   - **Hedef Görünüm:** (Opsiyonel) Hedef sahnede başlangıç bakış açısı
5. "Hotspot Ekle" butonuna tıklayın

> **İpucu:** Hotspot'un konumunu belirlemek için panorama üzerinde istediğiniz noktaya odaklanın ve "Bu Konumu Kullan" butonuna tıklayın. Bu, hotspot'un pitch (dikey) ve yaw (yatay) koordinatlarını otomatik olarak dolduracaktır.

### Hotspot Düzenleme

1. Düzenlemek istediğiniz hotspot'un yanındaki "Düzenle" butonuna tıklayın
2. Gerekli değişiklikleri yapın
3. "Değişiklikleri Kaydet" butonuna tıklayın

### Hotspot Silme

1. Silmek istediğiniz hotspot'un yanındaki "Sil" butonuna tıklayın
2. Onay penceresinde "Evet, Sil" butonuna tıklayarak işlemi onaylayın

## Kullanıcı Yönetimi

> **Not:** Kullanıcı yönetimine yalnızca "admin" rolüne sahip kullanıcılar erişebilir.

Kullanıcı yönetimi, sistem kullanıcılarını yönetmek için kullanılır. Üç farklı kullanıcı rolü vardır:

1. **Admin:** Sistem üzerinde tam yetkiye sahiptir
2. **Editör:** İçerik ekleme ve düzenleme yetkisine sahiptir, ancak kullanıcı yönetimine erişemez
3. **Kullanıcı:** Yalnızca görüntüleme yetkisine sahiptir (normal ziyaretçiler)

### Kullanıcı Listesi

Kullanıcı listesi ekranında tüm kullanıcıları görebilir, sıralayabilir ve filtreleyebilirsiniz. Her kullanıcı için aşağıdaki işlemleri yapabilirsiniz:

- Düzenleme
- Silme

> **Not:** Kendi kullanıcı hesabınızı silemezsiniz.

### Yeni Kullanıcı Ekleme

1. "Yeni Kullanıcı Ekle" butonuna tıklayın
2. Gerekli bilgileri doldurun:
   - **Ad Soyad:** Kullanıcının tam adı
   - **E-posta:** Kullanıcının e-posta adresi (giriş için kullanılacak)
   - **Şifre:** Kullanıcının şifresi
   - **Kullanıcı Rolü:** Admin, Editör veya Kullanıcı
   - **Hesap Durumu:** Aktif veya pasif
3. "Kullanıcı Ekle" butonuna tıklayın

### Kullanıcı Düzenleme

1. Düzenlemek istediğiniz kullanıcının yanındaki "Düzenle" butonuna tıklayın
2. Gerekli değişiklikleri yapın
3. "Değişiklikleri Kaydet" butonuna tıklayın

> **İpucu:** Kullanıcı şifresini değiştirmek istemiyorsanız, şifre alanını boş bırakın.

### Kullanıcı Silme

1. Silmek istediğiniz kullanıcının yanındaki "Sil" butonuna tıklayın
2. Onay penceresinde "Evet, Sil" butonuna tıklayarak işlemi onaylayın

## İstatistikler

İstatistikler ekranı, sanal tur uygulamasının kullanım istatistiklerini gösterir. Bu ekranda aşağıdaki bilgileri görebilirsiniz:

### Genel İstatistikler

- Toplam ziyaret sayısı
- Tekil ziyaretçi sayısı
- Günlük ortalama ziyaret

### Ziyaret Grafikleri

- Günlük/haftalık/aylık ziyaret grafiği
- Cihaz dağılımı (masaüstü, mobil, tablet)
- Tarayıcı dağılımı
- İşletim sistemi dağılımı

### Popüler İçerik

- En çok ziyaret edilen kampüsler
- En popüler sahneler

### Tarih Aralığı Filtreleme

İstatistikleri belirli bir tarih aralığına göre filtreleyebilirsiniz:

1. "Zaman Dilimi" bölümünden bir seçenek seçin:
   - Bugün
   - Dün
   - Son 7 Gün
   - Son 30 Gün
   - Özel (belirli bir tarih aralığı)
2. "Uygula" butonuna tıklayın

### İstatistikleri Dışa Aktarma

İstatistik verilerini Excel veya PDF formatında dışa aktarabilirsiniz:

1. "Dışa Aktar" menüsüne tıklayın
2. "Excel İndir" veya "PDF İndir" seçeneğini seçin

## Sistem Ayarları

> **Not:** Sistem ayarlarına yalnızca "admin" rolüne sahip kullanıcılar erişebilir.

Sistem ayarları, sanal tur uygulamasının genel yapılandırmasını yönetmek için kullanılır.

### Genel Ayarlar

- **Site Başlığı:** Tarayıcı sekmesinde ve üst başlıkta görünen başlık
- **Karşılama Metni:** Ana sayfada ziyaretçileri karşılayan metin
- **Footer Metni:** Sayfanın alt kısmında görünen metin
- **Varsayılan Kampüs:** Sanal tur başlatıldığında varsayılan olarak gösterilecek kampüs

### SEO ve Meta Bilgileri

- **Meta Açıklama:** Arama motorlarında görünecek site açıklaması
- **Meta Anahtar Kelimeler:** Arama motoru optimizasyonu için anahtar kelimeler
- **Google Analytics Kodu:** Google Analytics izleme kodunuzu girebilirsiniz

### Yapımcılar

Sanal tur uygulamasının yapımcılarını ekleyebilir veya düzenleyebilirsiniz:

1. "Yapımcı Ekle" butonuna tıklayın
2. Gerekli bilgileri doldurun:
   - **Ad Soyad:** Yapımcının tam adı
   - **Unvan/Görev:** Yapımcının unvanı veya görevi
   - **Fotoğraf URL:** Yapımcının fotoğrafı için URL
3. "Ayarları Kaydet" butonuna tıklayın

### Güvenlik Ayarları

- **Bakım Modu:** Sayfayı bakım moduna alır (sadece adminler erişebilir)
- **Giriş Zorunluluğu:** Sanal turu görüntülemek için giriş yapılmasını zorunlu kılar
- **Maksimum Giriş Denemesi:** Başarısız giriş denemesinden sonra hesap kilit süresi
- **Oturum Süresi:** Kullanıcı oturumunun aktif kalacağı süre (saniye)

### İstatistik Ayarları

- **İstatistik Toplama:** Ziyaretçi istatistiklerinin toplanmasını sağlar
- **Log Saklama Süresi:** Ziyaret ve aktivite kayıtlarının saklanacağı gün sayısı
- **Ziyaret Sayma Aralığı:** Aynı IP'den yeni bir ziyaret sayılması için geçmesi gereken süre

### Ayarları Sıfırlama

Tüm ayarları varsayılan değerlerine sıfırlamak için:

1. "Varsayılanlara Sıfırla" butonuna tıklayın
2. Onay penceresinde "Tamam" butonuna tıklayarak işlemi onaylayın

## Güvenlik

### Güvenli Şifre Politikası

Admin paneline erişen tüm kullanıcılar için güçlü şifreler kullanılmalıdır:

- En az 8 karakter uzunluğunda
- Büyük ve küçük harfler, rakamlar ve özel karakterler içermeli
- Kişisel bilgilerle ilgili olmamalı (ad, doğum tarihi vb.)
- Düzenli olarak değiştirilmeli (3-6 ayda bir)

### İki Faktörlü Kimlik Doğrulama (2FA)

Admin kullanıcıları için iki faktörlü kimlik doğrulama kullanılması önerilir. Bu, hesap güvenliğini önemli ölçüde artırır.

### IP Tabanlı Erişim Kısıtlaması

Admin paneli için güvenilir IP adreslerinden erişim kısıtlaması uygulanabilir. Bu, sunucu tarafında `.htaccess` veya benzeri yapılandırmalarla sağlanır.

### Aktivite Günlüğü

Sistem, tüm admin işlemlerini loglar. Bu loglar "Kullanıcı Aktiviteleri" tablosunda saklanır ve güvenlik ihlallerinin tespiti için kullanılabilir.

### Güvenlik İhlallerini Önleme

1. Kullanıcı hesabınızı başkalarıyla paylaşmayın
2. Güvenli olmayan Wi-Fi ağlarında admin paneline erişmeyin
3. Tarayıcı oturumunuzu kapatmadan bilgisayarınızı terk etmeyin
4. Düzenli olarak log kayıtlarını kontrol edin
5. Şüpheli bir aktivite fark ederseniz hemen sistem yöneticisine bildirin

### Önerilen Güvenlik Alışkanlıkları

1. Her oturumun sonunda "Çıkış Yap" butonunu kullanın
2. Giriş bilgilerinizi güvenli bir şekilde saklayın
3. Panele erişim için güvenilir ve güncel bir tarayıcı kullanın
4. Tarayıcı eklentilerini ve yazılımlarınızı güncel tutun
5. Kullanılmayan hesapları devre dışı bırakın veya silin
