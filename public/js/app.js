// import './bootstrap';
console.log("1233");
let filePath = '';
$(document).ready(function () {
console.log("hhhhhhhhhhhhhhh231");

    function addRow() {
        $('#imageContainer').append(`
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
    $(document).on('click', '.remove-btn', function () {
        removeRow(this);
    });

    window.addRow = addRow; // expose globally

    // FORM SUBMIT
    $('#mergeForm').submit(function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $('#loader').css('display','flex');

        $.ajax({
            url: "/process-images",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

//             success: function(res) {
//                     $('#shortUrl').html(
//     'Short URL: <a href="' + res.short_url + '" target="_blank">' + res.short_url + '</a>'
// );
//                 $('#loader').hide();

//                 const url = res.image + '?t=' + new Date().getTime();

//                 $('#modalImage').attr('src', url);

//                 $('#modalDownload').off('click').on('click', function () {
//                     const a = document.createElement('a');
//                     a.href = url;
//                     a.download = 'merged.jpg';
//                     a.click();
//                 });

//                 let modal = new bootstrap.Modal(document.getElementById('resultModal'));
//                 modal.show();
//             },
success: function(res) {

    $('#loader').hide();

    const url = res.image + '?t=' + new Date().getTime();
      filePath = res.file_path;
    $('#modalImage').attr('src', url);

    //  SHOW SHORT URL
    // $('#shortUrl').html(
    //     'Short URL: <a href="' + res.short_url + '" target="_blank">' + res.short_url + '</a>'
    // );

    $('#modalDownload').off('click').on('click', function () {
        const a = document.createElement('a');
        a.href = url;
        a.download = 'merged.jpg';
        a.click();
    });

    let modal = new bootstrap.Modal(document.getElementById('resultModal'));
    modal.show();
},

            error: function(xhr) {
                $('#loader').hide();
                console.log(xhr.responseText);
                alert("Error! Check console");
            }
        });
    });

    // MODE CHANGE
    $('select[name="mode"]').on('change', function () {

        $('#finalImage').attr('src', '');

        let mode = $(this).val();
        let html = '';

        if (mode === 'vertical') {
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

        $('#dynamicOptions').html(html);

    }).trigger('change');

});

$('#saveBtn').off('click').on('click', function () {

    let imageName = $('#renameInput').val();
        console.log(imageName,"___name");
    if (!imageName) {
        Swal.fire({
            icon: 'error',
            title: 'Enter image name'
        });
        return;
    }

    if (!filePath) {
        Swal.fire({
            icon: 'error',
            title: 'Generate image first'
        });
        return;
    }

    $.ajax({
        url: "/save-image",
        type: "POST",
        data: {
            image_name: imageName,
            file_path: filePath,
            _token: $('meta[name="csrf-token"]').attr('content')
        },

        success: function(res) {

            //  SUCCESS ALERT
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: res.message
            });

            //  SHOW SHORT URL
            $('#').html(
                'Short URL: <a href="' + res.short_url + '" target="_blank">' + res.short_url + '</a>'
            );
        },

        error: function(xhr) {

            let msg = 'Something went wrong';

            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }

            //  ERROR ALERT
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: msg
            });
        }
    });
});

// $('#saveBtn').off('click').on('click', function () {

//     let imageName = $('#renameInput').val();

//     if (!imageName) {
//         alert("Enter image name");
//         return;
//     }

//     $.ajax({
//         url: "/save-image",
//         type: "POST",
//         data: {
//             image_name: imageName,
//             file_path: filePath,
//             _token: $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function(res) {
//             $('#shortUrl').html(
//                     'Short URL: <a href="' + res.short_url + '" target="_blank">' + res.short_url + '</a>'
//                 );
//         },
//         error: function(err) {
//             console.log(err.responseText);
//         }
//     });
// });


