const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('resetPassword');
const passwordValidationMessage = document.querySelector('#password-validation-message');
const passwordMatchMessage = document.querySelector('#password-match-message');
const resetPasswordButton = document.querySelector('#reset-password-button');

passwordInput.addEventListener('input', validatePassword);
confirmPasswordInput.addEventListener('input', validatePassword);

function validatePassword() {
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;

    const requirements = [
        /.{8,}/, // Minimum 8 characters long
        /.*[!@#$%^&*(){}[\]<>?/|,:;~.]/, // At least one special character
        /.*[A-Z]/, // At least one uppercase letter
        /.*[a-z]/, // At least one lowercase letter
        /.*\d/ // At least one digit
    ];

    const isValid = requirements.every(regex => regex.test(password));

    passwordValidationMessage.textContent = isValid ? 'Password follows requirements' : 'Password does not follow requirements';
    passwordValidationMessage.style.color = isValid ? 'green' : 'red';
    passwordValidationMessage.style.fontWeight = 'bold';

    passwordMatchMessage.textContent = password === confirmPassword ? 'Passwords match' : 'Passwords do not match';
    passwordMatchMessage.style.color = password === confirmPassword ? 'green' : 'red';
    passwordMatchMessage.style.fontWeight = 'bold';

    resetPasswordButton.disabled = !(isValid && password === confirmPassword);
}