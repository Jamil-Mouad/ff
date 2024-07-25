document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('passwordInput');

    togglePassword.addEventListener('click', function() {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglePassword.classList.remove('bxs-lock-alt');
            togglePassword.classList.add('bxs-lock-open-alt');
        } else {
            passwordInput.type = 'password';
            togglePassword.classList.remove('bxs-lock-open-alt');
            togglePassword.classList.add('bxs-lock-alt');
        }
    });
});

function showAlert(message) {
  Swal.fire({
    title: 'Message',
    text: message,
    icon: 'info',
    confirmButtonText: 'OK'
  });
}