document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('exportModal');
    const form = document.getElementById('exportForm');

    if (!modal || !form) return;

    const reason = document.getElementById('exportReason');
    const otherField = document.getElementById('exportReasonOtherField');
    const otherReason = document.getElementById('exportReasonOther');
    const error = document.getElementById('exportError');
    const submit = document.getElementById('exportSubmit');

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.addEventListener('click', event => {
        const button = event.target.closest('[data-export-url]');

        if (button) {
            event.preventDefault();
            form.reset();
            form.action = button.dataset.exportUrl;
            document.getElementById('exportReportName').textContent = button.dataset.exportReport;
            otherField.classList.add('hidden');
            otherReason.required = false;
            otherReason.value = '';
            form.querySelectorAll('[data-export-context]').forEach(input => input.remove());

            const allowedFilters = ['search', 'status', 'user', 'program', 'activity'];
            new URLSearchParams(window.location.search).forEach((value, name) => {
                if (!allowedFilters.includes(name) || !value) return;

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                input.dataset.exportContext = 'true';
                form.appendChild(input);
            });

            error.classList.add('hidden');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            return;
        }

        if (event.target.closest('[data-export-close]')) {
            closeModal();
        }
    });

    reason.addEventListener('change', () => {
        const showOther = reason.value === 'other';
        otherField.classList.toggle('hidden', !showOther);
        otherReason.required = showOther;
    });

    form.addEventListener('submit', async event => {
        event.preventDefault();
        event.stopPropagation();
        error.classList.add('hidden');

        if (!form.reportValidity()) return;

        const fromDate = document.getElementById('exportFromDate').value;
        const toDate = document.getElementById('exportToDate').value;
        if (toDate < fromDate) {
            error.textContent = 'Tanggal sampai tidak boleh lebih kecil dari tanggal mulai.';
            error.classList.remove('hidden');
            return;
        }

        submit.disabled = true;
        submit.textContent = 'Menyiapkan Excel...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    Accept: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(form)
            });

            if (!response.ok) {
                const result = await response.json();
                throw new Error(Object.values(result.errors || {}).flat()[0] || result.message || 'Export gagal.');
            }

            const blob = await response.blob();
            const filename = response.headers.get('Content-Disposition')?.match(/filename="?([^";]+)"?/)?.[1] || 'qad-report.xlsx';
            const downloadUrl = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = downloadUrl;
            link.download = filename;
            link.click();
            URL.revokeObjectURL(downloadUrl);
            closeModal();
        } catch (exception) {
            error.textContent = exception.message;
            error.classList.remove('hidden');
        } finally {
            submit.disabled = false;
            submit.textContent = 'Download Excel';
        }
    });

    modal.addEventListener('click', event => {
        if (event.target === modal) closeModal();
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') closeModal();
    });
});