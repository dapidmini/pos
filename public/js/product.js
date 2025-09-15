Dropzone.autoDiscover = false;

document.addEventListener("DOMContentLoaded", function () {
    if (typeof Dropzone === "undefined") {
        console.error(
            "Dropzone.js is not loaded. Please include the Dropzone.js library.",
        );
        return;
    }

    const dzEl = document.getElementById("myDropzone");
    if (!dzEl) {
        console.error("Element with ID 'myDropzone' not found.");
        return;
    }

    const dzURL = dzEl.getAttribute("data-url");
    if (!dzURL) {
        console.error("Data attribute 'data-url' not found on the Dropzone element.");
        return;
    }

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute("content") : "";
    const moduleName = dzEl.getAttribute("data-module-name") || "File"; // Foto Gallery Produk

    const dz = new Dropzone(dzEl, {
        url: dzURL,
        paramName: "file", // nama field yang dikirim ke server
        maxFiles: 5,
        parallelUploads: 5,
        uploadMultiple: false,
        acceptedFiles: "image/jpeg,image/png",
        maxFilesize: 5, // MB
        addRemoveLinks: true,
        dictRemoveFile: "Hapus",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        dictDefaultMessage: `Letakkan foto produk di sini<br>atau klik untuk mengunggah.
            <br>Maksimal 5MB, format: .jpg, .jpeg, .png, dan maksimal 5 file sekaligus.`,
        dictRemoveFile: "Hapus",
    });

    dz.on("success", function (file, response) {
        const fileName =
            response &&
            (response.file_name || response.filename || response.path)
                ? response.file_name || response.filename || response.path
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

            const tempFilesContainerId =
                dzEl.getAttribute("data-temp-container") ||
                "tempFilesProductGallery";
            const tempFilesContainerEl =
                document.getElementById(tempFilesContainerId);
            if (tempFilesContainerEl) {
                tempFilesContainerEl.appendChild(tempInput);
            }

            showToast(
                `${moduleName} berhasil diunggah.`,
                "Upload Sukses",
                "success",
            );
        } else {
            dz.removeFile(file);
            showToast(
                `Error Upload ${moduleName}: Gagal mendapatkan nama file dari server.`,
                "Upload Error",
                "error",
            );
        }
    });

    dz.on("error", function (file, errorMessage) {
        const message =
            typeof errorMessage === "string"
                ? errorMessage
                : errorMessage.message || "Upload gagal.";
        showToast(
            `Error Upload ${moduleName}: ${message}`,
            "Upload Error",
            "error",
        );
        dz.removeFile(file);
    });

    // Supaya elemen input tidak menumpuk kalau user upload–hapus–upload ulang.
    dz.on("removedfile", function (file) {
        if (file._tempInput && file._tempInput.parentNode) {
            file._tempInput.parentNode.removeChild(file._tempInput);
        }
    });
});

// const myDropzone = new Dropzone(dzEl, {
//     url: dzURL, // route upload sementara
//     paramName: "file",
//     maxFilesize: 5, // MB
//     acceptedFiles: ".jpg,.jpeg,.png",
//     addRemoveLinks: true,
//     parallelUploads: 5,
//     uploadMultiple: false,
//     headers: csrfToken ? { "X-CSRF-TOKEN": csrfToken } : {},
//     dictDefaultMessage: `Letakkan foto produk di sini<br>atau klik untuk mengunggah.
//         <br>(Maksimal 5MB, format: .jpg, .jpeg, .png), maksimal 5 file sekaligus.`,
//     dictRemoveFile: "Hapus",
//     init: function () {
//         const dz = this;

//         dz.on("success", function (file, response) {
//             const moduleName =
//                 dzEl.getAttribute("data-module-name") || "File"; // Foto Gallery Produk

//             const fileName =
//                 response &&
//                 (response.file_name || response.filename || response.path)
//                     ? response.file_name ||
//                       response.filename ||
//                       response.path
//                     : null;

//             if (fileName) {
//                 const tempInput = document.createElement("input");
//                 tempInput.type = "hidden";
//                 tempInput.name = "tempFilesProductGallery[]";
//                 tempInput.value = fileName;
//                 tempInput.classList.add("temp-file-input");

//                 // simpan reference ke element file supaya bisa dihapus saat user remove
//                 file._tempInput = tempInput;
//                 file._serverFileName = fileName;

//                 const tempFilesContainerId =
//                     dzEl.getAttribute("data-temp-container") ||
//                     "tempFilesProductGallery";
//                 const tempFilesContainerEl =
//                     document.getElementById(tempFilesContainerId);
//                 if (tempFilesContainerEl) {
//                     tempFilesContainerEl.appendChild(tempInput);
//                 }

//                 showToast(
//                     `${moduleName} berhasil diunggah.`,
//                     "Upload Sukses",
//                     "success",
//                 );
//             } else {
//                 dz.removeFile(file);
//                 showToast(
//                     `Error Upload ${moduleName}: Gagal mendapatkan nama file dari server.`,
//                     "Upload Error",
//                     "error",
//                 );
//             }
//         });

//         dz.on("error", function (file, errorMessage) {
//             const message =
//                 typeof errorMessage === "string"
//                     ? errorMessage
//                     : errorMessage.message || "Upload gagal.";
//             showToast(
//                 `Error Upload ${moduleName}: ${message}`,
//                 "Upload Error",
//                 "error",
//             );
//             dz.removeFile(file);
//         });

//         // Supaya elemen input tidak menumpuk kalau user upload–hapus–upload ulang.
//         dz.on("removedfile", function (file) {
//             if (file._tempInput && file._tempInput.parentNode) {
//                 file._tempInput.parentNode.removeChild(file._tempInput);
//             }
//         });
//     },
// });
// });
