# SaaS ERP Mimari Tasarımı

Bu belge, **cPanel (Shared Hosting)** ortamında stabil çalışacak, ancak gelecekte **VPS/Cloud** altyapısına kolayca taşınabilecek SaaS ERP sisteminin genel mimarisini açıklar.

## Genel Mimari Diyagramı (Mermaid)

```mermaid
graph TD
    User[Kullanıcılar (Web/Mobile)] -->|HTTPS| WebServer[Web Server (Apache/Nginx)]
    
    subgraph "Hosting Environment (cPanel / VPS)"
        WebServer -->|Request| LaravelApp[Laravel Application Layer]
        
        subgraph "Laravel Core"
            Route[Routing & Middleware]
            Auth[Auth & Tenancy Scope]
            Controller[Controllers]
            Service[Business Logic Services]
        end
        
        LaravelApp --> Route
        Route --> Auth
        Auth --> Controller
        Controller --> Service
        
        subgraph "Data & Storage"
            Service -->|Read/Write| MySQL[(MySQL Database 8.0)]
            Service -->|Cache/Session| Redis{Redis / File Cache}
            Service -->|File Uploads| Storage[Local Storage / Symlink]
        end
        
        subgraph "Background Processes"
            Cron[cPanel Cron Job] -->|Triggers| QueueWorker[Queue Worker Runner]
            QueueWorker -->|Process Jobs| Service
        end
    end
    
    MySQL -->|Backup| LocalBackup[Local Backup / Dump]
    Storage -->|Backup| Archive[Archive]
```

## Katmanların Detayları

### 1. Web Sunucusu & Giriş
- **Mevcut:** cPanel üzerinde standart Apache veya Litespeed.
- **Gelecek:** Cloud ortamında Nginx + Load Balancer.
- **Kritik:** Tüm istekler `public/index.php` üzerinden karşılanır. `.htaccess` dosyasının doğru yapılandırılması hayati önem taşır.

### 2. Uygulama Katmanı (Laravel)
- **Multi-Tenancy:** Uygulama seviyesinde soyutlama.
  - Her sorguya otomatik `where('company_id', $id)` ekleyen Global Scope.
  - Middleware ile gelen isteğin hangi şirkete ait olduğunun tespiti (Subdomain veya Route parametresi ile).
- **Modülerlik:** `Modules/` klasörü altında ayrıştırılmış servisler.
  - `Modules/Accounting`
  - `Modules/HRM`
  - Çekirdek uygulama sadece modülleri yükler ve ortak servisleri (Auth, Log, Notification) sağlar.

### 3. Veritabanı (MySQL)
- **Tek Veritabanı Modeli:** Bakım kolaylığı ve cPanel kısıtlamaları nedeniyle tüm şirketler tek bir veritabanında tutulur.
- **İzolasyon:** Yazılım seviyesinde sağlanır.
- **Performans:** 
  - `company_id` tüm tablolarda partition key veya ilk index olarak kullanılır.
  - Büyük tablolar (hareketler) ay/yıl bazlı bölümlenebilir (Partitioning desteği varsa) veya yazılım seviyesinde arşiv tablolarına taşınır.

### 4. Cache & Session
- **cPanel Önceliği:** Eğer hosting Redis destekliyorsa (`predis` veya `phpredis` ile) Redis kullanılır.
- **Fallback:** Redis yoksa Laravel'in `file` driver'ı kullanılır. Kod değişikliği gerekmez, `.env` üzerinden yönetilir.

### 5. Queue & Arkaplan İşlemleri
- **Problem:** cPanel'de `supervisor` çalıştırmak genelde mümkün değildir veya zordur.
- **Çözüm:** `schedule:run` komutu dakikalık cron olarak eklenir. `queue:work --stop-when-empty` komutu Scheduler üzerinden her dakika tetiklenir. Bu sayede uzun süreli daemon proseslere ihtiyaç duymadan queue işlenebilir.
- **Veri Kaynağı:** Queue job'ları veritabanında (`jobs` tablosu) tutulur.

### 6. Dosya Depolama
- **Abstraction:** Laravel `Architecture` (Flysystem) kullanılır.
- **Local:** Başlangıçta `storage/app/public` kullanılır.
- **Cloud:** İleride sadece `.env` değişimi ile S3 veya MinIO'ya geçilebilir. Kodda `Storage::disk('public')->put(...)` kullanıldığı sürece geçiş sorunsuz olur.
