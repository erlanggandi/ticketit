window.openDialog = (id) => {
    const dialog = document.getElementById(id);
    if (dialog instanceof HTMLDialogElement) {
        dialog.showModal();
    }
};

window.closeDialog = (id) => {
    const dialog = document.getElementById(id);
    if (dialog instanceof HTMLDialogElement) {
        dialog.close();
    }
};

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form[data-disable-on-submit]').forEach((form) => {
        form.addEventListener('submit', function (event) {
            if (this.dataset.submitted === '1') {
                event.preventDefault();
                return;
            }

            this.dataset.submitted = '1';

            this.querySelectorAll('button[type="submit"]').forEach((btn) => {
                btn.classList.add('opacity-60', 'pointer-events-none');
            });
        });
    });
});
