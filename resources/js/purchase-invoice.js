window.__toastTimer = null;

window.__showToast = function (msg, type = 'success') {
    const el = document.getElementById('pi-toast');
    if (!el) return;

    const icons = {
        success: `<svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"/>
        </svg>`,

        error: `<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                clip-rule="evenodd"/>
        </svg>`,

        warning: `<svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M8.257 3.099c.366-.446.957-.446 1.323 0l6.518 7.94c.38.463.05 1.146-.662 1.146H2.401c-.712 0-1.042-.683-.662-1.146l6.518-7.94zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-7a1 1 0 00-1 1v3a1 1 0 002 0V7a1 1 0 00-1-1z"
                clip-rule="evenodd"/>
        </svg>`
    };

    el.innerHTML = `
        <div class="pi-toast-card pi-${type}">
            ${icons[type] ?? ''}
            <div class="font-medium">${msg}</div>
        </div>
    `;

    el.classList.add('show');

    if (window.__toastTimer) clearTimeout(window.__toastTimer);

    window.__toastTimer = setTimeout(() => {
        el.classList.remove('show');
    }, 3000);
};



(function syncPurchaseInvoiceTheme() {
    const sync = () => {
        document.querySelectorAll('.pi-root').forEach((el) => {
            document.documentElement.classList.contains('dark')
                ? el.classList.add('pi-dark')
                : el.classList.remove('pi-dark');
        });
    };
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', sync);
    } else {
        sync();
    }
    new MutationObserver(sync).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    document.addEventListener('livewire:navigated', sync);
})();


