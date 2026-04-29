function formatCardNumber(input) {
    let value = input.value.replace(/\D/g, '').substring(0, 16);
    let formattedValue = '';
    for (let i = 0; i < value.length; i++) {
        if (i > 0 && i % 4 === 0) {
            formattedValue += ' ';
        }
        formattedValue += value[i];
    }
    input.value = formattedValue;
}

function formatExpiry(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    input.value = value;
}

// Add smooth submit animation
const paymentForm = document.getElementById('payment-form');
if (paymentForm) {
    paymentForm.addEventListener('submit', function (e) {
        const button = document.querySelector('button[type="submit"]');
        button.textContent = 'Processing... ';
        button.disabled = true;
    });
}
