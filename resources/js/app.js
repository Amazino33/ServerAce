import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.css';

// ❌ REMOVED - Livewire 3 includes Alpine, don't import it separately
// import Alpine from 'alpinejs';
// window.Alpine = Alpine;
// Alpine.start();

// Copy link functionality
document.addEventListener('DOMContentLoaded', function() {
    const copyLinkButtons = document.querySelectorAll('[data-copy-link]');
    
    copyLinkButtons.forEach(button => {
        button.addEventListener('click', function() {
            const url = window.location.href;
            
            navigator.clipboard.writeText(url).then(() => {
                // Show success message
                const icon = this.querySelector('i');
                const originalIcon = icon.className;
                icon.className = 'fas fa-check mr-2';
                this.textContent = '';
                this.appendChild(icon);
                this.appendChild(document.createTextNode('Copied!'));
                
                // Reset after 2 seconds
                setTimeout(() => {
                    icon.className = originalIcon;
                    this.textContent = '';
                    this.appendChild(icon);
                    this.appendChild(document.createTextNode('Copy Link'));
                }, 2000);
            });
        });
    });
});

document.addEventListener('livewire:init', () => {
    Alpine.data('paymentForm', (config) => ({
        stripe: null,
        cardElement: null,
        processing: false,
        clientSecret: config.clientSecret,
        userName: config.userName,
        userEmail: config.userEmail,
        stripeKey: config.stripeKey,

        init() {
            this.stripe = Stripe(this.stripeKey);
            const elements = this.stripe.elements();
            this.cardElement = elements.create('card', {
                style: { base: { fontSize: '16px', color: '#424770', '::placeholder': { color: '#aab7c4' } } }
            });
            this.cardElement.mount('#card-element');

            this.cardElement.on('change', (event) => {
                const displayError = document.getElementById('card-errors');
                displayError.textContent = event.error ? event.error.message : '';
            });
        },

        async handlePayment() {
            this.processing = true;

            const result = await this.stripe.confirmCardPayment(this.clientSecret, {
                payment_method: {
                    card: this.cardElement,
                    billing_details: {
                        name: this.userName,
                        email: this.userEmail,
                    },
                },
            });

            console.log('STRIPE RESULT:', result);   // <--- ADD THIS LINE

            if (result.error) {
                document.getElementById('card-errors').textContent = result.error.message;
                this.processing = false;
            } else if (result.paymentIntent.status === 'succeeded') {
                // Success! Redirect to your success route
                console.log('SUCCESS BRANCH - ABOUT TO REDIRECT WITH ID:', result.paymentIntent.id);
                window.location.href = `/client/payment/success?payment_intent=${result.paymentIntent.id}`;}
        }
    }));
});