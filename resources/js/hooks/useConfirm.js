import Swal from 'sweetalert2';

export function useConfirm() {
    const confirm = (options = {}) => {
        return Swal.fire({
            title: options.title || 'Are you sure?',
            text: options.text || "You won't be able to revert this",
            icon: options.icon || 'warning',
            showCancelButton: true,
            confirmButtonColor: 'var(--color-primary)',
            cancelButtonColor: 'var(--color-error)',
            confirmButtonText: options.confirmButtonText || 'Yes, proceed',
            background: 'var(--color-bg-white)',
            color: 'var(--color-text-heading)',
            customClass: {
                container: 'swal2-blurred-backdrop',
                ...(options.customClass || {})
            },
            ...options
        });
    };

    return { confirm };
}
