document.addEventListener("DOMContentLoaded", function () {
    console.log("✅ invoice.js loaded");

    // --- Billing to Shipping ---
    const copyCheckbox = document.getElementById('copyBilling');
    if (copyCheckbox) {
        copyCheckbox.addEventListener('change', function () {
            const pairs = [
                ['cname', 'sname'],
                ['caddy', 'saddy'],
                ['ccity', 'scity'],
                ['cstate', 'sstate'],
                ['czip', 'szip'],
                ['cphone', 'sphone'],
                ['cemail', 'semail']
            ];
            pairs.forEach(([from, to]) => {
                const f = document.getElementById(from);
                const t = document.getElementById(to);
                if (f && t) t.value = this.checked ? f.value : '';
            });
        });
    }

    // --- Restore sessionStorage ---
    const fieldsToWatch = [/* ... all fields ... */];
    fieldsToWatch.forEach(id => {
        const el = document.getElementById(id);
        if (el && sessionStorage.getItem(id)) el.value = sessionStorage.getItem(id);
        if (el) {
            el.addEventListener('input', () => sessionStorage.setItem(id, el.value));
        }
    });

 /*
    // ✅ --- Discount Logic ---
    const applyBtn = document.getElementById('applyDiscount');
    if (applyBtn) {
        applyBtn.addEventListener('click', async () => {
            console.log("✅ Apply button clicked");

            const code = document.getElementById('discount_code').value.trim();
            const status = document.getElementById('discountStatus');
            console.log("➡️ Code entered:", code);

            if (!code) {
                status.textContent = "⚠️ Enter a code first.";
                status.style.color = "orange";
                return;
            }

            try {
                const res = await fetch(`check_promo.php?code=${encodeURIComponent(code)}`);
                const data = await res.json();
                console.log("🧾 Response:", data);

                if (data.valid) {
                    const discountPct = parseFloat(data.discount_pct);
                    const subtotal = parseFloat(document.querySelector('input[name="subtotal"]')?.value || 0);
                    const discountAmount = +(subtotal * discountPct / 100).toFixed(2);
                    const tax = +(subtotal * 0.08).toFixed(2);
                    const newTotal = +(subtotal - discountAmount + tax).toFixed(2);

                    document.getElementById('discount_pct').value = discountPct;
                    document.getElementById('discount_amount').value = discountAmount;
                    document.getElementById('total').value = newTotal;

                    status.textContent = `✅ ${discountPct}% discount applied!`;
                    status.style.color = "green";
                } else {
                    status.textContent = "❌ Invalid or expired code.";
                    status.style.color = "red";
                }
            } catch (err) {
                console.error("❌ Error checking code:", err);
                status.textContent = "⚠️ Error checking code.";
                status.style.color = "red";
            }
        });
    }
    */

    // --- Clear sessionStorage on submit ---
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', () => {
            fieldsToWatch.forEach(id => sessionStorage.removeItem(id));
        });
    }
});
