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

    // Add logic to handle applying discounts

    document.getElementById('applyDiscount').addEventListener('click', async () => {
  const code = document.getElementById('discount_code').value.trim();
  const status = document.getElementById('discountStatus');
  if (!code) {
    status.textContent = "⚠️ Enter a code first.";
    status.style.color = "orange";
    return;
  }

  try {
    const res = await fetch(`check_promo.php?code=${encodeURIComponent(code)}`);
    const data = await res.json();

    if (data.valid) {
      const discountPct = parseFloat(data.discount_pct);
      const subtotal = parseFloat(document.querySelector('input[name="subtotal"]').value);
      const discountAmount = +(subtotal * discountPct / 100).toFixed(2);
      const tax = +(subtotal * 0.08).toFixed(2);
      const newTotal = +(subtotal - discountAmount + tax).toFixed(2);

      // Update form hidden fields
      document.getElementById('discount_pct').value = discountPct;
      document.getElementById('discount_amount').value = discountAmount;
      document.getElementById('total').value = newTotal;

      // Display success
      status.textContent = `✅ ${discountPct}% discount applied!`;
      status.style.color = "green";
    } else {
      status.textContent = "❌ Invalid or expired code.";
      status.style.color = "red";
    }
  } catch (err) {
    status.textContent = "⚠️ Error checking code.";
    status.style.color = "red";
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
