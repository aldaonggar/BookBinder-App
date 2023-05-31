document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.register-form');
    form.addEventListener('submit', function (event) {
        const isValid = validatePasswords();

        if (!isValid) {
            event.preventDefault();
        }
    });

    const password = document.getElementById('registration_form_plainPassword_first');
    const repeatPassword = document.getElementById('registration_form_plainPassword_second');

    password.addEventListener('input', validatePasswords);
    repeatPassword.addEventListener('input', validatePasswords);

    function validatePasswords() {
        const passwordValue = password.value;
        const repeatPasswordValue = repeatPassword.value;
        const passwordMatch = document.getElementById('password-match');
        const passwordError = document.getElementById('password-error');

        let passwordsMatch = false;
        let passwordIsValid = false;

        if (repeatPasswordValue.length > 0) {
            if (passwordValue !== repeatPasswordValue) {
                passwordMatch.textContent = 'Passwords do not match';
                passwordMatch.classList.remove('success');
                passwordMatch.classList.add('error');
            } else {
                passwordMatch.textContent = 'Passwords match';
                passwordMatch.classList.remove('error');
                passwordMatch.classList.add('success');
                passwordsMatch = true;
            }
        } else {
            passwordMatch.textContent = '';
        }

        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
        if (!regex.test(passwordValue)) {
            passwordError.classList.remove('hidden');
        } else {
            passwordError.classList.add('hidden');
            passwordIsValid = true;
        }

        return (passwordsMatch && passwordIsValid);
    }
});