document.addEventListener("DOMContentLoaded", function () {
    const copyCheckbox = document.getElementById('copyBilling');

    if (!copyCheckbox) return; // safety check

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
});

document.addEventListener("DOMContentLoaded", function () {
    const fieldsToWatch = [
        'cname', 'caddy', 'ccity', 'cstate', 'czip', 'cphone', 'cemail',
        'sname', 'saddy', 'scity', 'sstate', 'szip', 'sphone', 'semail'
    ];

    // Load stored values
    fieldsToWatch.forEach(id => {
        const field = document.getElementById(id);
        if (field && sessionStorage.getItem(id)) {
            field.value = sessionStorage.getItem(id);
        }

        // Save changes
        if (field) {
            field.addEventListener('input', () => {
                sessionStorage.setItem(id, field.value);
            });
        }
    });
});

document.querySelector('form').addEventListener('submit', () => {
    fieldsToWatch.forEach(id => sessionStorage.removeItem(id));
});
