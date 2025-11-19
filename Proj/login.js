document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');
    const messageDiv = document.getElementById('message');
    const phpMessage = form.dataset.message?.trim();
    const button = form.querySelector('button');

    // Fade helper
    const fadeIn = (el) => {
        el.style.opacity = 0;
        el.style.display = 'block';
        el.style.transition = 'opacity 0.4s ease';
        requestAnimationFrame(() => {
            el.style.opacity = 1;
        });
    };

    const fadeOut = (el) => {
        el.style.transition = 'opacity 0.4s ease';
        el.style.opacity = 0;
        setTimeout(() => { el.style.display = 'none'; }, 400);
    };

    // Show PHP message (e.g. invalid login)
    if (phpMessage) {
        messageDiv.textContent = phpMessage;
        fadeIn(messageDiv);
        messageDiv.style.animation = 'shake 0.5s ease-in-out';
        // Reset button
        button.innerHTML = '<i class="fas fa-sign-in-alt"></i> Login';
        button.disabled = false;
    }

    // Form submit
    form.addEventListener('submit', (e) => {
        const username = form.username.value.trim();
        const password = form.password.value.trim();

        // Validate inputs
        if (!username || !password) {
            e.preventDefault();
            messageDiv.textContent = 'Please fill in all fields.';
            fadeIn(messageDiv);
            messageDiv.style.animation = 'shake 0.5s ease-in-out';
            return;
        }

        // Hide any old message
        fadeOut(messageDiv);

        // Show spinner + disable button
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
        button.disabled = true;
    });
});
