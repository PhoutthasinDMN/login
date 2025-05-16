<script>
  $(document).ready(function() {
    // Check URL parameters for notifications
    const urlParams = new URLSearchParams(window.location.search);
    
    // Welcome message for first login
    if (!localStorage.getItem('welcomeShown')) {
      Swal.fire({
        title: 'ยินดีต้อนรับ!',
        text: 'คุณได้เข้าสู่ระบบสำเร็จแล้ว',
        icon: 'success',
        confirmButtonColor: '#0075e1',
        timer: 2000,
        timerProgressBar: true
      });
      
      localStorage.setItem('welcomeShown', 'true');
    }
    
    // Animation for stats cards
    $('.bg-white').addClass('fade-in');
    
    // Hover effect for quick links
    $('.bg-primary-50, .bg-green-50, .bg-yellow-50').hover(
      function() {
        $(this).addClass('transform scale-105 transition-transform');
      },
      function() {
        $(this).removeClass('transform scale-105 transition-transform');
      }
    );
  });
</script>