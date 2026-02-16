<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 0. Maaş Tanımları (Geçmiş Takibi İçin)
        Schema::create('hr_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('type', ['gross', 'net'])->default('gross');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 1. Bordro Parametreleri (Yasal Oranlar)
        Schema::create('hr_payroll_parameters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->year('year');
            $table->string('name'); // e.g., "2026 Yasal Parametreleri"
            $table->decimal('sgk_worker_rate', 5, 2)->default(14.00);
            $table->decimal('unemployment_worker_rate', 5, 2)->default(1.00);
            $table->decimal('sgk_employer_rate', 5, 2)->default(15.50); // 5 puanlık indirim dahil varsayılan
            $table->decimal('unemployment_employer_rate', 5, 2)->default(2.00);
            $table->decimal('stamp_tax_rate', 8, 5)->default(0.00759);
            $table->json('income_tax_brackets'); // [%15, %20, %27, %35, %40 dilimleri]
            $table->decimal('min_wage_gross', 15, 2); // GV/DV istisnası için gerekli
            $table->decimal('sgk_base_matrah', 15, 2);
            $table->decimal('sgk_ceiling_matrah', 15, 2);
            $table->timestamps();
        });

        // 2. Bordro Üst Başlık
        Schema::create('hr_payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->integer('month');
            $table->integer('year');
            $table->string('description')->nullable();
            $table->enum('status', ['draft', 'approved', 'paid', 'cancelled'])->default('draft');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // 3. Bordro Kalemleri (Personel Bazlı)
        Schema::create('hr_payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained('hr_payrolls')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            
            // Kazançlar
            $table->decimal('gross_salary', 15, 2);
            $table->decimal('bonus', 15, 2)->default(0);
            $table->decimal('overtime_pay', 15, 2)->default(0);
            
            // Kesintiler (İşçi)
            $table->decimal('sgk_worker_cut', 15, 2);
            $table->decimal('unemployment_worker_cut', 15, 2);
            $table->decimal('income_tax_base', 15, 2); // GV Matrahı
            $table->decimal('cumulative_income_tax_base', 15, 2); // Kümülatif GV Matrahı
            $table->decimal('calculated_income_tax', 15, 2);
            $table->decimal('calculated_stamp_tax', 15, 2);
            
            // İstisnalar (Türkiye Mevzuatı - Asgari Ücret İstisnası)
            $table->decimal('income_tax_exemption', 15, 2)->default(0);
            $table->decimal('stamp_tax_exemption', 15, 2)->default(0);
            
            // Netler
            $table->decimal('net_salary', 15, 2);
            $table->decimal('other_deductions', 15, 2)->default(0); // Avans, icra vb.
            $table->decimal('final_net_paid', 15, 2);
            
            // İşveren Maliyeti
            $table->decimal('sgk_employer_cut', 15, 2);
            $table->decimal('unemployment_employer_cut', 15, 2);
            $table->decimal('total_employer_cost', 15, 2);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_payroll_items');
        Schema::dropIfExists('hr_payrolls');
        Schema::dropIfExists('hr_payroll_parameters');
        Schema::dropIfExists('hr_salaries');
    }
};
