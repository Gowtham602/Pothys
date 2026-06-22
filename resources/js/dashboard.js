// // Flash Messages
// document.addEventListener('DOMContentLoaded', function () {

//     if (typeof window.flashMessages !== 'undefined') {

//         const { success, error, validationErrors } = window.flashMessages;

//         if (validationErrors && validationErrors.length > 0) {
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Validation Error',
//                 html: validationErrors.join('<br>'),
//                 timer: 3000,
//                 showConfirmButton: false
//             });
//         }

//         if (error) {
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Error',
//                 text: error,
//                 timer: 2500,
//                 showConfirmButton: false
//             });
//         }

//         if (success) {
//             Swal.fire({
//                 icon: 'success',
//                 title: 'Success',
//                 text: success,
//                 timer: 2000,
//                 showConfirmButton: false
//             });
//         }
//     }

// });

// // document.addEventListener('DOMContentLoaded', function () {

// //     // DataTable
// //     if ($('#imageTable').length) {
// //         $('#imageTable').DataTable({
// //             pageLength: 10,
// //             lengthMenu: [5, 10, 25, 50],
// //             ordering: true
// //         });
// //     }

// //     // Copy Link
// //     window.copyLink = function (link) {
// //         navigator.clipboard.writeText(link);
// //         Swal.fire({
// //             icon: 'success',
// //             title: 'Copied',
// //             text: 'Short URL copied to clipboard',
// //             timer: 1500,
// //             showConfirmButton: false
// //         });
// //     };

// //     // Form Validation
// //     let form = document.getElementById('uploadForm');

// //     if (form) {
// //         form.addEventListener('submit', function (e) {

// //             let name = document.querySelector('input[name="image_name"]').value.trim();
// //             let file = document.querySelector('input[name="image"]').files[0];
// //             let files = document.querySelectorAll('input[name="images[]"]');

// // if (files.length === 0 || !files[0].files.length) {
// //     e.preventDefault();
// //     Swal.fire({
// //         icon: 'error',
// //         title: 'Please select at least one image'
// //     });
// //     return;
// // }

// //             if (name === '') {
// //                 e.preventDefault();
// //                 Swal.fire({
// //                     icon: 'error',
// //                     title: 'Image name is required'
// //                 });
// //                 return;
// //             }

// //             if (!file) {
// //                 e.preventDefault();
// //                 Swal.fire({
// //                     icon: 'error',
// //                     title: 'Please select an image'
// //                 });
// //                 return;
// //             }

// //             if (!['image/jpeg', 'image/jpg'].includes(file.type)) {
// //                 e.preventDefault();
// //                 Swal.fire({
// //                     icon: 'error',
// //                     title: 'Only JPEG/JPG allowed'
// //                 });
// //                 return;
// //             }

// //             if (file.size > 2048 * 1024) {
// //                 e.preventDefault();
// //                 Swal.fire({
// //                     icon: 'error',
// //                     title: 'File must be less than 2MB'
// //                 });
// //                 return;
// //             }

// //         });
// //     }

// // });

// import './bootstrap';

