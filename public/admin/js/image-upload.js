/**
 * Enhanced Image Upload with Drag & Drop functionality
 * For Auction Lot Create/Edit Forms
 */

class ImageUploadManager {
    constructor(options = {}) {
        this.options = {
            maxFiles: 10,
            maxFileSize: 2 * 1024 * 1024, // 2MB
            allowedTypes: ["image/jpeg", "image/png", "image/jpg", "image/gif"],
            thumbnailContainer: "#thumbnail-container",
            galleryContainer: "#gallery-container",
            thumbnailInput: "#thumbnail",
            galleryInput: "#gallery_images",
            ...options,
        };

        this.thumbnailFile = null;
        this.galleryFiles = [];
        this.init();
    }

    init() {
        // Prevent multiple initializations
        if (this.isInitialized) {
            console.warn("ImageUploadManager already initialized");
            return;
        }

        this.setupThumbnailUpload();
        this.setupGalleryUpload();
        this.setupSortable();
        this.isInitialized = true;
    }

    setupThumbnailUpload() {
        const container = document.querySelector(
            this.options.thumbnailContainer
        );
        const input = document.querySelector(this.options.thumbnailInput);

        if (!container || !input) {
            console.warn("Thumbnail container or input not found");
            return;
        }

        // Check if already initialized
        if (container.querySelector(".thumbnail-drop")) {
            console.warn("Thumbnail upload already initialized");
            return;
        }

        // Create drag & drop area
        this.createThumbnailDropArea(container, input);

        // Handle file input change
        input.addEventListener("change", (e) => {
            if (e.target.files.length > 0) {
                this.handleThumbnailFile(e.target.files[0]);
            }
        });
    }

