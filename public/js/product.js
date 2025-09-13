document.addEventListener("DOMContentLoaded", function () {
    if (typeof Dropzone === "undefined") {
        console.error(
            "Dropzone.js is not loaded. Please include the Dropzone.js library.",
        );
        return;
    }

    Dropzone.autoDiscover = false;

    const dzEl = document.getElementById("myDropzone");
    if (!dzEl) {
        console.error("Element with ID 'myDropzone' not found.");
        return;
    }

    const dzURL = dzEl.getAttribute("data-url");
    if (!dzURL) {
        console.error(
            "Data attribute 'data-url' not found on the Dropzone element.",
        );
        return;
    }

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute("content") : null;

    const myDropzone = new Dropzone(dzEl, {
        url: dzURL, // route upload sementara
        paramName: "file",
        maxFilesize: 5, // MB
        acceptedFiles: ".jpg,.jpeg,.png",
        addRemoveLinks: true,
        parallelUploads: 5,
        uploadMultiple: false,
        headers: csrfToken ? { "X-CSRF-TOKEN": csrfToken } : {},
        dictDefaultMessage:
            `Letakkan foto produk di sini<br>atau klik untuk mengunggah.
            <br>(Maksimal 5MB, format: .jpg, .jpeg, .png), maksimal 5 file sekaligus.`,
        dictRemoveFile: "Hapus",
        init: function () {
            const dz = this;

            dz.on("success", function (file, response) {
                const fileName = response && (response.file_name || response.filename || response.path) 
                                ? (response.file_name || response.filename || response.path) 
                                : null;

                if (fileName) {
                    const tempInput = document.createElement("input");
                    tempInput.type = "hidden";
                    tempInput.name = "tempFilesProductGallery[]";
                    tempInput.value = fileName;
                    tempInput.classList.add("temp-file-input");

                    // simpan reference ke element file supaya bisa dihapus saat user remove
                    file._tempInput = tempInput;
                    file._serverFileName = fileName;

                    const tempFilesContainer = document.getElementById("tempFilesProductGallery");
                    if (tempFilesContainer) {
                        tempFilesContainer.appendChild(tempInput);
                    }
                } else {
                    dz.removeFile(file);
                    showToast("Gagal mendapatkan nama file dari server.", "Upload Error", "error");
                }
            });
        }
    });
});
