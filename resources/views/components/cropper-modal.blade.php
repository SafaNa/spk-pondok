<div id="cropperModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6" style="background: rgba(15,23,42,0.8); backdrop-filter: blur(4px);">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col border border-slate-200 dark:border-slate-700 animate-fade-in relative">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
            <h3 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">crop</span>
                Sesuaikan Foto
            </h3>
            <button type="button" onclick="closeCropperModal()" class="text-slate-400 hover:text-red-500 transition-colors rounded-full hover:bg-red-50 dark:hover:bg-red-500/10 p-1">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-4 bg-slate-100 dark:bg-slate-950 flex justify-center items-center h-[350px] sm:h-[400px]">
            <div class="w-full h-full">
                <img id="cropperImage" src="" alt="Cropper" class="max-w-full block">
            </div>
        </div>
        <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3 bg-white dark:bg-slate-900">
            <button type="button" onclick="closeCropperModal()" class="px-5 py-2.5 rounded-xl font-semibold text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors text-sm">
                Batal
            </button>
            <button type="button" id="cropSaveBtn" class="px-5 py-2.5 rounded-xl font-semibold bg-primary text-white hover:bg-primary/90 shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all text-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">check</span>
                Potong & Simpan
            </button>
        </div>
    </div>
</div>

<style>
    /* Prevent cropper image from overflowing before initialization */
    #cropperImage { max-height: 100%; max-width: 100%; display: block; }
</style>

<script>
    let cropper = null;
    let currentFileInput = null;
    let cropperModal = document.getElementById('cropperModal');
    let cropperImage = document.getElementById('cropperImage');
    let cropSaveBtn = document.getElementById('cropSaveBtn');

    document.addEventListener('change', function(e) {
        if (e.target && e.target.matches('input[type="file"].crop-avatar')) {
            const file = e.target.files[0];
            if (!file) return;

            // Skip if this is our programmatically dispatched file
            if (e.target.dataset.isCropped === "1") {
                delete e.target.dataset.isCropped;
                return;
            }

            // Must be image
            if (!file.type.startsWith('image/')) return;

            currentFileInput = e.target;

            // Read file and load into cropper
            const reader = new FileReader();
            reader.onload = function(event) {
                cropperImage.src = event.target.result;
                openCropperModal();
            };
            reader.readAsDataURL(file);
            
            // Prevent default form behavior temporarily if any
            e.preventDefault();
        }
    });

    function openCropperModal() {
        cropperModal.classList.remove('hidden');
        cropperModal.classList.add('flex');
        
        if (cropper) {
            cropper.destroy();
        }

        cropper = new Cropper(cropperImage, {
            aspectRatio: 1, // 1:1 Square
            viewMode: 1, // Restrict the crop box to not exceed the size of the canvas
            dragMode: 'move',
            autoCropArea: 0.9,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
        });
    }

    function closeCropperModal() {
        cropperModal.classList.add('hidden');
        cropperModal.classList.remove('flex');
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        if (currentFileInput) {
            // If canceled, reset the input
            currentFileInput.value = '';
            currentFileInput = null;
        }
    }

    cropSaveBtn.addEventListener('click', function() {
        if (!cropper || !currentFileInput) return;

        // Show loading state
        const originalText = cropSaveBtn.innerHTML;
        cropSaveBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> Memproses...';
        cropSaveBtn.disabled = true;

        // Get cropped canvas
        const canvas = cropper.getCroppedCanvas({
            width: 800,
            height: 800,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        canvas.toBlob(function(blob) {
            // Create a new File from the blob
            const ext = currentFileInput.files[0].name.split('.').pop();
            const fileName = 'cropped_avatar.' + (ext === 'png' ? 'png' : 'jpg');
            const file = new File([blob], fileName, { type: blob.type, lastModified: new Date().getTime() });

            // Create DataTransfer to simulate file selection
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);

            // Replace input files
            currentFileInput.files = dataTransfer.files;
            currentFileInput.dataset.isCropped = "1"; // Mark as cropped to prevent infinite loop

            // Dispatch change event to trigger Alpine/Vue/other preview listeners
            currentFileInput.dispatchEvent(new Event('change', { bubbles: true }));

            // Cleanup & Close
            cropSaveBtn.innerHTML = originalText;
            cropSaveBtn.disabled = false;
            
            // Close modal without resetting the input
            const inputRef = currentFileInput;
            currentFileInput = null; // unset so closeCropperModal doesn't reset it
            closeCropperModal();
        }, 'image/jpeg', 0.9);
    });
</script>
