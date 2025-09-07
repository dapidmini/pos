document.addEventListener("DOMContentLoaded", function () {
    Dropzone.autoDiscover = false;

    let dzURL = document.getElementById("myDropzone").getAttribute("data-url");
    if (modulDropzone) {
    var myDropzone = new Dropzone("#myDropzone", {
        url: dzURL, // route upload sementara
        paramName: "file",
        maxFilesize: 5, // MB
        acceptedFiles: "image/jpeg,image/png",
        addRemoveLinks: true,
        parallelUploads: 5,
        uploadMultiple: false,
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
        success: function (file, response) {
            // simpan nama file sementara di hidden input
            let input = document.createElement("input");
            input.type = "hidden";
            input.name = "temp_files[]";
            input.value = response.file_name;
            document.getElementById("tempFiles").appendChild(input);
        },
    });
});
