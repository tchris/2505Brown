// Define globally so all blocks can access it
const fieldsToWatch = [
    'cname', 'caddy', 'ccity', 'cstate', 'czip', 'cphone', 'cemail',
    'sname', 'saddy', 'scity', 'sstate', 'szip', 'sphone', 'semail'
];

document.addEventListener("DOMContentLoaded", function () {
    const copyCheckbox = document.getElementById('copyBilling');

    if (copyCheckbox) {
        copyCheckbox.addEventListener('change', function () {
            const billingToShipping = [
                ['cname', 'sname'],
                ['caddy', 'saddy'],
                ['ccity', 'scity'],
                ['cstate', 'sstate'],
                ['czip', 'szip'],
                ['cphone', 'sphone'],
                ['cemail', 'semail']
            ];

            billingToShipping.forEach(([fromId, toId]) => {
                const from = document.getElementById(fromId);
                const to = document.getElementById(toId);
                if (from && to) {
                    to.value = this.checked ? from.value : '';
                }
            });
        });
    }

    // Load stored values
    fieldsToWatch.forEach(id => {
        const field = document.getElementById(id);
        if (field && sessionStorage.getItem(id)) {
            field.value = sessionStorage.getItem(id);
        }

        if (field) {
            field.addEventListener('input', () => {
                sessionStorage.setItem(id, field.value);
            });
        }
    });

    // Clear saved sessionStorage on form submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', () => {
            fieldsToWatch.forEach(id => sessionStorage.removeItem(id));
        });
    }
});
