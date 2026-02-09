# Veritabanı Şema Taslağı (Draft)

Bu taslak, multi-tenant SaaS yapısına uygun temel tabloları ve modüler genişlemeleri gösterir.
**Not:** Tüm tablolarda `created_at`, `updated_at` ve (hareket tablolarında) `deleted_at` (Soft Delete) standart olarak bulunur.

## Core (SaaS & Tenancy)

### `tenants` (veya `companies`)
Sistemi kullanan şirketlerin ana kaydı.
- `id` (PK, UUID veya BigInt)
- `name` (Şirket Adı)
- `domain` (Opsiyonel: subdomain girişleri için)
- `db_connection` (İleride sharding gerekirse veritabanı ayrımı için)
- `plan_id` (Abonelik planı)
- `subscription_status` (active, suspended, past_due)
- `settings` (JSON - Şirket özel ayarlar)

### `users`
Kullanıcılar. Email benzersizliği `company_id` ile kombinlenmelidir veya global email benzersizliği isteniyorsa tenant ilişkisi ayrı tabloda tutulmalıdır. Basitlik için tenant-scoped user:
- `id` (PK)
- `company_id` (FK -> tenants.id) **[INDEX]**
- `name`
- `email`
- `password`
- `is_company_owner` (Boolean)

### `roles` & `permissions` (Spatie yapısı)
- `id`
- `company_id` (FK) - Rollerin şirkete özel olması için.
- `name`
- `guard_name`

## Muhasebe Modülü (Accounting)

### `acc_fiscal_periods` (Mali Dönemler)
- `id`
- `company_id` (FK)
- `name` (Örn: "2024 Mali Yılı")
- `start_date`
- `end_date`
- `status` (open, closed, locked)

### `acc_accounts` (Hesap Planı - TDHP)
- `id`
- `company_id` (FK)
- `code` (Hesap Kodu: 100, 100.01 vb.) **[INDEX]**
- `name` (Hesap Adı)
- `parent_id` (Alt hesap hiyerarşisi için)
- `type` (asset, liability, equity, income, expense)

### `acc_transactions` (Yevmiye Fişleri - Master)
- `id`
- `company_id` (FK)
- `fiscal_period_id` (FK)
- `type` (opening, closing, regular, correction)
- `receipt_number` (Fiş No - Müteselsil)
- `date`
- `description`
- `is_approved` (Onay durumu)

### `acc_ledger_entries` (Yevmiye Satırları - Detail)
Bu tablo çok hızlı büyüyecektir. Partitioning adayıdır.
- `id`
- `company_id` (FK) **[INDEX]**
- `transaction_id` (FK -> acc_transactions)
- `account_id` (FK -> acc_accounts)
- `debit` (Borç - Decimal 15,2)
- `credit` (Alacak - Decimal 15,2)
- `description` (Satır açıklaması)
- `currency_code` (Dövizli işlemler için)
- `exchange_rate`

## Stok & Ürün (Inventory)

### `inv_items` (Stok Kartları)
- `id`
- `company_id` (FK)
- `code`
- `name`
- `unit` (Birim: adet, kg, lt)
- `tax_rate` (KDV Oranı)

### `inv_warehouses` (Depolar)
- `id`
- `company_id`

### `inv_movements` (Stok Hareketleri)
Append-only mantığı ile çalışır. Stok bakiyesi anlık hesaplanır veya snapshot alınır.
- `id`
- `company_id` (FK)
- `item_id` (FK)
- `warehouse_id` (FK)
- `type` (in, out, transfer)
- `quantity`
- `reference_type` (invoice, adjustment, production)
- `reference_id`

## CRM (Basitleştirilmiş)

### `crm_contacts` (Müşteri & Tedarikçi)
- `id`
- `company_id`
- `type` (customer, supplier, lead)
- `title`
- `tax_id`
- `address`
