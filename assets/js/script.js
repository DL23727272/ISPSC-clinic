document.addEventListener('DOMContentLoaded', function() {
    // QR Code Scanner Functionality
    const scanQrBtn = document.getElementById('scan-qr-btn');
    const qrScanner = document.getElementById('qr-scanner');
    const cancelScan = document.getElementById('cancel-scan');
    
    if (scanQrBtn && qrScanner) {
        scanQrBtn.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.add('hidden');
            qrScanner.classList.remove('hidden');
            
            // In a real implementation, you would initialize a QR scanner library here
            console.log('Initializing QR scanner...');
        });
        
        cancelScan.addEventListener('click', function(e) {
            e.preventDefault();
            qrScanner.classList.add('hidden');
            scanQrBtn.classList.remove('hidden');
            
            // Stop scanner if it was running
            console.log('QR scanner stopped');
        });
    }
    
    // Animation for cards on load
    const accessCards = document.querySelectorAll('.access-card');
    accessCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `all 0.5s ease ${index * 0.1}s`;
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100);
    });
});