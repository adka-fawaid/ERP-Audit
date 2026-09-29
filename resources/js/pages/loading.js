let loadingTimer = null;

function showLoading() {
    let loading = document.getElementById('globalLoading');

    if (!loading) {
        loading = document.createElement('div');

        loading.id = 'globalLoading';

        loading.innerHTML = `
            <div style="display:flex; flex-direction:column; align-items:center; justify-content:center;">
                <img src="/images/loading.gif" alt="Loading" style="width:96px; height:96px; object-fit:contain;">
                <p style="margin-top:12px; font-size:14px; font-weight:500; color:#6b7280;">Memuat...</p>
            </div>
        `;

        loading.style.cssText = 'position:fixed; inset:0; z-index:99999; display:flex; align-items:center; justify-content:center; background:rgba(255,255,255,0.82); backdrop-filter:blur(3px);';

        document.body.appendChild(loading);
    } else {
        loading.style.display = 'flex';
    }
}

function hideLoading() {
    const loading = document.getElementById('globalLoading');

    if (!loading) return;

    loading.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('submit', event => {
        const form = event.target;

        if (!(form instanceof HTMLFormElement)) return;

        if (form.dataset.loadingHandled === 'true') return;

        form.dataset.loadingHandled = 'true';

        showLoading();
    });

    document.addEventListener('click', event => {
        const link = event.target.closest('a');

        if (!link) return;

        const href = link.getAttribute('href');

        if (!href) return;
        if (href.startsWith('#')) return;
        if (href.startsWith('javascript:')) return;
        if (link.target === '_blank') return;
        if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;

        const url = new URL(link.href, window.location.href);

        if (url.origin !== window.location.origin) return;

        if (url.href === window.location.href) return;

        showLoading();
    });
});

window.showLoading = showLoading;
window.hideLoading = hideLoading;