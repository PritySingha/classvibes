// Password visibility toggle
document.addEventListener("DOMContentLoaded", function() {
    var passwordField = document.getElementById('password');
    var confirmPasswordField = document.getElementById('confirm_password');
    var togglePassword = document.getElementById('togglePassword');
    var toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

    function toggleVisibility(field, toggleIcon) {
        if (field.type === "password") {
            field.type = "text";
            toggleIcon.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            field.type = "password";
            toggleIcon.innerHTML = '<i class="fas fa-eye"></i>';
        }
    }

    togglePassword.addEventListener("click", function() {
        toggleVisibility(passwordField, togglePassword);
    });

    toggleConfirmPassword.addEventListener("click", function() {
        toggleVisibility(confirmPasswordField, toggleConfirmPassword);
    });
});