function asistanBaslat() {
    console.log("Asistan başlatılıyor...");
    
    const asistanTetikleyici = document.getElementById('asistanTetikleyici');
    const asistanPanel = document.getElementById('asistanPanel');
    const asistanKapat = document.getElementById('asistanKapat');
    const asistanSoru = document.getElementById('asistanSoru');
    const asistanGonder = document.getElementById('asistanGonder');
    const asistanMesajlar = document.getElementById('asistanMesajlar');
    const asistanSes = document.getElementById('asistanSes');
    
    if (!asistanTetikleyici || !asistanPanel || !asistanKapat || !asistanSoru || !asistanGonder || !asistanMesajlar) {
        console.error("Asistan elementleri bulunamadı!");
        return;
    }
    
    console.log("Asistan elementleri başarıyla bulundu!");

    asistanTetikleyici.addEventListener('click', function() {
        console.log("Asistan tetikleyici tıklandı!");
        asistanPanel.style.display = 'flex';
        asistanPanel.style.animation = 'slideIn 0.3s ease forwards';
    });
    
    asistanKapat.addEventListener('click', function() {
        console.log("Asistan kapat tıklandı!");
        asistanPanel.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(function() {
            asistanPanel.style.display = 'none';
        }, 300);
    });
    
    function mesajGonder() {
        const soru = asistanSoru.value.trim();
        if (soru === '') return;
        
        kullaniciMesajiEkle(soru);
        
        asistanSoru.value = '';
        
        cevapOlustur(soru);
    }
    
    asistanGonder.addEventListener('click', mesajGonder);
    
    asistanSoru.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            mesajGonder();
        }
    });
    
    function kullaniciMesajiEkle(metin) {
        const mesajDiv = document.createElement('div');
        mesajDiv.className = 'asistanMesaj kullanici';
        mesajDiv.innerHTML = `<div class="mesajIcerik">${metin}</div>`;
        asistanMesajlar.appendChild(mesajDiv);
        asistanMesajlar.scrollTop = asistanMesajlar.scrollHeight;
    }
    
    function asistanMesajiEkle(metin, gecikme = 500) {
        setTimeout(() => {
            const mesajDiv = document.createElement('div');
            mesajDiv.className = 'asistanMesaj asistan';
            mesajDiv.innerHTML = `<div class="mesajIcerik">${metin}</div>`;
            asistanMesajlar.appendChild(mesajDiv);
            asistanMesajlar.scrollTop = asistanMesajlar.scrollHeight;
            
            if (window.speechSynthesis) {
                const konusma = new SpeechSynthesisUtterance(metin);
                konusma.lang = 'tr-TR';
                window.speechSynthesis.speak(konusma);
            }
        }, gecikme);
    }
    
    function cevapOlustur(soru) {
        asistanMesajiEkle('<i class="fas fa-spinner fa-spin"></i> Düşünüyorum...', 0);
        
        setTimeout(() => {
            const dusunmeIndex = Array.from(asistanMesajlar.querySelectorAll('.asistanMesaj')).length - 1;
            if(dusunmeIndex >= 0 && asistanMesajlar.querySelectorAll('.asistanMesaj')[dusunmeIndex]) {
                asistanMesajlar.removeChild(asistanMesajlar.querySelectorAll('.asistanMesaj')[dusunmeIndex]);
            }
            
            const cevap = getAsistanCevabi(soru.toLowerCase());
            asistanMesajiEkle(cevap, 0);
        }, 1000);
    }
    
    function getAsistanCevabi(soru) {
        if (soru.includes('fakülte') || soru.includes('bölüm') || soru.includes('program')) {
            return 'Kırklareli Üniversitesi\'nde Fen-Edebiyat, İktisadi ve İdari Bilimler, Mühendislik, Mimarlık, Teknoloji, Turizm ve daha birçok fakülte bulunmaktadır. Hangi fakülte veya bölüm hakkında bilgi almak istersiniz?';
        }
        
        else if (soru.includes('kampüs') || soru.includes('kampus') || soru.includes('yerleşke')) {
            return 'Kırklareli Üniversitesi\'nin Kayalı, Merkez, Teknik Bilimler, Kavaklı ve Lüleburgaz olmak üzere 5 ana kampüsü bulunmaktadır. Sanal turumuzda şu an Teknik Bilimler Kampüsünü geziyorsunuz.';
        }
        
        else if (soru.includes('teknik bilimler')) {
            return 'Teknik Bilimler Kampüsü, Kırklareli Merkez\'de yer almaktadır. Bu kampüste Teknik Bilimler Meslek Yüksekokulu bulunmaktadır. Bilgisayar Teknolojileri, Elektronik ve Otomasyon, İnşaat, Makine, Tekstil ve birçok teknik program burada eğitim vermektedir.';
        }
        
        else if (soru.includes('yemekhane') || soru.includes('kantin') || soru.includes('yemek')) {
            return 'Kampüsümüzde öğrencilerin yemek ihtiyacını karşılayan bir kantin bulunmaktadır. Ayrıca kampüs çevresinde birçok yemek alternatifi mevcuttur. Kantinde çeşitli sıcak yemekler, tost, sandviç, içecek alternatifleri bulunmaktadır.';
        }
        
        else if (soru.includes('kütüphane') || soru.includes('kitap')) {
            return 'Üniversitemizde merkez kütüphane ve fakülte kütüphaneleri bulunmaktadır. Teknik Bilimler Kampüsündeki kütüphanemizde çeşitli teknik ve mesleki kitaplar, dergiler ve elektronik kaynaklar mevcuttur. Ayrıca çalışma alanları da öğrencilerin kullanımına sunulmuştur.';
        }
        
        else if (soru.includes('laboratuvar') || soru.includes('lab')) {
            return 'Teknik Bilimler Kampüsünde bilgisayar, elektronik, mekanik ve diğer teknik alanlarda eğitime destek veren modern laboratuvarlarımız bulunmaktadır. Bu laboratuvarlarda öğrencilerimiz teorik bilgilerini uygulama imkanı bulmaktadır.';
        }
        
        else if (soru.includes('kimsin') || soru.includes('adın ne') || soru.includes('kendini tanıt')) {
            return 'Ben KLÜ sanal asistanı. Kırklareli Üniversitesi\'nin sanal turunda size yardımcı olmak için buradayım. Kampüsler, fakülteler, bölümler ve diğer konularda sorularınızı cevaplayabilirim.';
        }
        
        else if (soru.includes('neredeyiz') || soru.includes('neresi')) {
            return 'Şu anda sanal tur aracılığıyla Kırklareli Üniversitesi Teknik Bilimler Kampüsünü geziyorsunuz. Farklı noktalara geçmek için ekrandaki ok işaretlerini kullanabilir veya sol menüden istediğiniz lokasyonu seçebilirsiniz.';
        }
        
        else if (soru.includes('öğrenci işleri') || soru.includes('kayıt') || soru.includes('ogrenci hizmetleri')) {
            return 'Öğrenci İşleri Daire Başkanlığı, öğrencilerimizin kayıt, belge, transkript gibi işlemlerini yürüten birimdir. Merkez Kampüste yer almaktadır. Ayrıca çevrimiçi olarak OBS (Öğrenci Bilgi Sistemi) üzerinden de birçok işleminizi gerçekleştirebilirsiniz.';
        }
        
        else if (soru.includes('yurt') || soru.includes('kalınacak yer') || soru.includes('barınma')) {
            return 'Üniversitemiz öğrencileri için KYK yurtları ve özel yurt seçenekleri bulunmaktadır. Ayrıca şehir merkezinde ve kampüslere yakın bölgelerde kiralık daire alternatifleri mevcuttur. Barınma konusunda Sağlık, Kültür ve Spor Daire Başkanlığı\'ndan detaylı bilgi alabilirsiniz.';
        }
        
        else if (soru.includes('ulaşım') || soru.includes('nasıl gelinir') || soru.includes('otobüs')) {
            return 'Kampüslerimize şehir merkezinden düzenli olarak belediye otobüsleri ve öğrenci servisleri ile ulaşım sağlanmaktadır. Ayrıca İstanbul, Edirne, Tekirdağ gibi çevre illerden de düzenli otobüs seferleri bulunmaktadır.';
        }
        
        else if (soru.includes('merhaba') || soru.includes('selam') || soru.includes('sa') || soru.includes('hello')) {
            return 'Merhaba! Size nasıl yardımcı olabilirim? Üniversitemiz hakkında merak ettiğiniz herhangi bir konuyu sorabilirsiniz.';
        }
        
        else if (soru.includes('teşekkür') || soru.includes('sağol') || soru.includes('tesekkur')) {
            return 'Rica ederim! Başka sorularınız olursa yardımcı olmaktan memnuniyet duyarım.';
        }
        
        else {
            return 'Bu konuda henüz detaylı bilgim yok, ancak Kırklareli Üniversitesi resmi web sitesi <a href="https://www.klu.edu.tr" target="_blank">www.klu.edu.tr</a> adresinden daha fazla bilgi alabilirsiniz. Başka nasıl yardımcı olabilirim?';
        }
    }
    
    if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        const recognition = new SpeechRecognition();
        recognition.lang = 'tr-TR';
        recognition.continuous = false;
        recognition.interimResults = false;
        
        asistanSes.addEventListener('click', function() {
            if (!asistanSes.classList.contains('dinliyor')) {
                recognition.start();
                asistanSes.classList.add('dinliyor');
                asistanSes.innerHTML = '<i class="fas fa-microphone-alt"></i>';
            } else {
                recognition.stop();
                asistanSes.classList.remove('dinliyor');
                asistanSes.innerHTML = '<i class="fas fa-microphone"></i>';
            }
        });
        
        recognition.onresult = function(event) {
            const seslendirilenMetin = event.results[0][0].transcript;
            asistanSoru.value = seslendirilenMetin;
            mesajGonder();
            asistanSes.classList.remove('dinliyor');
            asistanSes.innerHTML = '<i class="fas fa-microphone"></i>';
        };
        
        recognition.onerror = function() {
            asistanSes.classList.remove('dinliyor');
            asistanSes.innerHTML = '<i class="fas fa-microphone"></i>';
            asistanMesajiEkle('Sesinizi duyamadım. Lütfen tekrar deneyin veya yazarak sorunuzu iletebilirsiniz.');
        };
        
        recognition.onend = function() {
            asistanSes.classList.remove('dinliyor');
            asistanSes.innerHTML = '<i class="fas fa-microphone"></i>';
        };
    } else {
        if (asistanSes) {
            asistanSes.style.display = 'none';
        }
        console.log('Bu tarayıcı konuşma tanıma özelliğini desteklemiyor.');
    }
}