    createThumbnailDropArea(container, input) {
        const dropArea = document.createElement("div");
        dropArea.className = "image-drop-area thumbnail-drop";
        dropArea.innerHTML = `
            <div class="drop-content">
                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                <h5>Drop thumbnail image here</h5>
                <p class="text-muted">or click to browse</p>
                <small class="text-muted">JPG, PNG, GIF up to 2MB</small>
            </div>
            <div class="thumbnail-preview d-none">
                <img src="" alt="Thumbnail Preview" class="img-fluid">
                <div class="image-overlay">
                    <button type="button" class="btn btn-sm btn-danger remove-thumbnail">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        // Insert before the file input
        container.insertBefore(dropArea, input);
        input.style.display = "none";

        // Add event listeners
        this.addDropListeners(dropArea, (files) => {
            if (files.length > 0) {
                this.handleThumbnailFile(files[0]);
            }
        });

        // Click to browse
        dropArea.addEventListener("click", () => input.click());

        // Remove thumbnail
        dropArea
            .querySelector(".remove-thumbnail")
            .addEventListener("click", (e) => {
                e.stopPropagation();
                this.removeThumbnail();
            });
    }

    setupGalleryUpload() {
        const container = document.querySelector(this.options.galleryContainer);
        const input = document.querySelector(this.options.galleryInput);

        if (!container || !input) {
            console.warn("Gallery container or input not found");
            return;
        }

        // Check if already initialized
        if (container.querySelector(".gallery-drop")) {
            console.warn("Gallery upload already initialized");
            return;
        }

        // Create gallery drop area
        this.createGalleryDropArea(container, input);

        // Handle file input change
        input.addEventListener("change", (e) => {
            this.handleGalleryFiles(Array.from(e.target.files));
        });
    }

    createGalleryDropArea(container, input) {
        const dropArea = document.createElement("div");
        dropArea.className = "image-drop-area gallery-drop";
        dropArea.innerHTML = `
            <div class="drop-content">
                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                <h5>Drop gallery images here</h5>
                <p class="text-muted">or click to browse</p>
                <small class="text-muted">JPG, PNG, GIF up to 2MB each (max ${this.options.maxFiles} images)</small>
            </div>
        `;

        const previewContainer = document.createElement("div");
        previewContainer.className = "gallery-preview-container";
        previewContainer.innerHTML =
            '<div class="row gallery-preview" id="gallery-preview"></div>';

        // Insert elements
        container.insertBefore(dropArea, input);
        container.insertBefore(previewContainer, input);
        input.style.display = "none";
        input.multiple = true;

        // Add event listeners
        this.addDropListeners(dropArea, (files) => {
            this.handleGalleryFiles(Array.from(files));
        });

        // Click to browse
        dropArea.addEventListener("click", () => input.click());
    }

    addDropListeners(element, callback) {
        ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
            element.addEventListener(eventName, this.preventDefaults, false);
        });

        ["dragenter", "dragover"].forEach((eventName) => {
            element.addEventListener(
                eventName,
                () => element.classList.add("drag-over"),
                false
            );
        });

        ["dragleave", "drop"].forEach((eventName) => {
            element.addEventListener(
                eventName,
                () => element.classList.remove("drag-over"),
                false
            );
        });

        element.addEventListener(
            "drop",
            (e) => {
                const files = Array.from(e.dataTransfer.files);
                callback(files);
            },
            false
        );
    }

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    handleThumbnailFile(file) {
        if (!this.validateFile(file)) return;

        this.thumbnailFile = file;
        this.displayThumbnailPreview(file);
        this.updateThumbnailInput(file);
    }

    handleGalleryFiles(files) {
        const validFiles = files.filter((file) => this.validateFile(file));

        if (
            this.galleryFiles.length + validFiles.length >
            this.options.maxFiles
        ) {
            this.showError(
                `Maximum ${this.options.maxFiles} gallery images allowed`
            );
            return;
        }

        validFiles.forEach((file) => {
            this.galleryFiles.push({
                file: file,
                id: Date.now() + Math.random(),
                sortOrder: this.galleryFiles.length,
            });
        });

        this.displayGalleryPreviews();
        this.updateGalleryInput();
    }

    validateFile(file) {
        if (!this.options.allowedTypes.includes(file.type)) {
            this.showError(
                `Invalid file type: ${file.name}. Only JPG, PNG, GIF allowed.`
            );
            return false;
        }

        if (file.size > this.options.maxFileSize) {
            this.showError(
                `File too large: ${file.name}. Maximum size is 2MB.`
            );
            return false;
        }

        return true;
    }

    displayThumbnailPreview(file) {
        const container = document.querySelector(".thumbnail-drop");
        const preview = container.querySelector(".thumbnail-preview");
        const img = preview.querySelector("img");
        const dropContent = container.querySelector(".drop-content");

        const reader = new FileReader();
        reader.onload = (e) => {
            img.src = e.target.result;
            dropContent.classList.add("d-none");
            preview.classList.remove("d-none");
        };
        reader.readAsDataURL(file);
    }

    displayGalleryPreviews() {
        const previewContainer = document.querySelector("#gallery-preview");
        previewContainer.innerHTML = "";

        this.galleryFiles.forEach((fileObj, index) => {
            const col = document.createElement("div");
            col.className = "col-md-3 mb-3";
            col.innerHTML = `
                <div class="gallery-item" data-id="${fileObj.id}">
                    <div class="image-preview">
                        <img src="" alt="Gallery Image" class="img-fluid">
                        <div class="image-overlay">
                            <button type="button" class="btn btn-sm btn-danger remove-gallery-item" data-id="${
                                fileObj.id
                            }">
                                <i class="fas fa-trash"></i>
                            </button>
                            <div class="drag-handle">
                                <i class="fas fa-grip-vertical"></i>
                            </div>
                        </div>
                        <div class="sort-order-badge">${index + 1}</div>
                    </div>
                </div>
            `;

            const img = col.querySelector("img");
            const reader = new FileReader();
            reader.onload = (e) => {
                img.src = e.target.result;
            };
            reader.readAsDataURL(fileObj.file);

            // Add remove listener
            col.querySelector(".remove-gallery-item").addEventListener(
                "click",
                () => {
                    this.removeGalleryItem(fileObj.id);
                }
            );

            previewContainer.appendChild(col);
        });
    }

    setupSortable() {
        // Initialize sortable functionality for gallery items
        const previewContainer = document.querySelector("#gallery-preview");
        if (previewContainer && typeof Sortable !== "undefined") {
            new Sortable(previewContainer, {
                animation: 150,
                handle: ".drag-handle",
                onEnd: (evt) => {
                    this.reorderGalleryItems(evt.oldIndex, evt.newIndex);
                },
            });
        }
    }

    reorderGalleryItems(oldIndex, newIndex) {
        const item = this.galleryFiles.splice(oldIndex, 1)[0];
        this.galleryFiles.splice(newIndex, 0, item);

        // Update sort order
        this.galleryFiles.forEach((fileObj, index) => {
            fileObj.sortOrder = index;
        });

        this.updateSortOrderBadges();
        this.updateGalleryInput();
    }

    updateSortOrderBadges() {
        const badges = document.querySelectorAll(".sort-order-badge");
        badges.forEach((badge, index) => {
            badge.textContent = index + 1;
        });
    }

    removeThumbnail() {
        this.thumbnailFile = null;
        const container = document.querySelector(".thumbnail-drop");
        const preview = container.querySelector(".thumbnail-preview");
        const dropContent = container.querySelector(".drop-content");

        preview.classList.add("d-none");
        dropContent.classList.remove("d-none");

        // Clear file input
        document.querySelector(this.options.thumbnailInput).value = "";
    }

    removeGalleryItem(id) {
        this.galleryFiles = this.galleryFiles.filter(
            (fileObj) => fileObj.id !== id
        );
        this.displayGalleryPreviews();
        this.updateGalleryInput();
    }

    updateThumbnailInput(file) {
        const input = document.querySelector(this.options.thumbnailInput);
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
    }

    updateGalleryInput() {
        const input = document.querySelector(this.options.galleryInput);
        const dt = new DataTransfer();

        this.galleryFiles.forEach((fileObj) => {
            dt.items.add(fileObj.file);
        });

        input.files = dt.files;
    }

    showError(message) {
        // Create or update error alert
        let errorAlert = document.querySelector(".image-upload-error");
        if (!errorAlert) {
            errorAlert = document.createElement("div");
            errorAlert.className = "alert alert-danger image-upload-error";
            document
                .querySelector(".card-body")
                .insertBefore(
                    errorAlert,
                    document.querySelector(".card-body").firstChild
                );
        }

        errorAlert.innerHTML = `
            <i class="fas fa-exclamation-triangle"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (errorAlert.parentNode) {
                errorAlert.remove();
            }
        }, 5000);
    }
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    if (
        document.querySelector("#thumbnail-container") ||
        document.querySelector("#gallery-container")
    ) {
        window.imageUploadManager = new ImageUploadManager();
    }
});
