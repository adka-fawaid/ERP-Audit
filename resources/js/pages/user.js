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

function toggleUserType() {
    const type = document.getElementById('auth_type')?.value;
    const companyFields = document.getElementById('companyFields');
    const externalFields = document.getElementById('externalFields');

    if (!companyFields || !externalFields) return;

    if (type === 'company') {
        companyFields.classList.remove('hidden');
        externalFields.classList.add('hidden');
    } else {
        companyFields.classList.add('hidden');
        externalFields.classList.remove('hidden');
    }
}

async function showUser(id) {
    try {
        if (typeof showLoading === 'function') {
            showLoading();
        }

        const response = await fetch(`managementUser/${id}`, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const user = await response.json();

        const viewNik = document.getElementById('viewNik');
        const viewName = document.getElementById('viewName');
        const viewEmail = document.getElementById('viewEmail');
        const viewAuthType = document.getElementById('viewAuthType');
        const viewRole = document.getElementById('viewRole');
        const viewStatus = document.getElementById('viewStatus');
        const viewCreatedAt = document.getElementById('viewCreatedAt');
        const viewAvatar = document.getElementById('viewAvatar');

        if (viewNik) {
            viewNik.textContent = user.nik || '-';
        }

        if (viewName) {
            viewName.textContent = user.name || '-';
        }

        if (viewEmail) {
            viewEmail.textContent = user.email || '-';
        }

        if (viewAuthType) {
            viewAuthType.textContent = user.auth_type === 'company' ? 'Internal' : 'External';
        }

        if (viewRole) {
            viewRole.textContent = user.role === 'admin' ? 'Admin' : 'Viewer';
        }

        if (viewStatus) {
            viewStatus.textContent = user.is_active ? 'Aktif' : 'Nonaktif';
        }

        if (viewCreatedAt) {
            viewCreatedAt.textContent = user.created_at || '-';
        }

        if (viewAvatar) {
            viewAvatar.textContent = user.name ? user.name.charAt(0).toUpperCase() : '-';
        }

        const modal = document.getElementById('viewUserModal');

        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    } catch (error) {
        console.error('View user error:', error);
        alert('Gagal mengambil data user.');
    } finally {
        if (typeof hideLoading === 'function') {
            hideLoading();
        }
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
        if (typeof showLoading === 'function') {
            showLoading();
        }

        const response = await fetch(`managementUser/${id}`, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const user = await response.json();

        const form = document.getElementById('editUserForm');
        const editNik = document.getElementById('editNik');
        const editName = document.getElementById('editName');
        const editEmail = document.getElementById('editEmail');
        const editPassword = document.getElementById('editPassword');
        const editRole = document.getElementById('editRole');
        const editRoleDisplay = document.getElementById('editRoleDisplay');
        const editRoleHidden = document.getElementById('editRoleHidden');
        const editStatus = document.getElementById('editStatus');
        const companyFields = document.getElementById('editCompanyFields');
        const externalFields = document.getElementById('editExternalFields');

        if (form) {
            form.action = `managementUser/${id}`;
        }

        if (editNik) {
            editNik.value = user.nik || '';
        }

        if (editName) {
            editName.value = user.name || '';
        }

        if (editEmail) {
            editEmail.value = user.email || '';
        }

        if (editPassword) {
            editPassword.value = '';
        }

        if (editStatus) {
            editStatus.value = user.is_active ? '1' : '0';
        }

        if (user.auth_type === 'company') {
            companyFields?.classList.remove('hidden');
            externalFields?.classList.add('hidden');

            if (editRole) {
                editRole.classList.remove('hidden');
                editRole.value = user.role;
            }

            if (editRoleDisplay) {
                editRoleDisplay.classList.add('hidden');
            }

            if (editRoleHidden) {
                editRoleHidden.value = user.role;
            }
        } else {
            companyFields?.classList.add('hidden');
            externalFields?.classList.remove('hidden');

            if (editRole) {
                editRole.classList.add('hidden');
            }

            if (editRoleDisplay) {
                editRoleDisplay.classList.remove('hidden');
                editRoleDisplay.textContent = 'Viewer';
            }

            if (editRoleHidden) {
                editRoleHidden.value = 'viewer';
            }
        }

        const modal = document.getElementById('editUserModal');

        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    } catch (error) {
        console.error('Edit user error:', error);
        alert('Gagal mengambil data user.');
    } finally {
        if (typeof hideLoading === 'function') {
            hideLoading();
        }
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

    form.action = `managementUser/${id}`;
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

function closeAlert(id) {
    const alert = document.getElementById(id);

    if (!alert) return;

    alert.remove();
}

document.addEventListener('DOMContentLoaded', () => {
    const addUserModal = document.getElementById('addUserModal');
    const viewUserModal = document.getElementById('viewUserModal');
    const editUserModal = document.getElementById('editUserModal');
    const deleteUserModal = document.getElementById('deleteUserModal');
    const editRole = document.getElementById('editRole');
    const editRoleHidden = document.getElementById('editRoleHidden');

    addUserModal?.addEventListener('click', event => {
        if (event.target === addUserModal) {
            closeModal();
        }
    });

    viewUserModal?.addEventListener('click', event => {
        if (event.target === viewUserModal) {
            closeViewModal();
        }
    });

    editUserModal?.addEventListener('click', event => {
        if (event.target === editUserModal) {
            closeEditModal();
        }
    });

    deleteUserModal?.addEventListener('click', event => {
        if (event.target === deleteUserModal) {
            closeDeleteModal();
        }
    });

    editRole?.addEventListener('change', () => {
        if (editRoleHidden) {
            editRoleHidden.value = editRole.value;
        }
    });

    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') return;

        closeModal();
        closeViewModal();
        closeEditModal();
        closeDeleteModal();
    });
    const filterForm = document.getElementById('userFilterForm');
    const searchInput = document.getElementById('userSearch');

    if (filterForm) {
        const filterSelects = filterForm.querySelectorAll('select');

        filterSelects.forEach(select => {
            select.addEventListener('change', () => {
                if (typeof showLoading === 'function') {
                    showLoading();
                }

                filterForm.submit();
            });
        });

        if (searchInput) {
            let searchTimeout;

            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);

                searchTimeout = setTimeout(() => {
                    if (typeof showLoading === 'function') {
                        showLoading();
                    }

                    filterForm.submit();
                }, 500);
            });
        }
    }

    const successAlert = document.getElementById('successAlert');
    const errorAlert = document.getElementById('errorAlert');

    if (successAlert) {
        setTimeout(() => {
            successAlert.remove();
        }, 5000);
    }

    if (errorAlert) {
        setTimeout(() => {
            errorAlert.remove();
        }, 7000);
    }
});

window.openModal = openModal;
window.closeModal = closeModal;
window.toggleUserType = toggleUserType;
window.showUser = showUser;
window.closeViewModal = closeViewModal;
window.editUser = editUser;
window.closeEditModal = closeEditModal;
window.deleteUser = deleteUser;
window.closeDeleteModal = closeDeleteModal;
window.closeAlert = closeAlert;