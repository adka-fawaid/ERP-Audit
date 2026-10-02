function showLoading() {
    const loadingOverlay = document.getElementById('loadingOverlay');

    if (!loadingOverlay) return;

    loadingOverlay.style.display = 'flex';
}

function hideLoading() {
    const loadingOverlay = document.getElementById('loadingOverlay');

    if (!loadingOverlay) return;

    loadingOverlay.style.display = 'none';
}

function openLogoutModal() {
    const modal = document.getElementById('logoutModal');

    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
}

function closeLogoutModal() {
    const modal = document.getElementById('logoutModal');

    if (!modal) return;

    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function confirmLogout() {
    const form = document.getElementById('logoutForm');

    if (!form) return;

    form.submit();
}

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapseToggle = document.getElementById('sidebarCollapseToggle');
    const sidebarShowToggle = document.getElementById('sidebarShowToggle');
    const mainWrapper = document.getElementById('main-wrapper');
    const headerTitle = document.getElementById('headerTitle');

    const profileMenuButton = document.getElementById('profileMenuButton');
    const profileMenu = document.getElementById('profileMenu');
    const profileMenuIcon = document.getElementById('profileMenuIcon');

    const serverDateTime = document.getElementById('serverDateTime');

    function updateSidebarResponsive() {
        if (!sidebar || !mainWrapper) return;

        if (window.innerWidth < 1024) {
            sidebar.style.transform = 'translateX(-100%)';
            mainWrapper.style.marginLeft = '0';

            if (sidebarCollapseToggle) {
                sidebarCollapseToggle.style.display = 'none';
            }

            if (sidebarShowToggle) {
                sidebarShowToggle.style.display = 'flex';
            }

            return;
        }

        sidebar.style.transform = 'translateX(0)';
        mainWrapper.style.marginLeft = '16rem';

        if (sidebarCollapseToggle) {
            sidebarCollapseToggle.style.display = 'flex';
        }

        if (sidebarShowToggle) {
            sidebarShowToggle.style.display = 'none';
        }
    }

    updateSidebarResponsive();

    window.addEventListener('resize', updateSidebarResponsive);

    sidebarCollapseToggle?.addEventListener('click', () => {
        if (!sidebar || !mainWrapper) return;

        sidebar.style.transform = 'translateX(-100%)';
        mainWrapper.style.marginLeft = '0';

        if (sidebarCollapseToggle) {
            sidebarCollapseToggle.style.display = 'none';
        }

        if (sidebarShowToggle) {
            sidebarShowToggle.style.display = 'flex';
        }
    });

    sidebarShowToggle?.addEventListener('click', () => {
        if (!sidebar || !mainWrapper) return;

        sidebar.style.transform = 'translateX(0)';
        mainWrapper.style.marginLeft = '16rem';

        if (sidebarCollapseToggle) {
            sidebarCollapseToggle.style.display = 'flex';
        }

        if (sidebarShowToggle) {
            sidebarShowToggle.style.display = 'none';
        }
    });

    if (serverDateTime) {
        let serverTime = Number(serverDateTime.dataset.serverTime) * 1000;

        const updateServerTime = () => {
            const date = new Date(serverTime);

            const day = String(date.getDate()).padStart(2, '0');
            const month = date.toLocaleDateString('id-ID', {
                month: 'long'
            });
            const year = date.getFullYear();

            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');

            serverDateTime.textContent = `${day} ${month} ${year}, ${hours}:${minutes}:${seconds} WIB`;

            serverTime += 1000;
        };

        updateServerTime();

        setInterval(updateServerTime, 1000);
    }

    profileMenuButton?.addEventListener('click', () => {
        profileMenu?.classList.toggle('hidden');
        profileMenuIcon?.classList.toggle('rotate-180');
    });

    document.addEventListener('click', (event) => {
        if (!profileMenuButton || !profileMenu) return;

        if (!profileMenuButton.contains(event.target) && !profileMenu.contains(event.target)) {
            profileMenu.classList.add('hidden');
            profileMenuIcon?.classList.remove('rotate-180');
        }
    });

    document.querySelectorAll('form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (form.dataset.loadingSubmitted === 'true') {
                return;
            }

            event.preventDefault();

            form.dataset.loadingSubmitted = 'true';

            showLoading();

            setTimeout(() => {
                form.submit();
            }, 300);
        });
    });
});

window.showLoading = showLoading;
window.hideLoading = hideLoading;
window.openLogoutModal = openLogoutModal;
window.closeLogoutModal = closeLogoutModal;
window.confirmLogout = confirmLogout;