export function initLoginForm() {
    const form = document.querySelector('.login-form');
    if (!form) return;

    const submitBtn = document.getElementById('btn-login');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (submitBtn) submitBtn.disabled = true;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: new FormData(form),
                credentials: 'same-origin',
            });

            const payload = await response.json().catch(() => ({}));

            if (response.ok) {
                if (payload.message) {
                    window.useToast?.().success(payload.message);
                }

                window.location.assign(payload.redirect ?? '/dashboard');
                return;
            }

            const errors = payload.errors ?? {};
            const firstFieldError = Object.values(errors).flat()[0];
            const message = firstFieldError || payload.message || 'Unable to sign in. Please try again.';
            window.useToast?.().error(message);
        } catch {
            window.useToast?.().error('Network error. Please try again.');
        } finally {
            if (submitBtn) submitBtn.disabled = false;
        }
    });
}
