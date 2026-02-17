import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

window.Alpine = Alpine;
Alpine.plugin(collapse);

Alpine.data('sidebarMenu', (initialState) => ({
    activeMenu: initialState,
    _toggling: false,

    toggle(menu) {
        if (this._toggling) return;
        this._toggling = true;

        this.activeMenu = this.activeMenu === menu ? null : menu;

        setTimeout(() => {
            this._toggling = false;
        }, 300);
    },

    isOpen(menu) {
        return this.activeMenu === menu;
    }
}));

Alpine.start();

// Global Preloader Control
window.showLoader = (title = 'Veriler İşleniyor', message = 'Lütfen bekleyin...') => {
    const loader = document.getElementById('global-preloader');
    const titleEl = document.getElementById('preloader-title');
    const messageEl = document.getElementById('preloader-message');

    if (loader) {
        if (titleEl) titleEl.textContent = title;
        if (messageEl) messageEl.textContent = message;

        loader.classList.remove('hidden');
        // Small delay to trigger transition
        setTimeout(() => loader.classList.add('active'), 50);
    }
};

window.hideLoader = () => {
    const loader = document.getElementById('global-preloader');
    if (loader) {
        loader.classList.remove('active');
        // Wait for transition to finish before hiding
        setTimeout(() => loader.classList.add('hidden'), 500);
    }
};

// Global Delete Confirmation
document.addEventListener('submit', (e) => {
    const form = e.target;
    const methodInput = form.querySelector('input[name="_method"]');

    // Check if it's a DELETE form and hasn't been confirmed yet
    if (methodInput && methodInput.value.toUpperCase() === 'DELETE' && !form.dataset.confirmed) {
        e.preventDefault();

        const modal = document.getElementById('global-delete-modal');
        const modalMsg = document.getElementById('modal-message');
        const confirmBtn = document.getElementById('modal-confirm');
        const cancelBtn = document.getElementById('modal-cancel');

        // Custom message if provided
        if (form.dataset.confirm) {
            modalMsg.textContent = form.dataset.confirm;
        } else {
            modalMsg.textContent = 'Bu işlemi gerçekleştirmek istediğinize emin misiniz? Bu işlem geri alınamaz.';
        }

        // Show modal
        modal.classList.add('active');

        // Handle confirm
        const handleConfirm = () => {
            form.dataset.confirmed = "true";
            form.submit();
            closeModal();
        };

        // Handle cancel
        const closeModal = () => {
            modal.classList.remove('active');
            confirmBtn.removeEventListener('click', handleConfirm);
            cancelBtn.removeEventListener('click', closeModal);
        };

        confirmBtn.addEventListener('click', handleConfirm);
        cancelBtn.addEventListener('click', closeModal);
    }
});
