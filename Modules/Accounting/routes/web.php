<?php

use Illuminate\Support\Facades\Route;
use Modules\Accounting\Http\Controllers\AccountController;
use Modules\Accounting\Http\Controllers\TransactionController;
use Modules\Accounting\Http\Controllers\InvoiceController;
use Modules\Accounting\Http\Controllers\AccountTransactionController;
use Modules\Accounting\Http\Controllers\InvoiceTemplateController;

Route::middleware(['auth', 'verified', 'module_access:Accounting', 'readonly'])->prefix('accounting')->name('accounting.')->group(function () {
    Route::get('/', [\Modules\Accounting\Http\Controllers\DashboardController::class, 'index'])->name('index');
    Route::get('/dashboard', [\Modules\Accounting\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('accounts/import-defaults', [AccountController::class, 'importDefaults'])->name('accounts.import-defaults');
    Route::post('invoices/{invoice}/send-gib', [InvoiceController::class, 'sendToGib'])->name('invoices.send-to-gib');
    Route::resource('accounts', AccountController::class);
    Route::resource('transactions', TransactionController::class);
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    Route::resource('invoices', InvoiceController::class);
    
    // Fatura Şablonları
    Route::post('invoice-templates/preview', [InvoiceTemplateController::class, 'preview'])->name('invoice-templates.preview');
    Route::resource('invoice-templates', InvoiceTemplateController::class);
    
    // Cari Hesap Yönetimi
    Route::get('/account-transactions', [AccountTransactionController::class, 'index'])->name('account-transactions.index');
    Route::get('/account-transactions/create', [AccountTransactionController::class, 'create'])->name('account-transactions.create');
    Route::post('/account-transactions', [AccountTransactionController::class, 'store'])->name('account-transactions.store');
    Route::get('/account-transactions/{contact}', [AccountTransactionController::class, 'show'])->name('account-transactions.show');
    
    // Kasa/Banka Yönetimi
    Route::resource('cash-bank-accounts', \Modules\Accounting\Http\Controllers\CashBankAccountController::class);
    Route::resource('cash-bank-transactions', \Modules\Accounting\Http\Controllers\CashBankTransactionController::class);
    
    // Finansal Raporlar
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [\Modules\Accounting\Http\Controllers\FinancialReportController::class, 'index'])->name('index');
        Route::get('/income-statement', [\Modules\Accounting\Http\Controllers\FinancialReportController::class, 'incomeStatement'])->name('income-statement');
        Route::get('/balance-sheet', [\Modules\Accounting\Http\Controllers\FinancialReportController::class, 'balanceSheet'])->name('balance-sheet');
        Route::get('/trial-balance', [\Modules\Accounting\Http\Controllers\FinancialReportController::class, 'trialBalance'])->name('trial-balance');
        Route::get('/vat-declaration', [\Modules\Accounting\Http\Controllers\FinancialReportController::class, 'vatDeclaration'])->name('vat-declaration');
    });
    
    // Çek Yönetimi
    Route::prefix('cheques')->name('cheques.')->group(function () {
        Route::get('/', [\Modules\Accounting\Http\Controllers\ChequeController::class, 'index'])->name('index');
        Route::get('/create', [\Modules\Accounting\Http\Controllers\ChequeController::class, 'create'])->name('create');
        Route::post('/', [\Modules\Accounting\Http\Controllers\ChequeController::class, 'store'])->name('store');
        Route::get('/{id}', [\Modules\Accounting\Http\Controllers\ChequeController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\Modules\Accounting\Http\Controllers\ChequeController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\Modules\Accounting\Http\Controllers\ChequeController::class, 'update'])->name('update');
        Route::delete('/{id}', [\Modules\Accounting\Http\Controllers\ChequeController::class, 'destroy'])->name('destroy');
        
        // Çek İşlemleri
        Route::post('/{id}/deposit', [\Modules\Accounting\Http\Controllers\ChequeController::class, 'deposit'])->name('deposit');
        Route::post('/{id}/cash', [\Modules\Accounting\Http\Controllers\ChequeController::class, 'cash'])->name('cash');
        Route::post('/{id}/transfer', [\Modules\Accounting\Http\Controllers\ChequeController::class, 'transfer'])->name('transfer');
        Route::post('/{id}/bounce', [\Modules\Accounting\Http\Controllers\ChequeController::class, 'bounce'])->name('bounce');
        Route::post('/{id}/cancel', [\Modules\Accounting\Http\Controllers\ChequeController::class, 'cancel'])->name('cancel');
    });
    
    // Senet Yönetimi
    Route::prefix('promissory-notes')->name('promissory-notes.')->group(function () {
        Route::get('/', [\Modules\Accounting\Http\Controllers\PromissoryNoteController::class, 'index'])->name('index');
        Route::get('/create', [\Modules\Accounting\Http\Controllers\PromissoryNoteController::class, 'create'])->name('create');
        Route::post('/', [\Modules\Accounting\Http\Controllers\PromissoryNoteController::class, 'store'])->name('store');
        Route::get('/{id}', [\Modules\Accounting\Http\Controllers\PromissoryNoteController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\Modules\Accounting\Http\Controllers\PromissoryNoteController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\Modules\Accounting\Http\Controllers\PromissoryNoteController::class, 'update'])->name('update');
        Route::delete('/{id}', [\Modules\Accounting\Http\Controllers\PromissoryNoteController::class, 'destroy'])->name('destroy');
        
        // Senet İşlemleri
        Route::post('/{id}/deposit', [\Modules\Accounting\Http\Controllers\PromissoryNoteController::class, 'deposit'])->name('deposit');
        Route::post('/{id}/cash', [\Modules\Accounting\Http\Controllers\PromissoryNoteController::class, 'cash'])->name('cash');
        Route::post('/{id}/transfer', [\Modules\Accounting\Http\Controllers\PromissoryNoteController::class, 'transfer'])->name('transfer');
        Route::post('/{id}/protest', [\Modules\Accounting\Http\Controllers\PromissoryNoteController::class, 'protest'])->name('protest');
        Route::post('/{id}/cancel', [\Modules\Accounting\Http\Controllers\PromissoryNoteController::class, 'cancel'])->name('cancel');
    });
    
    // Portföy Yönetimi
    Route::prefix('portfolio')->name('portfolio.')->group(function () {
        Route::get('/', [\Modules\Accounting\Http\Controllers\ChequeNotePortfolioController::class, 'index'])->name('index');
        Route::get('/received-cheques', [\Modules\Accounting\Http\Controllers\ChequeNotePortfolioController::class, 'receivedCheques'])->name('received-cheques');
        Route::get('/issued-cheques', [\Modules\Accounting\Http\Controllers\ChequeNotePortfolioController::class, 'issuedCheques'])->name('issued-cheques');
        Route::get('/received-notes', [\Modules\Accounting\Http\Controllers\ChequeNotePortfolioController::class, 'receivedNotes'])->name('received-notes');
        Route::get('/issued-notes', [\Modules\Accounting\Http\Controllers\ChequeNotePortfolioController::class, 'issuedNotes'])->name('issued-notes');
        Route::get('/upcoming', [\Modules\Accounting\Http\Controllers\ChequeNotePortfolioController::class, 'upcomingDueDates'])->name('upcoming');
    });
});
