# Modül Bazlı Geliştirme Planı

Bu plan, sistemin parçalar halinde (modül modül) nasıl geliştirileceğini ve entegre edileceğini detaylandırır.

## Modül Yapısı
Sistem `nwidart/laravel-modules` paketi veya benzer bir klasör yapısı üzerine inşa edilecektir. Her modül kendi başına çalışan mini bir uygulama gibi davranır.

### Standart Modül Klasör Yapısı
```
Modules/
  ModuleAdi/
    Config/        # Modül ayarları
    Database/
      Migrations/  # Modüle özel tablolar
      Seeders/
    Http/
      Controllers/
      Middleware/
      Requests/
    Models/
    Resources/
      views/       # Modüle özel Blade dosyaları
      lang/        # Dil dosyaları
    Routes/        # web.php, api.php
    Providers/     # ServiceProvider (Modülün sisteme kaydı)
    composer.json  # Bağımlılıklar
```

## Geliştirme Sırası

### 1. Sistem Çekirdeği (Kernel & System)
Modüllerin üzerine inşa edileceği temel kaçınılmazdır.
- **Kimlik Doğrulama:** Merkezi giriş/çıkış.
- **Yetkilendirme:** Rol ve izin altyapısı.
- **Dashboard:** Modüllerin özetlerini gösteren ana ekran.
- **Tenant Management:** Şirket seçimi ve oluşturulması.

### 2. Finans ve Muhasebe (CORE Module)
ERP'nin kalbi olduğu için ilk sırada geliştirilir.
- **Hedef:** Fatura kesebilmek ve bunu muhasebeleştirmek.
- **Bağımlılık:** Sadece Sistem Çekirdeği.
- **Özellikler:** Cari Hesaplar, Kasa/Banka, Fatura, Gelir/Gider.

### 3. CRM Modülü
- **Hedef:** Müşteri ilişkilerini yönetmek.
- **Entegrasyon:** Muhasebe modülündeki "Cari Hesaplar" ile senkronize çalışır. CRM'den müşteri eklendiğinde Muhasebeye de cari olarak düşer (veya tersi).
- **Özellikler:** Teklifler, Görüşmeler, Müşteri Takibi.

### 4. Stok (Inventory) Modülü
- **Hedef:** Ürün ve depo takibi.
- **Entegrasyon:** Fatura (Muhasebe) ile doğrudan bağlantılıdır. Satış faturası stoktan düşer.
- **Özellikler:** Ürün kartları, Depo tanımları, Stok hareketleri.

### 5. HRM (İnsan Kaynakları)
- **Hedef:** Personel takibi.
- **Entegrasyon:** Maaş ödemeleri Muhasebe/Gider modülüne entegre olur.
- **Özellikler:** Personel kartları, İzinler, Bordro (Basit).

## Modüler Bağımsızlık ve Lisanslama
- Her modül `module.json` dosyasında `active: true/false` ayarına sahiptir.
- Abonelik paketine göre müşterinin hangi modülleri göreceği `Middleware` ile kontrol edilir.
- Örn: "Start Paketi" kullanan bir müşteri `CRM` rotalarına girmeye çalıştığında "Yükseltme Gerekli" hatası alır.
