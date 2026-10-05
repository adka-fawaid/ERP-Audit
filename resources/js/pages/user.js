function openModal() {
    const modal = document.getElementById('addUserModal');

    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
}

function closeModal() {
    const modal = document.getElementById('addUserModal');

    if (!modal) return;

    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

async function showUser(id) {
    try {
        const response = await fetch(`/managementUser/${id}`, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const user = await response.json();

        document.getElementById('viewNik').textContent = user.nik || '-';
        document.getElementById('viewNikDetail').textContent = user.nik || '-';
        document.getElementById('viewName').textContent = user.name || '-';

        document.getElementById('viewRole').textContent =
            user.role === 'admin' ? 'Admin' : 'Viewer';

        document.getElementById('viewStatus').textContent =
            user.status === 'active' ? 'Aktif' : 'Nonaktif';

        document.getElementById('viewAvatar').textContent =
            user.name ? user.name.charAt(0).toUpperCase() : '-';

        const modal = document.getElementById('viewUserModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');

    } catch (error) {
        console.error(error);
        alert('Gagal mengambil data user.');
    }
}

function closeViewModal() {
    const modal = document.getElementById('viewUserModal');

    if (!modal) return;

    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

async function editUser(id) {
    try {
        const response = await fetch(`/managementUser/${id}`, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const user = await response.json();

        document.getElementById('editUserForm').action =
            `/managementUser/${id}`;

        document.getElementById('editNik').value = user.nik || '';
        document.getElementById('editName').value = user.name || '';
        document.getElementById('editRole').value = user.role || 'viewer';
        document.getElementById('editStatus').value = user.status || 'active';

        const modal = document.getElementById('editUserModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');

    } catch (error) {
        console.error(error);
        alert('Gagal mengambil data user.');
    }
}

function closeEditModal() {
    const modal = document.getElementById('editUserModal');

    if (!modal) return;

    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function deleteUser(id, name) {
    const modal = document.getElementById('deleteUserModal');
    const form = document.getElementById('deleteUserForm');
    const userName = document.getElementById('deleteUserName');

    if (!modal || !form || !userName) return;

    form.action = `/managementUser/${id}`;
    userName.textContent = name;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteUserModal');

    if (!modal) return;

    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('input[name="search"]');
    const searchForm = searchInput?.closest('form');

    let searchTimer;

    searchInput?.addEventListener('input', () => {
        clearTimeout(searchTimer);

        searchTimer = setTimeout(() => {
            searchForm?.submit();
        }, 400);
    });
    const filters = searchForm?.querySelectorAll(
        'select[name="role"], select[name="status"]'
    );

    filters?.forEach(filter => {
        filter.addEventListener('change', () => {
            searchForm?.submit();
        });
    });

    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') return;

        closeModal();
        closeViewModal();
        closeEditModal();
        closeDeleteModal();
    });

    const successAlert = document.getElementById('successAlert');
    const errorAlert = document.getElementById('errorAlert');
    const validationAlert = document.getElementById('validationAlert');

    if (successAlert) {
        setTimeout(() => successAlert.remove(), 4000);
    }

    if (errorAlert) {
        setTimeout(() => errorAlert.remove(), 5000);
    }

    if (validationAlert) {
        setTimeout(() => validationAlert.remove(), 5000);
    }
});

function closeAlert(id) {
    const alert = document.getElementById(id);

    if (alert) {
        alert.remove();
    }
}

window.openModal = openModal;
window.closeModal = closeModal;
window.showUser = showUser;
window.closeViewModal = closeViewModal;
window.editUser = editUser;
window.closeEditModal = closeEditModal;
window.deleteUser = deleteUser;
window.closeDeleteModal = closeDeleteModal;
window.closeAlert = closeAlert;