export function useTaskForm(formId) {
    const form      = document.getElementById(formId);
    if (!form) return;

    const fileInput = document.getElementById('task-file-input');
    const dropZone  = document.getElementById('task-drop-zone');
    const fileList  = document.getElementById('task-ref-file-list');
    const submitBtn = document.getElementById('btn-submit-create-task');

    const selectedFiles = [];

    function formatBytes(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    function renderFileList() {
        if (!fileList) return;
        fileList.innerHTML = '';
        selectedFiles.forEach((file, idx) => {
            const item = document.createElement('div');
            item.className = 'ref-file-item';
            item.innerHTML = `
                <div class="ref-file-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <div class="ref-file-meta">
                    <div class="ref-file-name">${escapeHtml(file.name)}</div>
                    <div class="ref-file-size">${formatBytes(file.size)}</div>
                </div>
                <button type="button" class="ref-file-remove" data-idx="${idx}" aria-label="Remove file">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            `;
            fileList.appendChild(item);
        });

        fileList.querySelectorAll('.ref-file-remove').forEach(btn => {
            btn.addEventListener('click', () => {
                const idx = parseInt(btn.dataset.idx, 10);
                selectedFiles.splice(idx, 1);
                syncFilesToInput();
                renderFileList();
            });
        });
    }

    function syncFilesToInput() {
        if (!fileInput) return;
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        fileInput.files = dt.files;
    }

    function addFiles(newFiles) {
        const MAX_FILES = 5;
        Array.from(newFiles).forEach(file => {
            if (selectedFiles.length >= MAX_FILES) {
                window.useToast?.().warning('Maximum 5 reference files allowed.');
                return;
            }
            const duplicate = selectedFiles.some(f => f.name === file.name);
            if (duplicate) {
                window.useToast?.().warning(`"${file.name}" is already in the list.`);
                return;
            }
            selectedFiles.push(file);
        });
        syncFilesToInput();
        renderFileList();
    }

    function initDropZone() {
        if (!dropZone || !fileInput) return;

        dropZone.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', () => {
            addFiles(fileInput.files);
            fileInput.value = '';
        });

        dropZone.addEventListener('dragover', e => {
            e.preventDefault();
            dropZone.classList.add('file-drop-zone--drag-over');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('file-drop-zone--drag-over');
        });

        dropZone.addEventListener('drop', e => {
            e.preventDefault();
            dropZone.classList.remove('file-drop-zone--drag-over');
            addFiles(e.dataTransfer.files);
        });
    }



    function validateForm() {
        if (!submitBtn) return;
        const titleFilled = (form.querySelector('[name="title"]')?.value ?? '').trim().length > 0;
        submitBtn.disabled = !titleFilled;
    }

    function initFormGuard() {
        form.querySelector('[name="title"]')?.addEventListener('input', validateForm);
        validateForm();
    }

    initDropZone();
    initFormGuard();
}

function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}
