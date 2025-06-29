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
