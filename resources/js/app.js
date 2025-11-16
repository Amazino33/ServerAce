import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.css';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

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