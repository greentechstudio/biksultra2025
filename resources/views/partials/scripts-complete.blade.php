<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation for success card
    const successCard = document.querySelector('.success-card');
    if (successCard) {
        successCard.style.opacity = '0';
        successCard.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            successCard.style.transition = 'all 0.6s ease-out';
            successCard.style.opacity = '1';
            successCard.style.transform = 'translateY(0)';
        }, 100);
    }
    
    // Add pulse animation to success icon
    const successIcon = document.querySelector('.success-icon');
    if (successIcon) {
        setInterval(() => {
            successIcon.style.transform = 'scale(1.05)';
            setTimeout(() => {
                successIcon.style.transform = 'scale(1)';
            }, 150);
        }, 2000);
    }
});
</script>