let table;
let filePath = "";
$(document).ready(function () {
    console.log("working ");

    function addRow() {
        $("#imageContainer").append(`
            <div class="upload-box d-flex gap-2">
                <input type="file" name="images[]" class="form-control" required>
                <button type="button" class="btn btn-danger remove-btn">Remove</button>
            </div>
        `);
    }

    function removeRow(btn) {
        $(btn).parent().remove();
    }

    // Delegate remove click
    $(document).on("click", ".remove-btn", function () {
        removeRow(this);
    });

    window.addRow = addRow; // expose globally

    // FORM SUBMIT
    $("#mergeForm").submit(function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        let selectedDistrict = $("#district_id").val();
        $("#loader").css("display", "flex");

        $.ajax({
            // url: "/process-images",
            // url: "{{ route('image.process') }}",
            url: window.routes.processImage,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (res) {
                $("#loader").hide();

                const url = res.image + "?t=" + new Date().getTime();
                filePath = res.file_path;

                $("#modalImage").attr("src", url);

                //  CLEAR FILE INPUTS
                // $("#mergeForm")[0].reset();
                // Clear only image inputs
                $('input[name="images[]"]').val('');
                $("#district_id").val(selectedDistrict);
                $("#modalDownload")
                    .off("click")
                    .on("click", function () {
                        const a = document.createElement("a");
                        a.href = url;
                        a.download = "merged.jpg";
                        a.click();
                    });

                //  REMOVE EXTRA ADDED INPUT ROWS (keep only one)
                $("#imageContainer").html(`
        <div class="upload-box d-flex gap-2">
            <input type="file" name="images[]" class="form-control" required>
            <button type="button" class="btn btn-danger remove-btn">Remove</button>
        </div>
    `);

                let modal = new bootstrap.Modal(
                    document.getElementById("resultModal"),
                );
                modal.show();
            },

            error: function (xhr) {
                $("#loader").hide();
                console.log(xhr.responseText);
                alert("Error! Check console");
            },
        });
    });

    // MODE CHANGE
    $('select[name="mode"]')
        .on("change", function () {
            $("#finalImage").attr("src", "");

            let mode = $(this).val();
            let html = "";

            if (mode === "vertical") {
                html = `
                <div class="row">
                    <div class="col-md-4">
                        <label>Width (px)</label>
                        <input type="number" name="width" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Spacing</label>
                        <input type="number" name="spacing" class="form-control" value="0">
                    </div>
                    <div class="col-md-4">
                        <label>Background</label>
                        <input type="color" name="bgcolor" class="form-control" value="#ffffff">
                    </div>
                </div>
            `;
            } else {
                html = `
                <div class="row">
                    <div class="col-md-4">
                        <label>Height (px)</label>
                        <input type="number" name="height" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Spacing</label>
                        <input type="number" name="spacing" class="form-control" value="0">
                    </div>
                    <div class="col-md-4">
                        <label>Background</label>
                        <input type="color" name="bgcolor" class="form-control" value="#ffffff">
                    </div>
                </div>
            `;
            }

            $("#dynamicOptions").html(html);
        })
        .trigger("change");
});

$("#saveBtn")
    .off("click")
    .on("click", function () {
        let  imageName = $("#renameInput").val();
        let districtId = $("#district_id").val();
        console.log(districtId, "District id");
        if (!imageName) {
            Swal.fire({
                icon: "error",
                title: "Enter image name",
            });
            return;
        }

        if (!filePath) {
            Swal.fire({
                icon: "error",
                title: "Generate image first",
            });
            return;
        }
         if (!districtId) {
        Swal.fire({
            icon: "error",
            title: "Please select district"
        });
        return;
    }

        $.ajax({
            // url: "/save-image",
            // url: "{{ route('save.image') }}",
            url: window.routes.saveImage,
            type: "POST",
            data: {
                image_name: imageName,
                district_id: districtId,
                file_path: filePath,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },

         
            success: function (res) {
                console.log(res);
                console.log("success message  ");

                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false,
                }).then(() => {
                    // close modal
                    $("#renameInput").val("");
                $("#district_id").val(""); // default option
                filePath = "";
                    let modalEl = document.getElementById("resultModal");
                    let modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();

                    // clear input
                    $("#renameInput").val("");

                    //  RELOAD TABLE (IMPORTANT)
                    table.ajax.reload(null, false);
                });
            },
            error: function (xhr) {
                let msg = "Something went wrong";

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }

                //  ERROR ALERT
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: msg,
                });
            },
        });
    });

window.copyLink = function (link) {
    navigator.clipboard.writeText(link).then(() => {
        Swal.fire({
            icon: "success",
            title: "Copied",
            text: "Short URL copied",
            timer: 1200,
            showConfirmButton: false,
        });
    });
};

$(document).ready(function () {
    table = $("#imageTable").DataTable({
        processing: true,
        serverSide: true,
        // ajax: "/get-images",
        // ajax: "{{ url('get-images') }}",
        // ajax: window.appUrl + "/get-images",
        ajax: {
        url: window.routes.getImages,
        type: "GET"
    },
        columns: [
            { title: "#" },
            { title: "Image" },
            { title: "Short URL" },
            { title: "Count" },
            { title: "Date" },
        ],
    });
});
