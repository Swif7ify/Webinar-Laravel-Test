import Swal from 'sweetalert2';

export function useToast() {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: 'var(--color-bg-white)',
        color: 'var(--color-text-heading)',
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

    const success = (title) => Toast.fire({ icon: 'success', title });
    const error = (title) => Toast.fire({ icon: 'error', title });
    const warning = (title) => Toast.fire({ icon: 'warning', title });
    const info = (title) => Toast.fire({ icon: 'info', title });

    return { Toast, success, error, warning, info };
}
